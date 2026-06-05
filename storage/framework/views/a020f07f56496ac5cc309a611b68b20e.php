

<?php $__env->startSection('title', 'Football Clubs'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Football Clubs</h2>
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('matches.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Create Match
                </a>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <?php if($club->logo): ?>
                            <img src="<?php echo e(asset('storage/' . $club->logo)); ?>" alt="<?php echo e($club->name); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-shield-alt" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($club->name); ?></h5>
                            <p class="card-text text-muted"><?php echo e($club->description ?? 'No description'); ?></p>

                            <div class="mb-3">
                                <?php if($club->country): ?>
                                    <small class="text-muted d-block"><i class="fas fa-flag"></i> <?php echo e($club->country); ?></small>
                                <?php endif; ?>
                                <?php if($club->founded_year): ?>
                                    <small class="text-muted d-block"><i class="fas fa-calendar"></i> Founded <?php echo e($club->founded_year); ?></small>
                                <?php endif; ?>
                                <small class="text-muted d-block"><i class="fas fa-users"></i> <?php echo e($club->members_count); ?> Members</small>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('clubs.show', $club)); ?>" class="btn btn-sm btn-outline-primary flex-grow-1">
                                    View Club
                                </a>
                                <?php if(auth()->guard()->check()): ?>
                                    <?php if($club->isMember(auth()->user())): ?>
                                        <form action="<?php echo e(route('clubs.leave', $club)); ?>" method="POST" class="flex-grow-1">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-danger w-100">Leave</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('clubs.join', $club)); ?>" method="POST" class="flex-grow-1">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-primary w-100">Join</button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <p class="text-muted">No clubs available</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php echo e($clubs->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/clubs/index.blade.php ENDPATH**/ ?>