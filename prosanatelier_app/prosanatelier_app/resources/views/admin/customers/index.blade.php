@extends('layouts.admin')
@section('title', 'Customers')
@section('content')
<div class="section-heading"><h1>Customers</h1></div>
<form class="toolbar" method="GET">
    <input name="q" value="{{ request('q') }}" placeholder="Search customer, email or phone">
    <button class="btn" type="submit">Filter</button>
</form>
<div class="table-card">
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>City</th><th>Joined</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email ?: '—' }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->orders_count }}</td>
                <td>{{ $customer->city ?: '—' }}</td>
                <td>{{ $customer->created_at->format('d M Y') }}</td>
                <td><a href="{{ route('admin.customers.show', $customer) }}">View</a></td>
            </tr>
        @empty
            <tr><td colspan="7">No customers found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $customers->links() }}
@endsection
