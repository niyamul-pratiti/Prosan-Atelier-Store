@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)
@section('content')
<div class="section-heading admin-heading-row">
    <div><h1>Order {{ $order->order_number }}</h1><p class="muted">View, update status or customize this order.</p></div>
    <div class="d-flex gap-2 admin-order-actions">
        <a class="btn ghost" href="{{ route('admin.orders.index') }}">Back</a>
        <a class="btn ghost" target="_blank" href="{{ route('admin.orders.invoice', $order) }}">Print Invoice</a>
        <a class="btn ghost" target="_blank" href="{{ route('admin.orders.packing_slip', $order) }}">Packing Slip</a>
        <a class="btn" href="{{ route('admin.orders.edit', $order) }}">Edit / Customize</a>
        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Remove this order? Stock will be restored for non-completed orders.');" class="inline-delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn danger">Remove Order</button>
        </form>
    </div>
</div>
<div class="admin-grid-two">
    <div class="content-card">
        <h2>Customer</h2>
        <p><strong>Name:</strong> {{ $order->customer_name }}</p>
        @if($order->customer)<p><strong>Customer Account:</strong> <a href="{{ route('admin.customers.show', $order->customer) }}">View Customer Dashboard</a></p>@endif
        <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email ?: 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $order->address_line ?: 'N/A' }}</p>
        <p><strong>Thana / Area:</strong> {{ $order->area ?: 'N/A' }}</p>
        <p><strong>District:</strong> {{ $order->city ?: 'Dhaka' }}</p>
        @if($order->coupon_code)<p><strong>Coupon:</strong> {{ $order->coupon_code }} (-৳{{ number_format($order->discount_total, 0) }})</p>@endif
        <p><strong>Shipping:</strong> {{ (float) $order->shipping_total === 0.0 ? 'Free' : '৳' . number_format($order->shipping_total, 0) }}</p>
        <p><strong>Parcel Weight:</strong> {{ (int) ($order->parcel_weight_grams ?? 0) > 0 ? number_format(((int) $order->parcel_weight_grams) / 1000, 2) . ' kg' : 'Not calculated' }}</p>
        <p><strong>Charge Method:</strong> {{ $order->shipping_manually_set ? 'Manual override' : 'Automatic area + weight' }}</p>
        <p><strong>Payment Method:</strong> {{ $order->paymentMethodLabel() }}</p>
        @if($order->payment_method !== 'cod')
            <p><strong>Payment Account:</strong> {{ $order->payment_account ?: 'N/A' }}</p>
            <p><strong>Sender Number:</strong> {{ $order->payment_sender_number ?: 'N/A' }}</p>
            <p><strong>Transaction ID:</strong> {{ $order->payment_transaction_id ?: 'N/A' }}</p>
        @endif
        <p><strong>Note:</strong> {{ $order->customer_note ?: 'N/A' }}</p>
    </div>
    <div class="content-card">
        <h2>Status</h2>
        @php
            $forceFree = (bool) ($order->shipping_manually_set ?? false) && (float) $order->shipping_total === 0.0 && (float) $order->subtotal > 0;
            $freeMinimum = (int) ($siteSettings['free_delivery_minimum'] ?? 5000);
        @endphp
        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="form-grid">
            @csrf @method('PATCH')
            <label class="admin-check-row order-free-delivery-row">
                <input type="checkbox" name="free_delivery" value="1" @checked($forceFree)>
                <span>Free delivery for this order</span>
            </label>
            <label>Delivery Charge (৳)</label>
            <input type="number" name="shipping_total" value="{{ number_format((float) $order->shipping_total, 0, '.', '') }}" min="0" step="1" required>
            <small class="muted">Edit this amount and click Update Order. The customer total, invoice and Steadfast COD amount will update together.</small>
            <label class="admin-check-row">
                <input type="checkbox" name="recalculate_delivery" value="1">
                <span>Discard the manual amount and recalculate from area + parcel weight</span>
            </label>
            <small class="muted">Automatic free delivery applies when subtotal is ৳{{ number_format($freeMinimum) }} or more.</small>
            <label>Order Status</label>
            <select name="order_status">
                @foreach(['pending','processing','shipped','completed','cancelled'] as $status)
                    <option value="{{ $status }}" @selected($order->order_status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <label>Payment Status</label>
            <select name="payment_status">
                @foreach(['unpaid','paid','refunded'] as $status)
                    <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            @if($order->payment_method !== 'cod')
                <label>Sender/Account Number</label>
                <input name="payment_sender_number" value="{{ $order->payment_sender_number }}" placeholder="Customer payment number">
                <label>Transaction / Reference ID</label>
                <input name="payment_transaction_id" value="{{ $order->payment_transaction_id }}" placeholder="Transaction ID or bank reference">
                <label>Payment Account</label>
                <input name="payment_account" value="{{ $order->payment_account }}" placeholder="Payment number/account used">
            @endif
            <label>Admin Note</label>
            <textarea name="admin_note">{{ $order->admin_note }}</textarea>
            <button class="btn" type="submit">Update Order</button>
        </form>
    </div>
</div>

<div class="content-card courier-card-prosan">
    <div class="admin-heading-row">
        <div>
            <h2>Steadfast Courier</h2>
            <p class="muted">Send this order to Steadfast after confirming payment. Paid orders go with COD ৳0; unpaid orders go with full COD amount.</p>
        </div>
        <span class="badge {{ $order->steadfast_tracking_code ? 'paid' : 'pending' }}">{{ $order->courierStatusLabel() }}</span>
    </div>

    <div class="courier-summary-grid">
        <p><strong>COD Amount to Send:</strong><br>৳{{ number_format($order->codAmountForSteadfast(), 0) }}</p>
        <p><strong>Consignment ID:</strong><br>{{ $order->steadfast_consignment_id ?: 'Not sent yet' }}</p>
        <p><strong>Tracking Code:</strong><br>{{ $order->steadfast_tracking_code ?: 'Not available yet' }}</p>
        <p><strong>Last Checked:</strong><br>{{ $order->steadfast_last_checked_at ? $order->steadfast_last_checked_at->format('d M Y, h:i A') : 'Never' }}</p>
    </div>

    <div class="d-flex gap-2 flex-wrap mt-3">
        <form method="POST" action="{{ route('admin.orders.steadfast.send', $order) }}" onsubmit="return confirm('Send this order to Steadfast? COD amount will be ৳{{ number_format($order->codAmountForSteadfast(), 0) }}.');">
            @csrf
            <button type="submit" class="btn">{{ $order->steadfast_tracking_code ? 'Resend to Steadfast' : 'Send to Steadfast' }}</button>
        </form>
        <form method="POST" action="{{ route('admin.orders.steadfast.refresh', $order) }}">
            @csrf
            <button type="submit" class="btn ghost">Refresh Courier Status</button>
        </form>
    </div>

    @if($order->courier_note)
        <p class="muted mt-3 mb-0"><strong>Courier Note:</strong> {{ $order->courier_note }}</p>
    @endif
</div>

<div class="table-card">
    <h2>Items</h2>
    <table>
        <thead><tr><th>Product</th><th>SKU</th><th>Selling Price</th><th>Cost</th><th>Qty</th><th>Total</th><th>Profit</th></tr></thead>
        <tbody>
        @foreach($order->items as $item)
            @php($costTotal = (float) ($item->cost_total ?? 0))
            <tr>
                <td>{{ $item->product_name }} @if($item->variation_name)<span class="muted">({{ $item->variation_name }})</span>@endif</td>
                <td>{{ $item->sku }}</td>
                <td>৳{{ number_format($item->unit_price, 0) }}</td>
                <td>৳{{ number_format($item->cost_price ?? 0, 0) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>৳{{ number_format($item->line_total, 0) }}</td>
                <td>৳{{ number_format(max(0, (float) $item->line_total - $costTotal), 0) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @php($totalCost = $order->items->sum(fn($item) => (float) ($item->cost_total ?? 0)))
    <div class="summary-card align-right">
        <p>Subtotal: <strong>৳{{ number_format($order->subtotal, 0) }}</strong></p>
        @if($order->coupon_code)<p>Coupon ({{ $order->coupon_code }}): <strong>-৳{{ number_format($order->discount_total, 0) }}</strong></p>@else<p>Discount: <strong>-৳{{ number_format($order->discount_total, 0) }}</strong></p>@endif
        <p>Total Sales: <strong>৳{{ number_format($order->subtotal - $order->discount_total, 0) }}</strong></p>
        <p>Product Cost: <strong>৳{{ number_format($totalCost, 0) }}</strong></p>
        <p>Shipping: <strong>{{ (float) $order->shipping_total === 0.0 ? 'Free' : '৳' . number_format($order->shipping_total, 0) }}</strong></p>
        <h2>Profit: ৳{{ number_format(max(0, (float) $order->subtotal - (float) $order->discount_total - $totalCost), 0) }}</h2>
        <h2>Customer Total: ৳{{ number_format($order->grand_total, 0) }}</h2>
    </div>
</div>
@endsection
