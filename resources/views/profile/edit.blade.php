@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Profile</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', auth()->user()->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', auth()->user()->location) }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', auth()->user()->date_of_birth) }}">
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="favorite_club_id" class="form-label">Favorite Club</label>
                        <select class="form-select @error('favorite_club_id') is-invalid @enderror" id="favorite_club_id" name="favorite_club_id">
                            <option value="">Select a club</option>
                            @foreach ($clubs as $club)
                                <option value="{{ $club->id }}" @selected(old('favorite_club_id', auth()->user()->favorite_club_id) == $club->id)>{{ $club->name }}</option>
                            @endforeach
                        </select>
                        @error('favorite_club_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        @if (auth()->user()->profile_picture)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile picture" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture" accept="image/*">
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="cover_photo" class="form-label">Cover Photo</label>
                        @if (auth()->user()->cover_photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . auth()->user()->cover_photo) }}" alt="Cover photo" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('cover_photo') is-invalid @enderror" id="cover_photo" name="cover_photo" accept="image/*">
                        @error('cover_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                        <a href="{{ route('profile.show', auth()->user()) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
