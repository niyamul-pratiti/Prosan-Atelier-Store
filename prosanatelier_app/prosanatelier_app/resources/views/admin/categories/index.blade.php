@extends('layouts.admin')
@section('title', 'Categories')
@section('content')
<div class="section-heading"><h1>Categories</h1><a class="btn" href="{{ route('admin.categories.create') }}">Add Category</a></div>
<div class="table-card">
    <table>
        <thead><tr><th>Name</th><th>Parent</th><th>Status</th><th>Sort</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->parent->name ?? 'Main' }}</td>
                <td><span class="badge">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>{{ $category->sort_order }}</td>
                <td class="actions">
                    <a href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete category?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $categories->links() }}
@endsection
