<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController
{
    use AuthorizesRequests;

    public function feed()
    {
        $posts = Post::with(['user.favoriteClub', 'club', 'comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $clubs = Club::orderBy('name')->get();
        return view('posts.feed', ['posts' => $posts, 'clubs' => $clubs]);
    }

    public function news()
    {
        $posts = Post::with(['user.favoriteClub', 'club', 'comments', 'likes'])
            ->whereIn('post_type', ['transfer_news', 'player_stats'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $clubs = Club::orderBy('name')->get();
        return view('posts.news', ['posts' => $posts, 'clubs' => $clubs]);
    }

    public function showCreateForm()
    {
        $clubs = Club::orderBy('name')->get();
        return view('posts.create', ['clubs' => $clubs]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'image' => 'nullable|image|max:5120',
            'video' => 'nullable|mimetypes:video/mp4,video/mpeg|max:52428800',
            'club_id' => 'nullable|exists:clubs,id',
            'post_type' => 'in:general,match_discussion,transfer_news,player_stats',
        ]);

        $post = new Post($request->except(['image', 'video']));
        $post->user_id = Auth::id();

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('posts/images', 'public');
        }

        if ($request->hasFile('video')) {
            $post->video = $request->file('video')->store('posts/videos', 'public');
        }

        $post->save();

        return redirect()->route('feed')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['user.favoriteClub', 'club', 'comments.user', 'comments.replies.user', 'likes']);
        return view('posts.show', ['post' => $post]);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $clubs = Club::orderBy('name')->get();
        return view('posts.edit', ['post' => $post, 'clubs' => $clubs]);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'image' => 'nullable|image|max:5120',
            'video' => 'nullable|mimetypes:video/mp4,video/mpeg|max:52428800',
            'club_id' => 'nullable|exists:clubs,id',
            'post_type' => 'in:general,match_discussion,transfer_news,player_stats',
        ]);

        $post->update($request->except(['image', 'video']));

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = $request->file('image')->store('posts/images', 'public');
            $post->save();
        }

        if ($request->hasFile('video')) {
            if ($post->video) {
                Storage::disk('public')->delete($post->video);
            }
            $post->video = $request->file('video')->store('posts/videos', 'public');
            $post->save();
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return redirect()->route('feed')->with('success', 'Post deleted successfully!');
    }
}
