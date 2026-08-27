<?php $__env->startSection('title', 'Orders'); ?>
<?php $__env->startSection('content'); ?>
<div class="section-heading admin-heading-row">
    <div><h1>Orders</h1><p class="muted">Manage customer and manually created orders.</p></div>
    <a class="btn" href="<?php echo e(route('admin.orders.create')); ?>">+ Create Order</a>
</div>
<form class="toolbar" method="GET">
    <input name="q" value="<?php echo e(request('q')); ?>" placeholder="Search order/customer/phone">
    <select name="status">
        <option value="">All statuses</option>
        <?php $__currentLoopData = ['pending','processing','shipped','completed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button class="btn" type="submit">Filter</button>
</form>
<div class="table-card">
    <table>
        <thead><tr><th>Order</th><th>Customer</th><th>Phone</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($order->order_number); ?></td>
                <td><?php echo e($order->customer_name); ?></td>
                <td><?php echo e($order->customer_phone); ?></td>
                <td>৳<?php echo e(number_format($order->grand_total, 0)); ?></td>
                <td><span class="badge"><?php echo e($order->order_status); ?></span></td>
                <td><?php echo e($order->created_at->format('d M Y')); ?></td>
                <td class="table-actions">
                    <a href="<?php echo e(route('admin.orders.show', $order)); ?>">View</a>
                    <a href="<?php echo e(route('admin.orders.edit', $order)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.orders.destroy', $order)); ?>" onsubmit="return confirm('Remove this order? Stock will be restored for non-completed orders.');" class="inline-delete-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="table-delete-link">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($orders->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>