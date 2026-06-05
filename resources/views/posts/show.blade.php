@extends('layouts.app')

@section('title', 'View Post')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
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
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="img-fluid rounded mb-2" style="max-height: 500px; width: 100%; object-fit: cover;">
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
                <button class="w-100"><i class="fas fa-comment"></i> Comment ({{ $post->comments_count }})</button>
                <button class="w-100"><i class="fas fa-share"></i> Share ({{ $post->shares_count }})</button>
            </div>
        </div>

        @auth
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">Add a Comment</h6>
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" name="content" rows="3" placeholder="Write a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Comment</button>
                    </form>
                </div>
            </div>
        @endauth

        @foreach ($post->comments as $comment)
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <img src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : 'https://via.placeholder.com/32' }}" alt="{{ $comment->user->name }}" class="avatar me-2" style="width: 32px; height: 32px;">
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('profile.show', $comment->user) }}" class="text-decoration-none">{{ $comment->user->name }}</a>
                                </h6>
                                <small class="text-muted">@{{ $comment->user->username }} · {{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @auth
                            @if (auth()->user()->id === $comment->user_id)
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger">Delete</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <p class="mb-2">{{ $comment->content }}</p>
                    @auth
                        <div>
                            <form action="{{ route('comments.like', $comment) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link @if ($comment->isLikedBy(auth()->user())) text-danger @endif">
                                    <i class="fas fa-heart"></i> {{ $comment->likes_count }}
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
