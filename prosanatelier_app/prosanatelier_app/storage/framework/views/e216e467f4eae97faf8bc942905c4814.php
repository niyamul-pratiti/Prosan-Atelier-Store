<?php $__env->startSection('title', 'Customer Details'); ?>
<?php $__env->startSection('content'); ?>
<div class="section-heading"><h1><?php echo e($customer->name); ?></h1><a class="btn" href="<?php echo e(route('admin.customers.index')); ?>">Back</a></div>
<div class="grid-2">
    <div class="form-card">
        <h2>Customer Information</h2>
        <p><strong>Name:</strong> <?php echo e($customer->name); ?></p>
        <p><strong>Email:</strong> <?php echo e($customer->email ?: '—'); ?></p>
        <p><strong>Phone:</strong> <?php echo e($customer->phone); ?></p>
        <p><strong>Status:</strong> <?php echo e($customer->is_active ? 'Active' : 'Inactive'); ?></p>
    </div>
    <div class="form-card">
        <h2>Default Address</h2>
        <p><?php echo e($customer->address_line ?: 'No address saved.'); ?></p>
        <p><?php echo e($customer->area); ?> <?php echo e($customer->city); ?></p>
        <p><strong>Shipping Zone:</strong> <?php echo e(str_replace('_', ' ', ucfirst($customer->shipping_zone ?: 'N/A'))); ?></p>
    </div>
</div>
<div class="section-heading mt-4"><h2>Recent Orders</h2></div>
<div class="table-card">
    <table>
        <thead><tr><th>Order</th><th>Total</th><th>Status</th><th>Payment</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $customer->orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($order->order_number); ?></td>
                <td>৳<?php echo e(number_format($order->grand_total,0)); ?></td>
                <td><span class="badge"><?php echo e($order->order_status); ?></span></td>
                <td><?php echo e($order->payment_status); ?></td>
                <td><?php echo e($order->created_at->format('d M Y')); ?></td>
                <td><a href="<?php echo e(route('admin.orders.show', $order)); ?>">View Order</a></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6">No orders found for this customer.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/customers/show.blade.php ENDPATH**/ ?>