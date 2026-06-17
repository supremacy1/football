<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $signature = $request->header('x-paystack-signature');
        if (!$signature || $signature !== hash_hmac('sha512', $request->getContent(), env('PAYSTACK_SECRET_KEY'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $event = $request->input('event');
        $data = $request->input('data');

        if ($event === 'transfer.success') {
            Withdrawal::where('reference', $data['reference'])->update(['status' => 'success']);
        }

        if ($event === 'transfer.failed' || $event === 'transfer.reversed') {
            $withdrawal = Withdrawal::where('reference', $data['reference'])->first();
            if ($withdrawal && $withdrawal->status === 'pending') {
                $wallet = Wallet::where('user_id', $withdrawal->user_id)->first();
                if ($wallet) {
                    $wallet->increment('balance', $withdrawal->amount);
                }
                $withdrawal->update(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}