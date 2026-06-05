<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - Football Social Media</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1f4788;
            --secondary-color: #ff6b6b;
            --light-bg: #f8f9fa;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 70px;
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: white !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #153355;
            border-color: #153355;
        }

        .btn-danger {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-danger:hover {
            background-color: #e55555;
            border-color: #e55555;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .post-card {
            margin-bottom: 20px;
        }

        .post-header {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .post-body {
            padding: 15px;
        }

        .post-footer {
            padding: 10px 15px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: space-around;
            background-color: #f8f9fa;
        }

        .post-footer button {
            border: none;
            background: none;
            color: #6c757d;
            cursor: pointer;
            font-size: 0.9rem;
            flex: 1;
            padding: 10px;
        }

        .post-footer button:hover {
            color: var(--primary-color);
        }

        .post-footer button.liked {
            color: var(--secondary-color);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .large-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .sidebar {
            position: sticky;
            top: 20px;
        }

        .sidebar-card {
            margin-bottom: 20px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                top: auto;
            }
        }

        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo e(route('welcome')); ?>">
                <i class="fas fa-football-ball"></i> Football Social
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('feed')); ?>"><i class="fas fa-home"></i> Feed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('clubs.index')); ?>"><i class="fas fa-users"></i> Clubs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('profile.edit')); ?>"><i class="fas fa-user"></i> Profile</a>
                        </li>
                        <li class="nav-item">
                            <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="nav-link btn btn-link"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('login')); ?>"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('register')); ?>"><i class="fas fa-user-plus"></i> Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Errors!</strong>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('success_modal')): ?>
                <div class="modal fade" id="sessionSuccessModal" tabindex="-1" aria-labelledby="sessionSuccessModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sessionSuccessModalLabel">Success</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><?php echo e(session('success_modal')); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    window.addEventListener('DOMContentLoaded', function() {
                        var successModal = new bootstrap.Modal(document.getElementById('sessionSuccessModal'));
                        successModal.show();
                    });
                </script>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <script>
                    window.addEventListener('DOMContentLoaded', function() {
                        alert(<?php echo json_encode(session('success'), 15, 512) ?>);
                    });
                </script>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\banta\resources\views/layouts/app.blade.php ENDPATH**/ ?>