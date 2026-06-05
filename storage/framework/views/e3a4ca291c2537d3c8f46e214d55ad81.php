

<?php $__env->startSection('title', 'News Feed'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <?php if(auth()->guard()->check()): ?>
            <div class="card post-card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="<?php echo e(auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://via.placeholder.com/50'); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="avatar">
                        <button type="button" class="btn btn-outline-secondary flex-grow-1 text-start" data-bs-toggle="modal" data-bs-target="#createPostModal">
                            What's on your mind, <?php echo e(auth()->user()->name); ?>?
                        </button>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createPostModal">
                            <i class="fas fa-pencil-alt"></i> Create Post
                        </button>
                        <button type="button" class="btn btn-light btn-sm">
                            <i class="fas fa-image"></i> Photo
                        </button>
                        <button type="button" class="btn btn-light btn-sm">
                            <i class="fas fa-video"></i> Live Video
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createPostModalLabel">Create Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="<?php echo e(route('posts.store')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="content" class="form-label">What's on your mind?</label>
                                    <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="content" name="content" rows="5" placeholder="Share your thoughts..." required><?php echo e(old('content')); ?></textarea>
                                    <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="club_id" class="form-label">Club</label>
                                        <select class="form-select <?php $__errorArgs = ['club_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="club_id" name="club_id">
                                            <option value="">Select a club (optional)</option>
                                            <?php $__currentLoopData = $clubs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($club->id); ?>" <?php if(old('club_id') == $club->id): echo 'selected'; endif; ?>><?php echo e($club->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['club_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="post_type" class="form-label">Post Type</label>
                                        <select class="form-select <?php $__errorArgs = ['post_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="post_type" name="post_type">
                                            <option value="general" <?php if(old('post_type') == 'general'): echo 'selected'; endif; ?>>General</option>
                                            <option value="match_discussion" <?php if(old('post_type') == 'match_discussion'): echo 'selected'; endif; ?>>Match Discussion</option>
                                            <option value="transfer_news" <?php if(old('post_type') == 'transfer_news'): echo 'selected'; endif; ?>>Transfer News</option>
                                            <option value="player_stats" <?php if(old('post_type') == 'player_stats'): echo 'selected'; endif; ?>>Player Stats</option>
                                        </select>
                                        <?php $__errorArgs = ['post_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="image" name="image" accept="image/*">
                                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="video" class="form-label">Video</label>
                                        <input type="file" class="form-control <?php $__errorArgs = ['video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="video" name="video" accept="video/*">
                                        <?php $__errorArgs = ['video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Post</button>
                                </div>
                            </form>
                        </div>
                    </div>
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

<?php $__env->startSection('scripts'); ?>
    <?php if($errors->any() && old('content')): ?>
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                var createPostModal = new bootstrap.Modal(document.getElementById('createPostModal'));
                createPostModal.show();
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/posts/feed.blade.php ENDPATH**/ ?>