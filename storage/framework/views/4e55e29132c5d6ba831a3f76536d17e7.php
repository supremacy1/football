

<?php $__env->startSection('title', 'Welcome'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .welcome-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 80px 0;
        border-radius: 15px;
        margin-bottom: 40px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-radius: 12px;
    }
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 20px;
        background-color: #f8f9fa;
        color: #198754;
    }
    .btn-football {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }
    .btn-football:hover {
        background-color: #157347;
        border-color: #146c43;
        color: white;
    }
</style>

<div class="container-fluid px-0">
    <div class="welcome-hero text-center">
        <div class="container py-4">
            <i class="fas fa-futbol mb-4" style="font-size: 4rem; color: #198754;"></i>
            <h1 class="display-3 fw-bold mb-3">SPORTBANTA</h1>
            <h2 class="h4 mb-4 text-light">The Ultimate Football Fan Network</h2>
            <p class="lead mb-5 mx-auto opacity-75" style="max-width: 700px;">
                Connect with football fans, share your passion, and stay updated with the latest news and discussions from the beautiful game.
            </p>

            <?php if(auth()->guard()->guest()): ?>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-football btn-lg px-4 fw-bold">
                        <i class="fas fa-user-plus me-2"></i> Join the Squad
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light btn-lg px-4 fw-bold">
                        <i class="fas fa-sign-in-alt me-2"></i> Kick Off
                    </a>
                </div>
            <?php else: ?>
                <a href="<?php echo e(route('feed')); ?>" class="btn btn-football btn-lg px-4 fw-bold">
                    <i class="fas fa-home me-2"></i> Go to Stadium
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 feature-card text-center p-4">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Connect with Fans</h5>
                        <p class="card-text text-muted small">Join a vibrant community of football enthusiasts from around the world and debate the latest scores.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card text-center p-4">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="fas fa-comment-dots fa-lg"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Share & Discuss</h5>
                        <p class="card-text text-muted small">Share your thoughts, post match updates, and engage in tactical discussions with other fans.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card text-center p-4">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="fas fa-star fa-lg"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Support Your Club</h5>
                        <p class="card-text text-muted small">Join your favorite club's fan group, follow their journey, and celebrate every goal together.</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/welcome.blade.php ENDPATH**/ ?>