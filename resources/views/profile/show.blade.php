@extends('layouts.app')

@section('title', $user->name . ' Profile')

@section('content')
<div class="profile-cover mb-4">
    @php
        $coverUrl = $user->cover_photo ? asset('storage/' . $user->cover_photo) : null;
        $avatarUrl = $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=500&background=random';
    @endphp
    <div class="cover-wrapper shadow-sm {{ $coverUrl ? 'preview-trigger' : '' }}" 
         @if($coverUrl) data-src="{{ $coverUrl }}" @endif
         style="{{ $coverUrl ? 'background-image: url('.$coverUrl.');' : 'background: linear-gradient(135deg, #8e9eab 0%, #eef2f3 100%);' }}">
        @auth
            @if (auth()->user()->id === $user->id)
                <div class="edit-cover-btn">
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm fw-bold shadow-sm">
                        <i class="fas fa-camera me-1"></i> Edit Cover Photo
                    </a>
                </div>
            @endif
        @endauth
    </div>
    <div class="cover-overlay container">
        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end pb-3">
            <div class="me-4 profile-avatar-wrap">
                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="large-avatar border-4 border-white shadow preview-trigger" data-src="{{ $avatarUrl }}">
            </div>
            <div class="flex-grow-1 text-center text-md-start profile-info-text">
                <h1 class="fw-bold mb-0">{{ $user->name }}</h1>
                <p class="mb-1 text-muted">{{ '@' . $user->username }}</p>
                @if ($user->favoriteClub)
                    <p class="mb-2 small">
                        <i class="fas fa-shield-alt text-primary"></i> Supporting <strong>{{ $user->favoriteClub->name }}</strong>
                    </p>
                @endif
                @if ($user->bio)
                    <p class="small mb-0">{{ $user->bio }}</p>
                @endif
            </div>
            <div class="ms-auto">
                @auth
                    @if (auth()->user()->id !== $user->id)
                        @if (auth()->user()->isFollowing($user))
                            <form action="{{ route('profile.unfollow', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light">Following</button>
                            </form>
                        @else
                            <form action="{{ route('profile.follow', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-light">Follow</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('profile.edit') }}" class="btn btn-light">Edit Profile</a>
                    @endif
                @endauth
            </div>
        </div>
        <nav class="profile-nav border-top mt-2">
            <ul class="nav nav-pills py-2">
                <li class="nav-item"><a class="nav-link active" href="#posts" data-bs-toggle="tab">Posts</a></li>
                <li class="nav-item"><a class="nav-link" href="#about" data-bs-toggle="tab">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#photos" data-bs-toggle="tab">Photos</a></li>
            </ul>
        </nav>
    </div>
</div>

<div class="tab-content container">
    <div class="tab-pane active" id="posts">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-3">Posts</h4>
                @forelse ($user->posts as $post)
                    <div class="card post-card mb-3">
                        <div class="post-header d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=40&background=random' }}" alt="{{ $user->name }}" class="avatar me-3">
                                <div>
                                    <h6 class="mb-0"><a href="{{ route('profile.show', $user) }}" class="text-decoration-none">{{ $user->name }}</a></h6>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="post-body">
                            <p class="mb-2">{{ $post->content }}</p>

                            @php
                                $images = null;
                                if (!empty($post->image)) {
                                    $decoded = @json_decode($post->image, true);
                                    $images = is_array($decoded) ? $decoded : [$post->image];
                                }
                            @endphp

                            @if (!empty($images) && count($images) > 0)
                                <div class="post-images mb-2">
                                    @if (count($images) == 1)
                                        @php $img = $images[0]; $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); @endphp
                                        <img src="{{ $src }}" alt="Post image" class="img-fluid rounded preview-trigger" data-src="{{ $src }}" style="max-height:500px; width:100%; object-fit:cover; cursor: pointer;">
                                    @else
                                        <div class="row g-2">
                                            @foreach ($images as $i => $img)
                                                @php $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); @endphp
                                                <div class="col-6">
                                                    <img src="{{ $src }}" alt="Post image" class="img-fluid rounded preview-trigger" data-src="{{ $src }}" style="height:200px; width:100%; object-fit:cover; cursor: pointer;">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="post-footer">
                            <a href="{{ route('posts.show', $post) }}" class="w-100" style="color: #6c757d; text-decoration: none;"><i class="fas fa-comment"></i> View Post</a>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <p class="text-muted">No posts yet</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="col-lg-4">
                <div class="sidebar">
                    @auth
                        @if (auth()->user()->id === $user->id)
                            <div class="card sidebar-card">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-chart-bar"></i> Profile Stats</h6>
                                    <p class="mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p class="mb-0"><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            @php
                                $wallet = \DB::table('wallets')->where('user_id', $user->id)->first();
                            @endphp

                            @if($wallet)
                                <div class="card sidebar-card border-success shadow-sm">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-wallet me-2"></i> Football Wallet</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <small class="text-muted text-uppercase d-block color-secondary">Available Balance</small>
                                            <h4 class="fw-bold text-success mb-0 text-white">₦{{ number_format($wallet->balance, 2) }}</h4>
                                        </div>
                                        <div class="p-2 bg-light rounded border mb-3">
                                            <p class="mb-1 small text-uppercase text-muted text-dark"><strong>Bank:</strong> {{ $wallet->paystack_bank_name }}</p>
                                            <p class="mb-1 small text-uppercase text-muted text-dark"><strong>Account:</strong> {{ $wallet->paystack_account_number }}</p>
                                            <p class="mb-0 text-muted" style="font-size: 0.7rem;"> <strong>Name:</strong>{{ $wallet->paystack_account_name }}</p>
                                        </div>
<button type="button"
        class="btn btn-link text-white text-decoration-none w-100 text-uppercase fw-bold"
    data-bs-toggle="modal"
    data-bs-target="#withdrawModal">
    Withdraw Funds
</button>
                                    </div>
                                </div>

                                <!-- Withdrawal History Table (Sidebar version) -->
                                <div class="card sidebar-card border-0 shadow-sm mt-3">
                                    <div class="card-header bg-white py-2 border-bottom">
                                        <h6 class="mb-0 fw-bold text-dark small text-uppercase">Withdrawal History</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.75rem;">
                                                <thead class="table-light text-muted">
                                                    <tr>
                                                        <th class="ps-2">Details</th>
                                                        <th class="text-end pe-2">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($withdrawals as $withdrawal)
                                                        <tr>
                                                            <td class="ps-2 py-2">
                                                                <div class="fw-bold">{{ $withdrawal->bank_name }}</div>
                                                                <div class="text-muted">{{ $withdrawal->created_at->format('d M') }} • 
                                                                    <span class="text-{{ $withdrawal->status === 'success' ? 'success' : ($withdrawal->status === 'failed' ? 'danger' : 'warning') }}">
                                                                        {{ strtoupper($withdrawal->status) }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="text-end pe-2 fw-bold">
                                                                ₦{{ number_format($withdrawal->amount, 2) }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2" class="text-center py-3 text-muted">No history yet.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="about">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>About</h5>
                        <p class="mb-1"><strong>Location:</strong> {{ $user->location ?? '—' }}</p>
                        <p class="mb-1"><strong>Birthday:</strong> {{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : '—' }}</p>
                        <p class="mb-1"><strong>Favorite Club:</strong> {{ $user->favoriteClub->name ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>

    <div class="tab-pane" id="photos">
        <div class="row">
            <div class="col-lg-8">
                <h5 class="mb-3">Photos</h5>
                <div class="row g-2">
                    @foreach ($user->posts as $post)
                        @php
                            $images = null;
                            if (!empty($post->image)) {
                                $dec = @json_decode($post->image, true);
                                $images = is_array($dec) ? $dec : [$post->image];
                            }
                        @endphp
                        @if (!empty($images))
                            @foreach ($images as $i => $img)
                                @php $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); @endphp
                                <div class="col-4">
                                    <img src="{{ $src }}" class="img-fluid rounded preview-trigger" data-src="{{ $src }}" style="height:150px; width:100%; object-fit:cover; cursor: pointer;" alt="">
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</div>

@auth
    @if (auth()->user()->id === $user->id)
    <!-- Withdrawal Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="withdrawModalLabel"><i class="fas fa-university me-2"></i>Withdraw to Bank</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('withdrawal.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-light border mb-4 text-center">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Available Balance</small>
                            <h4 class="fw-bold text-dark mb-0">₦{{ number_format(optional(auth()->user()->wallet)->balance ?? 0, 2) }}</h4>
                        </div>
                        <input type="hidden" name="account_name" id="account_name_hidden">
                        <input type="hidden" name="bank_name" id="bank_name_hidden">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Select Bank</label>
                            <select name="bank_code" id="bank_code" class="form-select shadow-sm" required>
                                <option value="">Choose bank...</option>
                                @foreach($banks as $bank) 
                                    <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Account Number</label>
                            <input type="text" name="account_number" id="account_number" class="form-control shadow-sm" maxlength="10" placeholder="e.g. 0123456789" required>
                            <div id="verify_msg" class="small mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Amount to Withdraw (₦)</label>
                            <input type="number" name="amount" class="form-control shadow-sm" placeholder="Minimum 100" min="100" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="withdrawSubmitBtn" class="btn btn-danger px-4 fw-bold text-uppercase" disabled>Confirm Withdrawal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endauth

@section('styles')
    <style>
        .profile-cover { background: white; border-radius: 0 0 8px 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .cover-wrapper { height: 350px; background-size: cover; background-position: center; border-radius: 0 0 8px 8px; position: relative; }
        .cover-wrapper.preview-trigger { cursor: pointer; }
        .edit-cover-btn { position: absolute; bottom: 15px; right: 15px; z-index: 5; }
        .cover-overlay { margin-top: -100px; position: relative; z-index: 2; }
        .profile-avatar-wrap { width: 168px; height: 168px; position: relative; }
        .large-avatar { width: 168px; height: 168px; border-radius: 50%; object-fit: cover; background: white; cursor: pointer; }
        .profile-info-text { padding-bottom: 15px; }
        .profile-info-text h1 { font-size: 2rem; }
        .profile-nav .nav-link { color: #65676b; font-weight: 600; margin-right: 5px; }
        .profile-nav .nav-link.active { background-color: rgba(0,0,0,0.05); color: var(--primary-color); }
        .post-images .img-fluid { cursor: pointer; }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Stop event bubbling for the edit button so it doesn't trigger preview
            document.querySelector('.edit-cover-btn')?.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Withdrawal Verification Logic
            const bankSelect = document.getElementById('bank_code');
            const accountInput = document.getElementById('account_number');
            const verifyMsg = document.getElementById('verify_msg');
            const withdrawBtn = document.getElementById('withdrawSubmitBtn');

            async function verifyAccount() {
                if (bankSelect && bankSelect.value && accountInput.value.length === 10) {
                    verifyMsg.className = 'text-primary small'; 
                    verifyMsg.textContent = 'Verifying account details...';
                    
                    try {
                        const response = await fetch("{{ route('withdrawal.verify') }}", {
                            method: 'POST', 
                            headers: {
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({bank_code: bankSelect.value, account_number: accountInput.value})
                        });
                        const data = await response.json();
                        if (data.success) {
                            verifyMsg.className = 'text-success small fw-bold bg-light p-2 rounded d-block'; 
                            verifyMsg.innerHTML = '<i class="fas fa-check-circle me-1"></i> ' + data.account_name;
                            document.getElementById('account_name_hidden').value = data.account_name;
                            document.getElementById('bank_name_hidden').value = bankSelect.options[bankSelect.selectedIndex].text;
                            withdrawBtn.disabled = false;
                        } else {
                            verifyMsg.className = 'text-danger small'; 
                            verifyMsg.textContent = 'Invalid account number or bank selection.';
                            withdrawBtn.disabled = true;
                        }
                    } catch (e) { if(withdrawBtn) withdrawBtn.disabled = true; }
                }
            }
            if(bankSelect) bankSelect.addEventListener('change', verifyAccount);
            if(accountInput) accountInput.addEventListener('input', verifyAccount);
        });
    </script>
@endsection

@endsection
