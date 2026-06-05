@extends('layouts.app')

@section('title', 'News Feed')

@section('content')
<div class="row">
    <div class="col-lg-8">
        @auth
            <div class="card post-card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://via.placeholder.com/50' }}" alt="{{ auth()->user()->name }}" class="avatar">
                        <button type="button" class="btn btn-outline-secondary flex-grow-1 text-start" data-bs-toggle="modal" data-bs-target="#createPostModal">
                            What's on your mind, {{ auth()->user()->name }}?
                        </button>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createPostModal">
                            <i class="fas fa-pencil-alt"></i> Create Post
                        </button>
                        <button type="button" class="btn btn-light btn-sm">
                            <i class="fas fa-image"></i> Photo
                        </button>
                        <button type="button" class="btn btn-light btn-sm">
                            <i class="fas fa-video"></i> Live Video
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createPostModalLabel">Create Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="content" class="form-label">What's on your mind?</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" placeholder="Share your thoughts..." required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="club_id" class="form-label">Club</label>
                                        <select class="form-select @error('club_id') is-invalid @enderror" id="club_id" name="club_id">
                                            <option value="">Select a club (optional)</option>
                                            @foreach ($clubs ?? [] as $club)
                                                <option value="{{ $club->id }}" @selected(old('club_id') == $club->id)>{{ $club->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('club_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="post_type" class="form-label">Post Type</label>
                                        <select class="form-select @error('post_type') is-invalid @enderror" id="post_type" name="post_type">
                                            <option value="general" @selected(old('post_type') == 'general')>General</option>
                                            <option value="match_discussion" @selected(old('post_type') == 'match_discussion')>Match Discussion</option>
                                            <option value="transfer_news" @selected(old('post_type') == 'transfer_news')>Transfer News</option>
                                            <option value="player_stats" @selected(old('post_type') == 'player_stats')>Player Stats</option>
                                        </select>
                                        @error('post_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="video" class="form-label">Video</label>
                                        <input type="file" class="form-control @error('video') is-invalid @enderror" id="video" name="video" accept="video/*">
                                        @error('video')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Post</button>
                                </div>
                            </form>
                        </div>
                    </div>
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

@section('scripts')
    @if ($errors->any() && old('content'))
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                var createPostModal = new bootstrap.Modal(document.getElementById('createPostModal'));
                createPostModal.show();
            });
        </script>
    @endif
@endsection
