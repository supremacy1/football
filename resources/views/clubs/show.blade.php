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
                                @if (auth()->user()->favorite_club_id === $club->id)
                                    <form action="{{ route('clubs.join', $club) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Join Club
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-light text-dark border p-2">
                                        <i class="fas fa-info-circle"></i> Only fans can join
                                    </span>
                                @endif
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
                        <h6 class="mb-0">{{ $club->posts_count }}</h6>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $club->players_count }}</h6>
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
            @auth
                @if ($club->isMember(auth()->user()))
                    <div class="mb-4">
                        <button type="button" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createClubPostModal">
                            <i class="fas fa-edit me-2"></i> Share something with the fans...
                        </button>
                    </div>
                @endif
            @endauth

            <h4 class="mb-3">Recent Posts</h4>
            @forelse ($club->posts as $post)
                <div class="card post-card">
                    <div class="post-header d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <img src="{{ ($post->user && $post->user->profile_picture) ? asset('storage/' . $post->user->profile_picture) : 'https://via.placeholder.com/40' }}" alt="{{ optional($post->user)->name }}" class="avatar me-3">
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ $post->user ? route('profile.show', $post->user) : '#' }}" class="text-decoration-none">{{ optional($post->user)->name ?? 'Unknown User' }}</a>
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

                    <div class="post-footer border-top p-0">
                        <div class="p-3 bg-light">
                            <h6 class="small fw-bold mb-3"><i class="fas fa-comments me-1"></i> Discussion</h6>
                            
                            <div class="comment-list mb-3">
                                @forelse($post->comments->take(5) as $comment)
                                    <div class="d-flex mb-2">
                                        <img src="{{ ($comment->user && $comment->user->profile_picture) ? asset('storage/' . $comment->user->profile_picture) : 'https://via.placeholder.com/32' }}" class="avatar-sm rounded-circle me-2" style="width: 32px; height: 32px;">
                                        <div class="bg-white p-2 rounded shadow-sm flex-grow-1 border">
                                            <div class="d-flex justify-content-between">
                                                <small class="fw-bold text-primary">{{ optional($comment->user)->name ?? 'Deleted User' }}</small>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0 small">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0 italic">No comments yet. Start the conversation!</p>
                                @endforelse
                            </div>

                            @auth
                                <form action="{{ route('comments.store', $post) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="content" class="form-control" placeholder="Write a comment..." required>
                                        <button class="btn btn-primary" type="submit">Reply</button>
                                    </div>
                                </form>
                            @endauth
                        </div>
                        
                        <div class="p-2 text-center border-top">
                            <a href="{{ route('posts.show', $post) }}" class="small text-decoration-none text-muted">
                                View all comments
                            </a>
                        </div>
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
        {{-- Upcoming Matches Card --}}
        @if ($upcomingMatches->count() > 0)
            <div class="card sidebar-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-calendar-alt"></i> Upcoming Matches</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ($upcomingMatches as $match)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">{{ $match->match_date->format('M d, H:i') }}</small>
                                @if ($match->league)
                                    <span class="badge bg-info">{{ $match->league }}</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="{{ ($match->homeClub && $match->homeClub->logo) ? asset('storage/' . $match->homeClub->logo) : 'https://via.placeholder.com/20' }}" alt="{{ optional($match->homeClub)->name }}" class="avatar me-2" style="width: 20px; height: 20px;">
                                    <strong>{{ optional($match->homeClub)->name ?? 'TBD' }}</strong>
                                </div>
                                <span class="mx-2">vs</span>
                                <div class="d-flex align-items-center">
                                    <strong>{{ optional($match->awayClub)->name ?? 'TBD' }}</strong>
                                    <img src="{{ ($match->awayClub && $match->awayClub->logo) ? asset('storage/' . $match->awayClub->logo) : 'https://via.placeholder.com/20' }}" alt="{{ optional($match->awayClub)->name }}" class="avatar ms-2" style="width: 20px; height: 20px;">
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1"><i class="fas fa-map-marker-alt"></i> {{ $match->venue }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="sidebar">
            @if ($club->members->count() > 0)
                <div class="card sidebar-card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-users"></i> Recent Members</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach ($club->members->take(5) as $member)
                            @if($member && $member->id) {{-- Ensure the member object is valid and has an ID --}}
                            <a href="{{ route('profile.show', $member) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $member->profile_picture ? asset('storage/' . $member->profile_picture) : 'https://via.placeholder.com/32' }}" alt="{{ $member->name }}" class="avatar me-2" style="width: 32px; height: 32px;">
                                    <div>
                                        <h6 class="mb-0">{{ $member->name }}</h6>
                                        <small class="text-muted">{{ '@' . $member->username }}</small>
                                    </div>
                                </div>
                            </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@auth
    @if ($club->isMember(auth()->user()))
        <!-- Create Post Modal -->
        <div class="modal fade" id="createClubPostModal" tabindex="-1" aria-labelledby="createClubPostModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createClubPostModalLabel">Post to {{ $club->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="club_id" value="{{ $club->id }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <textarea name="content" class="form-control" rows="4" placeholder="What's happening in the club?" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="postImage" class="form-label small">Attach Image (Optional)</label>
                                <input type="file" name="image" id="postImage" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">Post to Group</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth
@endsection
