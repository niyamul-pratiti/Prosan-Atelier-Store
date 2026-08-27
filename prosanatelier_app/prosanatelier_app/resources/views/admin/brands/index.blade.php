@extends('layouts.admin')
@section('title', 'Brands')
@section('content')
<div class="section-heading"><h1>Brands</h1><a class="btn" href="{{ route('admin.brands.create') }}">Add Brand</a></div>
<div class="table-card">
    <table>
        <thead><tr><th>Logo</th><th>Name</th><th>Status</th><th>Sort</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($brands as $brand)
            <tr>
                <td><img class="admin-brand-thumb" src="{{ $brand->logo_url }}" alt="{{ $brand->name }} logo"></td>
                <td>{{ $brand->name }}</td>
                <td><span class="badge">{{ $brand->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>{{ $brand->sort_order }}</td>
                <td class="actions">
                    <a href="{{ route('admin.brands.edit', $brand) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" onsubmit="return confirm('Delete brand?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $brands->links() }}
@endsection
