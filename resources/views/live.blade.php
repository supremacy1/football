@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><span class="text-danger">●</span> Live & Today's Matches</h2>
        <!-- <span class="badge bg-dark">Powered by Free API Live Football Data</span> -->
    </div>

    @if(isset($error))
        <div class="alert alert-warning">{{ $error }}</div>
    @endif

    <div class="row">
        @forelse($matches as $match)
            @php
                $statusType = strtolower($match['status']['type'] ?? '');
                $isLive = $statusType === 'inprogress';
                $isFinished = $statusType === 'finished';
                $leagueName = $match['league']['name'] ?? 'Unknown League';
            @endphp
            <div class="col-md-12 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <small class="text-muted">{{ $leagueName }}</small>
                        @if($isLive)
                            <span class="badge bg-danger animate-pulse">LIVE {{ $match['status']['status'] }}</span>
                        @elseif($isFinished)
                            <span class="badge bg-secondary">FT</span>
                        @else
                            <span class="badge bg-primary">{{ $match['status']['status'] ?? 'Scheduled' }}</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center text-center">
                            <div class="col-4">
                                <img src="{{ $match['homeTeam']['logo'] ?? '' }}" alt="home" style="width: 40px; height: 40px;">
                                <p class="mt-2 mb-0 fw-bold small">{{ $match['homeTeam']['name'] }}</p>
                            </div>
                            <div class="col-4">
                                <h3 class="fw-bold mb-0">
                                    {{ $match['homeScore']['current'] ?? 0 }} - {{ $match['awayScore']['current'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="col-4">
                                <img src="{{ $match['awayTeam']['logo'] ?? '' }}" alt="away" style="width: 40px; height: 40px;">
                                <p class="mt-2 mb-0 fw-bold small">{{ $match['awayTeam']['name'] }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- The 'Free API Live Football Data' might not provide detailed 'events' in the same format as API-Football.
                         Check the actual API response (using dd($response->json()) in controller) for the correct keys.
                         Assuming 'description' and 'minute' for now based on common API structures. --}}
                    @if(isset($match['events']) && is_array($match['events']) && count($match['events']) > 0)
                        @php $latestEvent = end($match['events']); @endphp
                        <div class="card-footer bg-light py-2">
                            <small class="text-muted">Latest: {{ $latestEvent['description'] ?? 'Event' }} ({{ $latestEvent['minute'] ?? '' }}')</small>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-light rounded p-5">
                    <p class="text-muted mb-0">No matches found for today.</p>
                </div>
            </div>
        @endforelse
    </div>
    <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
     
        <span class="badge bg-dark">Powered by Free API Live Football Data</span>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endsection