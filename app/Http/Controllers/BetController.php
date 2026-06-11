<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\ClubMatch;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BetController extends Controller
{
    public function index()
    {
        $matches = ClubMatch::where('status', 'scheduled')
            ->whereBetween('match_date', [now(), now()->addHours(24)])
            ->with(['homeClub', 'awayClub'])
            ->withCount(['bets as pending_bets_count' => function ($query) {
                $query->where('status', 'pending')
                      ->where('user_id', '!=', Auth::id());
            }])
            ->orderBy('match_date', 'asc')
            ->get();

        // DASHBOARD: Keep bets visible until finished and results are claimed
        $myBets = Bet::where('user_id', Auth::id())
            ->with(['match.homeClub', 'match.awayClub', 'partner', 'user.wallet'])
            ->latest()
            ->get();

        return view('betting.index', compact('matches', 'myBets'));
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

        return DB::transaction(function () use ($request, $match, $user) {
            // Try to find a partner
            $partnerBet = Bet::where('match_id', $match->id)
                ->where('amount', $request->amount)
                ->where('selection', $request->selection === 'home' ? 'away' : 'home')
                ->where('status', 'pending')
                ->where('user_id', '!=', $user->id)
                ->first();
            
            $bet = Bet::create([
                'match_id' => $match->id,
                'user_id' => $user->id,
                'amount' => (float) $request->amount,
                'selection' => $request->selection,
                'status' => $partnerBet ? 'matched' : 'pending',
                'partner_id' => $partnerBet ? $partnerBet->user_id : null,
            ]);

            if ($partnerBet) {
                $partnerBet->update([
                    'status' => 'matched',
                    'partner_id' => $user->id
                ]);
                
                return back()->with('success', 'Partner found! Match matched. Please confirm and lock your bet before kickoff.');
            }

            return back()->with('success', 'Bet placed! Other members will now see a notice to match your stake.');
        });
    }

    public function confirm(Bet $bet)
    {
        if ($bet->user_id !== Auth::id()) abort(403);
        
        $bet->update(['status' => 'confirmed']);
        
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
        if ($bet->user_id !== Auth::id()) abort(403);

        return DB::transaction(function () use ($bet) {
            $wallet = Wallet::where('user_id', $bet->user_id)->first();
            
            if ($wallet->balance < $bet->amount) {
                return back()->with('error', 'Insufficient balance to lock.');
            }

            $wallet->decrement('balance', $bet->amount);
            $bet->update(['status' => 'locked']);

            return back()->with('success', 'Game locked! Funds debited.');
        });
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
                $commission = $totalPool * 0.10; // 10% commission
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