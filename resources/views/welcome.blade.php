@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="fas fa-football-ball" style="font-size: 4rem; color: #1f4788; margin-bottom: 20px;"></i>
                <h1 class="display-4 mb-4">Welcome to Football Social Media</h1>
                <p class="lead mb-4">Connect with football fans, share your passion, and stay updated with the latest football news and discussions.</p>

                <div class="row mb-5">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-users" style="font-size: 2rem; color: #1f4788; margin-bottom: 15px;"></i>
                                <h5 class="card-title">Connect with Fans</h5>
                                <p class="card-text">Join a vibrant community of football enthusiasts from around the world.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-comment-dots" style="font-size: 2rem; color: #1f4788; margin-bottom: 15px;"></i>
                                <h5 class="card-title">Share & Discuss</h5>
                                <p class="card-text">Share your thoughts, post match updates, and engage in discussions with other fans.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-star" style="font-size: 2rem; color: #1f4788; margin-bottom: 15px;"></i>
                                <h5 class="card-title">Support Your Club</h5>
                                <p class="card-text">Join your favorite club's fan group and follow their journey together.</p>
                            </div>
                        </div>
                    </div>
                </div>

                @guest
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus"></i> Create Account
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </div>
                @else
                    <a href="{{ route('feed') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Go to Feed
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
