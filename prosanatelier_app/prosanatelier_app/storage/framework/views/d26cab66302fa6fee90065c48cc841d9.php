<?php $__env->startSection('title', $homeSettings['meta_title'] ?? 'Prosan Atelier - Food, Cosmetics & Cooking Essentials'); ?>
<?php $__env->startSection('meta_description', $homeSettings['meta_description'] ?? 'Shop Korean, Thai, Chinese food items, cooking essentials, skincare and cosmetics at Prosan Atelier.'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $hs = $homeSettings ?? [];
    $homeShow = fn (string $key, string $default = '1') => (($hs[$key] ?? $default) === '1');
    $homeText = fn (string $key, string $default = '') => trim((string) ($hs[$key] ?? $default));
    $homeImage = fn (string $key, string $default) => \App\Models\SiteSetting::imageUrl($hs[$key] ?? $default, $default);
    $homeUrl = fn (string $key, string $fallback) => trim((string) ($hs[$key] ?? '')) !== '' ? trim((string) $hs[$key]) : $fallback;
?>

<?php if($homeShow('homepage_show_hero')): ?>
<?php
    $heroLayout = $homeText('homepage_hero_layout', 'full_width_image') ?: 'full_width_image';
    $heroLayout = in_array($heroLayout, ['full_width_image', 'contained_image', 'centered_overlay', 'split_banner_grid'], true) ? $heroLayout : 'full_width_image';
    $heroAlignment = $homeText('homepage_hero_alignment', $heroLayout === 'centered_overlay' ? 'center' : 'left') ?: 'left';
    if ($heroLayout === 'centered_overlay') { $heroAlignment = 'center'; }
    $heroAlignment = in_array($heroAlignment, ['left', 'center', 'right'], true) ? $heroAlignment : 'left';
    $heroBgColor = $homeText('homepage_hero_bg_color', '#f6efe5') ?: '#f6efe5';
    $heroTextColor = $homeText('homepage_hero_text_color', '#1d1d1f') ?: '#1d1d1f';
    $heroOverlayColor = $homeText('homepage_hero_overlay_color', '#000000') ?: '#000000';
    $heroOverlayOpacity = (int) ($hs['homepage_hero_overlay_opacity'] ?? 15);
    $heroOverlayOpacity = max(0, min(90, $heroOverlayOpacity)) / 100;
    $heroHeight = $homeText('homepage_hero_height', '560px') ?: '560px';
    $heroBgImage = $homeImage('homepage_hero_bg_image', $homeText('homepage_hero_1_image', 'foodmart/images/prosan-hero-food-beauty.svg'));
?>

<?php if(in_array($heroLayout, ['full_width_image', 'contained_image', 'centered_overlay'], true)): ?>
<section class="prosan-hero-v37 prosan-hero-v37--<?php echo e($heroLayout); ?> prosan-hero-v37--align-<?php echo e($heroAlignment); ?>" style="--hero-bg-color: <?php echo e($heroBgColor); ?>; --hero-text-color: <?php echo e($heroTextColor); ?>; --hero-height: <?php echo e($heroHeight); ?>; background-image: url('<?php echo e($heroBgImage); ?>');">
    <div class="prosan-hero-v37__overlay" style="background: <?php echo e($heroOverlayColor); ?>; opacity: <?php echo e($heroOverlayOpacity); ?>;"></div>
    <div class="<?php echo e($heroLayout === 'contained_image' ? 'container' : 'container-fluid'); ?> prosan-hero-v37__container">
        <div class="prosan-hero-v37__content">
            <?php if($homeText('homepage_hero_1_kicker', 'Curated Asian Food')): ?>
                <div class="prosan-hero-v37__kicker"><?php echo e($homeText('homepage_hero_1_kicker', 'Curated Asian Food')); ?></div>
            <?php endif; ?>
            <h1><?php echo e($homeText('homepage_hero_1_title', 'Korean, Thai & Chinese food essentials')); ?></h1>
            <p><?php echo e($homeText('homepage_hero_1_text', 'Ramen, seaweed, kimchi, coffee, snacks, rice, sauce and daily pantry picks for your home.')); ?></p>
            <?php if($homeText('homepage_hero_1_button_text', 'Shop Food')): ?>
                <a href="<?php echo e($homeUrl('homepage_hero_1_button_url', route('shop.index'))); ?>" class="prosan-hero-v37__button"><?php echo e($homeText('homepage_hero_1_button_text', 'Shop Food')); ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php else: ?>
<section class="py-3" style="background-image: url('<?php echo e(asset('foodmart/images/background-pattern.jpg')); ?>');background-repeat: no-repeat;background-size: cover;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="banner-blocks">
                    <div class="banner-ad large bg-info block-1">
                        <div class="swiper main-swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="row banner-content p-5">
                                        <div class="content-wrapper col-md-7">
                                            <div class="categories my-3"><?php echo e($homeText('homepage_hero_1_kicker', 'Curated Asian Food')); ?></div>
                                            <h3 class="display-4"><?php echo e($homeText('homepage_hero_1_title', 'Korean, Thai & Chinese food essentials')); ?></h3>
                                            <p><?php echo e($homeText('homepage_hero_1_text', 'Ramen, seaweed, kimchi, coffee, snacks, rice, sauce and daily pantry picks for your home.')); ?></p>
                                            <a href="<?php echo e($homeUrl('homepage_hero_1_button_url', route('shop.index', ['category'=>'ramen']))); ?>" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3"><?php echo e($homeText('homepage_hero_1_button_text', 'Shop Food')); ?></a>
                                        </div>
                                        <div class="img-wrapper col-md-5">
                                            <img src="<?php echo e($homeImage('homepage_hero_1_image', 'foodmart/images/prosan-hero-food-beauty.svg')); ?>" class="img-fluid" alt="<?php echo e($homeText('homepage_hero_1_title', 'Asian food and cosmetics')); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="row banner-content p-5">
                                        <div class="content-wrapper col-md-7">
                                            <div class="categories my-3"><?php echo e($homeText('homepage_hero_2_kicker', 'Beauty & Care')); ?></div>
                                            <h3 class="display-4"><?php echo e($homeText('homepage_hero_2_title', 'Skincare and cosmetics thoughtfully chosen')); ?></h3>
                                            <p><?php echo e($homeText('homepage_hero_2_text', 'Cleanser, essence, sun care and daily cosmetic products from trusted brands.')); ?></p>
                                            <a href="<?php echo e($homeUrl('homepage_hero_2_button_url', route('shop.index', ['category'=>'cosmetics']))); ?>" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3"><?php echo e($homeText('homepage_hero_2_button_text', 'Shop Beauty')); ?></a>
                                        </div>
                                        <div class="img-wrapper col-md-5">
                                            <img src="<?php echo e($homeImage('homepage_hero_2_image', 'foodmart/images/prosan-ad-beauty.svg')); ?>" class="img-fluid" alt="<?php echo e($homeText('homepage_hero_2_title', 'Beauty care')); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <div class="banner-ad bg-success-subtle block-2" style="background:url('<?php echo e($homeImage('homepage_promo_1_image', 'foodmart/images/prosan-ad-kfood.svg')); ?>') no-repeat;background-position: right bottom;background-size: 62%;">
                        <div class="row banner-content p-5">
                            <div class="content-wrapper col-md-7">
                                <div class="categories sale mb-3 pb-3"><?php echo e($homeText('homepage_promo_1_label', 'Popular')); ?></div>
                                <h3 class="banner-title"><?php echo e($homeText('homepage_promo_1_title', 'Korean Food')); ?></h3>
                                <a href="<?php echo e($homeUrl('homepage_promo_1_button_url', route('shop.index', ['category'=>'ramen']))); ?>" class="d-flex align-items-center nav-link"><?php echo e($homeText('homepage_promo_1_button_text', 'Shop Collection')); ?> <svg width="24" height="24"><use xlink:href="#arrow-right"></use></svg></a>
                            </div>
                        </div>
                    </div>
                    <div class="banner-ad bg-danger block-3" style="background:url('<?php echo e($homeImage('homepage_promo_2_image', 'foodmart/images/prosan-ad-cooking.svg')); ?>') no-repeat;background-position: right bottom;background-size: 62%;">
                        <div class="row banner-content p-5">
                            <div class="content-wrapper col-md-7">
                                <div class="categories sale mb-3 pb-3"><?php echo e($homeText('homepage_promo_2_label', 'Essentials')); ?></div>
                                <h3 class="item-title"><?php echo e($homeText('homepage_promo_2_title', 'Cooking Essentials')); ?></h3>
                                <a href="<?php echo e($homeUrl('homepage_promo_2_button_url', route('shop.index', ['category'=>'cooking-essentials']))); ?>" class="d-flex align-items-center nav-link"><?php echo e($homeText('homepage_promo_2_button_text', 'Shop Collection')); ?> <svg width="24" height="24"><use xlink:href="#arrow-right"></use></svg></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<?php endif; ?>

<?php if($homeShow('homepage_show_categories')): ?>
<section class="py-5 overflow-hidden">
    <div class="container-fluid">
        <div class="row"><div class="col-md-12"><div class="section-header d-flex flex-wrap justify-content-between mb-5"><h2 class="section-title"><?php echo e($homeText('homepage_category_title', 'Category')); ?></h2><div class="d-flex align-items-center"><a href="<?php echo e(route('shop.index')); ?>" class="btn-link text-decoration-none">View All Categories →</a><div class="swiper-buttons"><button class="swiper-prev category-carousel-prev btn btn-yellow">❮</button><button class="swiper-next category-carousel-next btn btn-yellow">❯</button></div></div></div></div></div>
        <div class="row"><div class="col-md-12"><div class="category-carousel swiper"><div class="swiper-wrapper">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('category.show', $category->slug)); ?>" class="nav-link category-item swiper-slide"><span class="category-icon-frame"><img src="<?php echo e($category->image_url); ?>" alt="<?php echo e($category->name); ?>"></span><h3 class="category-title"><?php echo e($category->name); ?></h3><span class="category-subtitle"><?php echo e($category->children->count()); ?> Sub Categories</span></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div></div></div></div>
    </div>
</section>
<?php endif; ?>

<?php if($homeShow('homepage_show_brands')): ?>
<section class="py-5 overflow-hidden">
    <div class="container-fluid">
        <div class="row"><div class="col-md-12"><div class="section-header d-flex flex-wrap justify-content-between mb-5"><h2 class="section-title"><?php echo e($homeText('homepage_brands_title', 'Newly Arrived Brands')); ?></h2><div class="d-flex align-items-center"><a href="<?php echo e(route('shop.index')); ?>" class="btn-link text-decoration-none">View All Brands →</a><div class="swiper-buttons"><button class="swiper-prev brand-carousel-prev btn btn-yellow">❮</button><button class="swiper-next brand-carousel-next btn btn-yellow">❯</button></div></div></div></div></div>
        <div class="row"><div class="col-md-12"><div class="brand-carousel swiper"><div class="swiper-wrapper">
            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide"><a href="<?php echo e(route('brand.show', $brand->slug)); ?>" class="brand-card-dynamic"><span class="brand-logo-frame"><img src="<?php echo e($brand->logo_url); ?>" alt="<?php echo e($brand->name); ?>"></span><span class="brand-copy"><small>Brand</small><strong><?php echo e($brand->name); ?></strong><em>Shop now →</em></span></a></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div></div></div></div>
    </div>
</section>
<?php endif; ?>

<?php if($homeShow('homepage_show_trending')): ?>
<section class="py-5">
    <div class="container-fluid">
        <div class="bootstrap-tabs product-tabs">
            <div class="tabs-header d-flex justify-content-between border-bottom my-5"><h3><?php echo e($homeText('homepage_trending_title', 'Trending Products')); ?></h3><nav><div class="nav nav-tabs" id="nav-tab" role="tablist"><a href="#" class="nav-link text-uppercase fs-6 active" data-bs-toggle="tab" data-bs-target="#nav-all">All</a><a href="#" class="nav-link text-uppercase fs-6" data-bs-toggle="tab" data-bs-target="#nav-food">Food</a><a href="#" class="nav-link text-uppercase fs-6" data-bs-toggle="tab" data-bs-target="#nav-beauty">Beauty</a></div></nav></div>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-all" role="tabpanel"><div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5"><?php $__currentLoopData = $popularProducts->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="col"><?php echo $__env->make('partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></div>
                <div class="tab-pane fade" id="nav-food" role="tabpanel"><div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5"><?php $__currentLoopData = $popularProducts->filter(fn($p) => !str_contains(strtolower($p->category->name ?? ''), 'skin'))->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="col"><?php echo $__env->make('partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></div>
                <div class="tab-pane fade" id="nav-beauty" role="tabpanel"><div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5"><?php $__empty_1 = true; $__currentLoopData = $beautyProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="col"><?php echo $__env->make('partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="col-12"><div class="alert alert-light border">No beauty products are available right now.</div></div><?php endif; ?></div></div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if($homeShow('homepage_show_best_selling')): ?>
<section class="py-5 overflow-hidden"><div class="container-fluid"><div class="row"><div class="col-md-12"><div class="section-header d-flex flex-wrap justify-content-between my-5"><h2 class="section-title"><?php echo e($homeText('homepage_best_selling_title', 'Best selling products')); ?></h2><div class="d-flex align-items-center"><a href="<?php echo e(route('shop.index')); ?>" class="btn-link text-decoration-none">View All Products →</a><div class="swiper-buttons"><button class="swiper-prev products-carousel-prev btn btn-primary">❮</button><button class="swiper-next products-carousel-next btn btn-primary">❯</button></div></div></div></div></div><div class="row"><div class="col-md-12"><div class="products-carousel swiper"><div class="swiper-wrapper">
<?php $__currentLoopData = $bestSellerProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo $__env->make('partials.product-card', ['product' => $product, 'carousel' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div></div></div></div></div></section>
<?php endif; ?>

<?php if($homeShow('homepage_show_offer')): ?>
<section class="py-5 product-request-section-wrap">
    <div class="container-fluid">
        <div class="product-request-section" style="background-image: url('<?php echo e(asset('foodmart/images/bg-leaves-img-pattern.png')); ?>');">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <span class="product-request-kicker">Product Request</span>
                    <h2><?php echo e($homeText('homepage_offer_title', 'Can’t find your product?')); ?></h2>
                    <p><?php echo e($homeText('homepage_offer_text', 'Request your favorite Korean, Thai or Asian product. We’ll try to source it for you.')); ?></p>
                    <div class="product-request-points">
                        <span>✓ Korean food</span>
                        <span>✓ Beauty & skincare</span>
                        <span>✓ Cooking essentials</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form method="POST" action="<?php echo e(route('product_requests.store')); ?>" class="product-request-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="source" value="homepage">
                        <div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
                            <label>Website</label>
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="product-request-grid">
                            <div>
                                <label>Name *</label>
                                <input type="text" name="customer_name" value="<?php echo e(old('customer_name')); ?>" placeholder="Your name" required>
                            </div>
                            <div>
                                <label>Phone *</label>
                                <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="01XXXXXXXXX" required>
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="name@gmail.com" pattern="^[^@\s]+@[A-Za-z0-9.-]+\.com$" title="Only Gmail or other .com email addresses are accepted.">
                                <small>Only Gmail or other .com email addresses are accepted.</small>
                            </div>
                            <div>
                                <label>Requested Product *</label>
                                <input type="text" name="product_name" value="<?php echo e(old('product_name')); ?>" placeholder="Product name" required>
                            </div>
                            <div>
                                <label>Brand</label>
                                <input type="text" name="brand" value="<?php echo e(old('brand')); ?>" placeholder="Brand if known">
                            </div>
                            <div>
                                <label>Quantity</label>
                                <input type="number" min="1" name="quantity" value="<?php echo e(old('quantity')); ?>" placeholder="1">
                            </div>
                            <div class="product-request-wide">
                                <label>Product Link</label>
                                <input type="text" name="product_link" value="<?php echo e(old('product_link')); ?>" placeholder="Optional link from another website/social post">
                            </div>
                            <div class="product-request-wide">
                                <label>Message</label>
                                <textarea name="message" rows="3" placeholder="Size, flavor, image reference, or any details..."><?php echo e(old('message')); ?></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn product-request-submit">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if($homeShow('homepage_show_new_arrivals')): ?>
<section class="py-5 overflow-hidden"><div class="container-fluid"><div class="row"><div class="col-md-12"><div class="section-header d-flex justify-content-between"><h2 class="section-title"><?php echo e($homeText('homepage_new_arrivals_title', 'New arrivals')); ?></h2><div class="d-flex align-items-center"><a href="<?php echo e(route('shop.index')); ?>" class="btn-link text-decoration-none">View All Products →</a><div class="swiper-buttons"><button class="swiper-prev products-carousel-prev btn btn-primary">❮</button><button class="swiper-next products-carousel-next btn btn-primary">❯</button></div></div></div></div></div><div class="row"><div class="col-md-12"><div class="products-carousel swiper"><div class="swiper-wrapper">
<?php $__currentLoopData = $newProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo $__env->make('partials.product-card', ['product' => $product, 'carousel' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div></div></div></div></div></section>
<?php endif; ?>

<?php if($homeShow('homepage_show_services')): ?>
<section class="py-5 prosan-service-section"><div class="container-fluid"><div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-4">
    <div class="col"><div class="prosan-service-card service-delivery"><div class="service-icon"><svg width="32" height="32"><use xlink:href="#cart"></use></svg></div><h5>Free Delivery</h5><p>Available on selected order values and campaign offers.</p></div></div>
    <div class="col"><div class="prosan-service-card service-payment"><div class="service-icon"><svg width="32" height="32"><use xlink:href="#check"></use></svg></div><h5>100% Secure Payment</h5><p>Safe checkout flow ready for online payment integration.</p></div></div>
    <div class="col"><div class="prosan-service-card service-quality"><div class="service-icon"><svg width="32" height="32"><use xlink:href="#star-solid"></use></svg></div><h5>Quality Guarantee</h5><p>Curated food, cosmetics and cooking essentials.</p></div></div>
    <div class="col"><div class="prosan-service-card service-savings"><div class="service-icon"><svg width="32" height="32"><use xlink:href="#tag"></use></svg></div><h5>Guaranteed Savings</h5><p>Smart pricing, bundle deals and seasonal discounts.</p></div></div>
    <div class="col"><div class="prosan-service-card service-offers"><div class="service-icon"><svg width="32" height="32"><use xlink:href="#calendar"></use></svg></div><h5>Daily Offers</h5><p>New deals on ramen, skincare and daily essentials.</p></div></div>
</div></div></section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/frontend/home.blade.php ENDPATH**/ ?>