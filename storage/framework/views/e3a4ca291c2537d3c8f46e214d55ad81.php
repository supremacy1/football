

<?php $__env->startSection('title', 'News Feed'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .fb-modal-header { border-bottom: 1px solid #ddd; text-align: center; padding: 15px; position: relative; }
    .fb-modal-header h5 { margin: 0; font-weight: 700; font-size: 1.25rem; }
    .fb-modal-header .btn-close { position: absolute; right: 15px; top: 15px; background-color: #e4e6eb; border-radius: 50%; opacity: 1; padding: 10px; font-size: 0.8rem; }
    
    .fb-user-info { display: flex; align-items: center; margin-bottom: 15px; }
    .fb-user-info img { width: 40px; height: 40px; border-radius: 50%; margin-right: 12px; object-fit: cover; }
    .fb-user-info .user-name { font-weight: 600; font-size: 0.95rem; line-height: 1.2; }
    
    .fb-textarea { border: none; font-size: 1.25rem; resize: none; width: 100%; min-height: 150px; padding: 0; margin-top: 10px; }
    .fb-textarea:focus { box-shadow: none; outline: none; }
    
    .fb-add-to-post { 
        display: flex; align-items: center; justify-content: space-between; 
        border: 1px solid #ddd; border-radius: 8px; padding: 10px 15px; margin-top: 15px;
    }
    .fb-add-to-post span { font-weight: 600; font-size: 0.95rem; }
    .fb-icon-btn { 
        background: none; border: none; padding: 8px; border-radius: 50%; 
        transition: background 0.2s; color: #45bd62; font-size: 1.25rem; cursor: pointer;
    }
    .fb-icon-btn:hover { background-color: #f2f2f2; }
    .fb-icon-btn.photo { color: #45bd62; }
    
    .fb-post-btn { width: 100%; border-radius: 6px; font-weight: 600; padding: 8px; margin-top: 15px; }
    
    .preview-container { position: relative; display: none; margin-top: 15px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: #f0f2f5; }
    .preview-container img { width: 100%; height: auto; max-height: 300px; object-fit: contain; }
    .remove-preview { 
        position: absolute; top: 10px; right: 10px; background: white; border-radius: 50%; 
        width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; 
        cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;
    }
    
    .fb-select-row { display: flex; gap: 8px; margin-top: 4px; }
    .fb-select-row select { font-size: 0.75rem; border-radius: 6px; background-color: #e4e6eb; border: none; font-weight: 600; color: #050505; padding: 2px 8px; cursor: pointer; }
    
    .modal-content { border-radius: 8px; border: none; box-shadow: 0 12px 28px rgba(0,0,0,0.2); }

    /* Left Sidebar Navigation */
    .left-nav-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-radius: 8px;
        text-decoration: none;
        color: #050505;
        font-weight: 600;
        transition: background 0.2s;
        margin-bottom: 2px;
    }
    .left-nav-item:hover { background-color: #e4e6eb; color: #050505; }
    .left-nav-item i {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 12px;
    }
    .left-nav-item img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin-right: 12px;
        object-fit: cover;
    }
    .left-nav-item.active { background-color: rgba(0,0,0,0.05); }
    .sticky-left-nav { position: sticky; top: 85px; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Left Navbar Sidebar -->
    <div class="col-lg-3 d-none d-lg-block">
        <div class="sticky-left-nav">
            <a href="<?php echo e(route('profile.show', auth()->user())); ?>" class="left-nav-item">
                <img src="<?php echo e(auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random'); ?>" alt="">
                <span><?php echo e(auth()->user()->name); ?></span>
            </a>
            <a href="<?php echo e(route('feed')); ?>" class="left-nav-item <?php echo e(Route::is('feed') ? 'active' : ''); ?>">
                <i class="fas fa-home text-primary"></i>
                <span>Home</span>
            </a>
            <a href="<?php echo e(route('news')); ?>" class="left-nav-item <?php echo e(Route::is('news') ? 'active' : ''); ?>">
                <i class="fas fa-newspaper text-success"></i>
                <span>News Feed</span>
            </a>
            <a href="<?php echo e(route('clubs.index')); ?>" class="left-nav-item">
                <i class="fas fa-users text-info"></i>
                <span>Clubs</span>
            </a>
            <a href="#" class="left-nav-item">
                <i class="fas fa-calendar-check text-danger"></i>
                <span>Matches</span>
            </a>
            <a href="#" class="left-nav-item">
                <i class="fas fa-bookmark text-warning"></i>
                <span>Saved</span>
            </a>
            <hr>
            <h6 class="px-3 text-muted mb-2">Shortcuts</h6>
            <?php $__currentLoopData = auth()->user()->clubMemberships->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('clubs.show', $club)); ?>" class="left-nav-item">
                    <div class="bg-light rounded p-1 me-3 text-center" style="width: 36px;"><i class="fas fa-shield-alt text-secondary"></i></div>
                    <span><?php echo e($club->name); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Main Content (Feed) -->
    <div class="col-lg-6">
        <?php echo $__env->yieldContent('feed_header'); ?>
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
                    <div class="fb-modal-header">
                        <h5 id="createPostModalLabel">Create post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <form action="<?php echo e(route('posts.store')); ?>" method="POST" enctype="multipart/form-data" id="fbPostForm">
                            <?php echo csrf_field(); ?>
                            <div class="fb-user-info">
                                <img src="<?php echo e(auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random'); ?>" alt="<?php echo e(auth()->user()->name); ?>">
                                <div>
                                    <div class="user-name"><?php echo e(auth()->user()->name); ?></div>
                                    <div class="fb-select-row">
                                        <select name="club_id">
                                            <option value="">Public</option>
                                            <?php $__currentLoopData = $clubs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($club->id); ?>" <?php if(old('club_id') == $club->id): echo 'selected'; endif; ?>><?php echo e($club->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <select name="post_type">
                                            <option value="general">General</option>
                                            <option value="match_discussion">Match</option>
                                            <option value="transfer_news">Transfer</option>
                                            <option value="player_stats">Stats</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <textarea class="fb-textarea" name="content" placeholder="What's on your mind, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>?" required><?php echo e(old('content')); ?></textarea>

                            <div class="preview-container" id="imagePreviewContainer">
                                <div class="remove-preview" onclick="removeImage()"><i class="fas fa-times"></i></div>
                                <img id="imagePreview" src="#" alt="Preview">
                            </div>

                            <input type="file" id="postImageInput" name="image" accept="image/*" class="d-none" onchange="previewImage(this)">

                            <div class="fb-add-to-post">
                                <span>Add to your post</span>
                                <div class="d-flex">
                                    <button type="button" class="fb-icon-btn photo" onclick="document.getElementById('postImageInput').click()" title="Photo">
                                        <i class="fas fa-images"></i>
                                    </button>
                                    <button type="button" class="fb-icon-btn" style="color: #f7b928;" title="Feeling/activity">
                                        <i class="fas fa-smile"></i>
                                    </button>
                                    <button type="button" class="fb-icon-btn" style="color: #f5533d;" title="Location">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary fb-post-btn">Post</button>
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
                        <small class="text-muted">
                            <?php echo e('@' . $post->user->username); ?> · 
                            <?php if($post->user->favoriteClub): ?>
                                <span class="text-primary fw-semibold"><i class="fas fa-shield-alt small"></i> <?php echo e($post->user->favoriteClub->name); ?></span> ·
                            <?php endif; ?>
                            <?php echo e($post->created_at->diffForHumans()); ?></small>
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
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
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
                        <button type="button" class="w-100 engagement-btn like-btn <?php if($post->isLikedBy(auth()->user())): ?> liked <?php endif; ?>" data-post-id="<?php echo e($post->id); ?>" onclick="handleEngagement(this, 'like')">
                            <i class="fas fa-thumbs-up"></i> <span>Like (<?php echo e($post->likes_count); ?>)</span>
                        </button>
                        <button type="button" class="w-100 engagement-btn dislike-btn <?php if($post->isDislikedBy(auth()->user())): ?> liked <?php endif; ?>" data-post-id="<?php echo e($post->id); ?>" onclick="handleEngagement(this, 'dislike')">
                            <i class="fas fa-thumbs-down"></i> <span>Dislike (<?php echo e($post->dislikes_count); ?>)</span>
                        </button>
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

    <!-- Right Sidebar (Popular) -->
    <div class="col-lg-3">
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

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('postImageInput').value = '';
            document.getElementById('imagePreviewContainer').style.display = 'none';
        }

        const textarea = document.querySelector('.fb-textarea');
        if(textarea) {
            textarea.addEventListener('input', function() {
                if(this.value.length > 80) {
                    this.style.fontSize = '1.1rem';
                } else {
                    this.style.fontSize = '1.25rem';
                }
            });
        }

        async function handleEngagement(btn, type) {
            const postId = btn.getAttribute('data-post-id');
            const url = `/posts/${postId}/${type}`;
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                const postCard = btn.closest('.card');
                const likeBtn = postCard.querySelector('.like-btn');
                const dislikeBtn = postCard.querySelector('.dislike-btn');
                
                if(data.liked) likeBtn.classList.add('liked'); else likeBtn.classList.remove('liked');
                if(data.disliked) dislikeBtn.classList.add('liked'); else dislikeBtn.classList.remove('liked');
                
                likeBtn.querySelector('span').textContent = `Like (${data.likes_count})`;
                dislikeBtn.querySelector('span').textContent = `Dislike (${data.dislikes_count})`;
            } catch (error) {
                console.error('Engagement failed:', error);
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/posts/feed.blade.php ENDPATH**/ ?>