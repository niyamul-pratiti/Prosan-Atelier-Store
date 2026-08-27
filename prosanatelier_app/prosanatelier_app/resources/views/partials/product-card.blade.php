@php
    $image = $product->image_url;
    $hasOptions = ($product->active_variations_count ?? null) !== null
        ? $product->active_variations_count > 0
        : ($product->is_variable && $product->activeVariations->count() > 0);
    $customerId = session('customer_id');
    $isWishlisted = $customerId ? \App\Models\Wishlist::where('customer_id', $customerId)->where('product_id', $product->id)->exists() : false;
@endphp
<div class="product-item {{ $carousel ?? false ? 'swiper-slide' : '' }}">
    @if($product->has_discount && $product->discount_percent > 0)<span class="badge bg-success position-absolute m-3">-{{ $product->discount_percent }}%</span>@endif
    <form method="POST" action="{{ route('wishlist.toggle', $product) }}" class="wishlist-toggle-form">
        @csrf
        <button type="submit" class="btn-wishlist {{ $isWishlisted ? 'is-active' : '' }}" aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
            <svg width="24" height="24"><use xlink:href="#heart"></use></svg>
        </button>
    </form>
    <figure>
        <a href="{{ route('product.show', $product->slug) }}" title="{{ $product->name }}">
            <img src="{{ $image }}" class="tab-image" alt="{{ $product->name }}" loading="lazy">
        </a>
    </figure>
    <h3><a href="{{ route('product.show', $product->slug) }}">{{ Str::limit($product->name, 54) }}</a></h3>
    @if($product->weight && ! $product->is_variable)
        <span class="qty">{{ rtrim(rtrim($product->weight, '0'), '.') . ' ' . $product->unit }}</span>
    @elseif($product->is_variable)
        <span class="qty">{{ $product->activeVariations->count() ?: ($product->active_variations_count ?? 0) }} options available</span>
    @endif
    <span class="rating"><svg width="24" height="24" class="text-primary"><use xlink:href="#star-solid"></use></svg> 4.5</span>
    <span class="price product-card-price">
        @if(! $product->is_variable && $product->has_discount)<del>৳{{ number_format($product->regular_price, 0) }}</del>@endif
        {{ $product->price_label }}
    </span>
    <form method="POST" action="{{ route('cart.add') }}" class="product-card-actions mt-3 js-ajax-add-to-cart">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        @if($hasOptions)
            <input type="hidden" name="quantity" value="1">
            <a href="{{ route('product.show', $product->slug) }}" class="product-option-link w-100">Select Option <iconify-icon icon="uil:arrow-right"></iconify-icon></a>
        @else
            <div class="input-group product-qty">
                <button type="button" class="quantity-left-minus btn btn-number" data-type="minus" aria-label="Decrease quantity"><svg width="16" height="16"><use xlink:href="#minus"></use></svg></button>
                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" aria-label="Quantity">
                <button type="button" class="quantity-right-plus btn btn-number" data-type="plus" aria-label="Increase quantity"><svg width="16" height="16"><use xlink:href="#plus"></use></svg></button>
            </div>
            <button type="submit" class="add-cart-link w-100"><span>Add to Cart</span><iconify-icon icon="uil:shopping-cart"></iconify-icon></button>
        @endif
    </form>
</div>
