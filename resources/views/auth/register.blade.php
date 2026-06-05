@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-5">
                <h2 class="card-title mb-4 text-center">Create Account</h2>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="favorite_club_id" class="form-label">Favorite Club (optional)</label>
                        <select class="form-select @error('favorite_club_id') is-invalid @enderror" id="favorite_club_id" name="favorite_club_id">
                            <option value="">Select a club</option>
                            @if(isset($clubs) && $clubs->count())
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}" {{ old('favorite_club_id') == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('favorite_club_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
                </form>

                <p class="text-center mb-0">
                    Already have an account? <a href="{{ route('login') }}">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
 
<script>
document.addEventListener('DOMContentLoaded', function () {
    var pwd = document.getElementById('password');
    var pwdConf = document.getElementById('password_confirmation');
    var submit = document.querySelector('form[action="{{ route('register') }}"] button[type="submit"]');

    function showFeedback(message) {
        var fb = pwdConf.parentNode.querySelector('.invalid-feedback.client-side');
        if (!fb) {
            fb = document.createElement('div');
            fb.className = 'invalid-feedback client-side';
            pwdConf.parentNode.appendChild(fb);
        }
        fb.textContent = message;
    }

    function clearFeedback() {
        var fb = pwdConf.parentNode.querySelector('.invalid-feedback.client-side');
        if (fb) fb.textContent = '';
    }

    function validate() {
        if (!pwd || !pwdConf || !submit) return;
        if (pwd.value === '' && pwdConf.value === '') {
            pwdConf.classList.remove('is-invalid');
            clearFeedback();
            submit.disabled = false;
            return;
        }

        if (pwd.value !== pwdConf.value) {
            pwdConf.classList.add('is-invalid');
            showFeedback('Passwords do not match');
            submit.disabled = true;
        } else {
            pwdConf.classList.remove('is-invalid');
            clearFeedback();
            submit.disabled = false;
        }
    }

    if (pwd && pwdConf) {
        pwd.addEventListener('input', validate);
        pwdConf.addEventListener('input', validate);
    }
});
</script>

@endsection
