<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

        Auth::login($user);

        // Auto-enroll into selected club if provided
        if (!empty($validated['favorite_club_id'])) {
            try {
                $user->clubMemberships()->attach($validated['favorite_club_id'], ['role' => 'member']);
            } catch (\Exception $e) {
                // ignore duplicate or attach errors; user still created and logged in
            }
        }

        return redirect()->route('feed')->with('success', 'Account created successfully!');
    }
}
