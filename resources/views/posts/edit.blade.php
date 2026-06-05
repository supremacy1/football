@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Post</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="content" class="form-label">Content *</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="club_id" class="form-label">Club</label>
                        <select class="form-select @error('club_id') is-invalid @enderror" id="club_id" name="club_id">
                            <option value="">Select a club (optional)</option>
                            @foreach ($clubs as $club)
                                <option value="{{ $club->id }}" @selected(old('club_id', $post->club_id) == $club->id)>{{ $club->name }}</option>
                            @endforeach
                        </select>
                        @error('club_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="post_type" class="form-label">Post Type</label>
                        <select class="form-select @error('post_type') is-invalid @enderror" id="post_type" name="post_type">
                            <option value="general" @selected(old('post_type', $post->post_type) == 'general')>General</option>
                            <option value="match_discussion" @selected(old('post_type', $post->post_type) == 'match_discussion')>Match Discussion</option>
                            <option value="transfer_news" @selected(old('post_type', $post->post_type) == 'transfer_news')>Transfer News</option>
                            <option value="player_stats" @selected(old('post_type', $post->post_type) == 'player_stats')>Player Stats</option>
                        </select>
                        @error('post_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        @if ($post->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Current image" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
