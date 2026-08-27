<?php $__env->startSection('title', 'Shop - Prosan Atelier'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $currentCategorySlug = request('category');
    $flatCategories = collect();

    foreach ($categories as $category) {
        $flatCategories->push($category);
        foreach (($category->children ?? collect()) as $child) {
            $flatCategories->push($child);
        }
    }
?>

<section class="shop-hero-prosan">
    <div class="container-fluid">
        <div class="shop-hero-prosan__inner">
            <span>Shop Collection</span>
            <h1>Shop Products</h1>
            <p>Korean, Thai, Chinese food items, cooking essentials and cosmetics.</p>
        </div>
    </div>
</section>

<section class="shop-category-strip-section">
    <div class="container-fluid">
        <div class="shop-category-strip-head">
            <div>
                <span>Browse faster</span>
                <h2>Shop by Category</h2>
            </div>
            <a href="<?php echo e(route('shop.index', request()->except(['category', 'page']))); ?>">View all products</a>
        </div>

        <div class="shop-category-scroller" aria-label="Shop categories">
            <a class="shop-category-chip <?php echo e(! $currentCategorySlug ? 'active' : ''); ?>" href="<?php echo e(route('shop.index', request()->except(['category', 'page']))); ?>">
                <span class="shop-category-chip-icon">🛒</span>
                <strong>All Products</strong>
            </a>

            <?php $__currentLoopData = $flatCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $categoryParams = array_merge(request()->except(['page']), ['category' => $category->slug]);
                ?>
                <a class="shop-category-chip <?php echo e($currentCategorySlug === $category->slug ? 'active' : ''); ?>" href="<?php echo e(route('shop.index', $categoryParams)); ?>">
                    <span class="shop-category-chip-icon">
                        <img src="<?php echo e($category->image_url); ?>" alt="<?php echo e($category->name); ?>">
                    </span>
                    <strong><?php echo e($category->name); ?></strong>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<section class="shop-main-section">
    <div class="container-fluid">
        <div class="row g-5">
            <aside class="col-lg-3">
                <div class="shop-sidebar foodmart-sidebar">
                    <h4>Filter Products</h4>
                    <form action="<?php echo e(route('shop.index')); ?>" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input class="form-control form-control-lg" type="search" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search product">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select form-select-lg" name="category">
                                <option value="">All Categories</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->slug); ?>" <?php if(request('category') === $category->slug): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($child->slug); ?>" <?php if(request('category') === $child->slug): echo 'selected'; endif; ?>>— <?php echo e($child->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select class="form-select form-select-lg" name="brand">
                                <option value="">All Brands</option>
                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($brand->slug); ?>" <?php if(request('brand') === $brand->slug): echo 'selected'; endif; ?>><?php echo e($brand->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sort</label>
                            <select class="form-select form-select-lg" name="sort">
                                <option value="">Latest</option>
                                <option value="price_low" <?php if(request('sort') === 'price_low'): echo 'selected'; endif; ?>>Price: Low to High</option>
                                <option value="price_high" <?php if(request('sort') === 'price_high'): echo 'selected'; endif; ?>>Price: High to Low</option>
                            </select>
                        </div>

                        <button class="btn btn-primary btn-lg w-100" type="submit">Apply Filter</button>
                        <a class="btn btn-outline-dark btn-lg w-100 mt-2" href="<?php echo e(route('shop.index')); ?>">Reset</a>
                    </form>
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="tabs-header shop-products-header d-flex justify-content-between border-bottom mb-5">
                    <div>
                        <h3 class="mb-0"><?php echo e($products->total()); ?> Products Found</h3>
                    </div>
                </div>

                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col mb-4"><?php echo $__env->make('partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12"><div class="alert alert-warning">No products found.</div></div>
                    <?php endif; ?>
                </div>

                <div class="pagination-wrap mt-5"><?php echo e($products->links()); ?></div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/frontend/shop.blade.php ENDPATH**/ ?>