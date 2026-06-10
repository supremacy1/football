<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Club;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController
{
    public function showRegisterForm()
    {
        $clubs = Club::orderBy('name')->get();

        return view('auth.register', compact('clubs'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'favorite_club_id' => 'nullable|exists:clubs,id',
            'date_of_birth' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'favorite_club_id' => $validated['favorite_club_id'] ?? null,
        ]);

        // Auto-enroll into selected club if provided
        if (!empty($validated['favorite_club_id'])) {
            try {
                $user->clubMemberships()->attach($validated['favorite_club_id'], ['role' => 'member']);
            } catch (\Exception $e) {
                // ignore duplicate or attach errors; user still created
            }
        }

        // Send welcome email to the user
        try {
            Mail::to($user->email)->queue(new WelcomeMail($user));
        } catch (\Throwable $e) {
            // Log the error but allow registration to continue
        }

        return redirect()->route('login')->with('success_modal', 'Registration successful! Please log in with your credentials.');
    }

    public function checkAvailability(Request $request)
    {
        $type = $request->type; // 'email' or 'username'
        $value = $request->value;
        $exists = User::where($type, $value)->exists();
        return response()->json(['exists' => $exists]);
    }
}
