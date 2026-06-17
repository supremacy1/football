<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        $wallet = Auth::user()->wallet;
        $banks = cache()->remember('paystack_banks', 86400, function() {
            $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))->get('https://api.paystack.co/bank');
            return $response->successful() ? $response->json('data') : [];
        });
        $withdrawals = Withdrawal::where('user_id', Auth::id())->latest()->get();

        return view('betting.withdraw', compact('wallet', 'banks', 'withdrawals'));
    }

    public function verifyAccount(Request $request)
    {
        $request->validate([
            'account_number' => 'required|string|size:10',
            'bank_code' => 'required|string'
        ]);

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->get("https://api.paystack.co/bank/resolve", [
                'account_number' => $request->account_number,
                'bank_code' => $request->bank_code,
            ]);

        if ($response->successful()) {
            return response()->json(['success' => true, 'account_name' => $response->json('data.account_name')]);
        }

        return response()->json(['success' => false, 'message' => 'Verification failed.'], 422);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'bank_code' => 'required|string',
            'bank_name' => 'required|string',
            'account_number' => 'required|string|size:10',
            'account_name' => 'required|string',
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'Insufficient funds.');
        }

        try {
            // 1. Create Recipient
            $recipientResponse = Http::withToken(env('PAYSTACK_SECRET_KEY'))->post('https://api.paystack.co/transferrecipient', [
                'type' => 'nuban',
                'name' => $request->account_name,
                'account_number' => $request->account_number,
                'bank_code' => $request->bank_code,
                'currency' => 'NGN',
            ]);

            if (!$recipientResponse->successful()) {
                return back()->with('error', 'Recipient Error: ' . $recipientResponse->json('message'));
            }

            $recipientCode = $recipientResponse->json('data.recipient_code');

            // 2. Initiate Transfer
            $transferResponse = Http::withToken(env('PAYSTACK_SECRET_KEY'))->post('https://api.paystack.co/transfer', [
                'source' => 'balance',
                'amount' => $request->amount * 100, // Kobo
                'recipient' => $recipientCode,
                'reason' => 'Wallet Withdrawal',
            ]);

            if (!$transferResponse->successful()) {
                return back()->with('error', 'Transfer Error: ' . $transferResponse->json('message'));
            }

            $transferData = $transferResponse->json('data');

            return DB::transaction(function () use ($user, $wallet, $request, $transferData) {
                $wallet->decrement('balance', $request->amount);

                Withdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'reference' => $transferData['reference'],
                    'status' => 'pending',
                    'transfer_code' => $transferData['transfer_code']
                ]);

                return redirect()->route('betting.index')->with('success_modal', 'Withdrawal initiated successfully!');
            });
        } catch (\Exception $e) {
            Log::error('Withdrawal failed: ' . $e->getMessage());
            return back()->with('error', 'Server error. Please try again.');
        }
    }
}