

<?php $__env->startSection('title', 'News Feed'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <?php if(auth()->guard()->check()): ?>
            <div class="card post-card mb-4">
                <div class="card-body">
                    <form action="<?php echo e(route('posts.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="content" class="form-label">What's on your mind?</label>
                            <textarea class="form-control" id="content" name="content" rows="4" placeholder="Share your thoughts..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="club_id" class="form-label">Club</label>
                            <select class="form-select" id="club_id" name="club_id">
                                <option value="">Select a club (optional)</option>
                                <?php $__currentLoopData = $clubs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($club->id); ?>"><?php echo e($club->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="post_type" class="form-label">Post Type</label>
                            <select class="form-select" id="post_type" name="post_type">
                                <option value="general">General</option>
                                <option value="match_discussion">Match Discussion</option>
                                <option value="transfer_news">Transfer News</option>
                                <option value="player_stats">Player Stats</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="video" class="form-label">Video</label>
                            <input type="file" class="form-control" id="video" name="video" accept="video/*">
                        </div>

                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card post-card">
                <div class="post-header d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo e($post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://via.placeholder.com/40'); ?>" alt="<?php echo e($post->user->name); ?>" class="avatar me-3">
                        <div>
                            <h6 class="mb-0">
                                <a href="<?php echo e(route('profile.show', $post->user)); ?>" class="text-decoration-none"><?php echo e($post->user->name); ?></a>
                            </h6>
                            <small class="text-muted">{{ $post->user->username }} · <?php echo e($post->created_at->diffForHumans()); ?></small>
                        </div>
                    </div>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->id === $post->user_id): ?>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?php echo e(route('posts.edit', $post)); ?>">Edit</a></li>
                                    <li><form action="<?php echo e(route('posts.destroy', $post)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                    </form></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <?php if($post->club_id): ?>
                    <div class="px-3 pt-2">
                        <span class="badge bg-info"><?php echo e($post->club->name); ?></span>
                    </div>
                <?php endif; ?>

                <div class="post-body">
                    <p class="mb-2"><?php echo e($post->content); ?></p>
                    <?php if($post->image): ?>
                        <img src="<?php echo e(asset('storage/' . $post->image)); ?>" alt="Post image" class="img-fluid rounded mb-2" style="max-height: 400px; width: 100%; object-fit: cover;">
                    <?php endif; ?>
                    <?php if($post->video): ?>
                        <video width="100%" height="auto" controls class="rounded mb-2">
                            <source src="<?php echo e(asset('storage/' . $post->video)); ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                </div>

                <div class="post-footer">
                    <?php if(auth()->guard()->check()): ?>
                        <form action="<?php echo e(route('posts.like', $post)); ?>" method="POST" class="w-100">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-100 <?php if($post->isLikedBy(auth()->user())): ?> liked <?php endif; ?>">
                                <i class="fas fa-thumbs-up"></i> Like (<?php echo e($post->likes_count); ?>)
                            </button>
                        </form>
                        <form action="<?php echo e(route('posts.dislike', $post)); ?>" method="POST" class="w-100">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-100 <?php if($post->isDislikedBy(auth()->user())): ?> liked <?php endif; ?>">
                                <i class="fas fa-thumbs-down"></i> Dislike (<?php echo e($post->dislikes_count); ?>)
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="w-100"><i class="fas fa-thumbs-up"></i> Like (<?php echo e($post->likes_count); ?>)</button>
                        <button class="w-100"><i class="fas fa-thumbs-down"></i> Dislike (<?php echo e($post->dislikes_count); ?>)</button>
                    <?php endif; ?>
                    <a href="<?php echo e(route('posts.show', $post)); ?>" class="w-100" style="color: #6c757d; text-decoration: none;">
                        <i class="fas fa-comment"></i> Comment (<?php echo e($post->comments_count); ?>)
                    </a>
                    <button class="w-100"><i class="fas fa-share"></i> Share (<?php echo e($post->shares_count); ?>)</button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <p class="text-muted">No posts yet. Be the first to post!</p>
                </div>
            </div>
        <?php endif; ?>

        <?php echo e($posts->links()); ?>

    </div>

    <div class="col-lg-4">
        <div class="sidebar">
            <div class="card sidebar-card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-fire"></i> Popular Clubs</h5>
                    <div class="list-group list-group-flush">
                        <?php $__empty_1 = true; $__currentLoopData = $clubs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(route('clubs.show', $club)); ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($club->name); ?></h6>
                                        <small class="text-muted"><?php echo e($club->members_count); ?> members</small>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-muted">No clubs available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/posts/feed.blade.php ENDPATH**/ ?>