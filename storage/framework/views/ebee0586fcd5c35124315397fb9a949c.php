

<?php $__env->startSection('title', $user->name . ' Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <?php if($user->cover_photo): ?>
                <img src="<?php echo e(asset('storage/' . $user->cover_photo)); ?>" alt="Cover" class="card-img-top" style="height: 250px; object-fit: cover;">
            <?php else: ?>
                <div style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            <?php endif; ?>

            <div class="card-body position-relative">
                <div class="d-flex justify-content-between align-items-start" style="margin-top: -70px;">
                    <img src="<?php echo e($user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://via.placeholder.com/120'); ?>" alt="<?php echo e($user->name); ?>" class="large-avatar border-4 border-white">
                    <div>
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->id !== $user->id): ?>
                                <?php if(auth()->user()->isFollowing($user)): ?>
                                    <form action="<?php echo e(route('profile.unfollow', $user)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-user-check"></i> Following
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?php echo e(route('profile.follow', $user)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-plus"></i> Follow
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Profile
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <h2 class="mt-3 mb-0"><?php echo e($user->name); ?></h2>
                <p class="text-muted mb-3">{{ $user->username }}</p>

                <?php if($user->bio): ?>
                    <p class="mb-2"><?php echo e($user->bio); ?></p>
                <?php endif; ?>

                <div class="d-flex gap-4 mb-3 text-muted">
                    <?php if($user->location): ?>
                        <div>
                            <i class="fas fa-map-marker-alt"></i> <?php echo e($user->location); ?>

                        </div>
                    <?php endif; ?>
                    <?php if($user->date_of_birth): ?>
                        <div>
                            <i class="fas fa-birthday-cake"></i> <?php echo e($user->date_of_birth->format('M d, Y')); ?>

                        </div>
                    <?php endif; ?>
                    <?php if($user->favoriteClub): ?>
                        <div>
                            <i class="fas fa-heart text-danger"></i> <?php echo e($user->favoriteClub->name); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-4">
                    <div>
                        <h6 class="mb-0"><?php echo e($user->getFollowingCount()); ?></h6>
                        <small class="text-muted">Following</small>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php echo e($user->getFollowerCount()); ?></h6>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php echo e($user->posts()->count()); ?></h6>
                        <small class="text-muted">Posts</small>
                    </div>
                </div>
            </div>
        </div>

        <?php if($user->clubMemberships->count() > 0): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Favorite Clubs</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $user->clubMemberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 mb-3">
                                <a href="<?php echo e(route('clubs.show', $club)); ?>" class="text-decoration-none">
                                    <div class="card h-100">
                                        <?php if($club->logo): ?>
                                            <img src="<?php echo e(asset('storage/' . $club->logo)); ?>" alt="<?php echo e($club->name); ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo e($club->name); ?></h6>
                                            <p class="text-muted small mb-0"><?php echo e($club->members_count); ?> members</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div>
            <h4 class="mb-3">Posts</h4>
            <?php $__empty_1 = true; $__currentLoopData = $user->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card post-card">
                    <div class="post-header d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo e($user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://via.placeholder.com/40'); ?>" alt="<?php echo e($user->name); ?>" class="avatar me-3">
                            <div>
                                <h6 class="mb-0">
                                    <a href="<?php echo e(route('profile.show', $user)); ?>" class="text-decoration-none"><?php echo e($user->name); ?></a>
                                </h6>
                                <small class="text-muted"><?php echo e($post->created_at->diffForHumans()); ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="post-body">
                        <p class="mb-2"><?php echo e($post->content); ?></p>
                        <?php if($post->image): ?>
                            <img src="<?php echo e(asset('storage/' . $post->image)); ?>" alt="Post image" class="img-fluid rounded mb-2" style="max-height: 400px; width: 100%; object-fit: cover;">
                        <?php endif; ?>
                    </div>

                    <div class="post-footer">
                        <a href="<?php echo e(route('posts.show', $post)); ?>" class="w-100" style="color: #6c757d; text-decoration: none;">
                            <i class="fas fa-comment"></i> View Post
                        </a>
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
    </div>

    <div class="col-lg-4">
        <div class="sidebar">
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->id === $user->id): ?>
                    <div class="card sidebar-card">
                        <div class="card-body">
                            <h6 class="card-title"><i class="fas fa-chart-bar"></i> Profile Stats</h6>
                            <p class="mb-2">
                                <strong>Email:</strong> <?php echo e($user->email); ?>

                            </p>
                            <p class="mb-0">
                                <strong>Joined:</strong> <?php echo e($user->created_at->format('M d, Y')); ?>

                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/profile/show.blade.php ENDPATH**/ ?>