<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Match as ClubMatch;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubController
{
    public function index()
    {
        $clubs = Club::withCount('members')->orderBy('members_count', 'desc')->paginate(12);
        return view('clubs.index', ['clubs' => $clubs]);
    }

    public function show(Club $club)
    {
        $club->load(['members', 'posts' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }, 'players', 'homeMatches', 'awayMatches']);

        return view('clubs.show', ['club' => $club]);
    }

    public function join(Club $club)
    {
        $user = Auth::user();
        $club->addMember($user);

        return back()->with('success', 'Successfully joined ' . $club->name . '!');
    }

    public function leave(Club $club)
    {
        $user = Auth::user();
        $club->removeMember($user);

        return back()->with('success', 'Left ' . $club->name);
    }

    public function createMatch()
    {
        $clubs = Club::orderBy('name')->get();
        return view('clubs.create-match', ['clubs' => $clubs]);
    }

    public function storeMatch(Request $request)
    {
        $validated = $request->validate([
            'home_club_id' => 'required|exists:clubs,id',
            'away_club_id' => 'required|exists:clubs,id|different:home_club_id',
            'match_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'league' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        ClubMatch::create($validated);

        return redirect()->route('clubs.index')->with('success', 'Match created successfully!');
    }

    public function updateMatchScore(Request $request, ClubMatch $match)
    {
        $validated = $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'status' => 'in:scheduled,live,finished,cancelled',
        ]);

        $match->update($validated);

        if ($request->expectsJson()) {
            return response()->json($match);
        }

        return back()->with('success', 'Match score updated!');
    }
}
