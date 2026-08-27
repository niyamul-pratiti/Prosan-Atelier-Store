<?php
    $selectedType = old('product_type', $product->product_type ?? 'simple');
    $selectedCategoryIds = collect(old('category_ids', isset($product) ? $product->categories->pluck('id')->all() : []))
        ->merge([old('category_id', $product->category_id ?? null)])
        ->filter()
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();
    $variationRows = collect(old('variations', isset($product) ? $product->variations->toArray() : []))->values()->all();
?>

<div class="content-card product-type-card">
    <div class="section-heading compact">
        <div>
            <h3>Product Type</h3>
            <span class="muted">Simple product uses one price. Variable product uses variation-wise price and stock like WooCommerce.</span>
        </div>
    </div>
    <div class="product-type-switch">
        <label class="type-option <?php echo e($selectedType === 'simple' ? 'is-selected' : ''); ?>">
            <input type="radio" name="product_type" value="simple" <?php if($selectedType === 'simple'): echo 'checked'; endif; ?>>
            <span><strong>Simple Product</strong><small>Single price, single stock</small></span>
        </label>
        <label class="type-option <?php echo e($selectedType === 'variable' ? 'is-selected' : ''); ?>">
            <input type="radio" name="product_type" value="variable" <?php if($selectedType === 'variable'): echo 'checked'; endif; ?>>
            <span><strong>Variable Product</strong><small>Different size/color/weight with own price</small></span>
        </label>
    </div>
</div>

<div class="form-grid two-col">
    <div>
        <label>Name *</label>
        <input name="name" value="<?php echo e(old('name', $product->name ?? '')); ?>" required>
    </div>
    <div>
        <label>Slug</label>
        <input name="slug" value="<?php echo e(old('slug', $product->slug ?? '')); ?>" placeholder="Auto generated if empty">
    </div>
    <div>
        <label>Main SKU</label>
        <input name="sku" value="<?php echo e(old('sku', $product->sku ?? '')); ?>" placeholder="Optional for variable products">
    </div>
    <div>
        <label>Barcode</label>
        <input name="barcode" value="<?php echo e(old('barcode', $product->barcode ?? '')); ?>">
    </div>
    <div>
        <label>Primary Category</label>
        <select name="category_id">
            <option value="">No primary category</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php if(old('category_id', $product->category_id ?? '') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->full_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <small>This is used as the main category for breadcrumb, related products and SEO.</small>
    </div>
    <div>
        <label>Brand</label>
        <select name="brand_id">
            <option value="">No brand</option>
            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($brand->id); ?>" <?php if(old('brand_id', $product->brand_id ?? '') == $brand->id): echo 'selected'; endif; ?>><?php echo e($brand->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<div class="content-card nested-card simple-product-fields" data-simple-fields>
    <div class="section-heading compact"><h3>Simple Product Price & Stock</h3><span class="muted">This block is hidden when product type is Variable.</span></div>
    <div class="form-grid two-col">
        <div>
            <label>Regular Price *</label>
            <input type="number" step="0.01" name="regular_price" value="<?php echo e(old('regular_price', $product->regular_price ?? 0)); ?>" data-simple-required>
        </div>
        <div>
            <label>Sale Price</label>
            <input type="number" step="0.01" name="sale_price" value="<?php echo e(old('sale_price', $product->sale_price ?? '')); ?>">
        </div>
        <div>
            <label>Purchase Price / Cost</label>
            <input type="number" step="0.01" name="purchase_price" value="<?php echo e(old('purchase_price', $product->purchase_price ?? 0)); ?>" placeholder="Your buying cost">
            <small>This is used only for admin profit calculation. It will not show to customers.</small>
        </div>
        <div>
            <label>Stock *</label>
            <input type="number" name="stock_quantity" value="<?php echo e(old('stock_quantity', $product->stock_quantity ?? 0)); ?>" data-simple-required>
        </div>
    </div>
</div>

<div class="form-grid two-col product-basic-extra">
    <div>
        <label>Low Stock Alert</label>
        <input type="number" name="low_stock_alert" value="<?php echo e(old('low_stock_alert', $product->low_stock_alert ?? 5)); ?>">
    </div>
    <div>
        <label>Expiry Date</label>
        <input type="date" name="expiry_date" value="<?php echo e(old('expiry_date', isset($product) && $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '')); ?>">
    </div>
    <div>
        <label>Default Delivery Weight</label>
        <input type="number" step="0.01" name="weight" value="<?php echo e(old('weight', $product->weight ?? '')); ?>" placeholder="Example: 0.50">
        <small>Use the actual packed product weight (including jar/bottle/retail pack), excluding only the outer courier packaging.</small>
    </div>
    <div>
        <label>Weight Unit</label>
        <input name="unit" value="<?php echo e(old('unit', $product->unit ?? '')); ?>" placeholder="kg, gm, ml or litre">
        <small>Recognized units: kg, gm/g, mg, ml, litre, lb and oz.</small>
    </div>
</div>

<div class="content-card nested-card variable-product-fields" data-variable-fields>
    <div class="section-heading compact">
        <div>
            <h3>Variations</h3>
            <span class="muted">Each variation has its own price, cost, SKU and stock. The product page will show a price range automatically.</span>
        </div>
        <button type="button" class="btn small" onclick="addVariationRow()">Add Variation</button>
    </div>

    <?php $__errorArgs = ['variations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="alert error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <div class="variation-table-wrap">
        <div class="variation-header">
            <span>Option Name *</span>
            <span>SKU</span>
            <span>Regular *</span>
            <span>Sale</span>
            <span>Cost</span>
            <span>Stock</span>
            <span>Delivery Weight</span>
            <span>Weight Unit</span>
            <span>Status</span>
            <span></span>
        </div>
        <div id="variationRows" class="variation-rows">
            <?php $__currentLoopData = $variationRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $variation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="variation-row-v35">
                    <input type="hidden" name="variations[<?php echo e($i); ?>][id]" value="<?php echo e($variation['id'] ?? ''); ?>">
                    <input name="variations[<?php echo e($i); ?>][name]" value="<?php echo e($variation['name'] ?? ''); ?>" placeholder="500g / Red / Large" data-variation-name>
                    <input name="variations[<?php echo e($i); ?>][sku]" value="<?php echo e($variation['sku'] ?? ''); ?>" placeholder="SKU">
                    <input type="number" step="0.01" name="variations[<?php echo e($i); ?>][regular_price]" value="<?php echo e($variation['regular_price'] ?? ''); ?>" placeholder="Price" data-variation-price>
                    <input type="number" step="0.01" name="variations[<?php echo e($i); ?>][sale_price]" value="<?php echo e($variation['sale_price'] ?? ''); ?>" placeholder="Sale">
                    <input type="number" step="0.01" name="variations[<?php echo e($i); ?>][purchase_price]" value="<?php echo e($variation['purchase_price'] ?? ''); ?>" placeholder="Cost">
                    <input type="number" name="variations[<?php echo e($i); ?>][stock_quantity]" value="<?php echo e($variation['stock_quantity'] ?? 0); ?>" placeholder="Stock">
                    <input type="number" step="0.01" name="variations[<?php echo e($i); ?>][weight]" value="<?php echo e($variation['weight'] ?? ''); ?>" placeholder="0.50">
                    <input name="variations[<?php echo e($i); ?>][unit]" value="<?php echo e($variation['unit'] ?? ''); ?>" placeholder="kg / gm">
                    <label class="variation-active"><input type="hidden" name="variations[<?php echo e($i); ?>][is_active]" value="0"><input type="checkbox" name="variations[<?php echo e($i); ?>][is_active]" value="1" <?php if(($variation['is_active'] ?? true)): echo 'checked'; endif; ?>> Active</label>
                    <button type="button" class="variation-remove" onclick="removeVariationRow(this)">×</button>
                </div>
                <?php $__errorArgs = ["variations.$i.regular_price"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger d-block"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <small>Tip: For size-based products, write variation names like 32, 34, 36 or S, M, L. For weight-based products, write 250g, 500g, 1kg.</small>
</div>

<div class="content-card nested-card">
    <div class="section-heading compact"><h3>Additional Categories</h3><span class="muted">A product can appear in multiple categories.</span></div>
    <select name="category_ids[]" multiple size="8" class="admin-multi-select">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($category->id); ?>" <?php if(in_array((string) $category->id, $selectedCategoryIds, true)): echo 'selected'; endif; ?>><?php echo e($category->full_name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <small>Hold Ctrl/Cmd to select multiple categories. The primary category will be included automatically.</small>
</div>

<div class="form-grid two-col">
    <label>Short Description</label>
    <input name="short_description" value="<?php echo e(old('short_description', $product->short_description ?? '')); ?>">
    <label>Description</label>
    <textarea name="description" rows="5"><?php echo e(old('description', $product->description ?? '')); ?></textarea>
    <label>Ingredients</label>
    <textarea name="ingredients" rows="4"><?php echo e(old('ingredients', $product->ingredients ?? '')); ?></textarea>
    <label>Usage Instruction</label>
    <textarea name="usage_instruction" rows="4"><?php echo e(old('usage_instruction', $product->usage_instruction ?? '')); ?></textarea>
</div>

<div class="content-card nested-card product-image-admin-block">
    <div class="section-heading"><h3>Product Images</h3></div>
    <div class="form-grid two-col">
        <div>
            <label>Featured Image</label>
            <input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp,image/gif">
            <small>This image will be used on product cards and as the main image. JPG, PNG, WebP or GIF up to 12MB is supported.</small>
        </div>
        <div>
            <label>Gallery Images</label>
            <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
            <small>These images will appear under the featured image. JPG, PNG, WebP or GIF up to 12MB is supported.</small>
        </div>
    </div>

    <?php if(isset($product) && $product->images->count()): ?>
        <?php ($featuredImage = $product->images->firstWhere('is_primary', true)); ?>
        <?php ($galleryImages = $product->images->where('is_primary', false)); ?>

        <?php if($featuredImage): ?>
            <h4 class="image-section-title">Current Featured Image</h4>
            <div class="image-list featured-image-list">
                <div class="featured-image-preview">
                    <img src="<?php echo e($featuredImage->url); ?>" alt="<?php echo e($featuredImage->alt_text); ?>">
                    <span class="image-badge">Featured</span>
                    <button form="delete-image-<?php echo e($featuredImage->id); ?>" type="submit" class="link-danger">Delete</button>
                </div>
            </div>
        <?php endif; ?>

        <?php if($galleryImages->count()): ?>
            <h4 class="image-section-title">Current Gallery Images</h4>
            <div class="image-list">
                <?php $__currentLoopData = $galleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <img src="<?php echo e($image->url); ?>" alt="<?php echo e($image->alt_text); ?>">
                        <button form="delete-image-<?php echo e($image->id); ?>" type="submit" class="link-danger">Delete</button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="form-grid two-col">
    <label class="checkbox-label"><input type="checkbox" name="is_featured" value="1" <?php if(old('is_featured', $product->is_featured ?? false)): echo 'checked'; endif; ?>> Featured</label>
    <label class="checkbox-label"><input type="checkbox" name="is_new_arrival" value="1" <?php if(old('is_new_arrival', $product->is_new_arrival ?? false)): echo 'checked'; endif; ?>> New Arrival</label>
    <label class="checkbox-label"><input type="checkbox" name="is_best_seller" value="1" <?php if(old('is_best_seller', $product->is_best_seller ?? false)): echo 'checked'; endif; ?>> Best Seller</label>
    <label class="checkbox-label"><input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $product->is_active ?? true)): echo 'checked'; endif; ?>> Active</label>
</div>

<div class="form-grid">
    <label>SEO Title</label>
    <input name="meta_title" value="<?php echo e(old('meta_title', $product->meta_title ?? '')); ?>">
    <label>SEO Description</label>
    <textarea name="meta_description"><?php echo e(old('meta_description', $product->meta_description ?? '')); ?></textarea>
</div>

<button class="btn" type="submit">Save Product</button>

<script>
let variationIndex = <?php echo e(count($variationRows ?? [])); ?>;

function productTypeValue() {
    const selected = document.querySelector('input[name="product_type"]:checked');
    return selected ? selected.value : 'simple';
}

function syncProductTypeUI() {
    const isVariable = productTypeValue() === 'variable';
    document.querySelectorAll('[data-simple-fields]').forEach(el => el.style.display = isVariable ? 'none' : 'block');
    document.querySelectorAll('[data-variable-fields]').forEach(el => el.style.display = isVariable ? 'block' : 'none');
    document.querySelectorAll('[data-simple-required]').forEach(el => {
        if (isVariable) {
            el.removeAttribute('required');
        } else {
            el.setAttribute('required', 'required');
        }
    });
    document.querySelectorAll('[data-variation-price], [data-variation-name]').forEach(el => {
        if (isVariable) {
            el.setAttribute('data-required-ready', '1');
        } else {
            el.removeAttribute('data-required-ready');
        }
    });
    document.querySelectorAll('.type-option').forEach(label => label.classList.remove('is-selected'));
    const activeInput = document.querySelector('input[name="product_type"]:checked');
    if (activeInput) activeInput.closest('.type-option')?.classList.add('is-selected');
}

function addVariationRow() {
    const wrap = document.getElementById('variationRows');
    const div = document.createElement('div');
    div.className = 'variation-row-v35';
    div.innerHTML = `
        <input type="hidden" name="variations[${variationIndex}][id]" value="">
        <input name="variations[${variationIndex}][name]" placeholder="500g / Red / Large" data-variation-name>
        <input name="variations[${variationIndex}][sku]" placeholder="SKU">
        <input type="number" step="0.01" name="variations[${variationIndex}][regular_price]" placeholder="Price" data-variation-price>
        <input type="number" step="0.01" name="variations[${variationIndex}][sale_price]" placeholder="Sale">
        <input type="number" step="0.01" name="variations[${variationIndex}][purchase_price]" placeholder="Cost">
        <input type="number" name="variations[${variationIndex}][stock_quantity]" placeholder="Stock" value="0">
        <input type="number" step="0.01" name="variations[${variationIndex}][weight]" placeholder="0.50">
        <input name="variations[${variationIndex}][unit]" placeholder="kg / gm">
        <label class="variation-active"><input type="hidden" name="variations[${variationIndex}][is_active]" value="0"><input type="checkbox" name="variations[${variationIndex}][is_active]" value="1" checked> Active</label>
        <button type="button" class="variation-remove" onclick="removeVariationRow(this)">×</button>
    `;
    wrap.appendChild(div);
    variationIndex++;
}

function removeVariationRow(button) {
    button.closest('.variation-row-v35')?.remove();
}

document.querySelectorAll('input[name="product_type"]').forEach(input => input.addEventListener('change', syncProductTypeUI));
syncProductTypeUI();
if (productTypeValue() === 'variable' && document.querySelectorAll('.variation-row-v35').length === 0) {
    addVariationRow();
}
</script>
<?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/products/_form.blade.php ENDPATH**/ ?>