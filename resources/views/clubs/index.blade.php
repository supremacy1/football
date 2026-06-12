@extends('layouts.app')

@section('title', 'Football Clubs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Football Clubs</h2>
            @auth
                <a href="{{ route('matches.create') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Create Match
                </a>
            @endauth
        </div>

        <div class="row">
            @forelse ($clubs as $club)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        @if ($club->logo)
                            <img src="{{ str_starts_with($club->logo, 'http') ? $club->logo : asset('storage/' . $club->logo) }}" alt="{{ $club->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-shield-alt" style="font-size: 3rem;"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $club->name }}</h5>
                            <p class="card-text text-muted">{{ $club->description ?? 'No description' }}</p>

                            <div class="mb-3">
                                @if ($club->country)
                                    <small class="text-muted d-block"><i class="fas fa-flag"></i> {{ $club->country }}</small>
                                @endif
                                @if ($club->founded_year)
                                    <small class="text-muted d-block"><i class="fas fa-calendar"></i> Founded {{ $club->founded_year }}</small>
                                @endif
                                <small class="text-muted d-block"><i class="fas fa-users"></i> {{ $club->members_count }} Members</small>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('clubs.show', $club) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                    View Club
                                </a>
                                @auth
                                    @if ($club->isMember(auth()->user()))
                                        <form action="{{ route('clubs.leave', $club) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger w-100">Leave</button>
                                        </form>
                                    @else
                                        <form action="{{ route('clubs.join', $club) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary w-100">Join</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <p class="text-muted">No clubs available</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{ $clubs->links() }}
    </div>
</div>
@endsection
