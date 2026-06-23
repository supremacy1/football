@extends('layouts.app')

@section('title', 'Register')

@section('content')
<style>
    body {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1529900748604-07564a03e7a6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    }
    .btn-football {
        background-color: #198754;
        border-color: #198754;
        color: white;
        font-weight: bold;
        padding: 12px;
        transition: all 0.3s ease;
    }
    .btn-football:hover {
        background-color: #146c43;
        transform: translateY(-2px);
    }
    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }
    .form-label {
        font-weight: bold;
        color: #040316;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card auth-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-user-plus fa-3x text-success mb-3"></i>
                    <h2 class="fw-bold text-dark"><i class="fas fa-futbol text-success me-2"></i>JOIN THE SQUAD</h2>
                    <p class="text-muted">Create your fan profile today</p>
                </div>

                <form action="/register" method="POST">
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
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="+234..." required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select @error('country') is-invalid @enderror" id="country" name="country" required>
                            <option value="">Select your country</option>
                            @php
                                $majorCountries = [
                                    'Nigeria', 'Ghana', 'South Africa', 'Kenya', 'Egypt', 'Cameroon', 'Senegal',
                                    'United Kingdom', 'United States', 'Brazil', 'Argentina', 'France', 
                                    'Germany', 'Spain', 'Portugal', 'Italy', 'Netherlands'
                                ];
                            @endphp
                            @foreach($majorCountries as $country)
                                <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="favorite_club_id" class="form-label">Favorite Club</label>
                        <select class="form-select @error('favorite_club_id') is-invalid @enderror" id="favorite_club_id" name="favorite_club_id" required>
                            <option value="">-- Select Your Squad --</option>
                            <option value="17" {{ old('favorite_club_id') == 17 ? 'selected' : '' }}>AC Milan</option>
                            <option value="4" {{ old('favorite_club_id') == 4 ? 'selected' : '' }}>Arsenal FC</option>
                            <option value="30" {{ old('favorite_club_id') == 30 ? 'selected' : '' }}>AS Monaco</option>
                            <option value="19" {{ old('favorite_club_id') == 19 ? 'selected' : '' }}>AS Roma</option>
                            <option value="7" {{ old('favorite_club_id') == 7 ? 'selected' : '' }}>Aston Villa</option>
                            <option value="21" {{ old('favorite_club_id') == 21 ? 'selected' : '' }}>Atalanta BC</option>
                            <option value="13" {{ old('favorite_club_id') == 13 ? 'selected' : '' }}>Athletic Club</option>
                            <option value="11" {{ old('favorite_club_id') == 11 ? 'selected' : '' }}>Atletico Madrid</option>
                            <option value="24" {{ old('favorite_club_id') == 24 ? 'selected' : '' }}>Bayer Leverkusen</option>
                            <option value="22" {{ old('favorite_club_id') == 22 ? 'selected' : '' }}>Bayern Munich</option>
                            <option value="23" {{ old('favorite_club_id') == 23 ? 'selected' : '' }}>Borussia Dortmund</option>
                            <option value="5" {{ old('favorite_club_id') == 5 ? 'selected' : '' }}>Chelsea FC</option>
                            <option value="9" {{ old('favorite_club_id') == 9 ? 'selected' : '' }}>FC Barcelona</option>
                            <option value="14" {{ old('favorite_club_id') == 14 ? 'selected' : '' }}>Girona FC</option>
                            <option value="16" {{ old('favorite_club_id') == 16 ? 'selected' : '' }}>Inter Milan</option>
                            <option value="15" {{ old('favorite_club_id') == 15 ? 'selected' : '' }}>Juventus</option>
                            <option value="3" {{ old('favorite_club_id') == 3 ? 'selected' : '' }}>Liverpool FC</option>
                            <option value="2" {{ old('favorite_club_id') == 2 ? 'selected' : '' }}>Manchester City</option>
                            <option value="1" {{ old('favorite_club_id') == 1 ? 'selected' : '' }}>Manchester United</option>
                            <option value="8" {{ old('favorite_club_id') == 8 ? 'selected' : '' }}>Newcastle United</option>
                            <option value="29" {{ old('favorite_club_id') == 29 ? 'selected' : '' }}>Olympique Lyon</option>
                            <option value="28" {{ old('favorite_club_id') == 28 ? 'selected' : '' }}>Olympique Marseille</option>
                            <option value="27" {{ old('favorite_club_id') == 27 ? 'selected' : '' }}>Paris Saint-Germain</option>
                            <option value="25" {{ old('favorite_club_id') == 25 ? 'selected' : '' }}>RB Leipzig</option>
                            <option value="10" {{ old('favorite_club_id') == 10 ? 'selected' : '' }}>Real Madrid</option>
                            <option value="12" {{ old('favorite_club_id') == 12 ? 'selected' : '' }}>Real Sociedad</option>
                            <option value="20" {{ old('favorite_club_id') == 20 ? 'selected' : '' }}>SS Lazio</option>
                            <option value="18" {{ old('favorite_club_id') == 18 ? 'selected' : '' }}>SSC Napoli</option>
                            <option value="6" {{ old('favorite_club_id') == 6 ? 'selected' : '' }}>Tottenham Hotspur</option>
                            <option value="26" {{ old('favorite_club_id') == 26 ? 'selected' : '' }}>VfB Stuttgart</option>
                            <option value="0">Others</option>
                        </select>
                        @error('favorite_club_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="fas fa-futbol text-muted"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small id="password-hint" class="text-muted d-block mt-1">Minimum 8 characters required.</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check-circle text-muted"></i></span>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-football w-100 mb-3 text-uppercase"  style="background-color: #03031a;">Sign Me Up</button>
                </form>

                <p class="text-center mb-0 text-muted text-dark">
                    Already in the squad? <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none ">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
 
<script>
document.addEventListener('DOMContentLoaded', function () {
    var pwd = document.getElementById('password');
    var pwdConf = document.getElementById('password_confirmation');
    var submit = document.querySelector('button[type="submit"]');
    var emailInput = document.getElementById('email');
    var phoneInput = document.getElementById('phone_number');
    var countryInput = document.getElementById('country');
    var usernameInput = document.getElementById('username');
    var nameInput = document.getElementById('name');
    var dobInput = document.getElementById('date_of_birth');
    var clubInput = document.getElementById('favorite_club_id');
    var checkTimeout;

    // Track which fields have been interacted with to avoid showing "Required" errors on a fresh form
    var touchedFields = new Set();

    // Initialize state flags
    var availability = { 
        nameValid: false, 
        usernameFilled: false, 
        usernameUnique: false, 
        emailFilled: false, 
        emailUnique: false, 
        passwordLength: false, 
        passwordMatch: false,
        phoneFilled: false,
        countryFilled: false,
        dobFilled: false,
        clubFilled: false
    };

    function showFieldError(inputId, message) {
        var input = document.getElementById(inputId);
        if (message) {
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    }

    async function checkAvailability(type, value) {
        // If field is empty, it's not unique, but also not "taken"
        // The 'filled' check will handle its emptiness
        if (!value.trim()) {
            availability[type + 'Unique'] = false; // e.g., availability.emailUnique = false
            updateSubmitButton();
            return;
        }
        try {
            let response = await fetch('{{ route('check.availability') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type: type, value: value })
            });
            let data = await response.json();
            if (data.exists) {
                const errorMsg = (type === 'email' ? 'The email has already been taken.' : 'The username has already been taken.');
                if (window.showModalAlert) {
                    window.showModalAlert('Registration Error', errorMsg, true);
                }
                showFieldError(type, errorMsg);
                availability[type + 'Unique'] = false;
            } else {
                showFieldError(type, null);
                availability[type + 'Unique'] = true;
            }
            updateSubmitButton();
        } catch (error) {
            console.error('Error checking availability:', error);
        }
    }

    function validate() {
        // Name validation
        if (nameInput.value.trim().length > 0) {
            showFieldError('name', '');
            availability.nameValid = true;
        } else {
            if (touchedFields.has('name')) showFieldError('name', 'Full Name is required.');
            availability.nameValid = false;
        }

        // Email filled check
        if (emailInput.value.trim().length > 0) {
            availability.emailFilled = true;
            showFieldError('email', '');
        } else {
            if (touchedFields.has('email')) showFieldError('email', 'Email is required.');
            availability.emailFilled = false;
        }

        // Username filled check
        if (usernameInput.value.trim().length > 0) {
            availability.usernameFilled = true;
            showFieldError('username', '');
        } else {
            if (touchedFields.has('username')) showFieldError('username', 'Username is required.');
            availability.usernameFilled = false;
        }

        // Phone filled check
        if (phoneInput.value.trim().length > 0) {
            availability.phoneFilled = true;
            showFieldError('phone_number', '');
        } else {
            if (touchedFields.has('phone_number')) showFieldError('phone_number', 'Phone Number is required.');
            availability.phoneFilled = false;
        }

        // Country filled check
        if (countryInput.value !== "") {
            availability.countryFilled = true;
            showFieldError('country', '');
        } else {
            if (touchedFields.has('country')) showFieldError('country', 'Country selection is required.');
            availability.countryFilled = false;
        }

        // Date of Birth check
        if (dobInput.value !== "") {
            availability.dobFilled = true;
            showFieldError('date_of_birth', '');
        } else {
            if (touchedFields.has('date_of_birth')) showFieldError('date_of_birth', 'Date of Birth is required.');
            availability.dobFilled = false;
        }

        // Club selection check
        if (clubInput.value !== "") {
            availability.clubFilled = true;
            showFieldError('favorite_club_id', '');
        } else {
            if (touchedFields.has('favorite_club_id')) showFieldError('favorite_club_id', 'Club selection is required.');
            availability.clubFilled = false;
        }

        // Password length validation
        var hint = document.getElementById('password-hint');
        if (pwd.value.length >= 8) {
            if (touchedFields.has('password')) showFieldError('password', '');
            hint.classList.remove('text-danger');
            hint.classList.add('text-success');
            availability.passwordLength = true;
        } else {
            if (pwd.value.length > 0) {
                if (touchedFields.has('password')) showFieldError('password', 'Password must be at least 8 characters');
                hint.classList.add('text-danger');
            } else {
                if (touchedFields.has('password') && pwd.value.length === 0) {
                    showFieldError('password', 'Password is required.');
                }
                hint.classList.remove('text-danger'); // Clear hint if empty
            }
            availability.passwordLength = false;
        }

        // Password match validation
        if (pwd.value && pwdConf.value && pwd.value !== pwdConf.value) {
            showFieldError('password_confirmation', 'Passwords do not match');
            availability.passwordMatch = false;
        } else if (pwd.value && pwdConf.value && pwd.value === pwdConf.value) {
            showFieldError('password_confirmation', '');
            availability.passwordMatch = true;
        } else {
            showFieldError('password_confirmation', '');
            availability.passwordMatch = false;
        }

        // Update submit button based on all checks
        updateSubmitButton();
    }

    function updateSubmitButton() {
        // The button is enabled only if all client-side validations pass AND AJAX checks pass
        const isFormValid = (
            availability.nameValid &&
            availability.emailFilled && availability.emailUnique &&
            availability.usernameFilled && availability.usernameUnique &&
            availability.passwordLength &&
            availability.passwordMatch &&
            availability.phoneFilled &&
            availability.countryFilled &&
            availability.dobFilled &&
            availability.clubFilled
        );
        submit.disabled = !isFormValid;
    }

    // Add touched state on blur or input
    const inputs = [nameInput, emailInput, usernameInput, pwd, pwdConf, phoneInput, countryInput, dobInput, clubInput];
    inputs.forEach(input => input.addEventListener('blur', () => { touchedFields.add(input.id); validate(); }));

    // Event Listeners for real-time validation
    nameInput.addEventListener('input', validate);

    function debounceCheck(type, value) {
        clearTimeout(checkTimeout);
        availability[type + 'Unique'] = false;
        validate();
        
        if (value.trim().length > 0) {
            checkTimeout = setTimeout(() => {
                checkAvailability(type, value);
            }, 300); // Wait 300ms after user stops typing
        }
    }

    emailInput.addEventListener('input', function() {
        debounceCheck('email', this.value);
    });

    usernameInput.addEventListener('input', function() {
        debounceCheck('username', this.value);
    });

    // Password match while typing
    pwd.addEventListener('input', validate);
    pwdConf.addEventListener('input', validate);
    phoneInput.addEventListener('input', validate);
    countryInput.addEventListener('change', validate);
    dobInput.addEventListener('change', validate);
    clubInput.addEventListener('change', validate);

    // Toggle Password Visibility Logic
    function setupPasswordToggle(inputId, toggleId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        if (input && toggle) {
            toggle.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    }

    setupPasswordToggle('password', 'togglePassword');
    setupPasswordToggle('password_confirmation', 'togglePasswordConfirmation');

    // Check availability for any pre-filled values (handles browser autocomplete and old inputs)
    // We skip the AJAX check if the field is already marked invalid by the server to prevent double alerts
    if (emailInput.value.trim() && !emailInput.classList.contains('is-invalid')) checkAvailability('email', emailInput.value);
    if (usernameInput.value.trim() && !usernameInput.classList.contains('is-invalid')) checkAvailability('username', usernameInput.value);

    // Perform initial validation to set button state correctly on page load
    validate();
});
</script>

@endsection
