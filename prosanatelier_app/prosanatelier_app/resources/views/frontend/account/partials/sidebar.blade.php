@php
    $wishlistCount = isset($customer) && $customer?->exists ? $customer->wishlists()->count() : 0;
@endphp
<div class="account-sidebar-prosan card border-0 shadow-sm rounded-4 p-3">
    <div class="account-mini-profile">
        <div class="account-avatar">{{ strtoupper(substr($customer->name ?? 'P', 0, 1)) }}</div>
        <div>
            <strong>{{ $customer->name ?? 'Customer' }}</strong>
            <small>{{ $customer->phone ?? 'Prosan Atelier' }}</small>
        </div>
    </div>
    <nav class="account-menu-prosan">
        <a class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">Dashboard</a>
        <a class="{{ request()->routeIs('customer.orders*') ? 'active' : '' }}" href="{{ route('customer.orders') }}">My Orders</a>
        <a class="{{ request()->routeIs('customer.wishlist') ? 'active' : '' }}" href="{{ route('customer.wishlist') }}">Wishlist <span>{{ $wishlistCount }}</span></a>
        <a class="{{ request()->routeIs('customer.profile') ? 'active' : '' }}" href="{{ route('customer.profile') }}">Profile & Address</a>
        <a href="{{ route('order.tracking') }}">Track Order</a>
        <form method="POST" action="{{ route('customer.logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
</div>
