<?php $__env->startSection('title', 'Order Tracking - Prosan Atelier'); ?>
<?php $__env->startSection('content'); ?>
<section class="py-5" style="background-image:url('<?php echo e(asset('foodmart/images/background-pattern.jpg')); ?>');background-size:cover;">
    <div class="container-fluid"><h1 class="display-5 fw-bold">Order Tracking</h1><p class="text-muted">Check your order status using order number and phone.</p></div>
</section>
<section class="py-5">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <form method="POST" action="<?php echo e(route('order.tracking.store')); ?>" class="row g-3">
                        <?php echo csrf_field(); ?>
                        <div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true"><label>Website</label><input type="text" name="website" value="" tabindex="-1" autocomplete="off"></div>
                        <div class="col-md-7"><label class="form-label">Order Number</label><input class="form-control form-control-lg" name="order_number" value="<?php echo e(old('order_number')); ?>" placeholder="PA-260707-XXXXXX" required></div>
                        <div class="col-md-5"><label class="form-label">Phone</label><input class="form-control form-control-lg" name="customer_phone" value="<?php echo e(old('customer_phone')); ?>" placeholder="Optional but recommended"></div>
                        <div class="col-12"><button class="btn btn-primary btn-lg w-100 rounded-pill" type="submit">Track Order</button></div>
                    </form>

                    <?php if($searched ?? false): ?>
                        <?php if($order): ?>
                            <div class="order-track-result mt-4">
                                <div class="alert alert-success rounded-4 mb-4"><h4 class="mb-1">Order <?php echo e($order->order_number); ?></h4><p class="mb-0">Your order was found successfully.</p></div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4"><div class="track-stat"><span>Status</span><strong><?php echo e(ucfirst($order->order_status)); ?></strong></div></div>
                                    <div class="col-md-4"><div class="track-stat"><span>Payment</span><strong><?php echo e(ucfirst($order->payment_status)); ?></strong></div></div>
                                    <div class="col-md-4"><div class="track-stat"><span>Total</span><strong>৳<?php echo e(number_format($order->grand_total,0)); ?></strong></div></div>
                                </div>
                                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Product</th><th>Qty</th><th>Total</th></tr></thead><tbody><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($item->product_name); ?> <?php if($item->variation_name): ?><small class="text-muted d-block"><?php echo e($item->variation_name); ?></small><?php endif; ?></td><td><?php echo e($item->quantity); ?></td><td>৳<?php echo e(number_format($item->line_total,0)); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody></table></div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger rounded-4 mt-4 mb-0">No order found. Please check your order number and phone number.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/frontend/order-tracking.blade.php ENDPATH**/ ?>