@extends('layouts.store')
@section('title', 'Checkout - Prosan Atelier')
@section('content')
@php
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
@endphp
<section class="py-5" style="background-image:url('{{ asset('foodmart/images/background-pattern.jpg') }}');background-size:cover;">
    <div class="container-fluid">
        <h1 class="display-5 fw-bold">Checkout</h1>
        <p class="text-muted mb-0">
            Inside Dhaka ৳{{ number_format($insideDelivery, 0) }}, Dhaka Suburban ৳{{ number_format($suburbanDelivery, 0) }}, outside Dhaka ৳{{ number_format($outsideDelivery, 0) }}.
            @if($freeDeliveryMinimum > 0)
                Free delivery from ৳{{ number_format($freeDeliveryMinimum, 0) }}.
            @endif
            @if($weightBasedEnabled)
                First {{ number_format($includedWeightGrams / 1000, 2) }} kg is included; each additional kg or part adds ৳{{ number_format($additionalPerKg) }}.
            @endif
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
                        @if(empty(session('customer_id')))
                            <a href="{{ route('customer.login') }}" class="btn btn-outline-dark rounded-pill">Login</a>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('checkout.store') }}" class="row g-3" id="checkout-form">
                        @csrf
                        <div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
                            <label>Website</label>
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="col-md-6"><label class="form-label">Name *</label><input class="form-control form-control-lg" name="customer_name" value="{{ old('customer_name', $customer->name ?? '') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Phone *</label><input class="form-control form-control-lg" name="customer_phone" value="{{ old('customer_phone', $customer->phone ?? '') }}" required></div>
                        <div class="col-12"><label class="form-label">Email</label><input class="form-control form-control-lg" type="email" name="customer_email" value="{{ old('customer_email', $customer->email ?? '') }}" pattern="^[^@\s]+@[A-Za-z0-9.-]+\.com$" title="Only Gmail or other .com email addresses are accepted."><div class="form-text">Only Gmail or other .com email addresses are accepted.</div></div>
                        <div class="col-12"><label class="form-label">Address *</label><textarea class="form-control form-control-lg" name="address_line" required>{{ old('address_line', $customer->address_line ?? '') }}</textarea></div>
                        <div class="col-md-6">
                            <label class="form-label" for="checkout-district">District *</label>
                            <select class="form-select form-select-lg" name="city" id="checkout-district" required>
                                <option value="">Select district</option>
                                @foreach($districtAreas as $district => $areas)
                                    <option value="{{ $district }}" @selected($selectedDistrict === $district)>{{ $district }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="checkout-area">Area / Thana *</label>
                            <select class="form-select form-select-lg" name="area" id="checkout-area" required @disabled(empty($selectedDistrict))>
                                <option value="">{{ $selectedDistrict ? 'Select area / thana' : 'Select district first' }}</option>
                                @if($selectedDistrict)
                                    @foreach($districtAreas[$selectedDistrict] ?? [] as $area)
                                        <option value="{{ $area }}" @selected($selectedArea === $area)>{{ $area }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-text" id="delivery-charge-preview">
                                Select your district to see the delivery charge.
                            </div>
                        </div>
                        <div class="col-12"><label class="form-label">Order Note</label><textarea class="form-control form-control-lg" name="customer_note">{{ old('customer_note') }}</textarea></div>
                        <div class="col-12"><button class="btn btn-primary btn-lg w-100 rounded-pill" type="submit">Place Order - Cash on Delivery</button></div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <aside class="card border-0 shadow-sm rounded-4 p-4 sticky-summary checkout-summary-prosan" data-subtotal="{{ (float) $totals['subtotal'] }}">
                    <h3>Order Summary</h3>
                    @foreach($cart as $item)
                        <div class="d-flex justify-content-between gap-3 border-bottom py-3"><span>{{ $item['name'] }} × {{ $item['quantity'] }}</span><strong>৳{{ number_format($item['price'] * $item['quantity'],0) }}</strong></div>
                    @endforeach
                    <div class="pt-3 summary-lines-prosan">
                        <p>Subtotal: <strong id="summary-subtotal">৳{{ number_format($totals['subtotal'],0) }}</strong></p>
                        @if($weightBasedEnabled)<p>Estimated parcel weight: <strong>{{ number_format($parcelWeightGrams / 1000, 2) }} kg</strong></p>@endif
                        <p>Shipping: <strong id="summary-shipping">{{ $hasSelectedLocation ? ((float) $totals['shipping'] === 0.0 ? 'Free' : '৳'.number_format($totals['shipping'],0)) : 'Select location' }}</strong></p>
                        <h3>Total: <span id="summary-total">৳{{ number_format($hasSelectedLocation ? $totals['grand_total'] : $totals['subtotal'],0) }}</span></h3>
                    </div>
                    <div class="alert alert-warning rounded-4 mt-3 mb-0">
                        <strong>Note:</strong> Base delivery is ৳{{ number_format($insideDelivery, 0) }} inside Dhaka, ৳{{ number_format($suburbanDelivery, 0) }} in Dhaka Suburban areas and ৳{{ number_format($outsideDelivery, 0) }} outside Dhaka.
                        @if($weightBasedEnabled)
                            Parcel weight includes a {{ number_format($packagingWeightGrams) }}g packaging buffer; every extra kg or part adds ৳{{ number_format($additionalPerKg) }}.
                        @endif
                        @if($freeDeliveryMinimum > 0)
                            Free delivery from ৳{{ number_format($freeDeliveryMinimum, 0) }}.
                        @endif
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
    const districtAreas = @json($districtAreas);
    const initialArea = @json((string) $selectedArea);
    const subtotal = Number(box.dataset.subtotal || 0);
    const insideDelivery = @json($insideDelivery);
    const suburbanDelivery = @json($suburbanDelivery);
    const outsideDelivery = @json($outsideDelivery);
    const dhakaSuburbanAreas = @json(array_values($dhakaSuburbanAreas));
    const freeDeliveryMinimum = @json($freeDeliveryMinimum);
    const weightBasedEnabled = @json($weightBasedEnabled);
    const includedWeightGrams = @json($includedWeightGrams);
    const additionalPerKg = @json($additionalPerKg);
    const parcelWeightGrams = @json($parcelWeightGrams);
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
@endsection
