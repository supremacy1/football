<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostEngagementController;
use App\Http\Controllers\Post\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\LiveMatchController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('check-availability', [RegisterController::class, 'checkAvailability'])->name('check.availability');

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Post Routes
    Route::get('feed', [PostController::class, 'feed'])->name('feed');
    Route::get('news', [PostController::class, 'news'])->name('news');
    Route::get('posts/create', [PostController::class, 'showCreateForm'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Post Engagement Routes
    Route::post('posts/{post}/like', [PostEngagementController::class, 'likePost'])->name('posts.like');
    Route::post('posts/{post}/dislike', [PostEngagementController::class, 'dislikePost'])->name('posts.dislike');
    Route::post('posts/{post}/share', [PostEngagementController::class, 'sharePost'])->name('posts.share');

    // Comment Routes
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('comments/{comment}/like', [CommentController::class, 'likeComment'])->name('comments.like');

    // Profile Routes
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('profile/{user}/follow', [ProfileController::class, 'follow'])->name('profile.follow');
    Route::post('profile/{user}/unfollow', [ProfileController::class, 'unfollow'])->name('profile.unfollow');

    // Club Routes
    Route::get('clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::get('clubs/{club}', [ClubController::class, 'show'])->name('clubs.show');
    Route::post('clubs/{club}/join', [ClubController::class, 'join'])->name('clubs.join');
    Route::post('clubs/{club}/leave', [ClubController::class, 'leave'])->name('clubs.leave');
    Route::get('matches/create', [ClubController::class, 'createMatch'])->name('matches.create');
    Route::post('matches', [ClubController::class, 'storeMatch'])->name('matches.store');
    Route::put('matches/{match}/score', [ClubController::class, 'updateMatchScore'])->name('matches.updateScore');

    // Live Matches Route
    Route::get('live', [LiveMatchController::class, 'index'])->name('matches.live');
});
