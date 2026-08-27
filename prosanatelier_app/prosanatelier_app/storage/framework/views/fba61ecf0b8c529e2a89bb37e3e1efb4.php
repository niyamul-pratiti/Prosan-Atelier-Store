<?php
    $image = $product->image_url;
    $hasOptions = ($product->active_variations_count ?? null) !== null
        ? $product->active_variations_count > 0
        : ($product->is_variable && $product->activeVariations->count() > 0);
    $customerId = session('customer_id');
    $isWishlisted = $customerId ? \App\Models\Wishlist::where('customer_id', $customerId)->where('product_id', $product->id)->exists() : false;
?>
<div class="product-item <?php echo e($carousel ?? false ? 'swiper-slide' : ''); ?>">
    <?php if($product->has_discount && $product->discount_percent > 0): ?><span class="badge bg-success position-absolute m-3">-<?php echo e($product->discount_percent); ?>%</span><?php endif; ?>
    <form method="POST" action="<?php echo e(route('wishlist.toggle', $product)); ?>" class="wishlist-toggle-form">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn-wishlist <?php echo e($isWishlisted ? 'is-active' : ''); ?>" aria-label="<?php echo e($isWishlisted ? 'Remove from wishlist' : 'Add to wishlist'); ?>">
            <svg width="24" height="24"><use xlink:href="#heart"></use></svg>
        </button>
    </form>
    <figure>
        <a href="<?php echo e(route('product.show', $product->slug)); ?>" title="<?php echo e($product->name); ?>">
            <img src="<?php echo e($image); ?>" class="tab-image" alt="<?php echo e($product->name); ?>" loading="lazy">
        </a>
    </figure>
    <h3><a href="<?php echo e(route('product.show', $product->slug)); ?>"><?php echo e(Str::limit($product->name, 54)); ?></a></h3>
    <?php if($product->weight && ! $product->is_variable): ?>
        <span class="qty"><?php echo e(rtrim(rtrim($product->weight, '0'), '.') . ' ' . $product->unit); ?></span>
    <?php elseif($product->is_variable): ?>
        <span class="qty"><?php echo e($product->activeVariations->count() ?: ($product->active_variations_count ?? 0)); ?> options available</span>
    <?php endif; ?>
    <span class="rating"><svg width="24" height="24" class="text-primary"><use xlink:href="#star-solid"></use></svg> 4.5</span>
    <span class="price product-card-price">
        <?php if(! $product->is_variable && $product->has_discount): ?><del>৳<?php echo e(number_format($product->regular_price, 0)); ?></del><?php endif; ?>
        <?php echo e($product->price_label); ?>

    </span>
    <form method="POST" action="<?php echo e(route('cart.add')); ?>" class="product-card-actions mt-3 js-ajax-add-to-cart">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
        <?php if($hasOptions): ?>
            <input type="hidden" name="quantity" value="1">
            <a href="<?php echo e(route('product.show', $product->slug)); ?>" class="product-option-link w-100">Select Option <iconify-icon icon="uil:arrow-right"></iconify-icon></a>
        <?php else: ?>
            <div class="input-group product-qty">
                <button type="button" class="quantity-left-minus btn btn-number" data-type="minus" aria-label="Decrease quantity"><svg width="16" height="16"><use xlink:href="#minus"></use></svg></button>
                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" aria-label="Quantity">
                <button type="button" class="quantity-right-plus btn btn-number" data-type="plus" aria-label="Increase quantity"><svg width="16" height="16"><use xlink:href="#plus"></use></svg></button>
            </div>
            <button type="submit" class="add-cart-link w-100"><span>Add to Cart</span><iconify-icon icon="uil:shopping-cart"></iconify-icon></button>
        <?php endif; ?>
    </form>
</div>
<?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/partials/product-card.blade.php ENDPATH**/ ?>