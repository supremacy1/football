<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\Club;
use App\Models\ClubMatch;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BetController extends Controller
{
    public function index()
    {
        $fixtures = cache()->remember('betting_fixtures', 15, function () {
            return $this->fetchUpcomingFixtures();
        });

        $matches = $this->syncAndGetMatches($fixtures);

        // DASHBOARD: Keep bets visible until finished and results are claimed
        $myBets = Bet::where('user_id', Auth::id())
            ->with(['match.homeClub', 'match.awayClub', 'partner', 'user.wallet'])
            ->latest()
            ->get();

        return view('betting.index', compact('matches', 'myBets'));
    }

    /**
     * Fetch upcoming fixtures directly from the API.
     */
    private function fetchUpcomingFixtures()
    {
        $apiKey = env('API_SPORTS_KEY');
        if (!$apiKey) return [];

        $fixtures = [];
        $dates = [now()->format('Y-m-d'), now()->addDay()->format('Y-m-d')];

        foreach ($dates as $date) {
            $response = Http::withHeaders(['x-apisports-key' => $apiKey])
                ->get("https://v3.football.api-sports.io/fixtures", ['date' => $date]);

            if ($response->successful()) {
                $fixtures = array_merge($fixtures, $response->json()['response'] ?? []);
            }
        }

        return collect($fixtures)->filter(function ($item) {
            $date = Carbon::parse($item['fixture']['date']);
            return $date->isBetween(now(), now()->addHours(24)) &&
                   ($item['fixture']['status']['short'] ?? '') === 'NS';
        })->sortBy('fixture.timestamp')->values()->toArray();
    }

    /**
     * Sync API fixtures with local matches table and return the collection.
     */
    private function syncAndGetMatches($fixtures)
    {
        $syncedMatches = collect();

        foreach ($fixtures as $item) {
            // We sync the match data into our local 'matches' table so we can track bets.
            // This ensures that 'match_id' exists for the Bet model relationships.
            $homeClub = Club::firstOrCreate(
                ['name' => $item['teams']['home']['name']],
                ['slug' => Str::slug($item['teams']['home']['name'])]
            );
            $awayClub = Club::firstOrCreate(
                ['name' => $item['teams']['away']['name']],
                ['slug' => Str::slug($item['teams']['away']['name'])]
            );

            $match = ClubMatch::updateOrCreate(
                [
                    'home_club_id' => $homeClub->id,
                    'away_club_id' => $awayClub->id,
                    'match_date'   => Carbon::parse($item['fixture']['date']),
                ],
                [
                    'venue'  => $item['fixture']['venue']['name'] ?? 'TBD',
                    'status' => 'scheduled',
                    'league' => $item['league']['name'] ?? null,
                ]
            );

            // Eager load necessary relations and open bets
            $match->load(['homeClub', 'awayClub', 'bets' => function ($query) {
                $query->where('status', 'pending')->where('user_id', '!=', Auth::id());
            }]);

            $syncedMatches->push($match);
        }

        return $syncedMatches;
    }

    public function placeBet(Request $request, ClubMatch $match)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'selection' => 'required|in:home,away',
        ]);

        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'Insufficient wallet balance to place this bet.');
        }

        return DB::transaction(function () use ($request, $match, $user, $wallet) {
            // Try to find a partner
            $partnerBet = Bet::where('match_id', $match->id)
                ->where('amount', $request->amount)
                ->where('selection', $request->selection === 'home' ? 'away' : 'home')
                ->where('status', 'pending')
                ->where('user_id', '!=', $user->id)
                ->first();

            if ($partnerBet) {
                // Check if the creator still has enough balance
                $partnerWallet = Wallet::where('user_id', $partnerBet->user_id)->first();
                if (!$partnerWallet || $partnerWallet->balance < $request->amount) {
                    // If partner balance dropped, we can't match. Cancel their bet or just error out.
                    return back()->with('error', 'The open bet is no longer valid (insufficient partner funds).');
                }

                // Deduct from both
                $wallet->decrement('balance', $request->amount);
                $partnerWallet->decrement('balance', $request->amount);

                $bet = Bet::create([
                    'match_id' => $match->id,
                    'user_id' => $user->id,
                    'amount' => (float) $request->amount,
                    'selection' => $request->selection,
                    'status' => 'locked',
                    'partner_id' => $partnerBet->user_id,
                ]);

                $partnerBet->update([
                    'status' => 'locked',
                    'partner_id' => $user->id
                ]);
                
                return back()->with('success', 'Bet matched and locked! Funds have been deducted.');
            }

            // Create pending bet (no deduction yet)
            Bet::create([
                'match_id' => $match->id,
                'user_id' => $user->id,
                'amount' => (float) $request->amount,
                'selection' => $request->selection,
                'status' => 'pending',
            ]);

            return back()->with('success', 'Bet placed! It will appear as pending until someone matches it.');
        });
    }

    public function confirm(Bet $bet)
    {
        // If both confirmed
        $partnerBet = Bet::where('match_id', $bet->match_id)
            ->where('user_id', $bet->partner_id)
            ->where('partner_id', $bet->user_id)
            ->first();

        if ($partnerBet && $partnerBet->status === 'confirmed') {
             return back()->with('success', 'Both players confirmed. You can now Lock the game.');
        }

        return back()->with('success', 'Confirmation sent to partner.');
    }

    public function lock(Bet $bet)
    {
        // Logic moved to placeBet for automatic matching
        return back();
    }

    public function claim(Bet $bet)
    {
        $match = $bet->match;
        
        // Ensure match is actually over before allowing claim
        if (in_array(strtolower($match->status), ['scheduled', 'notstarted', 'inprogress', 'in_progress'])) {
            return back()->with('error', 'Match is still ongoing. Please wait until completion.');
        }
        if ($match->status !== 'finished') {
            return back()->with('error', 'Match status is currently: ' . $match->status);
        }

        $winner = null;
        if ($match->home_score > $match->away_score) $winner = 'home';
        elseif ($match->away_score > $match->home_score) $winner = 'away';
        else $winner = 'draw';

        if ($bet->selection === $winner) {
            return DB::transaction(function () use ($bet) {
                $totalPool = $bet->amount * 2;
                $commission = $totalPool * 0.05; // 5% commission
                $payout = $totalPool - $commission; 

                $wallet = Wallet::where('user_id', $bet->user_id)->first();
                $wallet->increment('balance', $payout);
                
                $bet->update(['status' => 'won', 'payout' => $payout]);
                
                // Update partner bet
                Bet::where('match_id', $bet->match_id)
                    ->where('user_id', $bet->partner_id)
                    ->update(['status' => 'lost']);

                return back()->with('success', 'Congratulations! You won ₦' . number_format($payout, 2));
            });
        }

        return back()->with('info', 'Match finished. You lost this bet. Better luck next time!');
    }
}