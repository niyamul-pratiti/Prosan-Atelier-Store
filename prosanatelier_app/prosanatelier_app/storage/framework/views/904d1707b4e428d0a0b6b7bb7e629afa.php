<?php $__env->startSection('title', 'System Health'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-head prosan-tool-head">
    <div>
        <p class="admin-kicker">System Status</p>
        <h1>System Health</h1>
        <p class="text-muted">Quickly check whether the live store is ready for orders, uploads, email, and courier operations.</p>
    </div>
</div>

<div class="prosan-health-grid">
    <?php $__currentLoopData = $checks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $check): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="content-card prosan-health-card status-<?php echo e($check['status']); ?>">
            <div class="health-status-dot"></div>
            <div>
                <h3><?php echo e($check['label']); ?></h3>
                <strong><?php echo e($check['value']); ?></strong>
                <?php if($check['note']): ?>
                    <p><?php echo e($check['note']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/system/health.blade.php ENDPATH**/ ?>