@extends('layouts.app')

@section('title', $club->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            @if ($club->banner)
                <img src="{{ asset('storage/' . $club->banner) }}" alt="{{ $club->name }}" class="card-img-top" style="height: 300px; object-fit: cover;">
            @else
                <div style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            @endif

            <div class="card-body position-relative">
                <div class="d-flex justify-content-between align-items-start" style="margin-top: -80px;">
                    @if ($club->logo)
                        <img src="{{ asset('storage/' . $club->logo) }}" alt="{{ $club->name }}" class="large-avatar border-4 border-white">
                    @else
                        <div class="large-avatar border-4 border-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 3rem; color: white;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    @endif
                    <div>
                        @auth
                            @if ($club->isMember(auth()->user()))
                                <form action="{{ route('clubs.leave', $club) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-sign-out-alt"></i> Leave Club
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('clubs.join', $club) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Join Club
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>

                <h2 class="mt-3 mb-0">{{ $club->name }}</h2>

                @if ($club->description)
                    <p class="mb-3">{{ $club->description }}</p>
                @endif

                <div class="d-flex gap-4 mb-3 text-muted">
                    @if ($club->country)
                        <div>
                            <i class="fas fa-flag"></i> {{ $club->country }}
                        </div>
                    @endif
                    @if ($club->founded_year)
                        <div>
                            <i class="fas fa-calendar"></i> Founded {{ $club->founded_year }}
                        </div>
                    @endif
                </div>

                <div class="d-flex gap-4">
                    <div>
                        <h6 class="mb-0">{{ $club->members_count }}</h6>
                        <small class="text-muted">Members</small>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $club->posts()->count() }}</h6>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $club->players()->count() }}</h6>
                        <small class="text-muted">Players</small>
                    </div>
                </div>
            </div>
        </div>

        @if ($club->players->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Squad</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($club->players as $player)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start p-3 border rounded">
                                    <div>
                                        <h6 class="mb-1">{{ $player->name }}</h6>
                                        <small class="text-muted d-block">{{ $player->position }}</small>
                                        <small class="text-muted d-block">{{ $player->nationality }}</small>
                                    </div>
                                    <span class="badge bg-primary">#{{ $player->jersey_number }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div>
            <h4 class="mb-3">Recent Posts</h4>
            @forelse ($club->posts as $post)
                <div class="card post-card">
                    <div class="post-header d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <img src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://via.placeholder.com/40' }}" alt="{{ $post->user->name }}" class="avatar me-3">
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">{{ $post->user->name }}</a>
                                </h6>
                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="post-body">
                        <p class="mb-2">{{ $post->content }}</p>
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="img-fluid rounded mb-2" style="max-height: 400px; width: 100%; object-fit: cover;">
                        @endif
                    </div>

                    <div class="post-footer">
                        <a href="{{ route('posts.show', $post) }}" class="w-100" style="color: #6c757d; text-decoration: none;">
                            <i class="fas fa-comment"></i> View Post
                        </a>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <p class="text-muted">No posts yet for this club</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        <div class="sidebar">
            @if ($club->members->count() > 0)
                <div class="card sidebar-card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-users"></i> Recent Members</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach ($club->members->take(5) as $member)
                            <a href="{{ route('profile.show', $member) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $member->profile_picture ? asset('storage/' . $member->profile_picture) : 'https://via.placeholder.com/32' }}" alt="{{ $member->name }}" class="avatar me-2" style="width: 32px; height: 32px;">
                                    <div>
                                        <h6 class="mb-0">{{ $member->name }}</h6>
                                        <small class="text-muted">@{{ $member->username }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
