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
                            <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none text-white">{{ $post->user->name }}</a>
                        </h6>
                        <small class="text-muted">
                            {{ '@' . $post->user->username }} · 
                            @if($post->user->favoriteClub)
                                <span class="text-white fw-semibold"><i class="fas fa-shield-alt small"></i> {{ $post->user->favoriteClub->name }}</span> ·
                            @endif
                            {{ $post->created_at->diffForHumans() }}</small>
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
                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
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
                    <button type="button" class="w-100 engagement-btn like-btn @if ($post->isLikedBy(auth()->user())) liked @endif" data-post-id="{{ $post->id }}" onclick="handleEngagement(this, 'like')">
                        <i class="fas fa-thumbs-up"></i> <span>Like ({{ $post->likes_count }})</span>
                    </button>
                    <button type="button" class="w-100 engagement-btn dislike-btn @if ($post->isDislikedBy(auth()->user())) liked @endif" data-post-id="{{ $post->id }}" onclick="handleEngagement(this, 'dislike')">
                        <i class="fas fa-thumbs-down"></i> <span>Dislike ({{ $post->dislikes_count }})</span>
                    </button>
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
                                <h6 class="mb-0 text-white">
                                    <a href="{{ route('profile.show', $comment->user) }}" class="text-decoration-none text-white">{{ $comment->user->name }}</a>
                                </h6>
                                <small class="text-muted text-white">{{ '@' . $comment->user->username }} · {{ $comment->created_at->diffForHumans() }}</small>
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
                            <button type="button" class="btn btn-sm btn-link @if ($comment->isLikedBy(auth()->user())) text-danger @endif" onclick="handleCommentLike(this, '{{ $comment->id }}')">
                                <i class="fas fa-heart"></i> {{ $comment->likes_count }}
                            </button>
                            <button type="button" class="btn btn-sm btn-link text-white" onclick="toggleReplyForm({{ $comment->id }})">
                                <i class="fas fa-reply"></i> Reply
                            </button>
                        </div>
                        <div id="reply-form-{{ $comment->id }}" class="mt-2 d-none">
                            <form action="{{ route('comments.store', $post) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
                                <div class="input-group">
                                    <input type="text" name="content" class="form-control form-control-sm" placeholder="Write a reply..." required>
                                    <button type="submit" class="btn btn-primary btn-sm">Reply</button>
                                </div>
                            </form>
                        </div>
                    @endauth
                </div>

                @if($comment->replies->count() > 0)
                    <div class="ms-5 mb-3 pe-3">
                        @foreach ($comment->replies as $reply)
                            <div class="bg-light p-2 rounded mb-2 border-start border-primary border-4">
                                <div class="d-flex align-items-center mb-1">
                                    <img src="{{ $reply->user->profile_picture ? asset('storage/' . $reply->user->profile_picture) : 'https://via.placeholder.com/24' }}" class="avatar me-2" style="width: 24px; height: 24px;">
                                    <small class="fw-bold">{{ $reply->user->name }}</small>
                                    <small class="text-muted ms-2">{{ $reply->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 small">{{ $reply->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.classList.toggle('d-none');
}

async function handleEngagement(btn, type) {
    const postId = btn.getAttribute('data-post-id');
    const url = `/posts/${postId}/${type}`;
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        const postCard = btn.closest('.card');
        const likeBtn = postCard.querySelector('.like-btn');
        const dislikeBtn = postCard.querySelector('.dislike-btn');
        
        if(data.liked) likeBtn.classList.add('liked'); else likeBtn.classList.remove('liked');
        if(data.disliked) dislikeBtn.classList.add('liked'); else dislikeBtn.classList.remove('liked');
        
        likeBtn.querySelector('span').textContent = `Like (${data.likes_count})`;
        dislikeBtn.querySelector('span').textContent = `Dislike (${data.dislikes_count})`;
    } catch (error) {
        console.error('Engagement failed:', error);
    }
}

async function handleCommentLike(btn, commentId) {
    const url = `/comments/${commentId}/like`;
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        if(data.liked) btn.classList.add('text-danger'); else btn.classList.remove('text-danger');
        btn.innerHTML = `<i class="fas fa-heart"></i> ${data.likes_count}`;
    } catch (error) {
        console.error('Comment like failed:', error);
    }
}
</script>
@endsection
