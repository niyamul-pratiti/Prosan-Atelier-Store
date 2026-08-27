<?php $__env->startSection('title', 'Categories'); ?>
<?php $__env->startSection('content'); ?>
<div class="section-heading"><h1>Categories</h1><a class="btn" href="<?php echo e(route('admin.categories.create')); ?>">Add Category</a></div>
<div class="table-card">
    <table>
        <thead><tr><th>Name</th><th>Parent</th><th>Status</th><th>Sort</th><th>Action</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($category->name); ?></td>
                <td><?php echo e($category->parent->name ?? 'Main'); ?></td>
                <td><span class="badge"><?php echo e($category->is_active ? 'Active' : 'Inactive'); ?></span></td>
                <td><?php echo e($category->sort_order); ?></td>
                <td class="actions">
                    <a href="<?php echo e(route('admin.categories.edit', $category)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.categories.destroy', $category)); ?>" onsubmit="return confirm('Delete category?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button type="submit">Delete</button></form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($categories->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/categories/index.blade.php ENDPATH**/ ?>