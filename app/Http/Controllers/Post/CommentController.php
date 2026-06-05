<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'parent_comment_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'parent_comment_id' => $validated['parent_comment_id'] ?? null,
        ]);

        $post->increment('comments_count');

        if ($request->expectsJson()) {
            return response()->json([
                'comment' => $comment->load('user'),
                'comments_count' => $post->comments_count,
            ], 201);
        }

        return back();
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->update($validated);

        if ($request->expectsJson()) {
            return response()->json($comment->load('user'));
        }

        return back();
    }

    public function destroy(Request $request, Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $post = $comment->post;
        $comment->delete();
        $post->decrement('comments_count');

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function likeComment(Request $request, Comment $comment)
    {
        $user = Auth::user();
        $comment->toggleLike($user);

        if ($request->expectsJson()) {
            return response()->json([
                'liked' => $comment->isLikedBy($user),
                'likes_count' => $comment->likes_count,
            ]);
        }

        return back();
    }
}
