

<?php $__env->startSection('title', 'View Post'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
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
                    <img src="<?php echo e(asset('storage/' . $post->image)); ?>" alt="Post image" class="img-fluid rounded mb-2" style="max-height: 500px; width: 100%; object-fit: cover;">
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
                <button class="w-100"><i class="fas fa-comment"></i> Comment (<?php echo e($post->comments_count); ?>)</button>
                <button class="w-100"><i class="fas fa-share"></i> Share (<?php echo e($post->shares_count); ?>)</button>
            </div>
        </div>

        <?php if(auth()->guard()->check()): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">Add a Comment</h6>
                    <form action="<?php echo e(route('comments.store', $post)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <textarea class="form-control" name="content" rows="3" placeholder="Write a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Comment</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo e($comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : 'https://via.placeholder.com/32'); ?>" alt="<?php echo e($comment->user->name); ?>" class="avatar me-2" style="width: 32px; height: 32px;">
                            <div>
                                <h6 class="mb-0">
                                    <a href="<?php echo e(route('profile.show', $comment->user)); ?>" class="text-decoration-none"><?php echo e($comment->user->name); ?></a>
                                </h6>
                                <small class="text-muted">{{ $comment->user->username }} · <?php echo e($comment->created_at->diffForHumans()); ?></small>
                            </div>
                        </div>
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->id === $comment->user_id): ?>
                                <form action="<?php echo e(route('comments.destroy', $comment)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-link text-danger">Delete</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <p class="mb-2"><?php echo e($comment->content); ?></p>
                    <?php if(auth()->guard()->check()): ?>
                        <div>
                            <form action="<?php echo e(route('comments.like', $comment)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-link <?php if($comment->isLikedBy(auth()->user())): ?> text-danger <?php endif; ?>">
                                    <i class="fas fa-heart"></i> <?php echo e($comment->likes_count); ?>

                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/posts/show.blade.php ENDPATH**/ ?>