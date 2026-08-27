<?php echo $__env->make('emails.orders.partials-style', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="wrap">
    <div class="card">
        <div class="head"><h1>Your order has been updated</h1></div>
        <div class="body">
            <p>Dear <?php echo e($order->customer_name); ?>,</p>
            <p>Your order status has been updated.</p>
            <div class="summary">
                <strong>Order:</strong> <?php echo e($order->order_number); ?><br>
                <strong>Order Status:</strong> <?php echo e(ucfirst((string) $order->order_status)); ?><br>
                <strong>Payment Status:</strong> <?php echo e(ucfirst((string) $order->payment_status)); ?><br>
                <strong>Total:</strong> ৳<?php echo e(number_format((float) $order->grand_total, 0)); ?>

            </div>
            <a class="btn" href="<?php echo e(route('order.tracking', ['order_number' => $order->order_number, 'phone' => $order->customer_phone])); ?>">Track Order</a>
        </div>
    </div>
    <div class="foot"><?php echo e(\App\Models\SiteSetting::getValue('site_name', 'Prosan Atelier')); ?></div>
</div>
<?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/emails/orders/customer-status-update.blade.php ENDPATH**/ ?>