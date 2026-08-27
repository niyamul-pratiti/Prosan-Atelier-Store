<?php $__env->startSection('title', 'Product Requests'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-head prosan-tool-head">
    <div>
        <p class="admin-kicker">Customer Demand</p>
        <h1>Product Requests</h1>
        <p class="text-muted">Track products customers want you to source, then update status and contact them quickly.</p>
    </div>
    <a class="btn" href="<?php echo e(route('admin.product_requests.export', request()->query())); ?>">Export CSV</a>
</div>

<div class="prosan-request-stats">
    <div class="content-card prosan-request-stat"><span>Total</span><strong><?php echo e($summary['total']); ?></strong></div>
    <div class="content-card prosan-request-stat"><span>New</span><strong><?php echo e($summary['new']); ?></strong></div>
    <div class="content-card prosan-request-stat"><span>Checking</span><strong><?php echo e($summary['checking']); ?></strong></div>
    <div class="content-card prosan-request-stat"><span>Available Soon</span><strong><?php echo e($summary['available_soon']); ?></strong></div>
    <div class="content-card prosan-request-stat"><span>Completed</span><strong><?php echo e($summary['completed']); ?></strong></div>
</div>

<div class="content-card prosan-filter-card">
    <form method="GET" class="prosan-filter-grid product-request-filter-grid">
        <div>
            <label>Search</label>
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Name, phone, product, brand...">
        </div>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="">All Status</option>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php if(request('status') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="prosan-filter-actions">
            <button class="btn" type="submit">Filter</button>
            <a class="btn btn-light-outline" href="<?php echo e(route('admin.product_requests.index')); ?>">Reset</a>
        </div>
    </form>
</div>

<div class="table-card prosan-table-card">
    <div class="table-responsive">
        <table class="admin-table prosan-admin-table product-request-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Requested Product</th>
                    <th>Message</th>
                    <th>Status / Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e(optional($item->created_at)->format('d M Y')); ?></strong><br>
                            <small><?php echo e(optional($item->created_at)->format('h:i A')); ?></small><br>
                            <span class="badge-soft"><?php echo e($item->source ?: 'website'); ?></span>
                        </td>
                        <td>
                            <strong><?php echo e($item->customer_name); ?></strong><br>
                            <a href="tel:<?php echo e($item->phone); ?>"><?php echo e($item->phone); ?></a><br>
                            <?php if($item->email): ?><a href="mailto:<?php echo e($item->email); ?>"><?php echo e($item->email); ?></a><?php else: ?><small>Email: N/A</small><?php endif; ?>
                            <?php if($item->whatsapp_url): ?><br><a class="product-request-whatsapp" href="<?php echo e($item->whatsapp_url); ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a><?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo e($item->product_name); ?></strong><br>
                            <?php if($item->brand): ?><small>Brand: <?php echo e($item->brand); ?></small><br><?php endif; ?>
                            <?php if($item->quantity): ?><small>Qty: <?php echo e($item->quantity); ?></small><br><?php endif; ?>
                            <?php if($item->product_link): ?><a href="<?php echo e($item->product_link); ?>" target="_blank" rel="noopener noreferrer">Product Link</a><?php endif; ?>
                        </td>
                        <td><?php echo e($item->message ?: '—'); ?></td>
                        <td>
                            <form method="POST" action="<?php echo e(route('admin.product_requests.update', $item)); ?>" class="product-request-status-form">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <select name="status">
                                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>" <?php if($item->status === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <textarea name="admin_note" rows="2" placeholder="Admin note..."><?php echo e($item->admin_note); ?></textarea>
                                <button class="btn btn-small" type="submit">Update</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo e(route('admin.product_requests.destroy', $item)); ?>" onsubmit="return confirm('Delete this product request?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger-soft btn-small">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No product requests found yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3"><?php echo e($requests->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/admin/product_requests/index.blade.php ENDPATH**/ ?>