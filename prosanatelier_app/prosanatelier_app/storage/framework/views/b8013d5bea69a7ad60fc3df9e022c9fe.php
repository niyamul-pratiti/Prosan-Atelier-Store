<?php echo $__env->make('emails.orders.partials-style', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="wrap">
    <div class="card">
        <div class="head"><h1>New order received</h1></div>
        <div class="body">
            <p>A new order has been placed on <?php echo e(\App\Models\SiteSetting::getValue('site_name', 'Prosan Atelier')); ?>.</p>
            <div class="summary">
                <strong>Order:</strong> <?php echo e($order->order_number); ?><br>
                <strong>Placed:</strong> <?php echo e($order->created_at ? $order->created_at->copy()->timezone('Asia/Dhaka')->format('d M Y, h:i A') : 'N/A'); ?> (Dhaka time)<br>
                <strong>Customer:</strong> <?php echo e($order->customer_name); ?><br>
                <strong>Phone:</strong> <?php echo e($order->customer_phone); ?><br>
                <?php if(!empty($order->customer_email)): ?><strong>Email:</strong> <?php echo e($order->customer_email); ?><br><?php endif; ?>
                <strong>Address:</strong> <?php echo e($order->address_line ?: 'N/A'); ?><br>
                <strong>Area / District:</strong> <?php echo e($order->area ?: 'N/A'); ?> <?php if($order->city): ?> / <?php echo e($order->city); ?> <?php endif; ?><br>
                <strong>Delivery Zone:</strong> <?php echo e(ucwords(str_replace('_', ' ', (string) ($order->shipping_zone ?: 'N/A')))); ?><br>
                <strong>Parcel Weight:</strong> <?php echo e((int) ($order->parcel_weight_grams ?? 0) > 0 ? number_format(((int) $order->parcel_weight_grams) / 1000, 2) . ' kg' : 'Not calculated'); ?><br>
                <strong>Payment:</strong> <?php echo e($order->paymentMethodLabel()); ?> — <?php echo e(ucfirst((string) $order->payment_status)); ?><br>
                <strong>Order Status:</strong> <?php echo e(ucfirst((string) $order->order_status)); ?>

            </div>
            <?php if($order->items && $order->items->count()): ?>
                <table>
                    <thead><tr><th>Product</th><th>Unit Price</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php echo e($item->product_name); ?>

                                <?php if($item->variation_name): ?> <span class="muted">(<?php echo e($item->variation_name); ?>)</span> <?php endif; ?>
                                <?php if($item->sku): ?> <br><span class="muted">SKU: <?php echo e($item->sku); ?></span> <?php endif; ?>
                            </td>
                            <td>৳<?php echo e(number_format((float) $item->unit_price, 0)); ?></td>
                            <td><?php echo e($item->quantity); ?></td>
                            <td>৳<?php echo e(number_format((float) $item->line_total, 0)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <div class="summary">
                <strong>Subtotal:</strong> ৳<?php echo e(number_format((float) $order->subtotal, 0)); ?><br>
                <?php if((float) $order->discount_total > 0): ?>
                    <strong>Discount:</strong> -৳<?php echo e(number_format((float) $order->discount_total, 0)); ?><br>
                <?php endif; ?>
                <strong>Delivery Charge:</strong> <?php echo e((float) $order->shipping_total === 0.0 ? 'Free' : '৳' . number_format((float) $order->shipping_total, 0)); ?><br>
                <strong>Grand Total:</strong> ৳<?php echo e(number_format((float) $order->grand_total, 0)); ?>

            </div>
            <?php if(!empty($order->customer_note)): ?>
                <div class="summary">
                    <strong>Customer Note:</strong><br>
                    <?php echo e($order->customer_note); ?>

                </div>
            <?php endif; ?>
            <?php if($order->payment_method !== 'cod'): ?>
                <div class="summary">
                    <?php if($order->payment_account): ?><strong>Payment Account:</strong> <?php echo e($order->payment_account); ?><br><?php endif; ?>
                    <?php if($order->payment_sender_number): ?><strong>Sender Number:</strong> <?php echo e($order->payment_sender_number); ?><br><?php endif; ?>
                    <?php if($order->payment_transaction_id): ?><strong>Transaction ID:</strong> <?php echo e($order->payment_transaction_id); ?><?php endif; ?>
                </div>
            <?php endif; ?>
            <a class="btn" href="<?php echo e(route('admin.orders.show', $order)); ?>">Open Order</a>
        </div>
    </div>
    <div class="foot">Admin notification</div>
</div>
<?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/emails/orders/admin-new-order.blade.php ENDPATH**/ ?>