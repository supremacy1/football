<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Club;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProfileController
{
    public function show(User $user)
    {
        $user->load(['posts' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'followers', 'following', 'clubMemberships', 'favoriteClub']);

        $banks = []; $withdrawals = collect();
        if (Auth::id() === $user->id) {
            $banks = cache()->remember('paystack_banks', 86400, function() {
                $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))->get('https://api.paystack.co/bank');
                return $response->successful() ? $response->json('data') : [];
            });
            $withdrawals = Withdrawal::where('user_id', Auth::id())->latest()->get();
        }

        return view('profile.show', compact('user', 'banks', 'withdrawals'));
    }

    public function edit()
    {
        $user = Auth::user();
        $clubs = Club::orderBy('name')->get();
        return view('profile.edit', ['user' => $user, 'clubs' => $clubs]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255', // Corrected validation rule
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'profile_picture' => 'nullable|image|max:2048',
            'cover_photo' => 'nullable|image|max:2048',
            'favorite_club_id' => 'nullable|integer', // integer instead of exists allows more flexibility if using '0' for others
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles/pictures', 'public');
        }

        if ($request->hasFile('cover_photo')) {
            if ($user->cover_photo) {
                Storage::disk('public')->delete($user->cover_photo);
            }
            $validated['cover_photo'] = $request->file('cover_photo')->store('profiles/covers', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user)->with('success_modal', 'Profile updated successfully!');
    }

    public function follow(User $user)
    {
        $authUser = Auth::user();
        
        if ($authUser->id !== $user->id) {
            $authUser->follow($user);
        }

        return back()->with('success_modal', 'Following ' . $user->name);
    }

    public function unfollow(User $user)
    {
        $authUser = Auth::user();
        $authUser->unfollow($user);

        return back()->with('success_modal', 'Unfollowed ' . $user->name);
    }
}
