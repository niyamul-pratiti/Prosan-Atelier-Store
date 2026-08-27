@extends('layouts.store')
@section('title', 'My Dashboard - Prosan Atelier')
@section('content')
<section class="account-hero-prosan">
    <div class="container-fluid">
        <span>My Account</span>
        <h1>Welcome back, {{ $customer->name }}.</h1>
        <p>Manage orders, wishlist, delivery address and account details from one clean dashboard.</p>
    </div>
</section>
<section class="account-section-prosan">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-3">@include('frontend.account.partials.sidebar')</div>
            <div class="col-lg-9">
                <div class="account-stat-grid">
                    <div class="account-stat-card"><span>Total Orders</span><strong>{{ $stats['orders'] }}</strong></div>
                    <div class="account-stat-card"><span>Processing</span><strong>{{ $stats['pending'] }}</strong></div>
                    <div class="account-stat-card"><span>Completed</span><strong>{{ $stats['completed'] }}</strong></div>
                    <div class="account-stat-card"><span>Wishlist</span><strong>{{ $stats['wishlist'] }}</strong></div>
                    <div class="account-stat-card"><span>Total Spend</span><strong>৳{{ number_format($stats['total_spent'], 0) }}</strong></div>
                </div>

                <div class="row g-4 mt-1">
                    <div class="col-xl-7">
                        <div class="account-panel-prosan">
                            <div class="account-panel-head">
                                <div><h3>Recent Orders</h3><p>Your latest purchases and current status.</p></div>
                                <a href="{{ route('customer.orders') }}">View all →</a>
                            </div>
                            @forelse($recentOrders as $order)
                                <div class="account-order-card">
                                    <div>
                                        <strong>{{ $order->order_number }}</strong>
                                        <small>{{ $order->created_at->format('d M Y') }} · {{ ucfirst($order->order_status) }} · {{ $order->paymentMethodLabel() }}</small>
                                    </div>
                                    <div class="text-end">
                                        <strong>৳{{ number_format($order->grand_total,0) }}</strong>
                                        <a href="{{ route('customer.orders.show', $order->order_number) }}">View</a>
                                    </div>
                                </div>
                            @empty
                                <div class="account-empty-state"><strong>No orders yet.</strong><p>Start shopping and your order history will appear here.</p><a href="{{ route('shop.index') }}">Shop now</a></div>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="account-panel-prosan h-100">
                            <div class="account-panel-head">
                                <div><h3>Delivery Address</h3><p>Used for checkout and courier delivery.</p></div>
                                <a href="{{ route('customer.profile') }}">Edit →</a>
                            </div>
                            <div class="delivery-summary-box">
                                <strong>{{ $customer->name }}</strong>
                                <p>{{ $customer->phone }}</p>
                                <p>{{ $customer->address_line ?: 'No address saved yet.' }}</p>
                                <p>{{ $customer->area ?: 'Area not set' }}, {{ $customer->city ?: 'Dhaka' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($wishlistProducts->count())
                    <div class="account-panel-prosan mt-4">
                        <div class="account-panel-head">
                            <div><h3>Wishlist Preview</h3><p>Quick access to your saved products.</p></div>
                            <a href="{{ route('customer.wishlist') }}">Open wishlist →</a>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
                            @foreach($wishlistProducts as $product)
                                <div class="col">@include('partials.product-card', ['product' => $product])</div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
