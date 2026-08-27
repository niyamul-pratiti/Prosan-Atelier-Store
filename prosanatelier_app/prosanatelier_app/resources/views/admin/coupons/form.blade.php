@if($errors->any())
    <div class="alert alert-danger"><strong>Please fix:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="form-grid two-col clean-form-grid">
    <div>
        <label>Coupon Code *</label>
        <input name="code" value="{{ old('code', $coupon->code ?? '') }}" placeholder="WELCOME10" required>
    </div>
    <div>
        <label>Type *</label>
        <select name="type" id="coupon-type" required>
            <option value="fixed" @selected(old('type', $coupon->type ?? 'fixed') === 'fixed')>Fixed Amount</option>
            <option value="percent" @selected(old('type', $coupon->type ?? '') === 'percent')>Percentage</option>
            <option value="free_delivery" @selected(old('type', $coupon->type ?? '') === 'free_delivery')>Free Delivery</option>
        </select>
    </div>
    <div>
        <label>Amount</label>
        <input type="number" min="0" step="0.01" name="amount" value="{{ old('amount', $coupon->amount ?? 0) }}" placeholder="100 or 10">
        <small>For fixed amount use Taka. For percentage use percent value. Free delivery ignores amount.</small>
    </div>
    <div>
        <label>Minimum Order Amount</label>
        <input type="number" min="0" step="0.01" name="minimum_order_amount" value="{{ old('minimum_order_amount', $coupon->minimum_order_amount ?? 0) }}" placeholder="Example: 1000">
    </div>
    <div>
        <label>Usage Limit</label>
        <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" placeholder="Blank = unlimited">
    </div>
    <div>
        <label>Applies To</label>
        <select name="applies_to" id="coupon-applies-to">
            <option value="all" @selected(old('applies_to', $coupon->applies_to ?? 'all') === 'all')>All Products</option>
            <option value="categories" @selected(old('applies_to', $coupon->applies_to ?? '') === 'categories')>Selected Categories</option>
            <option value="products" @selected(old('applies_to', $coupon->applies_to ?? '') === 'products')>Selected Products</option>
        </select>
    </div>
    <div>
        <label>Starts At</label>
        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($coupon) && $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\\TH:i') : '') }}">
    </div>
    <div>
        <label>Expires At</label>
        <input type="datetime-local" name="expires_at" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\\TH:i') : '') }}">
    </div>
    <div class="field-full">
        <label>Description</label>
        <textarea name="description" rows="3">{{ old('description', $coupon->description ?? '') }}</textarea>
    </div>
    <label class="checkbox-label field-full"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true))> Active coupon</label>
</div>

@php
    $selectedCategories = collect(old('category_ids', isset($coupon) ? $coupon->categories->pluck('id')->all() : []))->map(fn($id) => (string) $id)->all();
    $selectedProducts = collect(old('product_ids', isset($coupon) ? $coupon->products->pluck('id')->all() : []))->map(fn($id) => (string) $id)->all();
@endphp
<div class="admin-coupon-scope-grid">
    <div class="content-card nested-card" data-coupon-scope="categories">
        <div class="section-heading compact"><h3>Allowed Categories</h3><span class="muted">Used only when applies to selected categories.</span></div>
        <select name="category_ids[]" multiple size="9" class="admin-multi-select">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(in_array((string) $category->id, $selectedCategories, true))>{{ $category->full_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="content-card nested-card" data-coupon-scope="products">
        <div class="section-heading compact"><h3>Allowed Products</h3><span class="muted">Used only when applies to selected products.</span></div>
        <input type="search" class="admin-product-filter" placeholder="Search products in this list...">
        <select name="product_ids[]" multiple size="9" class="admin-multi-select" data-filter-target>
            @foreach($products as $product)
                <option value="{{ $product->id }}" @selected(in_array((string) $product->id, $selectedProducts, true))>{{ $product->name }} {{ $product->sku ? '(' . $product->sku . ')' : '' }}</option>
            @endforeach
        </select>
    </div>
</div>
<button class="btn" type="submit">Save Coupon</button>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const applies = document.getElementById('coupon-applies-to');
    const scopes = document.querySelectorAll('[data-coupon-scope]');
    function refreshScopes(){ scopes.forEach(box => box.style.display = applies.value === box.dataset.couponScope ? 'block' : 'none'); }
    applies?.addEventListener('change', refreshScopes); refreshScopes();
    document.querySelectorAll('.admin-product-filter').forEach(input => {
        input.addEventListener('input', function(){
            const select = this.parentElement.querySelector('[data-filter-target]');
            const q = this.value.trim().toLowerCase();
            Array.from(select.options).forEach(option => option.hidden = q && !option.textContent.toLowerCase().includes(q));
        });
    });
});
</script>
