<?php $__env->startSection('title', 'Thank You - Prosan Atelier'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $method = $paymentMethods[$order->payment_method] ?? ['label' => $order->paymentMethodLabel(), 'account' => $order->payment_account, 'note' => ''];
?>
<section class="py-5" style="background-image:url('<?php echo e(asset('foodmart/images/background-pattern.jpg')); ?>');background-size:cover;">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5 thank-you-card-prosan">
                    <svg width="64" height="64" class="text-primary mx-auto mb-3"><use xlink:href="#check"></use></svg>
                    <h1>Thank you for your order!</h1>
                    <p>Your order number is <strong><?php echo e($order->order_number); ?></strong>.</p>
                    <?php if($order->coupon_code): ?><p>Coupon: <strong><?php echo e($order->coupon_code); ?></strong> saved you ৳<?php echo e(number_format($order->discount_total,0)); ?></p><?php endif; ?>
                    <p>Total: <strong>৳<?php echo e(number_format($order->grand_total,0)); ?></strong></p>
                    <div class="payment-summary-box-prosan text-start mx-auto my-4">
                        <h4>Payment</h4>
                        <p><strong>Method:</strong> <?php echo e($method['label']); ?></p>
                        <p><strong>Status:</strong> <?php echo e(ucfirst($order->payment_status)); ?></p>
                        <?php if($order->payment_method !== 'cod'): ?>
                            <p><strong>Pay To:</strong> <?php echo e($order->payment_account ?: ($method['account'] ?? 'N/A')); ?></p>
                            <p><strong>Sender/Account Number:</strong> <?php echo e($order->payment_sender_number ?: 'N/A'); ?></p>
                            <p><strong>Transaction/Reference ID:</strong> <?php echo e($order->payment_transaction_id ?: 'N/A'); ?></p>
                            <div class="alert alert-warning rounded-4 mb-0">We received your payment information. Admin will verify it and mark the order as paid.</div>
                        <?php else: ?>
                            <div class="alert alert-info rounded-4 mb-0">Please pay with cash when you receive your products.</div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a class="btn btn-primary btn-lg" target="_blank" href="<?php echo e(route('checkout.invoice', $order->order_number)); ?>">Download Invoice</a>
                        <a class="btn btn-outline-dark btn-lg" href="<?php echo e(route('order.tracking')); ?>">Track Order</a>
                        <a class="btn btn-outline-dark btn-lg" href="<?php echo e(route('shop.index')); ?>">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/frontend/thank-you.blade.php ENDPATH**/ ?>