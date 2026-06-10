

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><span class="text-danger">●</span> Live & Today's Matches</h2>
        <!-- <span class="badge bg-dark">Powered by Free API Live Football Data</span> -->
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-warning"><?php echo e($error); ?></div>
    <?php endif; ?>

    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $statusType = strtolower($match['status']['type'] ?? '');
                $isLive = $statusType === 'inprogress';
                $isFinished = $statusType === 'finished';
                $leagueName = $match['league']['name'] ?? 'Unknown League';
            ?>
            <div class="col-md-12 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <small class="text-muted"><?php echo e($leagueName); ?></small>
                        <?php if($isLive): ?>
                            <span class="badge bg-danger animate-pulse">LIVE <?php echo e($match['status']['status']); ?></span>
                        <?php elseif($isFinished): ?>
                            <span class="badge bg-secondary">FT</span>
                        <?php else: ?>
                            <span class="badge bg-primary"><?php echo e($match['status']['status'] ?? 'Scheduled'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center text-center">
                            <div class="col-4">
                                <img src="<?php echo e($match['homeTeam']['logo'] ?? ''); ?>" alt="home" style="width: 40px; height: 40px;">
                                <p class="mt-2 mb-0 fw-bold small"><?php echo e($match['homeTeam']['name']); ?></p>
                            </div>
                            <div class="col-4">
                                <h3 class="fw-bold mb-0">
                                    <?php echo e($match['homeScore']['current'] ?? 0); ?> - <?php echo e($match['awayScore']['current'] ?? 0); ?>

                                </h3>
                            </div>
                            <div class="col-4">
                                <img src="<?php echo e($match['awayTeam']['logo'] ?? ''); ?>" alt="away" style="width: 40px; height: 40px;">
                                <p class="mt-2 mb-0 fw-bold small"><?php echo e($match['awayTeam']['name']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(isset($match['events']) && is_array($match['events']) && count($match['events']) > 0): ?>
                        <?php $latestEvent = end($match['events']); ?>
                        <div class="card-footer bg-light py-2">
                            <small class="text-muted">Latest: <?php echo e($latestEvent['description'] ?? 'Event'); ?> (<?php echo e($latestEvent['minute'] ?? ''); ?>')</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center py-5">
                <div class="bg-light rounded p-5">
                    <p class="text-muted mb-0">No matches found for today.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
     
        <span class="badge bg-dark">Powered by Free API Live Football Data</span>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/live.blade.php ENDPATH**/ ?>