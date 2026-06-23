<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Football Social Media</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.svg') }}">
    <style>
        :root {
            --primary-color: #1f4788; 
            --secondary-color: #ff6b6b; 
            --light-bg: #0a0a2a; /* Dark background */
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 70px;
        }

        .navbar {
            background: linear-gradient(135deg, #1a1a3e 0%, #0a0a2a 100%);
            border-bottom: 2px solid #667eea;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #fafafc !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: white !important;
        }

        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
        }

        .btn-primary:hover {
            background-color: #556ee0;
            border-color: #556ee0;
        }

        .btn-danger {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-danger:hover {
            background-color: #e55555;
            border-color: #e55555;
        }

        .card {
            background-color: #1a1a3e;
            border: 1px solid #333;
            color: white;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .post-card {
            background: #0a0a2a;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .post-header {
            display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
        }

        .post-body {
            padding: 15px;
        }

        .post-footer {
            padding: 10px 15px;
            border-top: 1px solid #e9ecef;
            display: flex; justify-content: space-around;
            background-color: transparent;
            border-top: 1px solid #333;
        }

        .post-footer button, .post-footer a {
            border: none;
            background: none;
            color: #888;
            cursor: pointer;
            font-size: 0.9rem;
            flex: 1;
            padding: 10px;
        }

        .post-footer button:hover, .post-footer a:hover {
            color: var(--primary-color);
        }

        .post-footer button.liked {
            color: var(--secondary-color);
        }

        .avatar, .post-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .large-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .sidebar {
            position: sticky;
            top: 20px;
        }

        .sidebar-card {
            margin-bottom: 20px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                top: auto;
            }
        }

        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-dark text-white"> {{-- Set global dark background and text color --}}
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1a1a3e 0%, #0a0a2a 100%); border-bottom: 2px solid #667eea;">
        <div class="container-fluid ">
           <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}" style="color: #ffffff;">
    <img src="{{ asset('images/sportslogo.png') }}" alt="SportsBanta Logo" width="40" height="40" class="me-2">
    SportsBanta
</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth {{-- Authenticated user navigation --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('feed') }}"><i class="fas fa-home"></i> Feed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('matches.live') }}"><i class="fas fa-broadcast-tower text-danger"></i> Live</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('clubs.index') }}"><i class="fas fa-users"></i> Clubs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('betting.index') }}"><i class="fas fa-coins"></i> Betting</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="{{ route('profile.show', auth()->user()) }}">
                                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&size=24&background=random' }}" class="rounded-circle me-1" style="width: 24px; height: 24px; object-fit: cover; border: 1px solid rgba(255,255,255,0.2);">
                                <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </li>
                    @else {{-- Guest user navigation --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">🔐 Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">📝 Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="py-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                        @yield('content')
                    </div>
        </div>
    </main>

    <!-- Generic Alert Modal -->
    <div class="modal fade" id="genericAlertModal" tabindex="-1" aria-labelledby="genericAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" id="genericAlertModalHeader">
                    <h5 class="modal-title" id="genericAlertModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="genericAlertModalBody">
                    <!-- Message content will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Screen Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <img src="" id="previewImageSource" class="img-fluid rounded shadow-lg" style="max-height: 90vh;">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalEl = document.getElementById('genericAlertModal');
            const genericAlertModal = new bootstrap.Modal(modalEl);
            const modalTitle = document.getElementById('genericAlertModalLabel');
            const modalBody = document.getElementById('genericAlertModalBody');
            const modalHeader = document.getElementById('genericAlertModalHeader');

            window.showModalAlert = function(title, message, isError = false) {
                modalTitle.textContent = title;
                modalBody.innerHTML = typeof message === 'string' ? `<p>${message}</p>` : message;
                modalHeader.className = 'modal-header ' + (isError ? 'bg-danger' : 'bg-success') + ' text-white';
                genericAlertModal.show();
            };

            @if (session('success_modal'))
                showModalAlert('Success', '{{ session('success_modal') }}');
            @endif

            @if (session('error'))
                showModalAlert('Error', '{{ session('error') }}', true);
            @endif

            @if ($errors->any())
                let errorHtml = '<ul class="mb-0">';
                @foreach ($errors->all() as $error)
                    errorHtml += '<li>{{ $error }}</li>';
                @endforeach
                errorHtml += '</ul>';
                showModalAlert('Validation Error', errorHtml, true);
            @endif

            // Global Image Preview Logic for elements with .preview-trigger
            const previewModalEl = document.getElementById('imagePreviewModal');
            const previewModal = previewModalEl ? new bootstrap.Modal(previewModalEl) : null;
            const previewImg = document.getElementById('previewImageSource');
            document.addEventListener('click', function (e) {
                const trigger = e.target.closest('.preview-trigger');
                if (trigger && previewModal && previewImg) {
                    const src = trigger.getAttribute('data-src');
                    if (src) { previewImg.src = src; previewModal.show(); }
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
