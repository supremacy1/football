<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostEngagementController
{
    public function likePost(Request $request, Post $post)
    {
        $user = Auth::user();
        
        if ($post->isDislikedBy($user)) {
            $post->toggleDislike($user);
        }
        
        $post->toggleLike($user);

        if ($request->expectsJson()) {
            return response()->json([
                'liked' => $post->isLikedBy($user),
                'disliked' => $post->isDislikedBy($user),
                'likes_count' => $post->likes_count,
                'dislikes_count' => $post->dislikes_count,
            ]);
        }

        return back();
    }

    public function dislikePost(Request $request, Post $post)
    {
        $user = Auth::user();
        
        if ($post->isLikedBy($user)) {
            $post->toggleLike($user);
        }
        
        $post->toggleDislike($user);

        if ($request->expectsJson()) {
            return response()->json([
                'disliked' => $post->isDislikedBy($user),
                'liked' => $post->isLikedBy($user),
                'likes_count' => $post->likes_count,
                'dislikes_count' => $post->dislikes_count,
            ]);
        }

        return back();
    }

    public function sharePost(Request $request, Post $post)
    {
        $post->increment('shares_count');

        if ($request->expectsJson()) {
            return response()->json(['shares_count' => $post->shares_count]);
        }

        return back();
    }
}
