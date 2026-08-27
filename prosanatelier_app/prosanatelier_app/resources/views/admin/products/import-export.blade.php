@extends('layouts.admin')
@section('title', 'Product Import / Export')
@section('content')
<div class="section-heading">
    <h1>Product Import / Export</h1>
    <div class="admin-heading-actions">
        <a class="btn btn-light-outline" href="{{ route('admin.products.index') }}">Back to Products</a>
        <a class="btn" href="{{ route('admin.products.export') }}">Export Products</a>
    </div>
</div>

@if(session('import_errors'))
    <div class="alert-warning-box">
        <strong>Import notes:</strong>
        <ul>
            @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="admin-grid two-col import-export-grid">
    <div class="form-card">
        <h2>Import WooCommerce / Product CSV</h2>
        <p class="muted">Upload a WooCommerce product export CSV. Products will be matched by SKU first, then slug/name.</p>

        <form method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data" class="admin-form stacked-form">
            @csrf

            <label>CSV File *</label>
            <input type="file" name="csv_file" accept=".csv,.txt" required>
            @error('csv_file')<span class="form-error">{{ $message }}</span>@enderror

            <label>Import Mode</label>
            <select name="mode">
                <option value="create_update">Create new + update existing</option>
                <option value="update_only">Update existing only</option>
                <option value="create_only">Create new only</option>
            </select>

            <label>Image URL Mode</label>
            <select name="image_mode">
                <option value="keep">Keep existing images and add missing image URLs</option>
                <option value="replace">Replace existing gallery with CSV image URLs</option>
            </select>

            <div class="import-help-card">
                <strong>Supported WooCommerce columns:</strong>
                <span>Name, SKU, Regular price, Sale price, Stock, Categories, Brands, Images, Short description, Description, Weight, Published, Is featured?</span>
            </div>

            <button class="btn" type="submit" onclick="return confirm('Import products from this CSV? Please take a database backup first.')">Import Products</button>
        </form>
    </div>

    <div class="form-card">
        <h2>Export Products</h2>
        <p class="muted">Download all current products as a CSV file. The exported file includes multiple categories, brand, prices, stock, purchase price, and image URLs.</p>
        <a class="btn btn-wide" href="{{ route('admin.products.export') }}">Download Product CSV</a>

        <div class="import-help-card mt-4">
            <strong>Before importing:</strong>
            <span>Always export/backup current products first. For large imports, upload 100–300 products at a time on shared hosting.</span>
        </div>

        <h3 class="mt-4">Recently Updated Products</h3>
        <div class="mini-list-card">
            @forelse($latestProducts as $product)
                <div class="mini-list-row">
                    <div>
                        <strong>{{ $product->name }}</strong>
                        <span>{{ $product->sku ?: 'No SKU' }}</span>
                    </div>
                    <small>{{ optional($product->updated_at)->format('d M Y') }}</small>
                </div>
            @empty
                <p class="muted mb-0">No products found.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="table-card mt-4">
    <h2>Recommended CSV format</h2>
    <table>
        <thead>
            <tr>
                <th>Column</th>
                <th>Purpose</th>
                <th>Example</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>SKU</td><td>Used to update existing products</td><td>PA-RAMEN-001</td></tr>
            <tr><td>Name</td><td>Product title</td><td>Samyang Buldak Ramen 145g</td></tr>
            <tr><td>Categories</td><td>Multiple categories supported</td><td>Food &gt; Ramen, Korean Food</td></tr>
            <tr><td>Brands</td><td>Brand name</td><td>Samyang</td></tr>
            <tr><td>Images</td><td>Comma separated image URLs</td><td>https://example.com/image.jpg</td></tr>
            <tr><td>Purchase Price</td><td>Cost for profit calculation</td><td>120</td></tr>
        </tbody>
    </table>
</div>
@endsection
