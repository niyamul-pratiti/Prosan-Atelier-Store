<?php $__env->startSection('title', 'Customers'); ?>
<?php $__env->startSection('content'); ?>
<div class="section-heading"><h1>Customers</h1></div>
<form class="toolbar" method="GET">
    <input name="q" value="<?php echo e(request('q')); ?>" placeholder="Search customer, email or phone">
    <button class="btn" type="submit">Filter</button>
</form>
<div class="table-card">
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>City</th><th>Joined</th><th>Action</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($customer->name); ?></td>
                <td><?php echo e($customer->email ?: '—'); ?></td>
                <td><?php echo e($customer->phone); ?></td>
                <td><?php echo e($customer->orders_count); ?></td>
                <td><?php echo e($customer->city ?: '—'); ?></td>
                <td><?php echo e($customer->created_at->format('d M Y')); ?></td>
                <td><a href="<?php echo e(route('admin.customers.show', $customer)); ?>">View</a></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7">No customers found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php echo e($customers->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/customers/index.blade.php ENDPATH**/ ?>