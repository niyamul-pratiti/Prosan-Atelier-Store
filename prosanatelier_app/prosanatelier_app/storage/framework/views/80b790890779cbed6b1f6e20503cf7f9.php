<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-hero-card">
    <div>
        <span class="admin-kicker">Store overview</span>
        <h1>Welcome to Prosan Atelier Admin</h1>
        <p>Manage food, cosmetics, brands, categories, inventory, customers and order workflow from one fast custom panel.</p>
    </div>
    <div class="table-actions">
        <a class="btn ghost" href="<?php echo e(route('admin.orders.create')); ?>"><span class="btn-icon">＋</span> Create Order</a>
        <a class="btn" href="<?php echo e(route('admin.products.create')); ?>"><span class="btn-icon">＋</span> Add Product</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card"><span class="stat-icon">৳</span><span>Product Sales</span><strong>৳<?php echo e(number_format($stats['product_sales'], 0)); ?></strong><small class="muted">Order subtotal minus discounts. Shipping excluded.</small></div>
    <div class="stat-card"><span class="stat-icon">📈</span><span>Profit</span><strong>৳<?php echo e(number_format($stats['estimated_profit'], 0)); ?></strong><small class="muted">Product sales - purchase cost. Shipping not counted.</small></div>
    <div class="stat-card"><span class="stat-icon">🧾</span><span>Product Cost</span><strong>৳<?php echo e(number_format($stats['product_cost'], 0)); ?></strong><small class="muted">Purchase cost of sold products.</small></div>
    <div class="stat-card"><span class="stat-icon">🚚</span><span>Shipping Collected</span><strong>৳<?php echo e(number_format($stats['shipping_collected'], 0)); ?></strong><small class="muted">Separate tracking only, not profit.</small></div>
    <div class="stat-card"><span class="stat-icon">💰</span><span>Total Collected</span><strong>৳<?php echo e(number_format($stats['customer_total_collected'], 0)); ?></strong><small class="muted">Product sales + shipping.</small></div>
    <div class="stat-card"><span class="stat-icon">🧾</span><span>Today Orders</span><strong><?php echo e($stats['today_orders']); ?></strong></div>
    <div class="stat-card"><span class="stat-icon">⏳</span><span>Pending Orders</span><strong><?php echo e($stats['pending_orders']); ?></strong></div>
    <div class="stat-card"><span class="stat-icon">🧴</span><span>Products</span><strong><?php echo e($stats['products']); ?></strong></div>
    <div class="stat-card"><span class="stat-icon">👥</span><span>Customers</span><strong><?php echo e($stats['customers']); ?></strong></div>
    <div class="stat-card"><span class="stat-icon">⚠️</span><span>Low Stock</span><strong><?php echo e($stats['low_stock']); ?></strong></div>
</div>

<div class="admin-grid-two">
    <div class="table-card">
        <div class="section-heading compact"><h2>Recent Orders</h2><a href="<?php echo e(route('admin.orders.index')); ?>">View all</a></div>
        <table>
            <thead><tr><th>Order</th><th>Customer</th><th>Status</th><th>Total</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><a href="<?php echo e(route('admin.orders.show', $order)); ?>"><?php echo e($order->order_number); ?></a></td>
                    <td><?php echo e($order->customer_name); ?></td>
                    <td><span class="badge"><?php echo e($order->order_status); ?></span></td>
                    <td>৳<?php echo e(number_format($order->grand_total, 0)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4">No orders yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-card">
        <div class="section-heading compact"><h2>Low Stock Products</h2><a href="<?php echo e(route('admin.products.index')); ?>">Manage</a></div>
        <table>
            <thead><tr><th>Product</th><th>Stock</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td><?php echo e($product->name); ?></td><td><?php echo e($product->stock_quantity); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="2">No low stock product.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>