<?php $__env->startSection('title', 'Admin Account'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-heading admin-heading-row">
    <div>
        <h1>Admin Account</h1>
        <p class="muted">Update admin name, email and password securely.</p>
    </div>
    <a class="btn ghost" href="<?php echo e(route('admin.dashboard')); ?>">Back to Dashboard</a>
</div>

<?php if($errors->any()): ?>
    <div class="alert-box error-box">
        <strong>Please fix the following:</strong>
        <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
<?php endif; ?>

<div class="content-card admin-profile-card">
    <form method="POST" action="<?php echo e(route('admin.profile.update')); ?>" class="form-grid clean-form-grid">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div>
            <label>Name *</label>
            <input name="name" value="<?php echo e(old('name', $admin->name)); ?>" required>
        </div>

        <div>
            <label>Email *</label>
            <input type="email" name="email" value="<?php echo e(old('email', $admin->email)); ?>" required>
        </div>

        <div class="field-full">
            <label>Current Password *</label>
            <input type="password" name="current_password" required placeholder="Enter current admin password to save changes">
        </div>

        <div>
            <label>New Password</label>
            <input type="password" name="password" placeholder="Leave blank to keep current password">
            <small class="muted">Minimum 8 characters.</small>
        </div>

        <div>
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm new password">
        </div>

        <div class="field-full admin-form-actions">
            <button class="btn" type="submit">Update Admin Account</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/auth/profile.blade.php ENDPATH**/ ?>