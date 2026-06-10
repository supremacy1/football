

<?php $__env->startSection('title', 'Football News'); ?>

<?php $__env->startSection('feed_header'); ?>
<div class="mb-3 px-2">
    <h4 class="fw-bold"><i class="fas fa-newspaper text-success me-2"></i>Football & Transfer News</h4>
    <p class="text-muted small">Stay updated with the latest from the football world.</p>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('posts.feed', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/posts/news.blade.php ENDPATH**/ ?>