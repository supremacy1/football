@extends('layouts.app')

@section('title', 'Football Betting')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Upcoming Matches -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Fixtures (Next 24 Hours)</h2>
                <div class="badge bg-dark px-3 py-2">Wallet Balance: ₦{{ number_format(optional(auth()->user()->wallet)->balance ?? 0, 2) }}</div>
            </div>

            @if($matches->isEmpty())
                <div class="alert alert-info">No upcoming matches available for betting.</div>
            @endif

            @foreach($matches as $match)
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-body">
                        <div class="row align-items-center text-center">
                            <div class="col-4">
                                <img src="{{ ($match->homeClub && $match->homeClub->logo) ? asset('storage/'.$match->homeClub->logo) : 'https://via.placeholder.com/40' }}" class="avatar mb-2" style="width: 50px; height: 50px; object-fit: contain;">
                                <h6 class="mb-0 fw-bold">{{ $match->homeClub->name }}</h6>
                            </div>
                            <div class="col-4">
                                <div class="badge bg-light text-muted mb-1">{{ $match->match_date->format('d M, H:i') }}</div>
                                <div class="fw-bold my-1 text-primary">VS</div>
                                
                                @if($match->bets->count() > 0)
                                    <div class="mt-2 text-start">
                                        <p class="small fw-bold mb-1 text-center">Open Bets:</p>
                                        <div class="d-flex flex-column gap-1">
                                            @foreach($match->bets as $openBet)
                                                <form action="{{ route('betting.store', $match) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="amount" value="{{ $openBet->amount }}">
                                                    <input type="hidden" name="selection" value="{{ $openBet->selection === 'home' ? 'away' : 'home' }}">
                                                    <button type="submit" class="btn btn-xs btn-outline-warning w-100 py-1" style="font-size: 0.7rem;">
                                                        Match ₦{{ number_format($openBet->amount) }} 
                                                        ({{ $openBet->selection === 'home' ? $match->awayClub->name : $match->homeClub->name }} Win)
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-2">
                                        <button class="btn btn-sm btn-success px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#betModal{{ $match->id }}">
                                            Start a Bet
                                        </button>
                                    </div>
                                @endif

                            </div>
                            <div class="col-4">
                                <img src="{{ ($match->awayClub && $match->awayClub->logo) ? asset('storage/'.$match->awayClub->logo) : 'https://via.placeholder.com/40' }}" class="avatar mb-2" style="width: 50px; height: 50px; object-fit: contain;">
                                <h6 class="mb-0 fw-bold">{{ $match->awayClub->name }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bet Modal -->
                <div class="modal fade" id="betModal{{ $match->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('betting.store', $match) }}" method="POST" class="modal-content">
                            @csrf
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold">Stake on this Match</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Your Prediction</label>
                                    <select name="selection" class="form-select" required>
                                        <option value="home">{{ $match->homeClub->name }} to Win</option>
                                        <option value="away">{{ $match->awayClub->name }} to Win</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Stake Amount (₦)</label>
                                    <input type="number" name="amount" class="form-control" placeholder="Minimum 100" min="100" required>
                                    <p class="text-muted mt-2 small">
                                        <i class="fas fa-info-circle"></i> Once placed, the system will look for a partner. If you match with someone already waiting, the game will be "Matched" instantly.
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Find Partner / Place Bet</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- My Bets Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 90px;">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history"></i> My Bets Dashboard</h5>
                </div>
                <div class="list-group list-group-flush" style="max-height: 70vh; overflow-y: auto;">
                    @forelse($myBets as $bet)
                        <div class="list-group-item p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge {{ $bet->status === 'pending' ? 'bg-secondary' : ($bet->status === 'locked' ? 'bg-danger' : ($bet->status === 'won' ? 'bg-success' : 'bg-info')) }}">
                                    {{ strtoupper($bet->status) }}
                                </span>
                                <small class="text-muted">{{ $bet->created_at->diffForHumans() }}</small>
                            </div>
                            
                            <div class="fw-bold small mb-1">{{ $bet->match->homeClub->name }} vs {{ $bet->match->awayClub->name }}</div>
                            <div class="small text-muted mb-2">
                                Selection: <span class="text-primary fw-bold">{{ $bet->selection === 'home' ? $bet->match->homeClub->name : $bet->match->awayClub->name }}</span>
                                <br>Amount: <strong>₦{{ number_format($bet->amount, 2) }}</strong>
                            </div>

                            @if($bet->partner)
                                <div class="bg-light p-2 rounded small mb-2 border">
                                    <i class="fas fa-handshake"></i> Partner: <strong>{{ $bet->partner->name ?? 'User' }}</strong>
                                </div>
                            @endif

                            <div class="mt-2">
                                @if($bet->status === 'matched')
                                    <form action="{{ route('betting.confirm', $bet) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-primary w-100 fw-bold">Confirm Participation</button>
                                    </form>
                                @elseif($bet->status === 'confirmed')
                                    <form action="{{ route('betting.lock', $bet) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-warning w-100 fw-bold">Lock & Debit Funds</button>
                                    </form>
                                @elseif($bet->status === 'locked' && $bet->match->status === 'finished')
                                    <form action="{{ route('betting.claim', $bet) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success w-100 fw-bold pulse-green">Claim Result</button>
                                    </form>
                                @endif
                            </div>

                            @if($bet->status === 'won')
                                <div class="alert alert-success mt-2 py-1 small mb-0 border-0">
                                    <i class="fas fa-trophy text-warning"></i> Won ₦{{ number_format($bet->payout, 2) }}!
                                </div>
                            @elseif($bet->status === 'lost')
                                <div class="alert alert-light mt-2 py-1 small mb-0 border-0 text-muted">
                                    Better luck next time.
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-coins fa-2x mb-2 opacity-25"></i>
                            <p class="small mb-0">You haven't placed any bets yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .pulse-green { animation: pulse-green 1.5s infinite; }
    @keyframes pulse-green { 0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); } }
</style>
@endsection
