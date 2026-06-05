<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController
{
    public function show(User $user)
    {
        $user->load(['posts' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'followers', 'following', 'clubMemberships']);

        return view('profile.show', ['user' => $user]);
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
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'profile_picture' => 'nullable|image|max:2048',
            'cover_photo' => 'nullable|image|max:2048',
            'favorite_club_id' => 'nullable|exists:clubs,id',
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

        return redirect()->route('profile.show', $user)->with('success', 'Profile updated successfully!');
    }

    public function follow(User $user)
    {
        $authUser = Auth::user();
        
        if ($authUser->id !== $user->id) {
            $authUser->follow($user);
        }

        return back()->with('success', 'Following ' . $user->name);
    }

    public function unfollow(User $user)
    {
        $authUser = Auth::user();
        $authUser->unfollow($user);

        return back()->with('success', 'Unfollowed ' . $user->name);
    }
}
