@php
    $isEdit = filled($order);
    $oldItems = old('items');

    if (! $oldItems) {
        $oldItems = $isEdit
            ? $order->items->map(fn ($item) => [
                'product_id' => $item->product_id,
                'product_variation_id' => $item->product_variation_id,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
            ])->values()->all()
            : [['product_id' => '', 'product_variation_id' => '', 'quantity' => 1, 'unit_price' => 0]];
    }

    $productPayload = $products->map(fn ($product) => [
        'id' => $product->id,
        'name' => $product->name,
        'sku' => $product->sku,
        'price' => (float) $product->effective_price,
        'stock' => $product->stock_quantity,
        'variations' => $product->variations->map(fn ($variation) => [
            'id' => $variation->id,
            'name' => $variation->name,
            'sku' => $variation->sku,
            'price' => (float) $variation->effective_price,
            'stock' => $variation->stock_quantity,
        ])->values(),
    ])->values();
@endphp

<form method="POST" action="{{ $action }}" class="admin-order-form">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="admin-grid-two">
        <div class="content-card">
            <h2>Customer Details</h2>
            <div class="form-grid">
                <label>Existing Customer</label>
                <select name="customer_id" id="customer-id">
                    <option value="">Manual / New Customer</option>
                    @foreach($customers as $customer)
                        <option
                            value="{{ $customer->id }}"
                            data-name="{{ $customer->name }}"
                            data-phone="{{ $customer->phone }}"
                            data-email="{{ $customer->email }}"
                            data-address="{{ $customer->address_line }}"
                            data-area="{{ $customer->area }}"
                            data-city="{{ $customer->city }}"
                            data-zone="{{ $customer->shipping_zone }}"
                            @selected((string) old('customer_id', $order->customer_id ?? '') === (string) $customer->id)
                        >{{ $customer->name }} - {{ $customer->phone }}</option>
                    @endforeach
                </select>

                <label>Name *</label>
                <input name="customer_name" value="{{ old('customer_name', $order->customer_name ?? '') }}" required>

                <label>Phone *</label>
                <input name="customer_phone" value="{{ old('customer_phone', $order->customer_phone ?? '') }}" required>

                <label>Email</label>
                <input type="email" name="customer_email" value="{{ old('customer_email', $order->customer_email ?? '') }}">

                <label>Address *</label>
                <textarea name="address_line" required>{{ old('address_line', $order->address_line ?? '') }}</textarea>

                <div class="form-grid two-col">
                    <div>
                        <label>Area</label>
                        <input name="area" value="{{ old('area', $order->area ?? '') }}">
                    </div>
                    <div>
                        <label>City</label>
                        <input name="city" value="{{ old('city', $order->city ?? '') }}">
                    </div>
                </div>

                <label>Customer Note</label>
                <textarea name="customer_note">{{ old('customer_note', $order->customer_note ?? '') }}</textarea>
            </div>
        </div>

        <div class="content-card">
            <h2>Order Settings</h2>
            <div class="form-grid">
                <label>Shipping Zone *</label>
                <select name="shipping_zone" id="order-shipping-zone" required>
                    <option value="inside_dhaka" @selected(old('shipping_zone', $order->shipping_zone ?? 'inside_dhaka') === 'inside_dhaka')>Inside Dhaka - ৳60</option>
                    <option value="outside_dhaka" @selected(old('shipping_zone', $order->shipping_zone ?? '') === 'outside_dhaka')>Outside Dhaka - ৳130</option>
                </select>

                <label>Discount</label>
                <input type="number" min="0" step="1" name="discount_total" id="order-discount" value="{{ old('discount_total', (float) ($order->discount_total ?? 0)) }}">

                <label>Payment Method</label>
                <select name="payment_method">
                    @foreach(['cod' => 'Cash on Delivery', 'manual_bkash' => 'Manual bKash', 'manual_nagad' => 'Manual Nagad', 'bank_transfer' => 'Bank Transfer'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('payment_method', $order->payment_method ?? 'cod') === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <label>Payment Status</label>
                <select name="payment_status">
                    @foreach($paymentStatuses as $status)
                        <option value="{{ $status }}" @selected(old('payment_status', $order->payment_status ?? 'unpaid') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>

                <label>Order Status</label>
                <select name="order_status">
                    @foreach($orderStatuses as $status)
                        <option value="{{ $status }}" @selected(old('order_status', $order->order_status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>

                <label>Admin Note</label>
                <textarea name="admin_note">{{ old('admin_note', $order->admin_note ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="table-card mt-4">
        <div class="section-heading compact">
            <h2>Order Items</h2>
            <button class="btn small" type="button" id="add-order-item">Add Item</button>
        </div>

        <div class="admin-order-items" id="order-items">
            @foreach($oldItems as $index => $item)
                <div class="admin-order-item-row" data-row>
                    <select name="items[{{ $index }}][product_id]" data-product-select required>
                        <option value="">Select product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected((string) ($item['product_id'] ?? '') === (string) $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <select name="items[{{ $index }}][product_variation_id]" data-variation-select>
                        <option value="">No variation</option>
                    </select>
                    <input type="number" min="1" step="1" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? 1 }}" data-qty required>
                    <input type="number" min="0" step="1" name="items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] ?? 0 }}" data-price required>
                    <strong data-line-total>৳0</strong>
                    <button class="link-danger" type="button" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>

        <div class="summary-card align-right admin-order-summary">
            <p>Subtotal: <strong id="order-subtotal">৳0</strong></p>
            <p>Shipping: <strong id="order-shipping">৳60</strong></p>
            <p>Discount: <strong id="order-discount-text">৳0</strong></p>
            <h2>Total: <span id="order-grand-total">৳0</span></h2>
        </div>
    </div>

    <div class="actions mt-4">
        <button class="btn" type="submit">{{ $isEdit ? 'Update Order' : 'Create Order' }}</button>
        <a class="btn ghost" href="{{ $isEdit ? route('admin.orders.show', $order) : route('admin.orders.index') }}">Cancel</a>
    </div>
</form>

<template id="order-item-template">
    <div class="admin-order-item-row" data-row>
        <select data-product-select required>
            <option value="">Select product</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
        <select data-variation-select>
            <option value="">No variation</option>
        </select>
        <input type="number" min="1" step="1" value="1" data-qty required>
        <input type="number" min="0" step="1" value="0" data-price required>
        <strong data-line-total>৳0</strong>
        <button class="link-danger" type="button" data-remove-row>Remove</button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const products = @json($productPayload);
    const rows = document.getElementById('order-items');
    const template = document.getElementById('order-item-template');
    const addButton = document.getElementById('add-order-item');
    const zone = document.getElementById('order-shipping-zone');
    const discount = document.getElementById('order-discount');
    const customerSelect = document.getElementById('customer-id');
    const currency = n => '৳' + Number(n || 0).toLocaleString('en-US', {maximumFractionDigits: 0});

    function productById(id) {
        return products.find(product => String(product.id) === String(id));
    }

    function variationById(product, id) {
        if (!product || !id) return null;
        return product.variations.find(variation => String(variation.id) === String(id));
    }

    function refreshNames() {
        rows.querySelectorAll('[data-row]').forEach((row, index) => {
            row.querySelector('[data-product-select]').name = `items[${index}][product_id]`;
            row.querySelector('[data-variation-select]').name = `items[${index}][product_variation_id]`;
            row.querySelector('[data-qty]').name = `items[${index}][quantity]`;
            row.querySelector('[data-price]').name = `items[${index}][unit_price]`;
        });
    }

    function refreshVariations(row, selectedVariationId = null, keepPrice = false) {
        const productSelect = row.querySelector('[data-product-select]');
        const variationSelect = row.querySelector('[data-variation-select]');
        const priceInput = row.querySelector('[data-price]');
        const product = productById(productSelect.value);

        variationSelect.innerHTML = '<option value="">No variation</option>';
        if (product) {
            product.variations.forEach(variation => {
                const option = document.createElement('option');
                option.value = variation.id;
                option.textContent = `${variation.name} - ${currency(variation.price)} - Stock: ${variation.stock}`;
                variationSelect.appendChild(option);
            });
            variationSelect.value = selectedVariationId || '';
            if (!keepPrice) {
                const selectedVariation = variationById(product, variationSelect.value);
                priceInput.value = selectedVariation ? selectedVariation.price : product.price;
            }
        }

        calculate();
    }

    function calculate() {
        let subtotal = 0;
        rows.querySelectorAll('[data-row]').forEach(row => {
            const qty = Math.max(1, Number(row.querySelector('[data-qty]').value || 1));
            const price = Math.max(0, Number(row.querySelector('[data-price]').value || 0));
            const lineTotal = qty * price;
            subtotal += lineTotal;
            row.querySelector('[data-line-total]').textContent = currency(lineTotal);
        });

        const shipping = subtotal > 0 ? (zone.value === 'outside_dhaka' ? 130 : 60) : 0;
        const discountValue = Math.min(Math.max(0, Number(discount.value || 0)), subtotal + shipping);

        document.getElementById('order-subtotal').textContent = currency(subtotal);
        document.getElementById('order-shipping').textContent = currency(shipping);
        document.getElementById('order-discount-text').textContent = currency(discountValue);
        document.getElementById('order-grand-total').textContent = currency(Math.max(0, subtotal + shipping - discountValue));
    }

    rows.addEventListener('change', function (event) {
        const row = event.target.closest('[data-row]');
        if (!row) return;

        if (event.target.matches('[data-product-select]')) {
            refreshVariations(row);
        }

        if (event.target.matches('[data-variation-select]')) {
            const product = productById(row.querySelector('[data-product-select]').value);
            const variation = variationById(product, event.target.value);
            row.querySelector('[data-price]').value = variation ? variation.price : (product ? product.price : 0);
            calculate();
        }
    });

    rows.addEventListener('input', calculate);
    rows.addEventListener('click', function (event) {
        if (!event.target.matches('[data-remove-row]')) return;
        if (rows.querySelectorAll('[data-row]').length === 1) return;
        event.target.closest('[data-row]').remove();
        refreshNames();
        calculate();
    });

    addButton.addEventListener('click', function () {
        rows.appendChild(template.content.firstElementChild.cloneNode(true));
        refreshNames();
        calculate();
    });

    zone.addEventListener('change', calculate);
    discount.addEventListener('input', calculate);

    customerSelect.addEventListener('change', function () {
        const option = customerSelect.selectedOptions[0];
        if (!option || !option.value) return;

        document.querySelector('[name="customer_name"]').value = option.dataset.name || '';
        document.querySelector('[name="customer_phone"]').value = option.dataset.phone || '';
        document.querySelector('[name="customer_email"]').value = option.dataset.email || '';
        document.querySelector('[name="address_line"]').value = option.dataset.address || '';
        document.querySelector('[name="area"]').value = option.dataset.area || '';
        document.querySelector('[name="city"]').value = option.dataset.city || '';
        if (option.dataset.zone === 'inside_dhaka' || option.dataset.zone === 'outside_dhaka') {
            zone.value = option.dataset.zone;
        }
        calculate();
    });

    rows.querySelectorAll('[data-row]').forEach(row => {
        refreshVariations(row, row.querySelector('[data-variation-select]').dataset.selected || row.querySelector('[data-variation-select]').value, true);
    });

    @foreach($oldItems as $index => $item)
        (function () {
            const row = rows.querySelectorAll('[data-row]')[{{ $index }}];
            if (!row) return;
            refreshVariations(row, @json($item['product_variation_id'] ?? ''), true);
        })();
    @endforeach

    refreshNames();
    calculate();
});
</script>
