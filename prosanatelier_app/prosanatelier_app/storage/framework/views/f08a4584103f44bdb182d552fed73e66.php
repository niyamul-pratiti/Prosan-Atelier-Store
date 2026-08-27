<?php $__env->startSection('title', 'Checkout - Prosan Atelier'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $insideDelivery = (int) ($shippingSettings['inside_dhaka'] ?? 70);
    $suburbanDelivery = (int) ($shippingSettings['dhaka_suburban'] ?? 100);
    $outsideDelivery = (int) ($shippingSettings['outside_dhaka'] ?? 130);
    $freeDeliveryMinimum = (int) ($shippingSettings['free_minimum'] ?? 5000);
    $weightBasedEnabled = (bool) ($shippingSettings['weight_based_enabled'] ?? true);
    $includedWeightGrams = (int) ($shippingSettings['included_weight_grams'] ?? 1000);
    $packagingWeightGrams = (int) ($shippingSettings['packaging_weight_grams'] ?? 200);
    $additionalPerKg = (int) ($shippingSettings['additional_per_kg'] ?? 20);
    $parcelWeightGrams = (int) ($totals['parcel_weight_grams'] ?? 0);
    $hasSelectedLocation = filled($selectedDistrict) && filled($selectedArea);
?>
<section class="py-5" style="background-image:url('<?php echo e(asset('foodmart/images/background-pattern.jpg')); ?>');background-size:cover;">
    <div class="container-fluid">
        <h1 class="display-5 fw-bold">Checkout</h1>
        <p class="text-muted mb-0">
            Inside Dhaka ৳<?php echo e(number_format($insideDelivery, 0)); ?>, Dhaka Suburban ৳<?php echo e(number_format($suburbanDelivery, 0)); ?>, outside Dhaka ৳<?php echo e(number_format($outsideDelivery, 0)); ?>.
            <?php if($freeDeliveryMinimum > 0): ?>
                Free delivery from ৳<?php echo e(number_format($freeDeliveryMinimum, 0)); ?>.
            <?php endif; ?>
            <?php if($weightBasedEnabled): ?>
                First <?php echo e(number_format($includedWeightGrams / 1000, 2)); ?> kg is included; each additional kg or part adds ৳<?php echo e(number_format($additionalPerKg)); ?>.
            <?php endif; ?>
        </p>
    </div>
</section>
<section class="py-5">
    <div class="container-fluid">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 checkout-card-prosan">
                    <div class="d-flex justify-content-between flex-wrap gap-2 mb-4">
                        <div><h3 class="mb-1">Billing & Delivery Details</h3><p class="text-muted mb-0">Please provide your delivery information.</p></div>
                        <?php if(empty(session('customer_id'))): ?>
                            <a href="<?php echo e(route('customer.login')); ?>" class="btn btn-outline-dark rounded-pill">Login</a>
                        <?php endif; ?>
                    </div>
                    <form method="POST" action="<?php echo e(route('checkout.store')); ?>" class="row g-3" id="checkout-form">
                        <?php echo csrf_field(); ?>
                        <div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
                            <label>Website</label>
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="col-md-6"><label class="form-label">Name *</label><input class="form-control form-control-lg" name="customer_name" value="<?php echo e(old('customer_name', $customer->name ?? '')); ?>" required></div>
                        <div class="col-md-6"><label class="form-label">Phone *</label><input class="form-control form-control-lg" name="customer_phone" value="<?php echo e(old('customer_phone', $customer->phone ?? '')); ?>" required></div>
                        <div class="col-12"><label class="form-label">Email</label><input class="form-control form-control-lg" type="email" name="customer_email" value="<?php echo e(old('customer_email', $customer->email ?? '')); ?>" pattern="^[^@\s]+@[A-Za-z0-9.-]+\.com$" title="Only Gmail or other .com email addresses are accepted."><div class="form-text">Only Gmail or other .com email addresses are accepted.</div></div>
                        <div class="col-12"><label class="form-label">Address *</label><textarea class="form-control form-control-lg" name="address_line" required><?php echo e(old('address_line', $customer->address_line ?? '')); ?></textarea></div>
                        <div class="col-md-6">
                            <label class="form-label" for="checkout-district">District *</label>
                            <select class="form-select form-select-lg" name="city" id="checkout-district" required>
                                <option value="">Select district</option>
                                <?php $__currentLoopData = $districtAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district => $areas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($district); ?>" <?php if($selectedDistrict === $district): echo 'selected'; endif; ?>><?php echo e($district); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="checkout-area">Area / Thana *</label>
                            <select class="form-select form-select-lg" name="area" id="checkout-area" required <?php if(empty($selectedDistrict)): echo 'disabled'; endif; ?>>
                                <option value=""><?php echo e($selectedDistrict ? 'Select area / thana' : 'Select district first'); ?></option>
                                <?php if($selectedDistrict): ?>
                                    <?php $__currentLoopData = $districtAreas[$selectedDistrict] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($area); ?>" <?php if($selectedArea === $area): echo 'selected'; endif; ?>><?php echo e($area); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-text" id="delivery-charge-preview">
                                Select your district to see the delivery charge.
                            </div>
                        </div>
                        <div class="col-12"><label class="form-label">Order Note</label><textarea class="form-control form-control-lg" name="customer_note"><?php echo e(old('customer_note')); ?></textarea></div>
                        <div class="col-12"><button class="btn btn-primary btn-lg w-100 rounded-pill" type="submit">Place Order - Cash on Delivery</button></div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <aside class="card border-0 shadow-sm rounded-4 p-4 sticky-summary checkout-summary-prosan" data-subtotal="<?php echo e((float) $totals['subtotal']); ?>">
                    <h3>Order Summary</h3>
                    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between gap-3 border-bottom py-3"><span><?php echo e($item['name']); ?> × <?php echo e($item['quantity']); ?></span><strong>৳<?php echo e(number_format($item['price'] * $item['quantity'],0)); ?></strong></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="pt-3 summary-lines-prosan">
                        <p>Subtotal: <strong id="summary-subtotal">৳<?php echo e(number_format($totals['subtotal'],0)); ?></strong></p>
                        <?php if($weightBasedEnabled): ?><p>Estimated parcel weight: <strong><?php echo e(number_format($parcelWeightGrams / 1000, 2)); ?> kg</strong></p><?php endif; ?>
                        <p>Shipping: <strong id="summary-shipping"><?php echo e($hasSelectedLocation ? ((float) $totals['shipping'] === 0.0 ? 'Free' : '৳'.number_format($totals['shipping'],0)) : 'Select location'); ?></strong></p>
                        <h3>Total: <span id="summary-total">৳<?php echo e(number_format($hasSelectedLocation ? $totals['grand_total'] : $totals['subtotal'],0)); ?></span></h3>
                    </div>
                    <div class="alert alert-warning rounded-4 mt-3 mb-0">
                        <strong>Note:</strong> Base delivery is ৳<?php echo e(number_format($insideDelivery, 0)); ?> inside Dhaka, ৳<?php echo e(number_format($suburbanDelivery, 0)); ?> in Dhaka Suburban areas and ৳<?php echo e(number_format($outsideDelivery, 0)); ?> outside Dhaka.
                        <?php if($weightBasedEnabled): ?>
                            Parcel weight includes a <?php echo e(number_format($packagingWeightGrams)); ?>g packaging buffer; every extra kg or part adds ৳<?php echo e(number_format($additionalPerKg)); ?>.
                        <?php endif; ?>
                        <?php if($freeDeliveryMinimum > 0): ?>
                            Free delivery from ৳<?php echo e(number_format($freeDeliveryMinimum, 0)); ?>.
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const districtSelect = document.getElementById('checkout-district');
    const areaSelect = document.getElementById('checkout-area');
    const chargePreview = document.getElementById('delivery-charge-preview');
    const box = document.querySelector('.checkout-summary-prosan');
    if (!districtSelect || !areaSelect || !box) return;
    const districtAreas = <?php echo json_encode($districtAreas, 15, 512) ?>;
    const initialArea = <?php echo json_encode((string) $selectedArea, 15, 512) ?>;
    const subtotal = Number(box.dataset.subtotal || 0);
    const insideDelivery = <?php echo json_encode($insideDelivery, 15, 512) ?>;
    const suburbanDelivery = <?php echo json_encode($suburbanDelivery, 15, 512) ?>;
    const outsideDelivery = <?php echo json_encode($outsideDelivery, 15, 512) ?>;
    const dhakaSuburbanAreas = <?php echo json_encode(array_values($dhakaSuburbanAreas), 15, 512) ?>;
    const freeDeliveryMinimum = <?php echo json_encode($freeDeliveryMinimum, 15, 512) ?>;
    const weightBasedEnabled = <?php echo json_encode($weightBasedEnabled, 15, 512) ?>;
    const includedWeightGrams = <?php echo json_encode($includedWeightGrams, 15, 512) ?>;
    const additionalPerKg = <?php echo json_encode($additionalPerKg, 15, 512) ?>;
    const parcelWeightGrams = <?php echo json_encode($parcelWeightGrams, 15, 512) ?>;
    const fmt = n => '৳' + Number(n).toLocaleString('en-US', {maximumFractionDigits: 0});

    function updateAreas(preferredArea = '') {
        const district = districtSelect.value;
        const areas = districtAreas[district] || [];
        areaSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = district ? 'Select area / thana' : 'Select district first';
        areaSelect.appendChild(placeholder);

        areas.forEach(area => {
            const option = document.createElement('option');
            option.value = area;
            option.textContent = area;
            option.selected = area === preferredArea;
            areaSelect.appendChild(option);
        });

        areaSelect.disabled = !district;
    }

    function updateShipping() {
        const district = districtSelect.value;
        const area = areaSelect.value;
        if (!district || !area) {
            document.getElementById('summary-shipping').textContent = 'Select location';
            document.getElementById('summary-total').textContent = fmt(subtotal);
            chargePreview.textContent = district
                ? 'Select your area / thana to see the delivery charge.'
                : 'Select your district to see the delivery charge.';
            return;
        }

        const isSuburban = district === 'Dhaka' && dhakaSuburbanAreas.includes(area);
        let shipping = district !== 'Dhaka'
            ? outsideDelivery
            : (isSuburban ? suburbanDelivery : insideDelivery);
        if (weightBasedEnabled) {
            const excessWeightGrams = Math.max(0, parcelWeightGrams - includedWeightGrams);
            const extraKilograms = excessWeightGrams > 0 ? Math.ceil(excessWeightGrams / 1000) : 0;
            shipping += extraKilograms * additionalPerKg;
        }
        if (freeDeliveryMinimum > 0 && subtotal >= freeDeliveryMinimum) {
            shipping = 0;
        }

        document.getElementById('summary-shipping').textContent = shipping === 0 && subtotal > 0 ? 'Free' : fmt(shipping);
        document.getElementById('summary-total').textContent = fmt(subtotal + shipping);
        chargePreview.textContent = shipping === 0 && subtotal > 0
            ? 'Free delivery applies to this order.'
            : `Delivery charge for ${area}, ${district}: ${fmt(shipping)}.`;
    }

    districtSelect.addEventListener('change', function () {
        updateAreas();
        updateShipping();
    });
    areaSelect.addEventListener('change', updateShipping);

    updateAreas(initialArea);
    updateShipping();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/frontend/checkout.blade.php ENDPATH**/ ?>