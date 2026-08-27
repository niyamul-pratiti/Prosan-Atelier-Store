<?php $__env->startSection('title', 'Order ' . $order->order_number); ?>
<?php $__env->startSection('content'); ?>
<div class="section-heading admin-heading-row">
    <div><h1>Order <?php echo e($order->order_number); ?></h1><p class="muted">View, update status or customize this order.</p></div>
    <div class="d-flex gap-2 admin-order-actions">
        <a class="btn ghost" href="<?php echo e(route('admin.orders.index')); ?>">Back</a>
        <a class="btn ghost" target="_blank" href="<?php echo e(route('admin.orders.invoice', $order)); ?>">Print Invoice</a>
        <a class="btn ghost" target="_blank" href="<?php echo e(route('admin.orders.packing_slip', $order)); ?>">Packing Slip</a>
        <a class="btn" href="<?php echo e(route('admin.orders.edit', $order)); ?>">Edit / Customize</a>
        <form method="POST" action="<?php echo e(route('admin.orders.destroy', $order)); ?>" onsubmit="return confirm('Remove this order? Stock will be restored for non-completed orders.');" class="inline-delete-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn danger">Remove Order</button>
        </form>
    </div>
</div>
<div class="admin-grid-two">
    <div class="content-card">
        <h2>Customer</h2>
        <p><strong>Name:</strong> <?php echo e($order->customer_name); ?></p>
        <?php if($order->customer): ?><p><strong>Customer Account:</strong> <a href="<?php echo e(route('admin.customers.show', $order->customer)); ?>">View Customer Dashboard</a></p><?php endif; ?>
        <p><strong>Phone:</strong> <?php echo e($order->customer_phone); ?></p>
        <p><strong>Email:</strong> <?php echo e($order->customer_email ?: 'N/A'); ?></p>
        <p><strong>Address:</strong> <?php echo e($order->address_line ?: 'N/A'); ?></p>
        <p><strong>Thana / Area:</strong> <?php echo e($order->area ?: 'N/A'); ?></p>
        <p><strong>District:</strong> <?php echo e($order->city ?: 'Dhaka'); ?></p>
        <?php if($order->coupon_code): ?><p><strong>Coupon:</strong> <?php echo e($order->coupon_code); ?> (-৳<?php echo e(number_format($order->discount_total, 0)); ?>)</p><?php endif; ?>
        <p><strong>Shipping:</strong> <?php echo e((float) $order->shipping_total === 0.0 ? 'Free' : '৳' . number_format($order->shipping_total, 0)); ?></p>
        <p><strong>Parcel Weight:</strong> <?php echo e((int) ($order->parcel_weight_grams ?? 0) > 0 ? number_format(((int) $order->parcel_weight_grams) / 1000, 2) . ' kg' : 'Not calculated'); ?></p>
        <p><strong>Charge Method:</strong> <?php echo e($order->shipping_manually_set ? 'Manual override' : 'Automatic area + weight'); ?></p>
        <p><strong>Payment Method:</strong> <?php echo e($order->paymentMethodLabel()); ?></p>
        <?php if($order->payment_method !== 'cod'): ?>
            <p><strong>Payment Account:</strong> <?php echo e($order->payment_account ?: 'N/A'); ?></p>
            <p><strong>Sender Number:</strong> <?php echo e($order->payment_sender_number ?: 'N/A'); ?></p>
            <p><strong>Transaction ID:</strong> <?php echo e($order->payment_transaction_id ?: 'N/A'); ?></p>
        <?php endif; ?>
        <p><strong>Note:</strong> <?php echo e($order->customer_note ?: 'N/A'); ?></p>
    </div>
    <div class="content-card">
        <h2>Status</h2>
        <?php
            $forceFree = (bool) ($order->shipping_manually_set ?? false) && (float) $order->shipping_total === 0.0 && (float) $order->subtotal > 0;
            $freeMinimum = (int) ($siteSettings['free_delivery_minimum'] ?? 5000);
        ?>
        <form method="POST" action="<?php echo e(route('admin.orders.status', $order)); ?>" class="form-grid">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <label class="admin-check-row order-free-delivery-row">
                <input type="checkbox" name="free_delivery" value="1" <?php if($forceFree): echo 'checked'; endif; ?>>
                <span>Free delivery for this order</span>
            </label>
            <label>Delivery Charge (৳)</label>
            <input type="number" name="shipping_total" value="<?php echo e(number_format((float) $order->shipping_total, 0, '.', '')); ?>" min="0" step="1" required>
            <small class="muted">Edit this amount and click Update Order. The customer total, invoice and Steadfast COD amount will update together.</small>
            <label class="admin-check-row">
                <input type="checkbox" name="recalculate_delivery" value="1">
                <span>Discard the manual amount and recalculate from area + parcel weight</span>
            </label>
            <small class="muted">Automatic free delivery applies when subtotal is ৳<?php echo e(number_format($freeMinimum)); ?> or more.</small>
            <label>Order Status</label>
            <select name="order_status">
                <?php $__currentLoopData = ['pending','processing','shipped','completed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($status); ?>" <?php if($order->order_status === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <label>Payment Status</label>
            <select name="payment_status">
                <?php $__currentLoopData = ['unpaid','paid','refunded']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($status); ?>" <?php if($order->payment_status === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php if($order->payment_method !== 'cod'): ?>
                <label>Sender/Account Number</label>
                <input name="payment_sender_number" value="<?php echo e($order->payment_sender_number); ?>" placeholder="Customer payment number">
                <label>Transaction / Reference ID</label>
                <input name="payment_transaction_id" value="<?php echo e($order->payment_transaction_id); ?>" placeholder="Transaction ID or bank reference">
                <label>Payment Account</label>
                <input name="payment_account" value="<?php echo e($order->payment_account); ?>" placeholder="Payment number/account used">
            <?php endif; ?>
            <label>Admin Note</label>
            <textarea name="admin_note"><?php echo e($order->admin_note); ?></textarea>
            <button class="btn" type="submit">Update Order</button>
        </form>
    </div>
</div>

<div class="content-card courier-card-prosan">
    <div class="admin-heading-row">
        <div>
            <h2>Steadfast Courier</h2>
            <p class="muted">Send this order to Steadfast after confirming payment. Paid orders go with COD ৳0; unpaid orders go with full COD amount.</p>
        </div>
        <span class="badge <?php echo e($order->steadfast_tracking_code ? 'paid' : 'pending'); ?>"><?php echo e($order->courierStatusLabel()); ?></span>
    </div>

    <div class="courier-summary-grid">
        <p><strong>COD Amount to Send:</strong><br>৳<?php echo e(number_format($order->codAmountForSteadfast(), 0)); ?></p>
        <p><strong>Consignment ID:</strong><br><?php echo e($order->steadfast_consignment_id ?: 'Not sent yet'); ?></p>
        <p><strong>Tracking Code:</strong><br><?php echo e($order->steadfast_tracking_code ?: 'Not available yet'); ?></p>
        <p><strong>Last Checked:</strong><br><?php echo e($order->steadfast_last_checked_at ? $order->steadfast_last_checked_at->format('d M Y, h:i A') : 'Never'); ?></p>
    </div>

    <div class="d-flex gap-2 flex-wrap mt-3">
        <form method="POST" action="<?php echo e(route('admin.orders.steadfast.send', $order)); ?>" onsubmit="return confirm('Send this order to Steadfast? COD amount will be ৳<?php echo e(number_format($order->codAmountForSteadfast(), 0)); ?>.');">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn"><?php echo e($order->steadfast_tracking_code ? 'Resend to Steadfast' : 'Send to Steadfast'); ?></button>
        </form>
        <form method="POST" action="<?php echo e(route('admin.orders.steadfast.refresh', $order)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn ghost">Refresh Courier Status</button>
        </form>
    </div>

    <?php if($order->courier_note): ?>
        <p class="muted mt-3 mb-0"><strong>Courier Note:</strong> <?php echo e($order->courier_note); ?></p>
    <?php endif; ?>
</div>

<div class="table-card">
    <h2>Items</h2>
    <table>
        <thead><tr><th>Product</th><th>SKU</th><th>Selling Price</th><th>Cost</th><th>Qty</th><th>Total</th><th>Profit</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php ($costTotal = (float) ($item->cost_total ?? 0)); ?>
            <tr>
                <td><?php echo e($item->product_name); ?> <?php if($item->variation_name): ?><span class="muted">(<?php echo e($item->variation_name); ?>)</span><?php endif; ?></td>
                <td><?php echo e($item->sku); ?></td>
                <td>৳<?php echo e(number_format($item->unit_price, 0)); ?></td>
                <td>৳<?php echo e(number_format($item->cost_price ?? 0, 0)); ?></td>
                <td><?php echo e($item->quantity); ?></td>
                <td>৳<?php echo e(number_format($item->line_total, 0)); ?></td>
                <td>৳<?php echo e(number_format(max(0, (float) $item->line_total - $costTotal), 0)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php ($totalCost = $order->items->sum(fn($item) => (float) ($item->cost_total ?? 0))); ?>
    <div class="summary-card align-right">
        <p>Subtotal: <strong>৳<?php echo e(number_format($order->subtotal, 0)); ?></strong></p>
        <?php if($order->coupon_code): ?><p>Coupon (<?php echo e($order->coupon_code); ?>): <strong>-৳<?php echo e(number_format($order->discount_total, 0)); ?></strong></p><?php else: ?><p>Discount: <strong>-৳<?php echo e(number_format($order->discount_total, 0)); ?></strong></p><?php endif; ?>
        <p>Total Sales: <strong>৳<?php echo e(number_format($order->subtotal - $order->discount_total, 0)); ?></strong></p>
        <p>Product Cost: <strong>৳<?php echo e(number_format($totalCost, 0)); ?></strong></p>
        <p>Shipping: <strong><?php echo e((float) $order->shipping_total === 0.0 ? 'Free' : '৳' . number_format($order->shipping_total, 0)); ?></strong></p>
        <h2>Profit: ৳<?php echo e(number_format(max(0, (float) $order->subtotal - (float) $order->discount_total - $totalCost), 0)); ?></h2>
        <h2>Customer Total: ৳<?php echo e(number_format($order->grand_total, 0)); ?></h2>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>