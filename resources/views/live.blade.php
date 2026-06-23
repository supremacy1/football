@extends('layouts.app')

@section('title', 'Live Match Fixtures')

@section('styles')
<style>
.matches-wrapper{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap:16px;
    padding:10px;
}

.match-card{
    background:#0b0f1a;
    border-radius:14px;
    padding:15px;
    color:#fff;
    box-shadow:0 8px 20px rgba(0,0,0,0.25);
    transition:0.2s ease;
    border:1px solid rgba(255,255,255,0.05);
}

.match-card:hover{
    transform:translateY(-3px);
}

.match-card.live{
    border:1px solid #ff2d55;
}

.match-top{
    display:flex;
    justify-content:space-between;
    font-size:12px;
    margin-bottom:12px;
    opacity:0.8;
}

.status.live-badge{
    color:#ff2d55;
    font-weight:bold;
    animation: blink 1.2s infinite;
}

@keyframes blink{
    0%{opacity:1}
    50%{opacity:0.4}
    100%{opacity:1}
}

.teams{
    display:flex;
    align-items:center;
    justify-content:space-between;
    text-align:center;
}

.team{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:6px;
    width:40%;
}

.team img{
    width:38px;
    height:38px;
    object-fit:contain;
}

.score{
    font-size:20px;
    font-weight:700;
    width:20%;
    text-align:center;
    background:#111827;
    padding:6px 10px;
    border-radius:8px;
}
</style>
@endsection

@section('content')
    <h2 class="mt-4">Live Matches / Fixture</h2>
   <div class="matches-wrapper">
    @foreach ($matches as $match)
        @php
            $statusShort = $match['fixture']['status']['short'] ?? 'NS';
            $isLive = in_array($statusShort, ['1H','2H','HT','ET','P','BT','LIVE']);
            $homeTeam = $match['teams']['home'] ?? ['name' => 'Unknown', 'logo' => ''];
            $awayTeam = $match['teams']['away'] ?? ['name' => 'Unknown', 'logo' => ''];
            $homeScore = $match['goals']['home'] ?? null;
            $awayScore = $match['goals']['away'] ?? null;

            $time = isset($match['fixture']['timestamp'])
                ? \Carbon\Carbon::createFromTimestamp($match['fixture']['timestamp'])->format('H:i')
                : '--:--';
        @endphp

        <div class="match-card {{ $isLive ? 'live' : '' }}">
            
            <div class="match-top">
                <span class="time">{{ $time }}</span>

                <span class="status {{ $isLive ? 'live-badge' : '' }}">
                    {{ $isLive ? ($match['fixture']['status']['elapsed'] . "'") : $statusShort }}
                </span>
            </div>

            <div class="teams">
                <div class="team">
                    <img src="{{ $homeTeam['logo'] }}" />
                    <span>{{ $homeTeam['name'] }}</span>
                </div>

                <div class="score">
                    @if ($homeScore !== null && $awayScore !== null)
                        {{ $homeScore }} - {{ $awayScore }}
                    @else
                        VS
                    @endif
                </div>

                <div class="team">
                    <img src="{{ $awayTeam['logo'] }}" />
                    <span>{{ $awayTeam['name'] }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection