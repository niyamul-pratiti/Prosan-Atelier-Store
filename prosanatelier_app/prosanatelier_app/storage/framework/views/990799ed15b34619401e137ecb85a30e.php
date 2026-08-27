<?php echo $__env->make('emails.orders.partials-style', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="wrap">
    <div class="card">
        <div class="head"><h1>Thank you for your order</h1></div>
        <div class="body">
            <p>Dear <?php echo e($order->customer_name); ?>,</p>
            <p>Your order has been received successfully. We will contact you if anything else is needed.</p>
            <div class="summary">
                <strong>Order:</strong> <?php echo e($order->order_number); ?><br>
                <strong>Status:</strong> <?php echo e(ucfirst((string) $order->order_status)); ?><br>
                <strong>Payment:</strong> <?php echo e(ucwords(str_replace('_', ' ', (string) $order->payment_method))); ?> — <?php echo e(ucfirst((string) $order->payment_status)); ?><br>
                <strong>Total:</strong> ৳<?php echo e(number_format((float) $order->grand_total, 0)); ?>

            </div>
            <?php if($order->items && $order->items->count()): ?>
                <table>
                    <thead><tr><th>Product</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->product_name); ?> <?php if($item->variation_name): ?> <span class="muted">(<?php echo e($item->variation_name); ?>)</span> <?php endif; ?></td>
                            <td><?php echo e($item->quantity); ?></td>
                            <td>৳<?php echo e(number_format((float) $item->line_total, 0)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <a class="btn" href="<?php echo e(route('order.tracking', ['order_number' => $order->order_number, 'phone' => $order->customer_phone])); ?>">Track Order</a>
        </div>
    </div>
    <div class="foot"><?php echo e(\App\Models\SiteSetting::getValue('site_name', 'Prosan Atelier')); ?></div>
</div>
<?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/emails/orders/customer-placed.blade.php ENDPATH**/ ?>