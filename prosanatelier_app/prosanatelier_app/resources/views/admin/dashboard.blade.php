@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-hero-card">
    <div>
        <span class="admin-kicker">Store overview</span>
        <h1>Welcome to Prosan Atelier Admin</h1>
        <p>Manage food, cosmetics, brands, categories, inventory, customers and order workflow from one fast custom panel.</p>
    </div>
    <div class="table-actions">
        <a class="btn ghost" href="{{ route('admin.orders.create') }}"><span class="btn-icon">＋</span> Create Order</a>
        <a class="btn" href="{{ route('admin.products.create') }}"><span class="btn-icon">＋</span> Add Product</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card"><span class="stat-icon">৳</span><span>Product Sales</span><strong>৳{{ number_format($stats['product_sales'], 0) }}</strong><small class="muted">Order subtotal minus discounts. Shipping excluded.</small></div>
    <div class="stat-card"><span class="stat-icon">📈</span><span>Profit</span><strong>৳{{ number_format($stats['estimated_profit'], 0) }}</strong><small class="muted">Product sales - purchase cost. Shipping not counted.</small></div>
    <div class="stat-card"><span class="stat-icon">🧾</span><span>Product Cost</span><strong>৳{{ number_format($stats['product_cost'], 0) }}</strong><small class="muted">Purchase cost of sold products.</small></div>
    <div class="stat-card"><span class="stat-icon">🚚</span><span>Shipping Collected</span><strong>৳{{ number_format($stats['shipping_collected'], 0) }}</strong><small class="muted">Separate tracking only, not profit.</small></div>
    <div class="stat-card"><span class="stat-icon">💰</span><span>Total Collected</span><strong>৳{{ number_format($stats['customer_total_collected'], 0) }}</strong><small class="muted">Product sales + shipping.</small></div>
    <div class="stat-card"><span class="stat-icon">🧾</span><span>Today Orders</span><strong>{{ $stats['today_orders'] }}</strong></div>
    <div class="stat-card"><span class="stat-icon">⏳</span><span>Pending Orders</span><strong>{{ $stats['pending_orders'] }}</strong></div>
    <div class="stat-card"><span class="stat-icon">🧴</span><span>Products</span><strong>{{ $stats['products'] }}</strong></div>
    <div class="stat-card"><span class="stat-icon">👥</span><span>Customers</span><strong>{{ $stats['customers'] }}</strong></div>
    <div class="stat-card"><span class="stat-icon">⚠️</span><span>Low Stock</span><strong>{{ $stats['low_stock'] }}</strong></div>
</div>

<div class="admin-grid-two">
    <div class="table-card">
        <div class="section-heading compact"><h2>Recent Orders</h2><a href="{{ route('admin.orders.index') }}">View all</a></div>
        <table>
            <thead><tr><th>Order</th><th>Customer</th><th>Status</th><th>Total</th></tr></thead>
            <tbody>
            @forelse($recentOrders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                    <td>{{ $order->customer_name }}</td>
                    <td><span class="badge">{{ $order->order_status }}</span></td>
                    <td>৳{{ number_format($order->grand_total, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No orders yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-card">
        <div class="section-heading compact"><h2>Low Stock Products</h2><a href="{{ route('admin.products.index') }}">Manage</a></div>
        <table>
            <thead><tr><th>Product</th><th>Stock</th></tr></thead>
            <tbody>
            @forelse($lowStockProducts as $product)
                <tr><td>{{ $product->name }}</td><td>{{ $product->stock_quantity }}</td></tr>
            @empty
                <tr><td colspan="2">No low stock product.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
