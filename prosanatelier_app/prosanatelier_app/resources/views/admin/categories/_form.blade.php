<div class="form-grid">
    <label>Parent Category</label>
    <select name="parent_id">
        <option value="">Main Category</option>
        @foreach($parents as $parent)
            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? '') == $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>

    <label>Name *</label>
    <input name="name" value="{{ old('name', $category->name ?? '') }}" required>

    <label>Slug</label>
    <input name="slug" value="{{ old('slug', $category->slug ?? '') }}" placeholder="Auto generated if empty">

    <label>Image</label>
    <input type="file" name="image" accept="image/*">

    <label>Description</label>
    <textarea name="description">{{ old('description', $category->description ?? '') }}</textarea>

    <label>SEO Title</label>
    <input name="meta_title" value="{{ old('meta_title', $category->meta_title ?? '') }}">

    <label>SEO Description</label>
    <textarea name="meta_description">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>

    <label>Sort Order</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">

    <label class="checkbox-label"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))> Active</label>
</div>
<button class="btn" type="submit">Save Category</button>
