@extends('layouts.app')

@section('title', 'Create Match')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create New Match</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('matches.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="home_club_id" class="form-label">Home Team *</label>
                        <select class="form-select @error('home_club_id') is-invalid @enderror" id="home_club_id" name="home_club_id" required>
                            <option value="">Select home team</option>
                            @foreach ($clubs as $club)
                                <option value="{{ $club->id }}" @selected(old('home_club_id') == $club->id)>{{ $club->name }}</option>
                            @endforeach
                        </select>
                        @error('home_club_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="away_club_id" class="form-label">Away Team *</label>
                        <select class="form-select @error('away_club_id') is-invalid @enderror" id="away_club_id" name="away_club_id" required>
                            <option value="">Select away team</option>
                            @foreach ($clubs as $club)
                                <option value="{{ $club->id }}" @selected(old('away_club_id') == $club->id)>{{ $club->name }}</option>
                            @endforeach
                        </select>
                        @error('away_club_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="match_date" class="form-label">Match Date & Time *</label>
                        <input type="datetime-local" class="form-control @error('match_date') is-invalid @enderror" id="match_date" name="match_date" value="{{ old('match_date') }}" required>
                        @error('match_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="venue" class="form-label">Venue</label>
                        <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue" value="{{ old('venue') }}">
                        @error('venue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="league" class="form-label">League</label>
                        <input type="text" class="form-control @error('league') is-invalid @enderror" id="league" name="league" value="{{ old('league') }}">
                        @error('league')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Create Match</button>
                        <a href="{{ route('clubs.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
