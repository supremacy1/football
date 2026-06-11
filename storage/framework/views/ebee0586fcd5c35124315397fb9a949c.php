

<?php $__env->startSection('title', $user->name . ' Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="profile-cover mb-4">
    <?php
        $coverUrl = $user->cover_photo ? asset('storage/' . $user->cover_photo) : null;
        $avatarUrl = $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=500&background=random';
    ?>
    <div class="cover-wrapper shadow-sm <?php echo e($coverUrl ? 'preview-trigger' : ''); ?>" 
         <?php if($coverUrl): ?> data-src="<?php echo e($coverUrl); ?>" <?php endif; ?>
         style="<?php echo e($coverUrl ? 'background-image: url('.$coverUrl.');' : 'background: linear-gradient(135deg, #8e9eab 0%, #eef2f3 100%);'); ?>">
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->id === $user->id): ?>
                <div class="edit-cover-btn">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-light btn-sm fw-bold shadow-sm">
                        <i class="fas fa-camera me-1"></i> Edit Cover Photo
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="cover-overlay container">
        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end pb-3">
            <div class="me-4 profile-avatar-wrap">
                <img src="<?php echo e($avatarUrl); ?>" alt="<?php echo e($user->name); ?>" class="large-avatar border-4 border-white shadow preview-trigger" data-src="<?php echo e($avatarUrl); ?>">
            </div>
            <div class="flex-grow-1 text-center text-md-start profile-info-text">
                <h1 class="fw-bold mb-0"><?php echo e($user->name); ?></h1>
                <p class="mb-1 text-muted"><?php echo e('@' . $user->username); ?></p>
                <?php if($user->favoriteClub): ?>
                    <p class="mb-2 small">
                        <i class="fas fa-shield-alt text-primary"></i> Supporting <strong><?php echo e($user->favoriteClub->name); ?></strong>
                    </p>
                <?php endif; ?>
                <?php if($user->bio): ?>
                    <p class="small mb-0"><?php echo e($user->bio); ?></p>
                <?php endif; ?>
            </div>
            <div class="ms-auto">
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->id !== $user->id): ?>
                        <?php if(auth()->user()->isFollowing($user)): ?>
                            <form action="<?php echo e(route('profile.unfollow', $user)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-light">Following</button>
                            </form>
                        <?php else: ?>
                            <form action="<?php echo e(route('profile.follow', $user)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-light">Follow</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-light">Edit Profile</a>
                    <?php endif; ?>
                <?php endif; ?>
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
                <?php $__empty_1 = true; $__currentLoopData = $user->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="card post-card mb-3">
                        <div class="post-header d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e($user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=40&background=random'); ?>" alt="<?php echo e($user->name); ?>" class="avatar me-3">
                                <div>
                                    <h6 class="mb-0"><a href="<?php echo e(route('profile.show', $user)); ?>" class="text-decoration-none"><?php echo e($user->name); ?></a></h6>
                                    <small class="text-muted"><?php echo e($post->created_at->diffForHumans()); ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="post-body">
                            <p class="mb-2"><?php echo e($post->content); ?></p>

                            <?php
                                $images = null;
                                if (!empty($post->images)) {
                                    $decoded = json_decode($post->images, true);
                                    if (is_array($decoded)) {
                                        $images = $decoded;
                                    }
                                }
                                if (!$images && !empty($post->image)) {
                                    $images = [$post->image];
                                }
                            ?>

                            <?php if(!empty($images) && count($images) > 0): ?>
                                <div class="post-images mb-2">
                                    <?php if(count($images) == 1): ?>
                                        <?php $img = $images[0]; $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); ?>
                                        <a href="#" class="open-image-modal d-block" data-post-id="<?php echo e($post->id); ?>" data-index="0"><img src="<?php echo e($src); ?>" alt="Post image" class="img-fluid rounded" style="max-height:500px; width:100%; object-fit:cover;"></a>
                                    <?php else: ?>
                                        <div class="row g-2">
                                            <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); ?>
                                                <div class="col-6">
                                                    <a href="#" class="open-image-modal d-block" data-post-id="<?php echo e($post->id); ?>" data-index="<?php echo e($i); ?>">
                                                        <img src="<?php echo e($src); ?>" alt="Post image" class="img-fluid rounded" style="height:200px; width:100%; object-fit:cover;">
                                                    </a>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Modal / Carousel for this post -->
                                <div class="modal fade" id="imageModal-<?php echo e($post->id); ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-dark">
                                            <div class="modal-body p-0">
                                                <div id="carousel-<?php echo e($post->id); ?>" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); ?>
                                                            <div class="carousel-item <?php if($i==0): ?> active <?php endif; ?>">
                                                                <img src="<?php echo e($src); ?>" class="d-block w-100" style="max-height:80vh; object-fit:contain;" alt="">
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo e($post->id); ?>" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo e($post->id); ?>" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="post-footer">
                            <a href="<?php echo e(route('posts.show', $post)); ?>" class="w-100" style="color: #6c757d; text-decoration: none;"><i class="fas fa-comment"></i> View Post</a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <p class="text-muted">No posts yet</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="sidebar">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->id === $user->id): ?>
                            <div class="card sidebar-card">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-chart-bar"></i> Profile Stats</h6>
                                    <p class="mb-2"><strong>Email:</strong> <?php echo e($user->email); ?></p>
                                    <p class="mb-0"><strong>Joined:</strong> <?php echo e($user->created_at->format('M d, Y')); ?></p>
                                </div>
                            </div>

                            <?php
                                $wallet = \DB::table('wallets')->where('user_id', $user->id)->first();
                            ?>

                            <?php if($wallet): ?>
                                <div class="card sidebar-card border-success shadow-sm">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-wallet me-2"></i> Football Wallet</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <small class="text-muted text-uppercase d-block">Available Balance</small>
                                            <h4 class="fw-bold text-success mb-0">₦<?php echo e(number_format($wallet->balance, 2)); ?></h4>
                                        </div>
                                        <div class="p-2 bg-light rounded border mb-3">
                                            <p class="mb-1 small"><strong>Bank:</strong> <?php echo e($wallet->paystack_bank_name); ?></p>
                                            <p class="mb-1 small"><strong>Account:</strong> <?php echo e($wallet->paystack_account_number); ?></p>
                                            <p class="mb-0 text-muted" style="font-size: 0.7rem;"><?php echo e($wallet->paystack_account_name); ?></p>
                                        </div>
                                        <button class="btn btn-football w-100 btn-sm text-uppercase fw-bold" onclick="alert('Withdrawal request initiated!')">
                                            Withdraw Funds
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
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
                        <p class="mb-1"><strong>Location:</strong> <?php echo e($user->location ?? '—'); ?></p>
                        <p class="mb-1"><strong>Birthday:</strong> <?php echo e($user->date_of_birth ? $user->date_of_birth->format('M d, Y') : '—'); ?></p>
                        <p class="mb-1"><strong>Favorite Club:</strong> <?php echo e($user->favoriteClub->name ?? '—'); ?></p>
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
                    <?php $__currentLoopData = $user->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $images = null;
                            if (!empty($post->images)) {
                                $dec = json_decode($post->images, true);
                                if (is_array($dec)) $images = $dec;
                            }
                            if (!$images && !empty($post->image)) $images = [$post->image];
                        ?>
                        <?php if(!empty($images)): ?>
                            <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); ?>
                                <div class="col-4">
                                    <a href="#" class="open-image-modal" data-post-id="<?php echo e($post->id); ?>" data-index="<?php echo e($i); ?>"><img src="<?php echo e($src); ?>" class="img-fluid rounded" style="height:150px; width:100%; object-fit:cover;" alt=""></a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="col-lg-4"></div>
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

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.open-image-modal').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    var postId = el.getAttribute('data-post-id');
                    var index = parseInt(el.getAttribute('data-index') || 0);
                    var modalEl = document.getElementById('imageModal-' + postId);
                    if (!modalEl) return;
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                    var carouselEl = document.getElementById('carousel-' + postId);
                    if (carouselEl) {
                        var bsCarousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
                        bsCarousel.to(index);
                    }
                });
            });

            // Generic Image Preview Logic
            const previewModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            const previewImg = document.getElementById('previewImageSource');
            document.querySelectorAll('.preview-trigger').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    const src = this.getAttribute('data-src');
                    if (src) { previewImg.src = src; previewModal.show(); }
                });
            });

            // Stop event bubbling for the edit button so it doesn't trigger preview
            document.querySelector('.edit-cover-btn')?.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/profile/show.blade.php ENDPATH**/ ?>