@extends('layouts.admin')
@section('title', 'Customer Details')
@section('content')
<div class="section-heading"><h1>{{ $customer->name }}</h1><a class="btn" href="{{ route('admin.customers.index') }}">Back</a></div>
<div class="grid-2">
    <div class="form-card">
        <h2>Customer Information</h2>
        <p><strong>Name:</strong> {{ $customer->name }}</p>
        <p><strong>Email:</strong> {{ $customer->email ?: '—' }}</p>
        <p><strong>Phone:</strong> {{ $customer->phone }}</p>
        <p><strong>Status:</strong> {{ $customer->is_active ? 'Active' : 'Inactive' }}</p>
    </div>
    <div class="form-card">
        <h2>Default Address</h2>
        <p>{{ $customer->address_line ?: 'No address saved.' }}</p>
        <p>{{ $customer->area }} {{ $customer->city }}</p>
        <p><strong>Shipping Zone:</strong> {{ str_replace('_', ' ', ucfirst($customer->shipping_zone ?: 'N/A')) }}</p>
    </div>
</div>
<div class="section-heading mt-4"><h2>Recent Orders</h2></div>
<div class="table-card">
    <table>
        <thead><tr><th>Order</th><th>Total</th><th>Status</th><th>Payment</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($customer->orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>৳{{ number_format($order->grand_total,0) }}</td>
                <td><span class="badge">{{ $order->order_status }}</span></td>
                <td>{{ $order->payment_status }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td><a href="{{ route('admin.orders.show', $order) }}">View Order</a></td>
            </tr>
        @empty
            <tr><td colspan="6">No orders found for this customer.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
