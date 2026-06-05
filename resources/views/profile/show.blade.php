@extends('layouts.app')

@section('title', $user->name . ' Profile')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            @if ($user->cover_photo)
                <img src="{{ asset('storage/' . $user->cover_photo) }}" alt="Cover" class="card-img-top" style="height: 250px; object-fit: cover;">
            @else
                <div style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            @endif

            <div class="card-body position-relative">
                <div class="d-flex justify-content-between align-items-start" style="margin-top: -70px;">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://via.placeholder.com/120' }}" alt="{{ $user->name }}" class="large-avatar border-4 border-white">
                    <div>
                        @auth
                            @if (auth()->user()->id !== $user->id)
                                @if (auth()->user()->isFollowing($user))
                                    <form action="{{ route('profile.unfollow', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-user-check"></i> Following
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('profile.follow', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-plus"></i> Follow
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Profile
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <h2 class="mt-3 mb-0">{{ $user->name }}</h2>
                <p class="text-muted mb-3">@{{ $user->username }}</p>

                @if ($user->bio)
                    <p class="mb-2">{{ $user->bio }}</p>
                @endif

                <div class="d-flex gap-4 mb-3 text-muted">
                    @if ($user->location)
                        <div>
                            <i class="fas fa-map-marker-alt"></i> {{ $user->location }}
                        </div>
                    @endif
                    @if ($user->date_of_birth)
                        <div>
                            <i class="fas fa-birthday-cake"></i> {{ $user->date_of_birth->format('M d, Y') }}
                        </div>
                    @endif
                    @if ($user->favoriteClub)
                        <div>
                            <i class="fas fa-heart text-danger"></i> {{ $user->favoriteClub->name }}
                        </div>
                    @endif
                </div>

                <div class="d-flex gap-4">
                    <div>
                        <h6 class="mb-0">{{ $user->getFollowingCount() }}</h6>
                        <small class="text-muted">Following</small>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $user->getFollowerCount() }}</h6>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $user->posts()->count() }}</h6>
                        <small class="text-muted">Posts</small>
                    </div>
                </div>
            </div>
        </div>

        @if ($user->clubMemberships->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Favorite Clubs</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($user->clubMemberships as $club)
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('clubs.show', $club) }}" class="text-decoration-none">
                                    <div class="card h-100">
                                        @if ($club->logo)
                                            <img src="{{ asset('storage/' . $club->logo) }}" alt="{{ $club->name }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $club->name }}</h6>
                                            <p class="text-muted small mb-0">{{ $club->members_count }} members</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div>
            <h4 class="mb-3">Posts</h4>
            @forelse ($user->posts as $post)
                <div class="card post-card">
                    <div class="post-header d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://via.placeholder.com/40' }}" alt="{{ $user->name }}" class="avatar me-3">
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('profile.show', $user) }}" class="text-decoration-none">{{ $user->name }}</a>
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
                        <p class="text-muted">No posts yet</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        <div class="sidebar">
            @auth
                @if (auth()->user()->id === $user->id)
                    <div class="card sidebar-card">
                        <div class="card-body">
                            <h6 class="card-title"><i class="fas fa-chart-bar"></i> Profile Stats</h6>
                            <p class="mb-2">
                                <strong>Email:</strong> {{ $user->email }}
                            </p>
                            <p class="mb-0">
                                <strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection
