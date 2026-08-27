@extends('layouts.admin')
@section('title', 'Orders')
@section('content')
<div class="section-heading admin-heading-row">
    <div><h1>Orders</h1><p class="muted">Manage customer and manually created orders.</p></div>
    <a class="btn" href="{{ route('admin.orders.create') }}">+ Create Order</a>
</div>
<form class="toolbar" method="GET">
    <input name="q" value="{{ request('q') }}" placeholder="Search order/customer/phone">
    <select name="status">
        <option value="">All statuses</option>
        @foreach(['pending','processing','shipped','completed','cancelled'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <button class="btn" type="submit">Filter</button>
</form>
<div class="table-card">
    <table>
        <thead><tr><th>Order</th><th>Customer</th><th>Phone</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_phone }}</td>
                <td>৳{{ number_format($order->grand_total, 0) }}</td>
                <td><span class="badge">{{ $order->order_status }}</span></td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td class="table-actions">
                    <a href="{{ route('admin.orders.show', $order) }}">View</a>
                    <a href="{{ route('admin.orders.edit', $order) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Remove this order? Stock will be restored for non-completed orders.');" class="inline-delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="table-delete-link">Remove</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $orders->links() }}
@endsection
