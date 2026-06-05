@extends('layouts.app')

@section('title', 'News Feed')

@section('content')
<div class="row">
    <div class="col-lg-8">
        @auth
            <div class="card post-card mb-4">
                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">What's on your mind?</label>
                            <textarea class="form-control" id="content" name="content" rows="4" placeholder="Share your thoughts..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="club_id" class="form-label">Club</label>
                            <select class="form-select" id="club_id" name="club_id">
                                <option value="">Select a club (optional)</option>
                                @foreach ($clubs ?? [] as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="post_type" class="form-label">Post Type</label>
                            <select class="form-select" id="post_type" name="post_type">
                                <option value="general">General</option>
                                <option value="match_discussion">Match Discussion</option>
                                <option value="transfer_news">Transfer News</option>
                                <option value="player_stats">Player Stats</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="video" class="form-label">Video</label>
                            <input type="file" class="form-control" id="video" name="video" accept="video/*">
                        </div>

                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                </div>
            </div>
        @endauth

        @forelse ($posts as $post)
            <div class="card post-card">
                <div class="post-header d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center">
                        <img src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://via.placeholder.com/40' }}" alt="{{ $post->user->name }}" class="avatar me-3">
                        <div>
                            <h6 class="mb-0">
                                <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">{{ $post->user->name }}</a>
                            </h6>
                            <small class="text-muted">@{{ $post->user->username }} · {{ $post->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @auth
                        @if (auth()->user()->id === $post->user_id)
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">Edit</a></li>
                                    <li><form action="{{ route('posts.destroy', $post) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                    </form></li>
                                </ul>
                            </div>
                        @endif
                    @endauth
                </div>

                @if ($post->club_id)
                    <div class="px-3 pt-2">
                        <span class="badge bg-info">{{ $post->club->name }}</span>
                    </div>
                @endif

                <div class="post-body">
                    <p class="mb-2">{{ $post->content }}</p>
                    @if ($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="img-fluid rounded mb-2" style="max-height: 400px; width: 100%; object-fit: cover;">
                    @endif
                    @if ($post->video)
                        <video width="100%" height="auto" controls class="rounded mb-2">
                            <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                        </video>
                    @endif
                </div>

                <div class="post-footer">
                    @auth
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="w-100 @if ($post->isLikedBy(auth()->user())) liked @endif">
                                <i class="fas fa-thumbs-up"></i> Like ({{ $post->likes_count }})
                            </button>
                        </form>
                        <form action="{{ route('posts.dislike', $post) }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="w-100 @if ($post->isDislikedBy(auth()->user())) liked @endif">
                                <i class="fas fa-thumbs-down"></i> Dislike ({{ $post->dislikes_count }})
                            </button>
                        </form>
                    @else
                        <button class="w-100"><i class="fas fa-thumbs-up"></i> Like ({{ $post->likes_count }})</button>
                        <button class="w-100"><i class="fas fa-thumbs-down"></i> Dislike ({{ $post->dislikes_count }})</button>
                    @endauth
                    <a href="{{ route('posts.show', $post) }}" class="w-100" style="color: #6c757d; text-decoration: none;">
                        <i class="fas fa-comment"></i> Comment ({{ $post->comments_count }})
                    </a>
                    <button class="w-100"><i class="fas fa-share"></i> Share ({{ $post->shares_count }})</button>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <p class="text-muted">No posts yet. Be the first to post!</p>
                </div>
            </div>
        @endforelse

        {{ $posts->links() }}
    </div>

    <div class="col-lg-4">
        <div class="sidebar">
            <div class="card sidebar-card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-fire"></i> Popular Clubs</h5>
                    <div class="list-group list-group-flush">
                        @forelse ($clubs ?? [] as $club)
                            <a href="{{ route('clubs.show', $club) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $club->name }}</h6>
                                        <small class="text-muted">{{ $club->members_count }} members</small>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="text-muted">No clubs available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
