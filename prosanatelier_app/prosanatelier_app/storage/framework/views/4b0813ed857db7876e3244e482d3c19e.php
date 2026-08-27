<?php $__env->startSection('title', ($order?->exists ?? false) ? 'Edit Order' : 'Create Order'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $isEdit = $order?->exists ?? false;
    $orderItems = old('items');
    if (! is_array($orderItems)) {
        $orderItems = $isEdit
            ? $order->items->map(fn($item) => [
                'product_id' => $item->product_id,
                'product_variation_id' => $item->product_variation_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'cost_price' => $item->cost_price ?? 0,
            ])->values()->all()
            : [['product_id' => '', 'product_variation_id' => '', 'quantity' => 1, 'unit_price' => 0, 'cost_price' => 0]];
    }
    $currentDistrict = old('city', $order->city ?? 'Dhaka');
    $currentThana = old('area', $order->area ?? '');
    $currentAddress = old('address_line', $order->address_line ?? '');
    $currentShipping = \App\Support\BangladeshLocations::zoneForLocation($currentDistrict, $currentThana);
    $freeDelivery = old('free_delivery', (($order?->shipping_manually_set ?? false) && (float) ($order?->shipping_total ?? 0) === 0.0 && ($order?->subtotal ?? 0) > 0) ? 1 : 0);
    $insideCharge = (int) ($deliverySettings['inside_dhaka'] ?? 70);
    $suburbanCharge = (int) ($deliverySettings['dhaka_suburban'] ?? 100);
    $outsideCharge = (int) ($deliverySettings['outside_dhaka'] ?? 130);
    $freeMinimum = (int) ($deliverySettings['free_minimum'] ?? 5000);
    $weightBasedEnabled = (bool) ($deliverySettings['weight_based_enabled'] ?? true);
    $includedWeightGrams = (int) ($deliverySettings['included_weight_grams'] ?? 1000);
    $packagingWeightGrams = (int) ($deliverySettings['packaging_weight_grams'] ?? 200);
    $additionalPerKg = (int) ($deliverySettings['additional_per_kg'] ?? 20);
    $shippingManuallySet = (bool) old('shipping_manually_set', $order->shipping_manually_set ?? false);
    $currentShippingTotal = old('shipping_total', $order->shipping_total ?? 0);
    $currentCouponCode = old('coupon_code', $order->coupon_code ?? '');
    $currentShippingLabel = match ($currentShipping) {
        'dhaka_suburban' => 'Dhaka Suburban — ৳' . number_format($suburbanCharge),
        'outside_dhaka' => 'Outside Dhaka — ৳' . number_format($outsideCharge),
        default => 'Inside Dhaka — ৳' . number_format($insideCharge),
    };
?>
<div class="section-heading admin-heading-row">
    <div>
        <h1><?php echo e($isEdit ? 'Edit / Customize Order' : 'Create Order'); ?></h1>
        <p class="muted">Create customer orders, search products, adjust selling price, enter purchase cost and manage profit.</p>
    </div>
    <a class="btn ghost" href="<?php echo e($isEdit ? route('admin.orders.show', $order) : route('admin.orders.index')); ?>">Back to Orders</a>
</div>

<?php if($errors->any()): ?>
    <div class="alert-box error-box">
        <strong>Please fix the following:</strong>
        <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e($isEdit ? route('admin.orders.update', $order) : route('admin.orders.store')); ?>" class="admin-order-form" id="admin-order-form">
    <?php echo csrf_field(); ?>
    <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="admin-grid-two admin-order-top-grid">
        <div class="content-card">
            <h2>Customer Details</h2>
            <p class="muted">Guest customers will also be saved in the Customers panel. They can create/login to an account later using their phone/email.</p>
            <div class="form-grid two-col clean-form-grid">
                <div class="field-full">
                    <label>Existing Customer</label>
                    <select name="customer_id" id="customer-select">
                        <option value="">Manual / Guest Customer</option>
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($customer->id); ?>" <?php if((string) old('customer_id', $order->customer_id ?? '') === (string) $customer->id): echo 'selected'; endif; ?>><?php echo e($customer->name); ?> — <?php echo e($customer->phone); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div><label>Name *</label><input name="customer_name" id="customer-name" value="<?php echo e(old('customer_name', $order->customer_name ?? '')); ?>" required></div>
                <div><label>Phone *</label><input name="customer_phone" id="customer-phone" value="<?php echo e(old('customer_phone', $order->customer_phone ?? '')); ?>" required></div>
                <div><label>Email</label><input name="customer_email" id="customer-email" type="email" value="<?php echo e(old('customer_email', $order->customer_email ?? '')); ?>"></div>
                <div class="field-full">
                    <label>Full Delivery Address *</label>
                    <textarea name="address_line" id="customer-address" rows="3" required placeholder="House/road/block, building name, flat/floor, landmark"><?php echo e($currentAddress); ?></textarea>
                    <small class="muted">Required for courier delivery and Steadfast integration.</small>
                </div>
                <div>
                    <label>District *</label>
                    <select name="city" id="customer-district" required>
                        <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($district); ?>" <?php if($currentDistrict === $district): echo 'selected'; endif; ?>><?php echo e($district); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <small class="muted">Inside Dhaka ৳<?php echo e(number_format($insideCharge)); ?>, Dhaka Suburban ৳<?php echo e(number_format($suburbanCharge)); ?>, outside Dhaka ৳<?php echo e(number_format($outsideCharge)); ?>.</small>
                </div>
                <div>
                    <label>Thana / Area *</label>
                    <input name="area" id="customer-area" value="<?php echo e($currentThana); ?>" required placeholder="Example: Badda, Gulshan, Mirpur">
                </div>
                <input type="hidden" name="shipping_zone" id="shipping-zone-admin" value="<?php echo e($currentShipping); ?>">
                <div class="field-full"><label>Customer Note</label><textarea name="customer_note"><?php echo e(old('customer_note', $order->customer_note ?? '')); ?></textarea></div>
            </div>
        </div>

        <div class="content-card">
            <h2>Order Settings</h2>
            <div class="form-grid two-col clean-form-grid">
                <div>
                    <label>Shipping Preview</label>
                    <input type="text" id="shipping-preview" value="<?php echo e($currentShippingLabel); ?>" readonly>
                </div>
                <div>
                    <label>Parcel Weight</label>
                    <input type="text" id="parcel-weight-preview" value="<?php echo e(number_format(((int) ($order->parcel_weight_grams ?? 0)) / 1000, 2)); ?> kg" readonly>
                </div>
                <label class="admin-check-row field-full">
                    <input type="hidden" name="shipping_manually_set" value="0">
                    <input type="checkbox" name="shipping_manually_set" value="1" id="shipping-manually-set" <?php if($shippingManuallySet): echo 'checked'; endif; ?>>
                    <span><strong>Set a custom delivery charge for this order</strong><small>Turn this on to override the automatic area + weight calculation.</small></span>
                </label>
                <div class="field-full">
                    <label>Delivery Charge (৳)</label>
                    <input type="number" min="0" step="1" name="shipping_total" id="shipping-total-input" value="<?php echo e($currentShippingTotal); ?>" <?php if(! $shippingManuallySet): echo 'readonly'; endif; ?>>
                </div>
                <div>
                    <label>Manual Discount</label>
                    <input type="number" min="0" step="1" name="discount_total" id="discount-total" value="<?php echo e(old('discount_total', $order->discount_total ?? 0)); ?>">
                    <small>Coupon discount will be added on save if a valid code is entered.</small>
                </div>
                <div>
                    <label>Coupon Code</label>
                    <input name="coupon_code" value="<?php echo e($currentCouponCode); ?>" placeholder="WELCOME10 / FREEDELIVERY">
                    <small>Optional. Valid coupon will apply to order total.</small>
                </div>
                <div>
                    <label>Payment Method</label>
                    <select name="payment_method" id="admin-payment-method">
                        <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" data-account="<?php echo e($method['account']); ?>" <?php if(old('payment_method', $order->payment_method ?? 'cod') === $value): echo 'selected'; endif; ?>><?php echo e($method['label']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label>Payment Status</label>
                    <select name="payment_status">
                        <?php $__currentLoopData = ['unpaid','paid','refunded']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php if(old('payment_status', $order->payment_status ?? 'unpaid') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="manual-payment-admin-field">
                    <label>Sender/Account Number</label>
                    <input name="payment_sender_number" value="<?php echo e(old('payment_sender_number', $order->payment_sender_number ?? '')); ?>" placeholder="Customer payment number">
                </div>
                <div class="manual-payment-admin-field">
                    <label>Transaction / Reference ID</label>
                    <input name="payment_transaction_id" value="<?php echo e(old('payment_transaction_id', $order->payment_transaction_id ?? '')); ?>" placeholder="Transaction ID or bank reference">
                </div>
                <div class="field-full manual-payment-admin-field">
                    <label>Payment Account / Instructions</label>
                    <input name="payment_account" id="admin-payment-account" value="<?php echo e(old('payment_account', $order->payment_account ?? '')); ?>" placeholder="Payment number/account used">
                    <small class="muted">For bKash/Nagad payment verification. COD can remain empty.</small>
                </div>
                <div>
                    <label>Order Status</label>
                    <select name="order_status">
                        <?php $__currentLoopData = ['pending','processing','shipped','completed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php if(old('order_status', $order->order_status ?? 'pending') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <label class="admin-check-row field-full">
                    <input type="checkbox" name="free_delivery" value="1" id="free-delivery" <?php if((bool) $freeDelivery): echo 'checked'; endif; ?>>
                    <span><strong>Free delivery for this order</strong><small>Overrides both automatic and custom charge. The ৳<?php echo e(number_format($freeMinimum)); ?> threshold applies automatically unless a custom charge is enabled.</small></span>
                </label>
                <div class="field-full"><label>Admin Note</label><textarea name="admin_note"><?php echo e(old('admin_note', $order->admin_note ?? '')); ?></textarea></div>
            </div>
        </div>
    </div>

    <div class="content-card mt-4 order-items-card-v36">
        <div class="admin-heading-row mb-3 order-items-heading-v36">
            <div>
                <h2>Order Items</h2>
                <p class="muted">Use one product search box, then add selected products to the order. Existing items will stay editable below.</p>
            </div>
        </div>

        <div class="order-product-picker-v36">
            <div class="picker-search-v36">
                <label for="order-product-global-search">Search Product</label>
                <input type="text" id="order-product-global-search" placeholder="Type product name or SKU once, then add it to order...">
            </div>
            <div class="picker-select-v36">
                <label for="order-product-global-select">Product</label>
                <select id="order-product-global-select">
                    <option value="">Select product to add</option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($product->id); ?>" data-search="<?php echo e(strtolower($product->name . ' ' . $product->sku)); ?>"><?php echo e($product->name); ?><?php echo e($product->sku ? ' — ' . $product->sku : ''); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="picker-action-v36">
                <button type="button" class="btn" id="add-order-row">+ Add Product</button>
            </div>
        </div>

        <div class="admin-order-items" id="admin-order-items">
            <?php $__currentLoopData = $orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="admin-order-item-row admin-order-item-row-v36" data-row>
                    <div class="item-product-field item-product-field-v36">
                        <label>Product *</label>
                        <select name="items[<?php echo e($index); ?>][product_id]" data-product-select required>
                            <option value="">Select product</option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>" data-search="<?php echo e(strtolower($product->name . ' ' . $product->sku)); ?>" <?php if((string)($item['product_id'] ?? '') === (string)$product->id): echo 'selected'; endif; ?>><?php echo e($product->name); ?><?php echo e($product->sku ? ' — ' . $product->sku : ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="item-variation-field item-variation-field-v36">
                        <label>Variation</label>
                        <select name="items[<?php echo e($index); ?>][product_variation_id]" data-variation-select data-selected="<?php echo e($item['product_variation_id'] ?? ''); ?>">
                            <option value="">No variation</option>
                        </select>
                    </div>
                    <div class="item-qty-field-v36"><label>Qty *</label><input type="number" min="1" step="1" name="items[<?php echo e($index); ?>][quantity]" value="<?php echo e($item['quantity'] ?? 1); ?>" data-qty required></div>
                    <div class="item-price-field-v36"><label>Selling Price *</label><input type="number" min="0" step="1" name="items[<?php echo e($index); ?>][unit_price]" value="<?php echo e($item['unit_price'] ?? 0); ?>" data-price required></div>
                    <div class="item-cost-field-v36"><label>Purchase Cost / Unit</label><input type="number" min="0" step="1" name="items[<?php echo e($index); ?>][cost_price]" value="<?php echo e($item['cost_price'] ?? 0); ?>" data-cost></div>
                    <div class="item-total-box item-total-box-v36"><label>Line Total</label><strong data-row-total>৳0</strong></div>
                    <div class="item-total-box item-total-box-v36"><label>Line Profit</label><strong data-row-profit>৳0</strong></div>
                    <div class="item-action-field-v36"><button type="button" class="btn danger" data-remove-row>Remove</button></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="summary-card admin-order-summary-card admin-order-summary-card-v36">
        <div class="summary-line-v36"><span>Total Sales</span><strong id="admin-subtotal">৳0</strong></div>
        <div class="summary-line-v36"><span>Product Cost</span><strong id="admin-cost-total">৳0</strong></div>
        <div class="summary-line-v36"><span>Discount</span><strong id="admin-discount">৳0</strong></div>
        <div class="summary-line-v36"><span>Shipping</span><strong id="admin-shipping">৳0</strong></div>
        <div class="summary-total-v36"><span>Profit</span><strong id="admin-profit-total">৳0</strong></div>
        <div class="summary-total-v36 customer-total-v36"><span>Customer Total</span><strong id="admin-grand-total">৳0</strong></div>
        <button class="btn" type="submit"><?php echo e($isEdit ? 'Update Order' : 'Create Order'); ?></button>
    </div>
</form>

<script>
const prosanProducts = <?php echo json_encode($productOptions, 15, 512) ?>;
const prosanCustomers = <?php echo json_encode($customerOptions, 15, 512) ?>;
const insideCharge = <?php echo json_encode($insideCharge, 15, 512) ?>;
const suburbanCharge = <?php echo json_encode($suburbanCharge, 15, 512) ?>;
const outsideCharge = <?php echo json_encode($outsideCharge, 15, 512) ?>;
const dhakaSuburbanAreas = <?php echo json_encode(array_values($dhakaSuburbanAreas), 15, 512) ?>;
const freeMinimum = <?php echo json_encode($freeMinimum, 15, 512) ?>;
const weightBasedEnabled = <?php echo json_encode($weightBasedEnabled, 15, 512) ?>;
const includedWeightGrams = <?php echo json_encode($includedWeightGrams, 15, 512) ?>;
const packagingWeightGrams = <?php echo json_encode($packagingWeightGrams, 15, 512) ?>;
const additionalPerKg = <?php echo json_encode($additionalPerKg, 15, 512) ?>;
let rowIndex = <?php echo e(count($orderItems)); ?>;
function money(n){ return '৳' + Number(n || 0).toLocaleString('en-US',{maximumFractionDigits:0}); }
function productById(id){ return prosanProducts.find(p => String(p.id) === String(id)); }
function variationById(product,id){ return product ? (product.variations || []).find(v => String(v.id) === String(id)) : null; }
function zoneFromLocation(district, area){
    if(String(district || '').trim().toLowerCase() !== 'dhaka') return 'outside_dhaka';
    const currentArea=String(area || '').trim().toLowerCase();
    const isSuburban=dhakaSuburbanAreas.some(item=>String(item).trim().toLowerCase()===currentArea);
    return isSuburban ? 'dhaka_suburban' : 'inside_dhaka';
}
function escapeHtml(value){
    return String(value ?? '').replace(/[&<>'"]/g, function(ch){
        return ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'})[ch];
    });
}
function productOptionHtml(){
    return prosanProducts.map(p=>`<option value="${escapeHtml(p.id)}" data-search="${escapeHtml(p.search || p.name || '')}">${escapeHtml(p.name || '')}${p.sku ? ' — ' + escapeHtml(p.sku) : ''}</option>`).join('');
}
function setGlobalProductFilter(){
    const search=document.getElementById('order-product-global-search');
    const select=document.getElementById('order-product-global-select');
    if(!search || !select) return;
    const q=search.value.trim().toLowerCase();
    let first='';
    Array.from(select.options).forEach((option, index)=>{
        if(index===0){ option.hidden=false; return; }
        const match=!q || (option.dataset.search || option.textContent).toLowerCase().includes(q);
        option.hidden=!match;
        if(match && !first) first=option.value;
    });
    if(q && first && (!select.value || select.selectedOptions[0]?.hidden)) select.value=first;
}
function setupRow(row){
    const productSelect=row.querySelector('[data-product-select]');
    const variationSelect=row.querySelector('[data-variation-select]');
    const qty=row.querySelector('[data-qty]');
    const price=row.querySelector('[data-price]');
    const cost=row.querySelector('[data-cost]');
    const selectedVariation=variationSelect.dataset.selected || '';
    function populateVariations(resetPrice=false){
        const product=productById(productSelect.value);
        variationSelect.innerHTML='<option value="">No variation</option>';
        if(product && product.variations && product.variations.length){
            product.variations.forEach(variation=>{
                const option=document.createElement('option');
                option.value=variation.id;
                option.textContent=`${variation.name} — ${money(variation.price)} — Stock: ${variation.stock}`;
                variationSelect.appendChild(option);
            });
            variationSelect.disabled=false;
        }else{
            variationSelect.disabled=true;
        }
        if(selectedVariation){ variationSelect.value=selectedVariation; variationSelect.dataset.selected=''; }
        if(resetPrice || Number(price.value || 0) === 0){
            const selected=variationById(product, variationSelect.value);
            price.value=selected ? selected.price : (product ? product.price : 0);
            cost.value=selected ? (selected.cost_price || product?.cost_price || 0) : (product ? (product.cost_price || 0) : 0);
        }
        recalc();
    }
    productSelect.addEventListener('change',()=>populateVariations(true));
    variationSelect.addEventListener('change',()=>{
        const p=productById(productSelect.value);
        const selected=variationById(p, variationSelect.value);
        if(selected){
            price.value=selected.price || 0;
            cost.value=selected.cost_price || p?.cost_price || 0;
        }
        recalc();
    });
    qty.addEventListener('input',recalc);
    price.addEventListener('input',recalc);
    cost.addEventListener('input',recalc);
    row.querySelector('[data-remove-row]').addEventListener('click',()=>{
        const rows=document.querySelectorAll('[data-row]');
        if(rows.length>1){
            row.remove();
        }else{
            const product=row.querySelector('[data-product-select]');
            const variation=row.querySelector('[data-variation-select]');
            if(product) product.value='';
            if(variation){ variation.innerHTML='<option value="">No variation</option>'; variation.disabled=true; }
            row.querySelector('[data-qty]').value=1;
            row.querySelector('[data-price]').value=0;
            row.querySelector('[data-cost]').value=0;
        }
        recalc();
    });
    populateVariations(false);
}
function recalc(){
    let subtotal=0, productCost=0, productWeightGrams=0;
    document.querySelectorAll('[data-row]').forEach(row=>{
        const qty=Number(row.querySelector('[data-qty]').value || 0);
        const price=Number(row.querySelector('[data-price]').value || 0);
        const cost=Number(row.querySelector('[data-cost]').value || 0);
        const total=qty*price;
        const costTotal=qty*cost;
        subtotal+=total;
        productCost+=costTotal;
        const product=productById(row.querySelector('[data-product-select]').value);
        const variation=variationById(product, row.querySelector('[data-variation-select]').value);
        productWeightGrams+=qty*Number(variation ? (variation.weight_grams || 0) : (product?.weight_grams || 0));
        row.querySelector('[data-row-total]').textContent=money(total);
        row.querySelector('[data-row-profit]').textContent=money(total-costTotal);
    });
    const discount=Math.min(Number(document.getElementById('discount-total').value || 0), subtotal);
    const forceFree=document.getElementById('free-delivery').checked;
    const district=document.getElementById('customer-district').value;
    const area=document.getElementById('customer-area').value;
    const zone=zoneFromLocation(district, area);
    document.getElementById('shipping-zone-admin').value=zone;
    const shippingLabel=zone==='outside_dhaka'
        ? 'Outside Dhaka — '+money(outsideCharge)
        : (zone==='dhaka_suburban' ? 'Dhaka Suburban — '+money(suburbanCharge) : 'Inside Dhaka — '+money(insideCharge));
    document.getElementById('shipping-preview').value=shippingLabel;
    const zoneCharge=zone==='outside_dhaka' ? outsideCharge : (zone==='dhaka_suburban' ? suburbanCharge : insideCharge);
    const parcelWeightGrams=Math.max(0, productWeightGrams)+(weightBasedEnabled && subtotal>0 ? packagingWeightGrams : 0);
    const excessWeightGrams=weightBasedEnabled ? Math.max(0, parcelWeightGrams-includedWeightGrams) : 0;
    const extraKilograms=excessWeightGrams>0 ? Math.ceil(excessWeightGrams/1000) : 0;
    const automaticShipping=zoneCharge+(extraKilograms*additionalPerKg);
    const manualCheckbox=document.getElementById('shipping-manually-set');
    const shippingInput=document.getElementById('shipping-total-input');
    shippingInput.readOnly=!manualCheckbox.checked;
    if(!manualCheckbox.checked) shippingInput.value=(subtotal<=0 ? 0 : automaticShipping);
    const customShipping=Math.max(0, Number(shippingInput.value || 0));
    const shipping=(forceFree || (!manualCheckbox.checked && freeMinimum > 0 && subtotal>=freeMinimum) || subtotal<=0)
        ? 0
        : (manualCheckbox.checked ? customShipping : automaticShipping);
    const profit=Math.max(0, subtotal-discount-productCost);
    document.getElementById('admin-subtotal').textContent=money(subtotal);
    document.getElementById('admin-cost-total').textContent=money(productCost);
    document.getElementById('admin-discount').textContent=money(discount);
    document.getElementById('admin-shipping').textContent=shipping===0 && subtotal>0 ? 'Free' : money(shipping);
    document.getElementById('parcel-weight-preview').value=(parcelWeightGrams/1000).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2})+' kg';
    document.getElementById('shipping-preview').value=shippingLabel+(weightBasedEnabled ? ` + ${extraKilograms} extra kg × ${money(additionalPerKg)}` : '');
    document.getElementById('admin-profit-total').textContent=money(profit);
    document.getElementById('admin-grand-total').textContent=money(Math.max(0, subtotal-discount+shipping));
}
function addRow(productId=''){
    const wrapper=document.getElementById('admin-order-items');
    const product=productById(productId);
    const row=document.createElement('div');
    row.className='admin-order-item-row admin-order-item-row-v36';
    row.dataset.row='1';
    row.innerHTML=`
        <div class="item-product-field item-product-field-v36"><label>Product *</label><select name="items[${rowIndex}][product_id]" data-product-select required><option value="">Select product</option>${productOptionHtml()}</select></div>
        <div class="item-variation-field item-variation-field-v36"><label>Variation</label><select name="items[${rowIndex}][product_variation_id]" data-variation-select><option value="">No variation</option></select></div>
        <div class="item-qty-field-v36"><label>Qty *</label><input type="number" min="1" step="1" name="items[${rowIndex}][quantity]" value="1" data-qty required></div>
        <div class="item-price-field-v36"><label>Selling Price *</label><input type="number" min="0" step="1" name="items[${rowIndex}][unit_price]" value="${product ? Number(product.price || 0) : 0}" data-price required></div>
        <div class="item-cost-field-v36"><label>Purchase Cost / Unit</label><input type="number" min="0" step="1" name="items[${rowIndex}][cost_price]" value="${product ? Number(product.cost_price || 0) : 0}" data-cost></div>
        <div class="item-total-box item-total-box-v36"><label>Line Total</label><strong data-row-total>৳0</strong></div>
        <div class="item-total-box item-total-box-v36"><label>Line Profit</label><strong data-row-profit>৳0</strong></div>
        <div class="item-action-field-v36"><button type="button" class="btn danger" data-remove-row>Remove</button></div>`;
    wrapper.prepend(row);
    rowIndex++;
    if(productId) row.querySelector('[data-product-select]').value=productId;
    setupRow(row);
    row.scrollIntoView({behavior:'smooth', block:'center'});
}
document.querySelectorAll('[data-row]').forEach(setupRow);
const globalSearch=document.getElementById('order-product-global-search');
const globalSelect=document.getElementById('order-product-global-select');
if(globalSearch) globalSearch.addEventListener('input', setGlobalProductFilter);
document.getElementById('add-order-row').addEventListener('click',()=>{
    setGlobalProductFilter();
    const selected=globalSelect ? globalSelect.value : '';
    if(!selected){
        alert('Please search and select a product first.');
        return;
    }
    addRow(selected);
    if(globalSearch) globalSearch.value='';
    if(globalSelect) globalSelect.value='';
    setGlobalProductFilter();
});
document.getElementById('discount-total').addEventListener('input',recalc);
document.getElementById('customer-district').addEventListener('change',recalc);
document.getElementById('customer-area').addEventListener('input',recalc);
document.getElementById('free-delivery').addEventListener('change',recalc);
document.getElementById('shipping-manually-set').addEventListener('change',recalc);
document.getElementById('shipping-total-input').addEventListener('input',recalc);
const paymentSelect=document.getElementById('admin-payment-method');
const paymentAccount=document.getElementById('admin-payment-account');
function updateAdminPaymentAccount(){ if(!paymentSelect || !paymentAccount) return; const selected=paymentSelect.options[paymentSelect.selectedIndex]; if(!paymentAccount.value && selected && selected.dataset.account){ paymentAccount.value=selected.dataset.account; } }
if(paymentSelect){ paymentSelect.addEventListener('change', updateAdminPaymentAccount); updateAdminPaymentAccount(); }
document.getElementById('customer-select').addEventListener('change',function(){
    const customer=prosanCustomers.find(c=>String(c.id)===String(this.value));
    if(!customer) return;
    document.getElementById('customer-name').value=customer.name || '';
    document.getElementById('customer-phone').value=customer.phone || '';
    document.getElementById('customer-email').value=customer.email || '';
    document.getElementById('customer-address').value=customer.address_line || '';
    document.getElementById('customer-area').value=customer.area || '';
    document.getElementById('customer-district').value=customer.city || customer.district || 'Dhaka';
    recalc();
});
recalc();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/orders/form.blade.php ENDPATH**/ ?>