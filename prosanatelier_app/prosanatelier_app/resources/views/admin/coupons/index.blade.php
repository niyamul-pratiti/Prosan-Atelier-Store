@extends('layouts.admin')
@section('title', 'Coupons')
@section('content')
<div class="section-heading"><h1>Coupons</h1><a class="btn" href="{{ route('admin.coupons.create') }}">Add Coupon</a></div>
<form class="toolbar" method="GET"><input name="q" value="{{ request('q') }}" placeholder="Search coupon code"><button class="btn" type="submit">Search</button></form>
<div class="table-card">
    <table>
        <thead><tr><th>Code</th><th>Type</th><th>Amount</th><th>Applies To</th><th>Minimum</th><th>Usage</th><th>Status</th><th>Expires</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($coupons as $coupon)
            <tr>
                <td><strong>{{ $coupon->code }}</strong><br><span class="muted">{{ $coupon->description }}</span></td>
                <td>{{ $coupon->type_label }}</td>
                <td>
                    @if($coupon->type === 'percent') {{ rtrim(rtrim(number_format($coupon->amount, 2), '0'), '.') }}%
                    @elseif($coupon->type === 'free_delivery') Free delivery
                    @else ৳{{ number_format($coupon->amount, 0) }}
                    @endif
                </td>
                <td>{{ $coupon->applies_to_label }}</td>
                <td>৳{{ number_format($coupon->minimum_order_amount, 0) }}</td>
                <td>{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}</td>
                <td><span class="badge">{{ $coupon->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'No expiry' }}</td>
                <td class="actions">
                    <a href="{{ route('admin.coupons.edit', $coupon) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete coupon?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="9">No coupons created yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $coupons->links() }}
@endsection
