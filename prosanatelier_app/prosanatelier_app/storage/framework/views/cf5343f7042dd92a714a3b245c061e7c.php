<?php $__env->startSection('title', 'Brands'); ?>
<?php $__env->startSection('content'); ?>
<div class="section-heading"><h1>Brands</h1><a class="btn" href="<?php echo e(route('admin.brands.create')); ?>">Add Brand</a></div>
<div class="table-card">
    <table>
        <thead><tr><th>Logo</th><th>Name</th><th>Status</th><th>Sort</th><th>Action</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><img class="admin-brand-thumb" src="<?php echo e($brand->logo_url); ?>" alt="<?php echo e($brand->name); ?> logo"></td>
                <td><?php echo e($brand->name); ?></td>
                <td><span class="badge"><?php echo e($brand->is_active ? 'Active' : 'Inactive'); ?></span></td>
                <td><?php echo e($brand->sort_order); ?></td>
                <td class="actions">
                    <a href="<?php echo e(route('admin.brands.edit', $brand)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.brands.destroy', $brand)); ?>" onsubmit="return confirm('Delete brand?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button type="submit">Delete</button></form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($brands->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/brands/index.blade.php ENDPATH**/ ?>