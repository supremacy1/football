<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMatch;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::withCount('members')->orderBy('members_count', 'desc')->paginate(12);
        return view('clubs.index', ['clubs' => $clubs]);
    }

    public function show(Club $club)
    {
        $club->load(['members', 'posts' => function ($query) {
            $query->with(['user', 'comments.user', 'comments.replies.user', 'likes', 'comments.likes'])->orderBy('created_at', 'desc')->limit(10);
        }, 'players'])->loadCount(['members', 'posts', 'players']);

        // Fetch upcoming matches for the club
        $upcomingMatches = ClubMatch::where(function ($query) use ($club) {
            $query->where('home_club_id', $club->id)
                  ->orWhere('away_club_id', $club->id);
        })
        ->where('match_date', '>=', now()) // Only future matches
        ->orderBy('match_date', 'asc')
        ->with(['homeClub', 'awayClub']) // Eager load related clubs for display
        ->limit(5) // Display up to 5 upcoming matches
        ->get();

        return view('clubs.show', ['club' => $club, 'upcomingMatches' => $upcomingMatches]);
    }

    public function join(Club $club)
    {
        $user = Auth::user();

        if ($club->isMember($user)) {
            return back()->with('info', 'You are already a member of this club.');
        }
        
        if ((int)$user->favorite_club_id !== (int)$club->id) {
            return back()->with('error', 'You can only join the club you selected as your favorite during registration!');
        }

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

    public function updateMatchScore(Request $request, $clubMatchId)
    {
        $clubMatch = ClubMatch::findOrFail($clubMatchId);

        $validated = $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'status' => 'in:scheduled,live,finished,cancelled',
        ]);

        $clubMatch->update($validated);

        if ($request->expectsJson()) {
            return response()->json($clubMatch);
        }

        return back()->with('success', 'Match score updated!');
    }
}
