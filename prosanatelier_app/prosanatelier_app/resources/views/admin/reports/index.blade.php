@extends('layouts.admin')
@section('title', 'Advanced Reports')
@section('content')
@php
    $money = fn($value) => '৳' . number_format((float) $value, 0);
    $fromValue = $from->format('Y-m-d');
    $toValue = $to->format('Y-m-d');
@endphp

<div class="section-heading admin-heading-row">
    <div>
        <h1>Advanced Reports</h1>
        <p class="muted">Track sales, profit, shipping, coupons, customers, stock and courier activity.</p>
    </div>
    <div class="admin-heading-actions">
        <a class="btn btn-light-outline" href="{{ route('admin.reports.export', ['type' => 'orders', 'from' => $fromValue, 'to' => $toValue]) }}">Export Orders</a>
        <a class="btn btn-light-outline" href="{{ route('admin.reports.export', ['type' => 'products', 'from' => $fromValue, 'to' => $toValue]) }}">Export Products</a>
        <a class="btn" href="{{ route('admin.reports.export', ['type' => 'customers', 'from' => $fromValue, 'to' => $toValue]) }}">Export Customers</a>
    </div>
</div>

<form class="toolbar report-filter-bar" method="GET">
    <label>From <input type="date" name="from" value="{{ $fromValue }}"></label>
    <label>To <input type="date" name="to" value="{{ $toValue }}"></label>
    <button class="btn" type="submit">Apply Report</button>
    <a class="btn ghost" href="{{ route('admin.reports.index') }}">Last 30 Days</a>
</form>

<div class="stats-grid report-stats-grid">
    <div class="stat-card"><span class="stat-icon">৳</span><span>Product Sales</span><strong>{{ $money($summary['product_sales']) }}</strong><small class="muted">Shipping excluded.</small></div>
    <div class="stat-card"><span class="stat-icon">📈</span><span>Profit</span><strong>{{ $money($summary['profit']) }}</strong><small class="muted">Product sales - product cost.</small></div>
    <div class="stat-card"><span class="stat-icon">🧾</span><span>Product Cost</span><strong>{{ $money($summary['product_cost']) }}</strong><small class="muted">Purchase cost of sold products.</small></div>
    <div class="stat-card"><span class="stat-icon">🚚</span><span>Shipping Collected</span><strong>{{ $money($summary['shipping_collected']) }}</strong><small class="muted">Separate from profit.</small></div>
    <div class="stat-card"><span class="stat-icon">🏷️</span><span>Discount Given</span><strong>{{ $money($summary['discount_total']) }}</strong><small class="muted">Coupon/order discount.</small></div>
    <div class="stat-card"><span class="stat-icon">💰</span><span>Total Collected</span><strong>{{ $money($summary['grand_total']) }}</strong><small class="muted">Product + shipping - discount.</small></div>
    <div class="stat-card"><span class="stat-icon">🛒</span><span>Total Orders</span><strong>{{ number_format($summary['order_count']) }}</strong><small class="muted">Non-cancelled orders.</small></div>
    <div class="stat-card"><span class="stat-icon">📦</span><span>Average Order</span><strong>{{ $money($summary['average_order']) }}</strong><small class="muted">Average customer total.</small></div>
</div>

<div class="admin-grid-two reports-grid-two">
    <div class="table-card">
        <div class="section-heading compact"><h2>Daily Sales</h2><span class="muted">Date-wise summary</span></div>
        <table>
            <thead><tr><th>Date</th><th>Orders</th><th>Product Sales</th><th>Shipping</th><th>Total</th></tr></thead>
            <tbody>
            @forelse($dailySales as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row->report_date)->format('d M Y') }}</td>
                    <td>{{ $row->orders }}</td>
                    <td>{{ $money($row->product_sales) }}</td>
                    <td>{{ $money($row->shipping) }}</td>
                    <td>{{ $money($row->total) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No sales found for this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-card">
        <div class="section-heading compact"><h2>Top Selling Products</h2><span class="muted">By quantity sold</span></div>
        <table>
            <thead><tr><th>Product</th><th>SKU</th><th>Qty</th><th>Sales</th></tr></thead>
            <tbody>
            @forelse($topProducts as $product)
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->sku ?: '—' }}</td>
                    <td>{{ number_format($product->qty) }}</td>
                    <td>{{ $money($product->sales) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No product sales found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="admin-grid-two reports-grid-two">
    <div class="table-card">
        <div class="section-heading compact"><h2>Order Status Report</h2><span class="muted">Status-wise order value</span></div>
        <table>
            <thead><tr><th>Status</th><th>Orders</th><th>Amount</th></tr></thead>
            <tbody>
            @forelse($statusBreakdown as $status)
                <tr><td><span class="badge">{{ $status->order_status }}</span></td><td>{{ $status->total }}</td><td>{{ $money($status->amount) }}</td></tr>
            @empty
                <tr><td colspan="3">No status data found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-card">
        <div class="section-heading compact"><h2>Payment Method Report</h2><span class="muted">COD, bKash, Nagad</span></div>
        <table>
            <thead><tr><th>Payment</th><th>Orders</th><th>Amount</th></tr></thead>
            <tbody>
            @forelse($paymentBreakdown as $payment)
                <tr><td>{{ ucfirst(str_replace(['manual_', '_'], ['', ' '], $payment->payment_method ?: 'cod')) }}</td><td>{{ $payment->total }}</td><td>{{ $money($payment->amount) }}</td></tr>
            @empty
                <tr><td colspan="3">No payment data found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="admin-grid-two reports-grid-two">
    <div class="table-card">
        <div class="section-heading compact"><h2>Coupon Usage</h2><span class="muted">Discount performance</span></div>
        <table>
            <thead><tr><th>Coupon</th><th>Orders</th><th>Discount</th></tr></thead>
            <tbody>
            @forelse($couponUsage as $coupon)
                <tr><td>{{ $coupon->coupon_code }}</td><td>{{ $coupon->total }}</td><td>{{ $money($coupon->discount) }}</td></tr>
            @empty
                <tr><td colspan="3">No coupon usage found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-card">
        <div class="section-heading compact"><h2>Steadfast / Courier Orders</h2><span class="muted">Recently sent to courier</span></div>
        <table>
            <thead><tr><th>Order</th><th>Tracking</th><th>Status</th><th>COD</th></tr></thead>
            <tbody>
            @forelse($steadfastOrders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                    <td>{{ $order->steadfast_tracking_code ?: $order->steadfast_consignment_id ?: '—' }}</td>
                    <td><span class="badge">{{ $order->courierStatusLabel() }}</span></td>
                    <td>{{ $money($order->codAmountForSteadfast()) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No courier orders found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="admin-grid-two reports-grid-two">
    <div class="table-card">
        <div class="section-heading compact"><h2>Low Stock Products</h2><a href="{{ route('admin.products.index') }}">Manage stock</a></div>
        <table>
            <thead><tr><th>Product</th><th>SKU</th><th>Stock</th><th>Alert</th></tr></thead>
            <tbody>
            @forelse($lowStockProducts as $product)
                <tr><td>{{ $product->name }}</td><td>{{ $product->sku ?: '—' }}</td><td>{{ $product->stock_quantity }}</td><td>{{ $product->low_stock_alert }}</td></tr>
            @empty
                <tr><td colspan="4">No low stock products.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-card">
        <div class="section-heading compact"><h2>Recent Customers</h2><a href="{{ route('admin.customers.index') }}">View all</a></div>
        <table>
            <thead><tr><th>Customer</th><th>Phone</th><th>Orders</th><th>District</th></tr></thead>
            <tbody>
            @forelse($recentCustomers as $customer)
                <tr><td><a href="{{ route('admin.customers.show', $customer) }}">{{ $customer->name }}</a></td><td>{{ $customer->phone }}</td><td>{{ $customer->orders_count }}</td><td>{{ $customer->city ?: '—' }}</td></tr>
            @empty
                <tr><td colspan="4">No customer found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
