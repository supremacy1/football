@extends('layouts.app')

@section('title', $user->name . ' — Profile')

@section('content')
<div class="profile-file-page">
    @if (!empty($user->cover_photo))
        <div class="cover-photo" style="background-image: url('{{ asset('storage/' . $user->cover_photo) }}'); height: 300px; background-size: cover; background-position: center; border-radius:8px;"></div>
    @else
        <div class="cover-photo bg-secondary" style="height:300px; border-radius:8px;"></div>
    @endif

    <div class="container mt-3">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://via.placeholder.com/150' }}" alt="{{ $user->name }}" style="width:150px; height:150px; object-fit:cover; border-radius:50%; border:5px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
            </div>
            <div>
                <h2 class="mb-0">{{ $user->name }}</h2>
                <p class="text-muted mb-0">@{{ $user->username }}</p>
                @if($user->bio)
                    <p class="mt-2">{{ $user->bio }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

@section('styles')
    <style>
        .profile-file-page .cover-photo { border-radius: 6px; }
        .profile-file-page .container { margin-top: -75px; }
    </style>
@endsection

@endsection
