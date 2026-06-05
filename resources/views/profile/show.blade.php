@extends('layouts.app')

@section('title', $user->name . ' Profile')

@section('content')
<div class="profile-cover mb-4">
    @if ($user->cover_photo)
        <div class="cover-wrapper" style="background-image: url('{{ asset('storage/' . $user->cover_photo) }}');"></div>
    @else
        <div class="cover-wrapper" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
    @endif
    <div class="cover-overlay container">
        <div class="d-flex align-items-end" style="min-height: 180px;">
            <div class="me-4 profile-avatar-wrap">
                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://via.placeholder.com/140' }}" alt="{{ $user->name }}" class="large-avatar border-4 border-white shadow">
            </div>
            <div class="flex-grow-1 text-white">
                <h2 class="mb-0">{{ $user->name }}</h2>
                <p class="mb-1">@{{ $user->username }}</p>
                @if ($user->bio)
                    <p class="small mb-0">{{ $user->bio }}</p>
                @endif
            </div>
            <div class="ms-auto">
                @auth
                    @if (auth()->user()->id !== $user->id)
                        @if (auth()->user()->isFollowing($user))
                            <form action="{{ route('profile.unfollow', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light">Following</button>
                            </form>
                        @else
                            <form action="{{ route('profile.follow', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-light">Follow</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('profile.edit') }}" class="btn btn-light">Edit Profile</a>
                    @endif
                @endauth
            </div>
        </div>
        <nav class="profile-nav mt-3">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" href="#posts" data-bs-toggle="tab">Posts</a></li>
                <li class="nav-item"><a class="nav-link" href="#about" data-bs-toggle="tab">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#photos" data-bs-toggle="tab">Photos</a></li>
            </ul>
        </nav>
    </div>
</div>

<div class="tab-content container">
    <div class="tab-pane active" id="posts">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-3">Posts</h4>
                @forelse ($user->posts as $post)
                    <div class="card post-card mb-3">
                        <div class="post-header d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://via.placeholder.com/40' }}" alt="{{ $user->name }}" class="avatar me-3">
                                <div>
                                    <h6 class="mb-0"><a href="{{ route('profile.show', $user) }}" class="text-decoration-none">{{ $user->name }}</a></h6>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="post-body">
                            <p class="mb-2">{{ $post->content }}</p>

                            @php
                                $images = null;
                                if (!empty($post->images)) {
                                    $decoded = json_decode($post->images, true);
                                    if (is_array($decoded)) {
                                        $images = $decoded;
                                    }
                                }
                                if (!$images && !empty($post->image)) {
                                    $images = [$post->image];
                                }
                            @endphp

                            @if (!empty($images) && count($images) > 0)
                                <div class="post-images mb-2">
                                    @if (count($images) == 1)
                                        @php $img = $images[0]; $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); @endphp
                                        <a href="#" class="open-image-modal d-block" data-post-id="{{ $post->id }}" data-index="0"><img src="{{ $src }}" alt="Post image" class="img-fluid rounded" style="max-height:500px; width:100%; object-fit:cover;"></a>
                                    @else
                                        <div class="row g-2">
                                            @foreach ($images as $i => $img)
                                                @php $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); @endphp
                                                <div class="col-6">
                                                    <a href="#" class="open-image-modal d-block" data-post-id="{{ $post->id }}" data-index="{{ $i }}">
                                                        <img src="{{ $src }}" alt="Post image" class="img-fluid rounded" style="height:200px; width:100%; object-fit:cover;">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Modal / Carousel for this post -->
                                <div class="modal fade" id="imageModal-{{ $post->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-dark">
                                            <div class="modal-body p-0">
                                                <div id="carousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @foreach ($images as $i => $img)
                                                            @php $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); @endphp
                                                            <div class="carousel-item @if($i==0) active @endif">
                                                                <img src="{{ $src }}" class="d-block w-100" style="max-height:80vh; object-fit:contain;" alt="">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="post-footer">
                            <a href="{{ route('posts.show', $post) }}" class="w-100" style="color: #6c757d; text-decoration: none;"><i class="fas fa-comment"></i> View Post</a>
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

            <div class="col-lg-4">
                <div class="sidebar">
                    @auth
                        @if (auth()->user()->id === $user->id)
                            <div class="card sidebar-card">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-chart-bar"></i> Profile Stats</h6>
                                    <p class="mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p class="mb-0"><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="about">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>About</h5>
                        <p class="mb-1"><strong>Location:</strong> {{ $user->location ?? '—' }}</p>
                        <p class="mb-1"><strong>Birthday:</strong> {{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : '—' }}</p>
                        <p class="mb-1"><strong>Favorite Club:</strong> {{ $user->favoriteClub->name ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>

    <div class="tab-pane" id="photos">
        <div class="row">
            <div class="col-lg-8">
                <h5 class="mb-3">Photos</h5>
                <div class="row g-2">
                    @foreach ($user->posts as $post)
                        @php
                            $images = null;
                            if (!empty($post->images)) {
                                $dec = json_decode($post->images, true);
                                if (is_array($dec)) $images = $dec;
                            }
                            if (!$images && !empty($post->image)) $images = [$post->image];
                        @endphp
                        @if (!empty($images))
                            @foreach ($images as $i => $img)
                                @php $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); @endphp
                                <div class="col-4">
                                    <a href="#" class="open-image-modal" data-post-id="{{ $post->id }}" data-index="{{ $i }}"><img src="{{ $src }}" class="img-fluid rounded" style="height:150px; width:100%; object-fit:cover;" alt=""></a>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</div>

@section('styles')
    <style>
        .cover-wrapper { height: 260px; background-size: cover; background-position: center; border-radius: 8px; }
        .cover-overlay { margin-top: -120px; }
        .profile-avatar-wrap { width: 140px; height: 140px; }
        .profile-nav .nav-link { color: #fff; background: rgba(0,0,0,0.2); border: none; }
        .post-images .img-fluid { cursor: pointer; }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.open-image-modal').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    var postId = el.getAttribute('data-post-id');
                    var index = parseInt(el.getAttribute('data-index') || 0);
                    var modalEl = document.getElementById('imageModal-' + postId);
                    if (!modalEl) return;
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                    var carouselEl = document.getElementById('carousel-' + postId);
                    if (carouselEl) {
                        var bsCarousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
                        bsCarousel.to(index);
                    }
                });
            });
        });
    </script>
@endsection

@endsection
