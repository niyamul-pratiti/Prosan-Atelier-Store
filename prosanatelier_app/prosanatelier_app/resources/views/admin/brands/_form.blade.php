<div class="form-grid">
    <label>Name *</label>
    <input name="name" value="{{ old('name', $brand->name ?? '') }}" required>

    <label>Slug</label>
    <input name="slug" value="{{ old('slug', $brand->slug ?? '') }}" placeholder="Auto generated if empty">

    <label>Logo</label>
    <div>
        <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml">
        <small>Supports JPG, JPEG, PNG, WebP, GIF and SVG.</small>
        @if(isset($brand) && $brand->logo)
            <div class="brand-logo-preview mt-2">
                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }} logo">
            </div>
        @endif
    </div>

    <label>Description</label>
    <textarea name="description">{{ old('description', $brand->description ?? '') }}</textarea>

    <label>SEO Title</label>
    <input name="meta_title" value="{{ old('meta_title', $brand->meta_title ?? '') }}">

    <label>SEO Description</label>
    <textarea name="meta_description">{{ old('meta_description', $brand->meta_description ?? '') }}</textarea>

    <label>Sort Order</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $brand->sort_order ?? 0) }}" min="0">

    <label class="checkbox-label"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $brand->is_active ?? true))> Active</label>
</div>
<button class="btn" type="submit">Save Brand</button>
