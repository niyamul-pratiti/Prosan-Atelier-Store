<div class="order-md-last prosan-cart-panel-content">
    <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-primary">Your cart</span>
        <span class="badge bg-primary rounded-pill" data-cart-count><?php echo e($cartCount ?? 0); ?></span>
    </h4>
    <?php if(empty($cart)): ?>
        <p class="text-muted">Your cart is empty.</p>
        <a class="w-100 btn btn-primary btn-lg" href="<?php echo e(route('shop.index')); ?>">Start Shopping</a>
    <?php else: ?>
        <ul class="list-group mb-3">
            <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0"><?php echo e(Str::limit($item['name'], 42)); ?></h6>
                        <?php if(!empty($item['variation_name'])): ?><small class="text-body-secondary d-block"><?php echo e($item['variation_name']); ?></small><?php endif; ?>
                        <small class="text-body-secondary">Qty: <?php echo e($item['quantity']); ?></small>
                    </div>
                    <span class="text-body-secondary">৳<?php echo e(number_format($item['price'] * $item['quantity'], 0)); ?></span>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <li class="list-group-item d-flex justify-content-between"><span>Total (BDT)</span><strong>৳<?php echo e(number_format($cartTotal ?? 0, 0)); ?></strong></li>
        </ul>
        <a class="w-100 btn btn-primary btn-lg" href="<?php echo e(route('checkout.index')); ?>">Continue to checkout</a>
        <a class="w-100 btn btn-outline-dark mt-2" href="<?php echo e(route('cart.index')); ?>">View cart</a>
    <?php endif; ?>
</div><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/partials/cart-offcanvas-body.blade.php ENDPATH**/ ?>