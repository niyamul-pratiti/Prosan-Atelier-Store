@extends('layouts.admin')
@section('title', 'Products')
@section('content')
<div class="section-heading admin-heading-actions">
    <h1>Products</h1>
    <a class="btn" href="{{ route('admin.products.create') }}">Add Product</a>
</div>
<form class="toolbar" method="GET">
    <input name="q" value="{{ request('q') }}" placeholder="Search product or SKU">
    <button class="btn" type="submit">Search</button>
</form>
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Type</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>
                    <strong>{{ $product->name }}</strong><br>
                    <span class="muted">{{ $product->sku ?: 'No main SKU' }}</span>
                    @if($product->is_variable)
                        <br><span class="muted">{{ $product->activeVariations->count() }} variation{{ $product->activeVariations->count() === 1 ? '' : 's' }}</span>
                    @endif
                </td>
                <td><span class="badge">{{ $product->is_variable ? 'Variable' : 'Simple' }}</span></td>
                <td>{{ $product->category->full_name ?? 'N/A' }}</td>
                <td>{{ $product->brand->name ?? 'N/A' }}</td>
                <td><strong>{{ $product->price_label }}</strong></td>
                <td>{{ $product->display_stock }}</td>
                <td><span class="badge">{{ $product->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td class="actions">
                    <a href="{{ route('admin.products.edit', $product) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete product?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $products->links() }}
@endsection
