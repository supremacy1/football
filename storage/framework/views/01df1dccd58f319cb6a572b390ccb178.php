

<?php $__env->startSection('title', $club->name); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <?php if($club->banner): ?>
                <img src="<?php echo e(asset('storage/' . $club->banner)); ?>" alt="<?php echo e($club->name); ?>" class="card-img-top" style="height: 300px; object-fit: cover;">
            <?php else: ?>
                <div style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            <?php endif; ?>

            <div class="card-body position-relative">
                <div class="d-flex justify-content-between align-items-start" style="margin-top: -80px;">
                    <?php if($club->logo): ?>
                        <img src="<?php echo e(str_starts_with($club->logo, 'http') ? $club->logo : asset('storage/' . $club->logo)); ?>" alt="<?php echo e($club->name); ?>" class="large-avatar border-4 border-white">
                    <?php else: ?>
                        <div class="large-avatar border-4 border-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 3rem; color: white;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <?php if(auth()->guard()->check()): ?>
                            <?php if($club->isMember(auth()->user())): ?>
                                <form action="<?php echo e(route('clubs.leave', $club)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-sign-out-alt"></i> Leave Club
                                    </button>
                                </form>
                            <?php else: ?>
                                <?php if(auth()->user()->favorite_club_id === $club->id): ?>
                                    <form action="<?php echo e(route('clubs.join', $club)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Join Club
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark border p-2">
                                        <i class="fas fa-info-circle"></i> Only fans can join
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <h2 class="mt-3 mb-0"><?php echo e($club->name); ?></h2>

                <?php if($club->description): ?>
                    <p class="mb-3"><?php echo e($club->description); ?></p>
                <?php endif; ?>

                <div class="d-flex gap-4 mb-3 text-muted">
                    <?php if($club->country): ?>
                        <div>
                            <i class="fas fa-flag"></i> <?php echo e($club->country); ?>

                        </div>
                    <?php endif; ?>
                    <?php if($club->founded_year): ?>
                        <div>
                            <i class="fas fa-calendar"></i> Founded <?php echo e($club->founded_year); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-4">
                    <div>
                        <h6 class="mb-0"><?php echo e($club->members_count); ?></h6>
                        <small class="text-muted">Members</small>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php echo e($club->posts_count); ?></h6>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div>
                        <h6 class="mb-0"><?php echo e($club->players_count); ?></h6>
                        <small class="text-muted">Players</small>
                    </div>
                </div>
            </div>
        </div>

        <?php if($club->players->count() > 0): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Squad</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $club->players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start p-3 border rounded">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($player->name); ?></h6>
                                        <small class="text-muted d-block"><?php echo e($player->position); ?></small>
                                        <small class="text-muted d-block"><?php echo e($player->nationality); ?></small>
                                    </div>
                                    <span class="badge bg-primary">#<?php echo e($player->jersey_number); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div>
            <?php if(auth()->guard()->check()): ?>
                <?php if($club->isMember(auth()->user())): ?>
                    <div class="mb-4">
                        <button type="button" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createClubPostModal">
                            <i class="fas fa-edit me-2"></i> Share something with the fans...
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <h4 class="mb-3">Recent Posts</h4>
            <?php $__empty_1 = true; $__currentLoopData = $club->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card post-card">
                    <div class="post-header d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo e(($post->user && $post->user->profile_picture) ? asset('storage/' . $post->user->profile_picture) : 'https://via.placeholder.com/40'); ?>" alt="<?php echo e(optional($post->user)->name); ?>" class="avatar me-3">
                            <div>
                                <h6 class="mb-0">
                                    <a href="<?php echo e($post->user ? route('profile.show', $post->user) : '#'); ?>" class="text-decoration-none"><?php echo e(optional($post->user)->name ?? 'Unknown User'); ?></a>
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

                    <div class="post-actions px-3 py-2 border-top d-flex gap-4">
                        <?php if(auth()->guard()->check()): ?>
                            <form action="<?php echo e(route('posts.like', $post)); ?>" method="POST" class="d-inline engagement-form" data-type="like">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none <?php echo e($post->isLikedBy(auth()->user()) ? 'text-primary fw-bold' : 'text-muted'); ?>">
                                    <i class="fas fa-thumbs-up"></i> <span class="count"><?php echo e($post->likes_count ?? 0); ?></span>
                                </button>
                            </form>
                            <form action="<?php echo e(route('posts.dislike', $post)); ?>" method="POST" class="d-inline engagement-form" data-type="dislike">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none <?php echo e($post->isDislikedBy(auth()->user()) ? 'text-danger fw-bold' : 'text-muted'); ?>">
                                    <i class="fas fa-thumbs-down"></i> <span class="count"><?php echo e($post->dislikes_count ?? 0); ?></span>
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted small"><i class="far fa-thumbs-up"></i> <?php echo e($post->likes_count ?? 0); ?></span>
                            <span class="text-muted small"><i class="far fa-thumbs-down"></i> <?php echo e($post->dislikes_count ?? 0); ?></span>
                        <?php endif; ?>
                        
                        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted">
                            <i class="far fa-comment"></i> <?php echo e($post->comments->count()); ?>

                        </button>

                        <form action="<?php echo e(route('posts.share', $post)); ?>" method="POST" class="d-inline ms-auto">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none text-muted">
                                <i class="far fa-share-square"></i> Share
                            </button>
                        </form>
                    </div>

                    <div class="post-footer border-top p-0">
                        <div class="p-3 bg-white">
                            <?php if(auth()->guard()->check()): ?>
                                <div class="d-flex mb-3">
                                    <img src="<?php echo e(auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name)); ?>" class="avatar-sm rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <form action="<?php echo e(route('comments.store', $post)); ?>" method="POST" class="flex-grow-1">
                                        <?php echo csrf_field(); ?>
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="content" class="form-control bg-light border-0" placeholder="Write a comment..." required>
                                            <button class="btn btn-primary px-3" type="submit">Post</button>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>

                            <div class="comment-list">
                                <?php $__empty_2 = true; $__currentLoopData = $post->comments->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <div class="d-flex mb-2">
                                        <img src="<?php echo e(($comment->user && $comment->user->profile_picture) ? asset('storage/' . $comment->user->profile_picture) : 'https://via.placeholder.com/32'); ?>" class="avatar-sm rounded-circle me-2" style="width: 32px; height: 32px;">
                                        <div class="flex-grow-1">
                                            <div class="bg-light p-2 rounded">
                                                <div class="d-flex justify-content-between">
                                                    <a href="<?php echo e($comment->user ? route('profile.show', $comment->user) : '#'); ?>" class="text-decoration-none small fw-bold text-dark">
                                                        <?php echo e(optional($comment->user)->name ?? 'Deleted User'); ?>

                                                    </a>
                                                </div>
                                                <p class="mb-0 small"><?php echo e($comment->content); ?></p>
                                            </div>
                                            <div class="mt-1 d-flex align-items-center gap-3 px-1">
                                                <?php if(auth()->guard()->check()): ?>
                                                    <form action="<?php echo e(route('comments.like', $comment)); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-link p-0 btn-sm text-decoration-none <?php echo e($comment->isLikedBy(auth()->user()) ? 'text-primary fw-bold' : 'text-muted'); ?>" style="font-size: 0.7rem;">
                                                            Like <?php echo e($comment->likes_count > 0 ? $comment->likes_count : ''); ?>

                                                        </button>
                                                    </form>
                                                <button class="btn btn-link p-0 btn-sm text-decoration-none text-muted reply-toggle-btn" data-comment-id="<?php echo e($comment->id); ?>" style="font-size: 0.7rem;">Reply</button>
                                                <?php endif; ?>
                                                <small class="text-muted" style="font-size: 0.7rem;"><?php echo e($comment->created_at->diffForHumans()); ?></small>
                                            </div>

                                        
                                        <?php if(auth()->guard()->check()): ?>
                                            <div class="reply-form-container mt-2 d-none" id="reply-form-<?php echo e($comment->id); ?>">
                                                <form action="<?php echo e(route('comments.store', $post)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="parent_comment_id" value="<?php echo e($comment->id); ?>">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" name="content" class="form-control bg-white border" placeholder="Write a reply..." required>
                                                        <button class="btn btn-primary px-2" type="submit">Reply</button>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <?php if($comment->replies->count() > 0): ?>
                                            <div class="replies-container mt-2 ms-2 ps-2 border-start">
                                                <?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="d-flex mb-2">
                                                        <img src="<?php echo e(($reply->user && $reply->user->profile_picture) ? asset('storage/' . $reply->user->profile_picture) : 'https://via.placeholder.com/24'); ?>" class="avatar-xs rounded-circle me-2" style="width: 24px; height: 24px;">
                                                        <div class="flex-grow-1">
                                                            <div class="bg-light p-2 rounded">
                                                                <a href="<?php echo e($reply->user ? route('profile.show', $reply->user) : '#'); ?>" class="text-decoration-none small fw-bold text-dark" style="font-size: 0.75rem;">
                                                                    <?php echo e(optional($reply->user)->name ?? 'Deleted User'); ?>

                                                                </a>
                                                                <p class="mb-0 small" style="font-size: 0.8rem;"><?php echo e($reply->content); ?></p>
                                                            </div>
                                                            <small class="text-muted" style="font-size: 0.65rem;"><?php echo e($reply->created_at->diffForHumans()); ?></small>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <p class="text-muted small mb-0 italic">No comments yet. Start the conversation!</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="p-2 text-center border-top">
                            <a href="<?php echo e(route('posts.show', $post)); ?>" class="small text-decoration-none text-muted">
                                View all comments
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <p class="text-muted">No posts yet for this club</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        
        <?php if($upcomingMatches->count() > 0): ?>
            <div class="card sidebar-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-calendar-alt"></i> Upcoming Matches</h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $upcomingMatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted"><?php echo e($match->match_date->format('M d, H:i')); ?></small>
                                <?php if($match->league): ?>
                                    <span class="badge bg-info"><?php echo e($match->league); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(($match->homeClub && $match->homeClub->logo) ? asset('storage/' . $match->homeClub->logo) : 'https://via.placeholder.com/20'); ?>" alt="<?php echo e(optional($match->homeClub)->name); ?>" class="avatar me-2" style="width: 20px; height: 20px;">
                                    <strong><?php echo e(optional($match->homeClub)->name ?? 'TBD'); ?></strong>
                                </div>
                                <span class="mx-2">vs</span>
                                <div class="d-flex align-items-center">
                                    <strong><?php echo e(optional($match->awayClub)->name ?? 'TBD'); ?></strong>
                                    <img src="<?php echo e(($match->awayClub && $match->awayClub->logo) ? asset('storage/' . $match->awayClub->logo) : 'https://via.placeholder.com/20'); ?>" alt="<?php echo e(optional($match->awayClub)->name); ?>" class="avatar ms-2" style="width: 20px; height: 20px;">
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1"><i class="fas fa-map-marker-alt"></i> <?php echo e($match->venue); ?></small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="sidebar">
            <?php if($club->members->count() > 0): ?>
                <div class="card sidebar-card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-users"></i> Recent Members</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $club->members->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($member && $member->id): ?> 
                            <a href="<?php echo e(route('profile.show', $member)); ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e($member->profile_picture ? asset('storage/' . $member->profile_picture) : 'https://via.placeholder.com/32'); ?>" alt="<?php echo e($member->name); ?>" class="avatar me-2" style="width: 32px; height: 32px;">
                                    <div>
                                        <h6 class="mb-0"><?php echo e($member->name); ?></h6>
                                        <small class="text-muted"><?php echo e('@' . $member->username); ?></small>
                                    </div>
                                </div>
                            </a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(auth()->guard()->check()): ?>
    <?php if($club->isMember(auth()->user())): ?>
        <!-- Create Post Modal -->
        <div class="modal fade" id="createClubPostModal" tabindex="-1" aria-labelledby="createClubPostModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createClubPostModalLabel">Post to <?php echo e($club->name); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?php echo e(route('posts.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="club_id" value="<?php echo e($club->id); ?>">
                        <div class="modal-body">
                            <div class="mb-3">
                                <textarea name="content" class="form-control" rows="4" placeholder="What's happening in the club?" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="postImage" class="form-label small">Attach Image (Optional)</label>
                                <input type="file" name="image" id="postImage" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">Post to Group</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle Like/Dislike asynchronously
    document.querySelectorAll('.engagement-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const url = this.action;
            const type = this.dataset.type;
            const button = this.querySelector('button');
            const countSpan = this.querySelector('.count');
            const oppositeForm = this.parentElement.querySelector(`.engagement-form[data-type="${type === 'like' ? 'dislike' : 'like'}"]`);
            const oppositeButton = oppositeForm ? oppositeForm.querySelector('button') : null;
            const oppositeCount = oppositeForm ? oppositeForm.querySelector('.count') : null;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'text/html', // Many controllers return back(), which is a redirect to HTML
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => {
                // UI Logic: Toggle classes and adjust counts locally to simulate "silent" update
                const isActive = button.classList.contains(type === 'like' ? 'text-primary' : 'text-danger');
                let currentCount = parseInt(countSpan.textContent);

                if (isActive) {
                    button.classList.remove(type === 'like' ? 'text-primary' : 'text-danger', 'fw-bold');
                    button.classList.add('text-muted');
                    countSpan.textContent = Math.max(0, currentCount - 1);
                } else {
                    button.classList.add(type === 'like' ? 'text-primary' : 'text-danger', 'fw-bold');
                    button.classList.remove('text-muted');
                    countSpan.textContent = currentCount + 1;

                    // If the other action was active, deactivate it
                    if (oppositeButton && oppositeButton.classList.contains(type === 'like' ? 'text-danger' : 'text-primary')) {
                        oppositeButton.classList.remove('text-primary', 'text-danger', 'fw-bold');
                        oppositeButton.classList.add('text-muted');
                        oppositeCount.textContent = Math.max(0, parseInt(oppositeCount.textContent) - 1);
                    }
                }
            }).catch(error => console.error('Error:', error));
        });
    });

    // Handle Reply Toggle
    document.querySelectorAll('.reply-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const form = document.getElementById(`reply-form-${commentId}`);
            if (form) {
                form.classList.toggle('d-none');
                if (!form.classList.contains('d-none')) {
                    form.querySelector('input').focus();
                }
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/clubs/show.blade.php ENDPATH**/ ?>