@extends('layouts.app')

@section('title', $user->name . ' — Profile')

@section('content')
<div class="profile-file-page">
    @if (!empty($user->cover_photo))
        <div class="cover-photo shadow-sm" style="background-image: url('{{ asset('storage/' . $user->cover_photo) }}'); height: 350px; background-size: cover; background-position: center; border-radius: 0 0 8px 8px;"></div>
    @else
        <div class="cover-photo bg-light shadow-sm" style="height:350px; border-radius: 0 0 8px 8px; background: linear-gradient(to bottom, #f0f2f5, #e4e6eb);"></div>
    @endif

    <div class="container">
        <div class="profile-header-content d-flex flex-column flex-md-row align-items-center align-items-md-end px-4">
            <div class="profile-avatar-container">
                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://via.placeholder.com/168' }}" alt="{{ $user->name }}" class="profile-img shadow">
            </div>
            <div class="ms-md-3 mb-md-3 text-center text-md-start flex-grow-1">
                <h1 class="fw-bold mb-0" style="font-size: 2rem;">{{ $user->name }}</h1>
                <p class="text-muted fw-semibold mb-0">{{ '@' . $user->username }}</p>
                @if($user->bio)<p class="mt-1 small">{{ $user->bio }}</p>@endif
            </div>
        </div>
    </div>
</div>

@section('styles')
    <style>
        .profile-file-page { background: white; padding-bottom: 20px; border-bottom: 1px solid #ddd; }
        .profile-header-content { margin-top: -90px; position: relative; z-index: 5; }
        .profile-avatar-container { width: 168px; height: 168px; }
        .profile-img { 
            width: 168px; height: 168px; object-fit: cover; 
            border-radius: 50%; border: 4px solid #fff; background: #fff;
        }
    </style>
@endsection

@endsection
