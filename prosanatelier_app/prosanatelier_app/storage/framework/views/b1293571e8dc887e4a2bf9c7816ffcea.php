<?php $__env->startSection('title', 'Coupons'); ?>
<?php $__env->startSection('content'); ?>
<div class="section-heading"><h1>Coupons</h1><a class="btn" href="<?php echo e(route('admin.coupons.create')); ?>">Add Coupon</a></div>
<form class="toolbar" method="GET"><input name="q" value="<?php echo e(request('q')); ?>" placeholder="Search coupon code"><button class="btn" type="submit">Search</button></form>
<div class="table-card">
    <table>
        <thead><tr><th>Code</th><th>Type</th><th>Amount</th><th>Applies To</th><th>Minimum</th><th>Usage</th><th>Status</th><th>Expires</th><th>Action</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($coupon->code); ?></strong><br><span class="muted"><?php echo e($coupon->description); ?></span></td>
                <td><?php echo e($coupon->type_label); ?></td>
                <td>
                    <?php if($coupon->type === 'percent'): ?> <?php echo e(rtrim(rtrim(number_format($coupon->amount, 2), '0'), '.')); ?>%
                    <?php elseif($coupon->type === 'free_delivery'): ?> Free delivery
                    <?php else: ?> ৳<?php echo e(number_format($coupon->amount, 0)); ?>

                    <?php endif; ?>
                </td>
                <td><?php echo e($coupon->applies_to_label); ?></td>
                <td>৳<?php echo e(number_format($coupon->minimum_order_amount, 0)); ?></td>
                <td><?php echo e($coupon->used_count); ?><?php echo e($coupon->usage_limit ? ' / ' . $coupon->usage_limit : ''); ?></td>
                <td><span class="badge"><?php echo e($coupon->is_active ? 'Active' : 'Inactive'); ?></span></td>
                <td><?php echo e($coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'No expiry'); ?></td>
                <td class="actions">
                    <a href="<?php echo e(route('admin.coupons.edit', $coupon)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.coupons.destroy', $coupon)); ?>" onsubmit="return confirm('Delete coupon?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button type="submit">Delete</button></form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="9">No coupons created yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php echo e($coupons->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/coupons/index.blade.php ENDPATH**/ ?>