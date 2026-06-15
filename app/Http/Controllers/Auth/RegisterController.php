<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Club;
use App\Models\Wallet;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RegisterController
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'favorite_club_id' => 'required|integer',
            'date_of_birth' => 'required|date',
        ]);

        return DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'country' => $validated['country'],
                'password' => Hash::make($validated['password']),
                'date_of_birth' => $validated['date_of_birth'],
                'favorite_club_id' => $validated['favorite_club_id'] > 0 ? $validated['favorite_club_id'] : null,
            ]);

            // Auto-enroll into selected club if provided
            if ($user->favorite_club_id) {
                try {
                    $user->clubMemberships()->attach($user->favorite_club_id, ['role' => 'member']);
                } catch (\Exception $e) {
                    // ignore duplicate or attach errors; user still created
                }
            }

            // Create Paystack Virtual Account & Wallet
            $this->createPaystackWallet($user);

            // Send welcome email to the user
            try {
                Mail::to($user->email)->queue(new WelcomeMail($user));
            } catch (\Throwable $e) {
                // Log the error but allow registration to continue
            }

            return redirect()->route('login')->with('success_modal', 'Registration successful! Your football wallet and virtual account have been activated.');
        });
    }

    protected function createPaystackWallet($user)
    {
        $secretKey = env('PAYSTACK_SECRET_KEY');
        $nameParts = explode(' ', $user->name);

        // 1. Create Paystack Customer
        $customerResponse = Http::withToken($secretKey)->post('https://api.paystack.co/customer', [
            'email' => $user->email,
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? 'Fan',
            'phone' => $user->phone_number,
        ]);

        if ($customerResponse->successful()) {
            $customerData = $customerResponse->json('data');
            
            // 2. Create Dedicated Virtual Account
            $accountResponse = Http::withToken($secretKey)->post('https://api.paystack.co/dedicated_account', [
                'customer' => $customerData['customer_code'],
                'preferred_bank' => 'wema-bank',
            ]);

            if ($accountResponse->successful()) {
                $acc = $accountResponse->json('data');
                
                Wallet::create([
                    'user_id' => $user->id,
                    'paystack_customer_code' => $customerData['customer_code'],
                    'paystack_account_number' => $acc['account_number'],
                    'paystack_bank_name' => $acc['bank']['name'],
                    'paystack_account_name' => $acc['account_name'],
                ]);
            }
        }
    }

    public function checkAvailability(Request $request)
    {
        $type = $request->type; // 'email' or 'username'
        $value = $request->value;
        $exists = User::where($type, $value)->exists();
        return response()->json(['exists' => $exists]);
    }
}
