@extends('layouts.admin')

@section('title', 'Product Requests')

@section('content')
<div class="admin-page-head prosan-tool-head">
    <div>
        <p class="admin-kicker">Customer Demand</p>
        <h1>Product Requests</h1>
        <p class="text-muted">Track products customers want you to source, then update status and contact them quickly.</p>
    </div>
    <a class="btn" href="{{ route('admin.product_requests.export', request()->query()) }}">Export CSV</a>
</div>

<div class="prosan-request-stats">
    <div class="content-card prosan-request-stat"><span>Total</span><strong>{{ $summary['total'] }}</strong></div>
    <div class="content-card prosan-request-stat"><span>New</span><strong>{{ $summary['new'] }}</strong></div>
    <div class="content-card prosan-request-stat"><span>Checking</span><strong>{{ $summary['checking'] }}</strong></div>
    <div class="content-card prosan-request-stat"><span>Available Soon</span><strong>{{ $summary['available_soon'] }}</strong></div>
    <div class="content-card prosan-request-stat"><span>Completed</span><strong>{{ $summary['completed'] }}</strong></div>
</div>

<div class="content-card prosan-filter-card">
    <form method="GET" class="prosan-filter-grid product-request-filter-grid">
        <div>
            <label>Search</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, phone, product, brand...">
        </div>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="">All Status</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="prosan-filter-actions">
            <button class="btn" type="submit">Filter</button>
            <a class="btn btn-light-outline" href="{{ route('admin.product_requests.index') }}">Reset</a>
        </div>
    </form>
</div>

<div class="table-card prosan-table-card">
    <div class="table-responsive">
        <table class="admin-table prosan-admin-table product-request-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Requested Product</th>
                    <th>Message</th>
                    <th>Status / Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $item)
                    <tr>
                        <td>
                            <strong>{{ optional($item->created_at)->format('d M Y') }}</strong><br>
                            <small>{{ optional($item->created_at)->format('h:i A') }}</small><br>
                            <span class="badge-soft">{{ $item->source ?: 'website' }}</span>
                        </td>
                        <td>
                            <strong>{{ $item->customer_name }}</strong><br>
                            <a href="tel:{{ $item->phone }}">{{ $item->phone }}</a><br>
                            @if($item->email)<a href="mailto:{{ $item->email }}">{{ $item->email }}</a>@else<small>Email: N/A</small>@endif
                            @if($item->whatsapp_url)<br><a class="product-request-whatsapp" href="{{ $item->whatsapp_url }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>@endif
                        </td>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            @if($item->brand)<small>Brand: {{ $item->brand }}</small><br>@endif
                            @if($item->quantity)<small>Qty: {{ $item->quantity }}</small><br>@endif
                            @if($item->product_link)<a href="{{ $item->product_link }}" target="_blank" rel="noopener noreferrer">Product Link</a>@endif
                        </td>
                        <td>{{ $item->message ?: '—' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.product_requests.update', $item) }}" class="product-request-status-form">
                                @csrf
                                @method('PATCH')
                                <select name="status">
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected($item->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <textarea name="admin_note" rows="2" placeholder="Admin note...">{{ $item->admin_note }}</textarea>
                                <button class="btn btn-small" type="submit">Update</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.product_requests.destroy', $item) }}" onsubmit="return confirm('Delete this product request?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-soft btn-small">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No product requests found yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $requests->links() }}</div>
</div>
@endsection
