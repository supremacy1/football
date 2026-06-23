

<?php $__env->startSection('title', 'News Feed'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    /* Specific styles for the feed page, overriding or extending Bootstrap */
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
    
    .modal-content { border-radius: 8px; border: none; box-shadow: 0 12px 28px rgba(0,0,0,0.2); background-color: #1a1a3e; color: white; }

    /* Left Sidebar Navigation */
    .hero-img {
    height: 220px;
    object-fit: cover;
    filter: brightness(0.6);
}

.carousel-caption {
    bottom: 30%;
    text-align: left;
}
    .left-nav-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-radius: 8px; text-decoration: none;
        color: white;
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
    .left-nav-item:hover, .left-nav-item.active { background-color: #3a3b3c; color: white; }
    .sticky-left-nav { position: sticky; top: 85px; }

    /* Hero Section */
    .hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 30px;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 20px;
    }
    .hero h1 { font-size: 36px; margin-bottom: 10px; }
    .hero p { font-size: 18px; opacity: 0.9; }
    
    /* News Ticker */
    .news-ticker {
        background: #1a1a3e;
        border-radius: 10px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        overflow: hidden;
        margin-top: 20px;
        border: 1px solid #333;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }
    .news-ticker-label {
        background: #f44336;
        color: white;
        padding: 4px 12px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 12px;
        margin-right: 15px;
        white-space: nowrap;
    }
    .news-ticker-content {
        white-space: nowrap;
        overflow: hidden;
        position: relative;
        flex: 1;
    }
    .news-ticker-content span {
        display: inline-block;
        animation: scrollNews 30s linear infinite;
        color: #ccc;
        font-size: 14px;
    }
    @keyframes scrollNews {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    
    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
    }
    .action-btn {
        background: #1a1a3e;
        border: none;
        padding: 15px;
        border-radius: 10px;
        cursor: pointer;
        color: white;
        transition: all 0.3s;
        text-align: center;
    }
    .action-btn:hover {
        background: #667eea;
        transform: translateY(-3px);
    }
    .action-icon { font-size: 30px; }
    .action-label { font-size: 12px; margin-top: 5px; }
    
    /* Ad Banners */
    .ad-banner {
        background: #1a1a3e;
        border-radius: 15px;
        border: 1px solid #333;
        overflow: hidden;
        margin: 15px auto;
        max-width: 1200px;
    }
    .hero-ad { padding: 15px; text-align: center; }
    .hero-ad .ad-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    .hero-ad .ad-content span {
        background: #f39c12;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 10px;
        color: white;
    }
    .hero-ad img { max-height: 80px; }
    
    /* Main 3-Column Layout */
    @media (max-width: 1000px) { .main-container { grid-template-columns: 1fr; } }
    
    .section-card {
        background: #1a1a3e;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
    }
    .section-title {
        font-size: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
        padding-left: 15px;
    }
    
    /* Side Ads */
    .side-ad .ad-content span {
        background: #667eea;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 10px;
        color: white;
    }
    .side-ad img { max-height: 150px; object-fit: cover; width: 100%; border-radius: 10px; }
    .side-ad p { font-size: 10px; color: #666; text-align: center; margin-top: 5px; }
    
    /* Match Cards */
    .match-card {
        background: #0a0a2a;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
    }
    .match-teams {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        font-size: 16px;
    }
    .match-time { text-align: center; color: #f8f9fa; font-size: 12px; margin: 10px 0; }
    .match-league { font-size: 11px; color: #888; }
    .bet-now-btn {
        background: #4caf50;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 15px;
        cursor: pointer;
        font-size: 11px;
        margin-top: 10px;
    }
    .post-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .post-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    
    /* News & Analytics */
    .news-card {
        background: #0a0a2a;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
    }
    .news-title { font-weight: bold; margin-bottom: 8px; }
    .news-category { font-size: 10px; color: #667eea; }
    
    .stat-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #333;
    }
    .stat-value { font-weight: bold; color: #4caf50; }
    
    .loading { text-align: center; padding: 40px; color: #888; }

    .post-footer button.liked {
        color: #667eea;
    }

    /* Specific styles for the global image preview modal to ensure it looks good on dark background */
    #imagePreviewModal .modal-content {
        background: transparent;
        border: none;
    }
    #imagePreviewModal .btn-close-white { filter: invert(1) grayscale(1) brightness(2); }
    #imagePreviewModal .img-fluid { background-color: rgba(0,0,0,0.5); }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="col-12">

    <!-- HERO IMAGE SLIDER -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="2000">

        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">

            <!-- Slide 1 (your uploaded image) -->
            <div class="carousel-item active">
                <img src="/images/imagespo.png"
                     class="d-block w-100 hero-img" alt="Slide 1">
                
                <div class="carousel-caption">
                    <!-- <h1>🏆 The Sport Challenger's Platform</h1>
                    <p>Watch matches, share opinions, create bets, and win real money!</p> -->
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <img src="/images/1k.jpg"
                     class="d-block w-100 hero-img" alt="Slide 2">

                <div class="carousel-caption">
                    <!-- <h1>⚽ Live Matches</h1>
                    <p>Follow real-time scores and match updates instantly.</p> -->
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">
                <img src="/images/2ki.jpg"
                     class="d-block w-100 hero-img" alt="Slide 3">

                <div class="carousel-caption">
                    <!-- <h1>💰 Win Big Rewards</h1>
                    <p>Predict games and earn real cash prizes.</p> -->
                </div>
            </div>

        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>

    <!-- News Ticker -->
    <div class="news-ticker">
        <div class="news-ticker-label">📰 BREAKING NEWS</div>
        <div class="news-ticker-content" id="newsTicker">
            <span>Loading news...</span>
        </div>
    </div>

</div>
    <!-- Quick Actions (Bet Buttons) -->
    <div class="quick-actions">
        <button class="action-btn" onclick="location.href='<?php echo e(route('betting.index')); ?>'">
            <div class="action-icon">⚽</div>
            <div class="action-label">Match Betting</div>
        </button>
        <button class="action-btn" onclick="location.href='<?php echo e(route('matches.live')); ?>'">
            <div class="action-icon">🏆</div>
            <div class="action-label">All Matches</div>
        </button>
        <button class="action-btn" onclick="location.href='<?php echo e(route('profile.show', auth()->user())); ?>'">
            <div class="action-icon">👤</div>
            <div class="action-label">Dashboard</div>
        </button>
    </div>
    
    <!-- Hero Ad Banner -->
    <!-- <div class="ad-banner hero-ad">
        <div class="ad-content">
            <span>📢 SPONSORED</span>
            <p>Your Ad Here - Contact us for advertising opportunities</p>
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='80'%3E%3Crect width='300' height='80' fill='%23333'/%3E%3Ctext x='20' y='50' fill='%23666' font-size='16'%3EAd Placeholder%3C/text%3E%3C/svg%3E" alt="Ad">
        </div>
    </div> -->
    
    <!-- Main 3-Column Layout -->
    <div class="row mt-4">
       
        <div class="col-lg-3 d-none d-lg-block">
            <div class="section-card sticky-left-nav">
                <div class="section-title">🧭 Navigation</div>
                <a href="<?php echo e(route('profile.show', auth()->user())); ?>" class="left-nav-item">
                    <img src="<?php echo e(auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random'); ?>" alt="">
                    <span><?php echo e(auth()->user()->name); ?></span>
                </a>
                <a href="<?php echo e(route('feed')); ?>" class="left-nav-item <?php echo e(Route::is('feed') ? 'active' : ''); ?>">
                    <i class="fas fa-home text-primary"></i>
                    <span>Feed</span>
                </a>
                <a href="<?php echo e(route('news')); ?>" class="left-nav-item <?php echo e(Route::is('news') ? 'active' : ''); ?>">
                  <i class="fas fa-newspaper text-success"></i>
                    <span>News Feed</span>
                </a>
                <a href="<?php echo e(route('clubs.index')); ?>" class="left-nav-item <?php echo e(Route::is('clubs.index') ? 'active' : ''); ?>">
                    <i class="fas fa-users text-info"></i> 
                    <span>Clubs</span> 
                </a>
                <a href="<?php echo e(route('matches.live')); ?>" class="left-nav-item"> 
                    <i class="fas fa-calendar-check text-danger"></i>
                    <span>Matches</span>
                </a>
                <a href="#" class="left-nav-item"> 
                    <i class="fas fa-bookmark text-warning"></i>
                    <span>Saved</span>
                </a>
                <hr class="my-3 border-secondary">
                <h6 class="px-3 text-muted mb-2 small text-uppercase fw-bold">Shortcuts</h6>
                <?php $__currentLoopData = auth()->user()->clubMemberships->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('clubs.show', $club)); ?>" class="left-nav-item">
                        <div class="bg-light rounded p-1 me-3 text-center" style="width: 36px;"><i class="fas fa-shield-alt text-secondary"></i></div>
                        <span><?php echo e($club->name); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Left Side Ad -->
            <!-- <div class="ad-banner side-ad section-card mt-4">
                <div class="ad-content">
                    <span>📢 AD</span>
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='150'%3E%3Crect width='200' height='150' fill='%23333'/%3E%3Ctext x='20' y='80' fill='%23666' font-size='16'%3EAd%3C/text%3E%3C/svg%3E" alt="Ad">
                    <p>Sponsored Content</p>
                </div>
            </div> -->
            
            
        </div>
        
        <!-- MIDDLE COLUMN: Social Feed -->
        <div class="col-lg-6">
            <?php echo $__env->yieldContent('feed_header'); ?> 
            <?php if(auth()->guard()->check()): ?>
                <div class="card section-card mb-4 bg-white ">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="<?php echo e(auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random'); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="avatar">
                            <button type="button" class="btn btn-outline-secondary flex-grow-1 text-start" data-bs-toggle="modal" data-bs-target="#createPostModal">
                                Drop Your Banta, <?php echo e(auth()->user()->name); ?>?
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
                                        <div class="user-name text-white"><?php echo e(auth()->user()->name); ?></div>
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

            <div id="socialFeedContainer">
                <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="card post-card mb-3 text-white">
                            <div class="post-header d-flex justify-content-between align-items-start text-white">
                                <div class="d-flex align-items-center text-white">
                                    <img src="<?php echo e($post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&background=random'); ?>" alt="<?php echo e($post->user->name); ?>" class="avatar me-3 ">
                                    <div>
                                        <h6 class="mb-0 ">
                                            <a href="<?php echo e(route('profile.show', $post->user)); ?>" class="text-white text-decoration-none"><?php echo e($post->user->name); ?></a>
                                        </h6>
                                    <small class="text-muted">
                                        <?php echo e('@' . $post->user->username); ?> · 
                                        <?php if($post->user->favoriteClub): ?>
                                            <span class="text-white fw-semibold"><i class="fas fa-shield-alt small text-dark"></i> <?php echo e($post->user->favoriteClub->name); ?></span> ·
                                        <?php endif; ?>
                                        <?php echo e($post->created_at->diffForHumans()); ?></small>
                                    </div>
                                </div>
                                <?php if(auth()->guard()->check()): ?>
                                    <?php if(auth()->user()->id === $post->user_id): ?>
                                        <div class="dropdown text-white">
                                            <button class="btn btn-sm btn-link text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                                <div class="px-3 pt-2 ">
                                    <span class="badge bg-info tex-white"><?php echo e($post->club->name); ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="post-body">
                                <p class="mb-2"><?php echo e($post->content); ?></p>

                                <?php
                                    $images = null;
                                    if (!empty($post->image)) {
                                        $decoded = @json_decode($post->image, true);
                                        $images = is_array($decoded) ? $decoded : [$post->image];
                                    }
                                ?>

                                <?php if(!empty($images) && count($images) > 0): ?>
                                    <div class="post-images mb-2">
                                        <?php if(count($images) == 1): ?>
                                            <?php $img = $images[0]; $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); ?>
                                            <img src="<?php echo e($src); ?>" alt="Post image" class="img-fluid rounded preview-trigger" data-src="<?php echo e($src); ?>" style="max-height:500px; width:100%; object-fit:cover; cursor: pointer;">
                                        <?php else: ?>
                                            <div class="row g-2">
                                                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php $src = \Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/' . $img); ?>
                                                    <div class="col-6">
                                                        <img src="<?php echo e($src); ?>" alt="Post image" class="img-fluid rounded preview-trigger" data-src="<?php echo e($src); ?>" style="height:200px; width:100%; object-fit:cover; cursor: pointer;">
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($post->video): ?>
                                    <video width="100%" height="auto" controls class="rounded mb-2">
                                        <source src="<?php echo e(asset('storage/' . $post->video)); ?>" type="video/mp4">
                                    </video>
                                <?php endif; ?>
                            </div>

                            <div class="post-footer">
                                <?php if(auth()->guard()->check()): ?>
                                    <button type="button" class="engagement-btn like-btn <?php if($post->isLikedBy(auth()->user())): ?> liked <?php endif; ?>" data-post-id="<?php echo e($post->id); ?>" onclick="handleEngagement(this, 'like')">
                                        <i class="fas fa-thumbs-up "></i> <span>Like (<?php echo e($post->likes_count); ?>)</span>
                                    </button>
                                    <button type="button" class="engagement-btn dislike-btn <?php if($post->isDislikedBy(auth()->user())): ?> liked <?php endif; ?>" data-post-id="<?php echo e($post->id); ?>" onclick="handleEngagement(this, 'dislike')">
                                        <i class="fas fa-thumbs-down"></i> <span>Dislike (<?php echo e($post->dislikes_count); ?>)</span>
                                    </button>
                                <?php else: ?>
                                    <button><i class="fas fa-thumbs-up"></i> Like (<?php echo e($post->likes_count); ?>)</button>
                                    <button><i class="fas fa-thumbs-down"></i> Dislike (<?php echo e($post->dislikes_count); ?>)</button>
                                <?php endif; ?>
                                <a href="<?php echo e(route('posts.show', $post)); ?>" style="color: #6c757d; text-decoration: none;">
                                    <i class="fas fa-comment"></i> Comment (<?php echo e($post->comments_count); ?>)
                                </a>
                                <button><i class="fas fa-share"></i> Share (<?php echo e($post->shares_count); ?>)</button>
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
        </div>
        
        <!-- RIGHT COLUMN -->
        <div class="col-lg-3 d-none d-lg-block">
            <!-- <div class="section-card"> -->
                <div class="section-card sticky-left-nav">
                <div class="section-title">📰 Trending News</div>
                <div id="newsList">
                    <div class="news-card">
                        <div class="news-category">⚡ BREAKING</div>
                        <div class="news-title">Mbappe signs for Real Madrid for €250M</div>
                        <small>2 hours ago</small>
                    </div>
                    <div class="news-card">
                        <div class="news-category">🏆 TRANSFER</div>
                        <div class="news-title">Haaland wins Premier League Golden Boot with 36 goals</div>
                        <small>5 hours ago</small>
                    </div>
                    <div class="news-card">
                        <div class="news-category">🌍 WORLD CUP</div>
                        <div class="news-title">FIFA announces expanded 48-team World Cup format</div>
                        <small>1 day ago</small>
                    </div>
                </div>
            </div>
            
            <!-- Right Side Ad -->
            <!-- <div class="ad-banner side-ad section-card mt-4">
                <div class="ad-content">
                    <span>📢 AD</span>
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='150'%3E%3Crect width='200' height='150' fill='%23333'/%3E%3Ctext x='20' y='80' fill='%23666' font-size='16'%3EAd%3C/text%3E%3C/svg%3E" alt="Ad">
                    <p>Sponsored Content</p>
                </div>
            </div> -->
            
            <!-- <div class="section-card mt-4">
                <div class="section-title">⚽ Top Scorers 2025/26</div>
                <div class="stat-item"><span>1. Erling Haaland</span><span class="stat-value">36 goals</span></div>
                <div class="stat-item"><span>2. Kylian Mbappe</span><span class="stat-value">32 goals</span></div>
                <div class="stat-item"><span>3. Harry Kane</span><span class="stat-value">28 goals</span></div>
                <div class="stat-item"><span>4. Victor Osimhen</span><span class="stat-value">24 goals</span></div>
                <div class="stat-item"><span>5. Mohamed Salah</span><span class="stat-value">22 goals</span></div>
            </div> -->
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

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
        // News Ticker JavaScript (from snippet)
        async function fetchNews() {
            try {
                const response = await fetch('https://api.rss2json.com/v1/api.json?rss_url=https://www.supersport.com/rss/feed');
                const data = await response.json();
                if (data.items && data.items.length > 0) {
                    const titles = data.items.slice(0, 5).map(item => item.title).join(' | ');
                    document.querySelector('#newsTicker span').textContent = titles;
                }
            } catch (error) {
                const fallback = [
                    '⚽ Manchester City wins Premier League',
                    '🏆 Champions League final set for June',
                    '🇳🇬 Super Eagles rise in FIFA rankings',
                    '🔥 Transfer window opens next month',
                    '⚽ Women\'s World Cup dates announced'
                ];
                document.querySelector('#newsTicker span').textContent = fallback.join(' | ');
            }
        }

        // Load Matches (hardcoded from snippet)
        async function loadMatches() {
            const container = document.getElementById('matchesList');
            const matches = [
                { home: "🇧🇷 Brazil", away: "🇫🇷 France", time: "8:00 PM", date: "Today", league: "FIFA World Cup" },
                { home: "🏴󠁧󠁢󠁥󠁮󠁧󠁿 England", away: "🇪🇸 Spain", time: "9:30 PM", date: "Today", league: "FIFA World Cup" },
                { home: "🇦🇷 Argentina", away: "🇩🇪 Germany", time: "3:00 PM", date: "Tomorrow", league: "Friendly" },
            ];
            container.innerHTML = matches.map(m => `
                <div class="match-card">
                    <div class="match-teams"><span>${m.home}</span><span>vs</span><span>${m.away}</span></div>
                    <div class="match-time">📅 ${m.date} - ${m.time} | ${m.league}</div>
                    <button class="bet-now-btn" onclick="quickCreateBet('${m.home} vs ${m.away}')">🎲 Create Bet</button>
                </div>
            `).join('');
        }

        function quickCreateBet(matchTitle) {
            // This function assumes currentUser is managed by the global JS,
            // but in Laravel, we rely on server-side auth.
            // For now, it will redirect to betting.index.
            window.location.href = '<?php echo e(route('betting.index')); ?>';
        }

        // Load Social Feed (from snippet, adapted for Laravel)
        async function loadSocialFeed() {
            try {
                const container = document.getElementById('socialFeedContainer');                
                if (!container.innerHTML.trim() || container.innerHTML.includes('Loading posts...')) {
                    if (<?php echo e($posts->isEmpty() ? 'true' : 'false'); ?>) {
                        container.innerHTML = '<div class="loading">No posts yet. Be the first to post!</div>';
                    }
                }
            } catch (error) {
                console.error('Error loading social feed:', error);
            }
        }

        // Original JS from feed.blade.php
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

        // Initialize functions from snippet
        document.addEventListener('DOMContentLoaded', function() {
            fetchNews();
            loadMatches();
            loadSocialFeed(); // This will primarily manage the loading state if no posts are rendered server-side
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/posts/feed.blade.php ENDPATH**/ ?>