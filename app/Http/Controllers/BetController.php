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
        // Automatically sync results and settle bets whenever the dashboard is viewed.
        // Throttled to every 10 minutes to protect API rate limits.
        cache()->remember('auto_settlement_check', 10, function() {
            $this->autoSettleFinishedBets();
            return true;
        });

        $fixtures = cache()->remember('betting_fixtures', 15, function () {
            return $this->fetchUpcomingFixtures();
        });

        $matches = $this->syncAndGetMatches($fixtures);

        // DASHBOARD: Keep bets visible until finished and results are claimed
        $myBets = Bet::where('user_id', Auth::id())
            ->with(['match.homeClub', 'match.awayClub', 'partner', 'user.wallet'])
            ->latest()
            ->get();

        $pendingBetsForOthers = Bet::where('status', 'pending')
            ->where('user_id', '!=', Auth::id())
            ->get();
        
        $availableBetsCount = $pendingBetsForOthers->count();
        $firstPendingMatchId = $pendingBetsForOthers->isNotEmpty() ? $pendingBetsForOthers->first()->match_id : null;

        return view('betting.index', compact('matches', 'myBets', 'availableBetsCount', 'firstPendingMatchId'));
    }

    /**
     * Fetch upcoming fixtures directly from the API.
     */
    private function fetchUpcomingFixtures()
    {
        $apiKey = env('API_SPORTS_KEY');
        if (!$apiKey) return [];

        $fixtures = [];
        
        // Priority: Target Top European Leagues and Major International Tournaments
        // World Cup (1), Euro (4), Nations League (5), AFCON (6), Copa America (9), Friendlies (10)
        // Premier League (39), La Liga (140), Serie A (135), Bundesliga (78), Ligue 1 (61)
        $topLeagueIds = [1, 4, 5, 6, 9, 10, 39, 140, 135, 78, 61];

        foreach ($topLeagueIds as $id) {
            $response = Http::timeout(15)->withHeaders(['x-apisports-key' => $apiKey])
                ->get("https://v3.football.api-sports.io/fixtures", [
                    'league' => $id,
                    'next' => 15,
                    'status' => 'NS'
                ]);

            if ($response->successful()) {
                $fixtures = array_merge($fixtures, $response->json()['response'] ?? []);
            }
        }

        // Fallback: If we don't have many top matches, fetch general fixtures for today and tomorrow
        if (count($fixtures) < 15) {
            $dates = [now()->format('Y-m-d'), now()->addDay()->format('Y-m-d')];
            foreach ($dates as $date) {
                $response = Http::timeout(15)->withHeaders(['x-apisports-key' => $apiKey])
                    ->get("https://v3.football.api-sports.io/fixtures", [
                        'date' => $date,
                        'status' => 'NS'
                    ]);

                if ($response->successful()) {
                    $fixtures = array_merge($fixtures, $response->json()['response'] ?? []);
                }
            }
        }

        return collect($fixtures)->unique('fixture.id')->filter(function ($item) {
            $date = Carbon::parse($item['fixture']['date']);
            // Keep matches within a 3-day window
            return $date->isAfter(now()) && $date->isBefore(now()->addDays(3));
        })->sort(function ($a, $b) use ($topLeagueIds) {
            $aIsTop = in_array($a['league']['id'] ?? 0, $topLeagueIds);
            $bIsTop = in_array($b['league']['id'] ?? 0, $topLeagueIds);

            // If one is a top league and the other isn't, prioritize the top league
            if ($aIsTop && !$bIsTop) return -1;
            if (!$aIsTop && $bIsTop) return 1;

            // Otherwise, sort by match time
            return $a['fixture']['timestamp'] <=> $b['fixture']['timestamp'];
        })->values()->take(50)->toArray();
    }

    /**
     * Sync API fixtures with local matches table and return the collection.
     */
    private function syncAndGetMatches($fixtures)
    {
        $syncedMatches = collect();

        // Optimization: Only perform DB writes if fixtures were recently updated or every 30 mins
        $syncKey = 'matches_synced_to_db_' . md5(json_encode(collect($fixtures)->pluck('fixture.id')));
        $shouldSync = !cache()->has($syncKey);

        $homeClubIds = [];
        $awayClubIds = [];

        foreach ($fixtures as $item) {
            if ($shouldSync) {
                // We use updateOrCreate to ensure elite clubs have their latest logos
                $homeClub = Club::updateOrCreate(
                    ['name' => $item['teams']['home']['name']],
                    [
                        'slug' => Str::slug($item['teams']['home']['name']),
                        'logo' => $item['teams']['home']['logo'] ?? null
                    ]
                );
                $awayClub = Club::updateOrCreate(
                    ['name' => $item['teams']['away']['name']],
                    [
                        'slug' => Str::slug($item['teams']['away']['name']),
                        'logo' => $item['teams']['away']['logo'] ?? null
                    ]
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
            } else {
                // Lightweight retrieval if already synced
                $match = ClubMatch::whereHas('homeClub', fn($q) => $q->where('name', $item['teams']['home']['name']))
                    ->whereHas('awayClub', fn($q) => $q->where('name', $item['teams']['away']['name']))
                    ->where('match_date', Carbon::parse($item['fixture']['date']))
                    ->first();
            }

            if (!$match) continue;

            // Eager load necessary relations and open bets
            $match->load(['homeClub', 'awayClub', 'bets' => function ($query) {
                $query->where('status', 'pending')->where('user_id', '!=', Auth::id());
            }]);

            $syncedMatches->push($match);
        }

        if ($shouldSync && $syncedMatches->isNotEmpty()) {
            cache()->put($syncKey, true, now()->addMinutes(30));
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
            // Deduct funds immediately to hold the stake
            $wallet->decrement('balance', $request->amount);

            // Try to find a partner
            $partnerBet = Bet::where('match_id', $match->id)
                ->where('amount', $request->amount)
                ->where('selection', $request->selection === 'home' ? 'away' : 'home')
                ->where('status', 'pending')
                ->where('user_id', '!=', $user->id)
                ->first();

            if ($partnerBet) {
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

    /**
     * Internal method to settle all locked bets for finished matches automatically.
     */
    private function autoSettleFinishedBets()
    {
        $this->syncFinishedMatchResults();

        // Refund unmatched (pending) bets for finished matches
        $unmatchedBets = Bet::where('status', 'pending')
            ->whereHas('match', function ($query) {
                $query->where('status', 'finished');
            })->get();

        foreach ($unmatchedBets as $uBet) {
            DB::transaction(function () use ($uBet) {
                Wallet::where('user_id', $uBet->user_id)->increment('balance', $uBet->amount);
                $uBet->update(['status' => 'cancelled']);
            });
        }

        $lockedBets = Bet::where('status', 'locked')
            ->whereHas('match', function ($query) {
                $query->where('status', 'finished');
            })
            ->get();

        foreach ($lockedBets as $bet) {
            $bet->refresh();
            if ($bet->status === 'locked') {
                $this->performSettlement($bet);
            }
        }
    }

    public function claim(Bet $bet)
    {
        $match = $bet->match;
        
        // Attempt to sync result if match time has passed but status isn't updated
        if ($match->status !== 'finished' && $match->match_date < now()->subHours(2)) {
            $this->updateSingleMatchResult($match);
            $match->refresh();
        }

        if (in_array(strtolower($match->status), ['scheduled', 'notstarted', 'inprogress', 'in_progress'])) {
            return back()->with('error', 'Match is still ongoing. Please wait until completion.');
        }
        if ($match->status !== 'finished') {
            return back()->with('error', 'Match status is currently: ' . $match->status);
        }

        $result = $this->performSettlement($bet);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back()->with($result['type'], $result['message']);
    }

    private function performSettlement(Bet $bet)
    {
        $match = $bet->match;
        $winner = $this->calculateWinner($match);

        return DB::transaction(function () use ($bet, $winner) {
            if ($bet->status !== 'locked') {
                return ['type' => 'info', 'message' => 'This bet has already been settled.'];
            }

            $partnerBet = Bet::where('match_id', $bet->match_id)
                ->where('user_id', $bet->partner_id)
                ->where('partner_id', $bet->user_id)
                ->where('status', 'locked')
                ->first();

            if ($winner === 'draw') {
                $refundAmount = $bet->amount * 0.98; // Deduct 2% from stake

                // Settle current user
                $wallet = Wallet::where('user_id', $bet->user_id)->first();
                if ($wallet) $wallet->increment('balance', $refundAmount);
                $bet->update(['status' => 'draw', 'payout' => $refundAmount]);

                // Settle partner
                if ($partnerBet) {
                    $pWallet = Wallet::where('user_id', $partnerBet->user_id)->first();
                    if ($pWallet) $pWallet->increment('balance', $refundAmount);
                    $partnerBet->update(['status' => 'draw', 'payout' => $refundAmount]);
                }

                return ['type' => 'info', 'message' => 'Match ended in a draw. Stake returned minus 2% commission.'];
            }

            // Winner logic
            $claimingUserWon = ($bet->selection === $winner);
            $winningBet = $claimingUserWon ? $bet : $partnerBet;
            $losingBet = $claimingUserWon ? $partnerBet : $bet;

            if ($winningBet) {
                $totalPool = $winningBet->amount * 2;
                $payout = $totalPool - ($winningBet->amount * 0.05); // Deduct 5% from stake

                $wWallet = Wallet::where('user_id', $winningBet->user_id)->first();
                if ($wWallet) $wWallet->increment('balance', $payout);
                $winningBet->update(['status' => 'won', 'payout' => $payout]);
            }

            if ($losingBet) {
                $losingBet->update(['status' => 'lost']);
            }

            if ($claimingUserWon) {
                return ['type' => 'success', 'message' => 'Congratulations! You won ₦' . number_format($winningBet->payout ?? 0, 2)];
            }

            return ['type' => 'info', 'message' => 'Match finished. You lost this bet. Winner has been credited.'];
        });
    }

    private function calculateWinner($match)
    {
        if ($match->home_score > $match->away_score) return 'home';
        if ($match->away_score > $match->home_score) return 'away';
        return 'draw';
    }

    private function syncFinishedMatchResults()
    {
        $apiKey = env('API_SPORTS_KEY');
        if (!$apiKey) return;

        // Find matches that are past their start time but not yet marked finished
        $matches = ClubMatch::where('status', 'scheduled')
            ->where('match_date', '<', now()->subHours(2))
            ->with(['homeClub', 'awayClub'])
            ->get();

        if ($matches->isEmpty()) return;

        // Group by date to minimize API calls (Batching)
        $dates = $matches->pluck('match_date')->map(fn($d) => $d->format('Y-m-d'))->unique();

        foreach ($dates as $date) {
            $response = Http::timeout(10)->withHeaders(['x-apisports-key' => $apiKey])
                ->get("https://v3.football.api-sports.io/fixtures", ['date' => $date]);

            if ($response->successful()) {
                $fixtures = $response->json()['response'] ?? [];
                
                // Create a lookup map for faster processing
                $apiMap = [];
                foreach ($fixtures as $f) {
                    $key = Str::slug($f['teams']['home']['name'] . '-' . $f['teams']['away']['name']);
                    $apiMap[$key] = $f;
                }

                foreach ($matches as $match) {
                    if ($match->match_date->format('Y-m-d') !== $date) continue;

                    if (!$match->homeClub || !$match->awayClub) continue;

                    $lookupKey = Str::slug($match->homeClub->name . '-' . $match->awayClub->name);
                    
                    if (isset($apiMap[$lookupKey])) {
                        $apiMatch = $apiMap[$lookupKey];
                        $status = $apiMatch['fixture']['status']['short'] ?? '';
                        
                        if (in_array($status, ['FT', 'AET', 'PEN'])) {
                            $match->update([
                                'home_score' => $apiMatch['goals']['home'],
                                'away_score' => $apiMatch['goals']['away'],
                                'status' => 'finished'
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function updateSingleMatchResult(ClubMatch $match)
    {
        $apiKey = env('API_SPORTS_KEY');
        $date = $match->match_date->format('Y-m-d');
        
        $response = Http::withHeaders(['x-apisports-key' => $apiKey])
            ->get("https://v3.football.api-sports.io/fixtures", ['date' => $date]);

        if ($response->successful()) {
            $fixtures = $response->json()['response'] ?? [];
            foreach ($fixtures as $item) {
                // Identify the specific match by comparing team names
                if ($item['teams']['home']['name'] === $match->homeClub->name && 
                    $item['teams']['away']['name'] === $match->awayClub->name) {
                    
                    $status = $item['fixture']['status']['short'];
                    if (in_array($status, ['FT', 'AET', 'PEN'])) {
                        $match->update([
                            'home_score' => $item['goals']['home'],
                            'away_score' => $item['goals']['away'],
                            'status' => 'finished'
                        ]);
                    }
                    break;
                }
            }
        }
    }
}