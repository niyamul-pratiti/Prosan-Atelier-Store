<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Prosan Atelier</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/prosan-atelier.css')); ?>">
</head>
<body class="login-body">
<div class="login-card">
    <div class="login-logo"><img src="<?php echo e(asset('images/prosan-logo.jpg')); ?>" alt="Prosan Atelier"><span>Prosan Atelier</span></div>
    <h1>Admin Login</h1>
    <p class="muted">Manage products, categories, brands, stock and orders.</p>
    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form method="POST" action="<?php echo e(route('admin.login.store')); ?>" class="form-grid">
        <?php echo csrf_field(); ?>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
        <label>Password</label>
        <input type="password" name="password" required>
        <button class="btn full" type="submit">Login</button>
    </form>
</div>
</body>
</html>
<?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>