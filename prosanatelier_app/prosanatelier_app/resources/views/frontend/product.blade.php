@extends('layouts.store')
@section('title', $product->meta_title ?: $product->name.' - Prosan Atelier')
@section('meta_description', $product->meta_description ?: Str::limit(strip_tags($product->short_description ?: $product->description), 150))
@section('content')
@php
    $galleryImages = $product->images->where('is_primary', false);
    $isVariable = $product->is_variable && $product->variations->count() > 0;
    $displayCategory = $product->category
        ?? $product->categories->firstWhere('pivot.is_primary', true)
        ?? $product->categories->first();
    $variationPayload = $product->variations->map(function ($variation) {
        return [
            'id' => $variation->id,
            'name' => $variation->name,
            'sku' => $variation->sku,
            'regular_price' => (float) $variation->regular_price,
            'sale_price' => (float) ($variation->sale_price ?? 0),
            'effective_price' => (float) $variation->effective_price,
            'stock_quantity' => (int) $variation->stock_quantity,
            'weight' => $variation->weight ? rtrim(rtrim((string) $variation->weight, '0'), '.') : null,
            'unit' => $variation->unit,
            'has_discount' => $variation->has_discount,
        ];
    })->values();
@endphp

<section class="py-5" style="background-image:url('{{ asset('foodmart/images/background-pattern.jpg') }}');background-size:cover;">
    <div class="container-fluid">
        <nav class="breadcrumb"><a href="{{ route('home') }}">Home</a><span>/</span><a href="{{ route('shop.index') }}">Shop</a><span>/</span><span>{{ $product->name }}</span></nav>
    </div>
</section>

<section class="py-5 product-detail-section">
    <div class="container-fluid">
        <div class="row g-5 align-items-start">
            <div class="col-lg-6">
                <div class="single-product-image rounded-5 bg-light p-5 text-center"><img src="{{ $product->image_url }}" class="img-fluid" alt="{{ $product->name }}"></div>
                @if($galleryImages->count())
                    <div class="d-flex gap-3 mt-3 flex-wrap">
                        @foreach($galleryImages as $image)
                            <img src="{{ $image->url }}" class="product-thumb-small" alt="{{ $image->alt_text ?: $product->name }}">
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-lg-6">
                <div class="product-summary foodmart-product-summary">
                    @if($displayCategory)
                        <a class="product-category-eyebrow text-primary fw-bold mb-2" href="{{ route('category.show', $displayCategory->slug) }}">{{ $displayCategory->name }}</a>
                    @else
                        <a class="product-category-eyebrow text-primary fw-bold mb-2" href="{{ route('shop.index') }}">Shop</a>
                    @endif
                    <h1 class="display-6 fw-bold">{{ $product->name }}</h1>
                    <div class="rating mb-3"><svg width="24" height="24" class="text-primary"><use xlink:href="#star-solid"></use></svg> 4.5 <span class="text-muted ms-2">Brand: {{ $product->brand->name ?? 'No Brand' }}</span></div>
                    <p class="lead">{{ $product->short_description }}</p>

                    <div class="price product-detail-price mb-4" id="productPriceDisplay">
                        @if(! $product->is_variable && $product->has_discount)<del>৳{{ number_format($product->regular_price,0) }}</del>@endif
                        {{ $product->price_label }}
                    </div>

                    <form class="product-form card border-0 shadow-sm rounded-4 p-4 js-ajax-add-to-cart" method="POST" action="{{ route('cart.add') }}" id="productAddToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        @if($isVariable)
                            <div class="mb-3 variation-picker-box">
                                <label class="form-label fw-bold">Choose option <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg" name="variation_id" id="variationSelect" required>
                                    <option value="">Select an option</option>
                                    @foreach($product->variations as $variation)
                                        <option value="{{ $variation->id }}" @disabled($variation->stock_quantity <= 0)>
                                            {{ $variation->name }} — ৳{{ number_format($variation->effective_price, 0) }}{{ $variation->stock_quantity <= 0 ? ' (Out of stock)' : ' (Stock: '.$variation->stock_quantity.')' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="variation-live-info mt-3" id="variationLiveInfo">Select a variation to see exact price, SKU and stock.</div>
                            </div>
                        @endif

                        <div class="product-detail-actions">
                            <div class="input-group product-qty detail-qty">
                                <button type="button" class="quantity-left-minus btn btn-number" data-type="minus" aria-label="Decrease quantity"><svg width="16" height="16"><use xlink:href="#minus"></use></svg></button>
                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" aria-label="Quantity">
                                <button type="button" class="quantity-right-plus btn btn-number" data-type="plus" aria-label="Increase quantity"><svg width="16" height="16"><use xlink:href="#plus"></use></svg></button>
                            </div>
                            <button class="btn btn-primary btn-lg" type="submit" id="addToCartButton">Add to Cart</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('wishlist.toggle', $product) }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark rounded-pill px-4 py-3"><svg width="20" height="20"><use xlink:href="#heart"></use></svg> Add / Remove Wishlist</button>
                    </form>

                    <div class="row g-3 mt-4 product-meta-grid">
                        <div class="col-sm-6"><div class="info-pill"><strong>SKU:</strong> <span id="productSkuDisplay">{{ $product->sku ?: 'N/A' }}</span></div></div>
                        <div class="col-sm-6"><div class="info-pill"><strong>Stock:</strong> <span id="productStockDisplay">{{ $product->is_variable ? 'Select option' : $product->stock_quantity }}</span></div></div>
                        <div class="col-sm-6"><div class="info-pill"><strong>Category:</strong> @if($displayCategory)<a href="{{ route('category.show', $displayCategory->slug) }}">{{ $displayCategory->name }}</a>@else N/A @endif</div></div>
                        <div class="col-sm-6"><div class="info-pill"><strong>Weight:</strong> <span id="productWeightDisplay">{{ $product->weight && ! $product->is_variable ? $product->weight . ' ' . $product->unit : 'N/A' }}</span></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($product->description || $product->ingredients || $product->usage_instruction)
<section class="py-5 bg-light">
    <div class="container-fluid">
        <div class="card border-0 rounded-5 p-4 p-md-5">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc" type="button">Description</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#ingredients" type="button">Ingredients</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#usage" type="button">Usage</button></li>
            </ul>
            <div class="tab-content pt-4">
                <div class="tab-pane fade show active" id="desc">{!! nl2br(e($product->description)) !!}</div>
                <div class="tab-pane fade" id="ingredients">{!! nl2br(e($product->ingredients ?: 'Not specified.')) !!}</div>
                <div class="tab-pane fade" id="usage">{!! nl2br(e($product->usage_instruction ?: 'Not specified.')) !!}</div>
            </div>
        </div>
    </div>
</section>
@endif

<section class="py-5 overflow-hidden related-products-section">
    <div class="container-fluid">
        <div class="section-header prosan-section-header d-flex justify-content-between align-items-center"><h2 class="section-title">Related Products</h2><a href="{{ route('shop.index') }}" class="btn-link text-decoration-none">View Shop →</a></div>
        <div class="products-carousel swiper"><div class="swiper-wrapper">@foreach($relatedProducts as $related)@include('partials.product-card', ['product' => $related, 'carousel' => true])@endforeach</div></div>
    </div>
</section>

@if($isVariable)
<script>
(function () {
    const variations = @json($variationPayload);
    const select = document.getElementById('variationSelect');
    const priceDisplay = document.getElementById('productPriceDisplay');
    const skuDisplay = document.getElementById('productSkuDisplay');
    const stockDisplay = document.getElementById('productStockDisplay');
    const weightDisplay = document.getElementById('productWeightDisplay');
    const infoBox = document.getElementById('variationLiveInfo');
    const addButton = document.getElementById('addToCartButton');
    const qtyInput = document.getElementById('quantity');

    function money(value) {
        return '৳' + new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 }).format(Number(value || 0));
    }

    function renderVariation() {
        const selected = variations.find(item => String(item.id) === String(select.value));

        if (!selected) {
            priceDisplay.innerHTML = @json($product->price_label);
            skuDisplay.textContent = @json($product->sku ?: 'N/A');
            stockDisplay.textContent = 'Select option';
            weightDisplay.textContent = 'N/A';
            infoBox.textContent = 'Select a variation to see exact price, SKU and stock.';
            addButton.disabled = true;
            return;
        }

        if (selected.has_discount) {
            priceDisplay.innerHTML = '<del>' + money(selected.regular_price) + '</del> ' + money(selected.effective_price);
        } else {
            priceDisplay.textContent = money(selected.effective_price);
        }

        skuDisplay.textContent = selected.sku || @json($product->sku ?: 'N/A');
        stockDisplay.textContent = selected.stock_quantity > 0 ? selected.stock_quantity : 'Out of stock';
        weightDisplay.textContent = selected.weight ? (selected.weight + ' ' + (selected.unit || '')) : 'N/A';
        infoBox.textContent = selected.stock_quantity > 0 ? (selected.name + ' selected. Available stock: ' + selected.stock_quantity) : (selected.name + ' is out of stock.');
        addButton.disabled = selected.stock_quantity <= 0;
        if (qtyInput && Number(qtyInput.value || 1) > selected.stock_quantity) {
            qtyInput.value = Math.max(selected.stock_quantity, 1);
        }
    }

    select.addEventListener('change', renderVariation);
    renderVariation();
})();
</script>
@endif
@endsection
