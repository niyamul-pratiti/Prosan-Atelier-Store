<?php $__env->startSection('title', 'Products'); ?>
<?php $__env->startSection('content'); ?>
<div class="section-heading admin-heading-actions">
    <h1>Products</h1>
    <a class="btn" href="<?php echo e(route('admin.products.create')); ?>">Add Product</a>
</div>
<form class="toolbar" method="GET">
    <input name="q" value="<?php echo e(request('q')); ?>" placeholder="Search product or SKU">
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
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <strong><?php echo e($product->name); ?></strong><br>
                    <span class="muted"><?php echo e($product->sku ?: 'No main SKU'); ?></span>
                    <?php if($product->is_variable): ?>
                        <br><span class="muted"><?php echo e($product->activeVariations->count()); ?> variation<?php echo e($product->activeVariations->count() === 1 ? '' : 's'); ?></span>
                    <?php endif; ?>
                </td>
                <td><span class="badge"><?php echo e($product->is_variable ? 'Variable' : 'Simple'); ?></span></td>
                <td><?php echo e($product->category->full_name ?? 'N/A'); ?></td>
                <td><?php echo e($product->brand->name ?? 'N/A'); ?></td>
                <td><strong><?php echo e($product->price_label); ?></strong></td>
                <td><?php echo e($product->display_stock); ?></td>
                <td><span class="badge"><?php echo e($product->is_active ? 'Active' : 'Inactive'); ?></span></td>
                <td class="actions">
                    <a href="<?php echo e(route('admin.products.edit', $product)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.products.destroy', $product)); ?>" onsubmit="return confirm('Delete product?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button type="submit">Delete</button></form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($products->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/products/index.blade.php ENDPATH**/ ?>