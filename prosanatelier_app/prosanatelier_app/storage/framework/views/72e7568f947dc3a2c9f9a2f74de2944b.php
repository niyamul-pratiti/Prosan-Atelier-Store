<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $__env->yieldContent('title', $siteSettings['site_name'] ?? 'Prosan Atelier'); ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', $siteSettings['meta_description'] ?? 'Prosan Atelier - Korean, Thai, Chinese food items, cooking essentials and cosmetics.'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" onerror="this.remove()">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('foodmart/css/vendor.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('foodmart/style.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('foodmart/css/prosan-foodmart.css')); ?>?v=20260729-1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        .footer-socials {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 16px;
        }
        .footer-social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(172, 127, 46, 0.12);
            color: #8a6425;
            border: 1px solid rgba(172, 127, 46, 0.22);
            transition: all .2s ease;
            text-decoration: none !important;
        }
        .footer-social-icon svg {
            width: 18px;
            height: 18px;
            display: block;
        }
        .footer-social-icon:hover {
            background: #ac7f2e;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(172, 127, 46, .22);
        }
        @media (max-width: 767px) {
            .footer-socials {
                margin-bottom: 18px;
            }
        }
    </style>
</head>
<body>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <defs>
        <symbol xmlns="http://www.w3.org/2000/svg" id="link" viewBox="0 0 24 24">
          <path fill="currentColor" d="M12 19a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0-4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm-5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm7-12h-1V2a1 1 0 0 0-2 0v1H8V2a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 17a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V6a1 1 0 0 1 1-1h1v1a1 1 0 0 0 2 0V5h8v1a1 1 0 0 0 2 0V5h1a1 1 0 0 1 1 1ZM7 15a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="arrow-right" viewBox="0 0 24 24">
          <path fill="currentColor" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="category" viewBox="0 0 24 24">
          <path fill="currentColor" d="M19 5.5h-6.28l-.32-1a3 3 0 0 0-2.84-2H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-10a3 3 0 0 0-3-3Zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1h4.56a1 1 0 0 1 .95.68l.54 1.64a1 1 0 0 0 .95.68h7a1 1 0 0 1 1 1Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="calendar" viewBox="0 0 24 24">
          <path fill="currentColor" d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="heart" viewBox="0 0 24 24">
          <path fill="currentColor" d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="plus" viewBox="0 0 24 24">
          <path fill="currentColor" d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="minus" viewBox="0 0 24 24">
          <path fill="currentColor" d="M19 11H5a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24">
          <path fill="currentColor" d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="check" viewBox="0 0 24 24">
          <path fill="currentColor" d="M18.71 7.21a1 1 0 0 0-1.42 0l-7.45 7.46l-3.13-3.14A1 1 0 1 0 5.29 13l3.84 3.84a1 1 0 0 0 1.42 0l8.16-8.16a1 1 0 0 0 0-1.47Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="trash" viewBox="0 0 24 24">
          <path fill="currentColor" d="M10 18a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1ZM20 6h-4V5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2ZM10 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm7 14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8h10Zm-3-1a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="tag" viewBox="0 0 24 24">
          <path fill="currentColor" d="M21.41 11.58l-9-9A2 2 0 0 0 11 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 .59 1.41l9 9a2 2 0 0 0 2.82 0l7-7a2 2 0 0 0 0-2.83ZM6.5 8A1.5 1.5 0 1 1 8 6.5A1.5 1.5 0 0 1 6.5 8Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="star-outline" viewBox="0 0 15 15">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.804L5.337 11l.413-2.533L4 6.674l2.418-.37L7.5 4l1.082 2.304l2.418.37l-1.75 1.793L9.663 11L7.5 9.804Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="star-solid" viewBox="0 0 15 15">
          <path fill="currentColor" d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="search" viewBox="0 0 24 24">
          <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24">
          <path fill="currentColor" d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 15 15">
          <path fill="currentColor" d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z"/>
        </symbol>

        <symbol xmlns="http://www.w3.org/2000/svg" id="social-facebook" viewBox="0 0 24 24">
          <path fill="currentColor" d="M22 12.07C22 6.48 17.52 2 11.93 2S2 6.48 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.54V9.85c0-2.5 1.49-3.89 3.77-3.89c1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.62.77-1.62 1.56v1.9h2.76l-.44 2.9h-2.32V22C18.34 21.25 22 17.09 22 12.07Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="social-instagram" viewBox="0 0 24 24">
          <path fill="currentColor" d="M7.8 2h8.4A5.8 5.8 0 0 1 22 7.8v8.4A5.8 5.8 0 0 1 16.2 22H7.8A5.8 5.8 0 0 1 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2Zm-.2 2A3.6 3.6 0 0 0 4 7.6v8.8A3.6 3.6 0 0 0 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6A3.6 3.6 0 0 0 16.4 4H7.6Zm9.65 1.5a1.25 1.25 0 1 1 0 2.5a1.25 1.25 0 0 1 0-2.5ZM12 7a5 5 0 1 1 0 10a5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6a3 3 0 0 0 0-6Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="social-youtube" viewBox="0 0 24 24">
          <path fill="currentColor" d="M21.58 7.19a2.72 2.72 0 0 0-1.91-1.93C17.98 4.8 12 4.8 12 4.8s-5.98 0-7.67.46a2.72 2.72 0 0 0-1.91 1.93C2 8.9 2 12.47 2 12.47s0 3.57.42 5.28a2.72 2.72 0 0 0 1.91 1.93c1.69.46 7.67.46 7.67.46s5.98 0 7.67-.46a2.72 2.72 0 0 0 1.91-1.93c.42-1.71.42-5.28.42-5.28s0-3.57-.42-5.28ZM10 15.75V9.2l5.25 3.27L10 15.75Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="social-tiktok" viewBox="0 0 24 24">
          <path fill="currentColor" d="M16.6 5.82a5.5 5.5 0 0 0 3.46 1.23v3.1a8.64 8.64 0 0 1-3.46-.74v5.34A6.24 6.24 0 1 1 10.36 8.5c.42 0 .84.04 1.24.13v3.2a3.15 3.15 0 1 0 2.18 3v-12h2.82v3Z"/>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="social-whatsapp" viewBox="0 0 24 24">
          <path fill="currentColor" d="M19.05 4.95A9.86 9.86 0 0 0 12.04 2a9.94 9.94 0 0 0-8.6 14.9L2 22l5.25-1.38A9.9 9.9 0 0 0 12.04 22h.01A9.95 9.95 0 0 0 22 12.05a9.88 9.88 0 0 0-2.95-7.1Zm-7 15.35h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18l-3.11.82l.83-3.03l-.2-.31a8.17 8.17 0 1 1 6.97 3.85Zm4.48-6.14c-.25-.13-1.45-.72-1.68-.8c-.22-.08-.39-.13-.55.13c-.16.25-.63.8-.77.96c-.14.17-.28.19-.52.06c-.25-.13-1.04-.38-1.98-1.22c-.73-.65-1.22-1.45-1.37-1.7c-.14-.25-.02-.38.11-.51c.11-.11.25-.28.37-.42c.12-.14.16-.25.25-.41c.08-.17.04-.31-.02-.43c-.06-.13-.55-1.33-.75-1.82c-.2-.48-.4-.41-.55-.42h-.47c-.16 0-.43.06-.65.31c-.22.25-.85.83-.85 2.02s.87 2.34.99 2.5c.12.17 1.71 2.62 4.14 3.67c.58.25 1.03.4 1.38.51c.58.18 1.1.16 1.52.1c.46-.07 1.45-.59 1.65-1.16c.2-.57.2-1.06.14-1.16c-.06-.1-.22-.16-.47-.29Z"/>
        </symbol>
      </defs>
    </svg>
<?php
    $cart = session('cart', []);
    $cartCount = collect($cart)->sum('quantity');
    $cartTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    $menuCategories = $navCategories ?? ($categories ?? collect());
    $menuBrands = $navBrands ?? ($brands ?? collect());
    $storeSiteName = $siteSettings['site_name'] ?? 'Prosan Atelier';
    $storeTagline = $siteSettings['site_tagline'] ?? 'Everyday essentials, thoughtfully chosen.';
    $storeLogoUrl = \App\Models\SiteSetting::imageUrl($siteSettings['site_logo'] ?? null);
    $supportPhone = $siteSettings['support_phone'] ?? '01410283178';
    $supportEmail = $siteSettings['support_email'] ?? 'hello@prosanatelier.com';
    $footerText = $siteSettings['footer_text'] ?? 'Everyday essentials, thoughtfully chosen. Korean, Thai and Chinese food items, cooking essentials and cosmetics.';
    $footerCopyright = str_replace('{year}', date('Y'), $siteSettings['footer_copyright'] ?? '© {year} Prosan Atelier. All rights reserved.');
    $footerCreditText = $siteSettings['footer_credit_text'] ?? 'Md Niyamul Pratiti';
    $footerCreditUrl = $siteSettings['footer_credit_url'] ?? 'https://niyamulpratiti.com';

    $facebookUrl = $siteSettings['facebook_url'] ?? '';
    $instagramUrl = $siteSettings['instagram_url'] ?? '';
    $youtubeUrl = $siteSettings['youtube_url'] ?? '';
    $tiktokUrl = $siteSettings['tiktok_url'] ?? '';
    $whatsappUrl = $siteSettings['whatsapp_url'] ?? '';
    if (! $whatsappUrl && $supportPhone) {
        $whatsappDigits = preg_replace('/\D+/', '', $supportPhone);
        if (str_starts_with($whatsappDigits, '0')) {
            $whatsappDigits = '88' . substr($whatsappDigits, 1);
        } elseif ($whatsappDigits && ! str_starts_with($whatsappDigits, '88')) {
            $whatsappDigits = '88' . $whatsappDigits;
        }
        $whatsappUrl = $whatsappDigits ? 'https://wa.me/' . $whatsappDigits : '';
    }
    $footerSocials = collect([
        ['key' => 'facebook', 'label' => 'Facebook', 'url' => $facebookUrl, 'icon' => 'social-facebook'],
        ['key' => 'instagram', 'label' => 'Instagram', 'url' => $instagramUrl, 'icon' => 'social-instagram'],
        ['key' => 'youtube', 'label' => 'YouTube', 'url' => $youtubeUrl, 'icon' => 'social-youtube'],
        ['key' => 'tiktok', 'label' => 'TikTok', 'url' => $tiktokUrl, 'icon' => 'social-tiktok'],
        ['key' => 'whatsapp', 'label' => 'WhatsApp', 'url' => $whatsappUrl, 'icon' => 'social-whatsapp'],
    ])->filter(fn($item) => ! empty($item['url']));
?>
<div class="preloader-wrapper"><div class="preloader"></div></div>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart" aria-labelledby="My Cart">
    <div class="offcanvas-header justify-content-end prosan-offcanvas-close-row"><button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button></div>
    <div class="offcanvas-body" id="prosan-cart-panel-body">
        <?php echo $__env->make('partials.cart-offcanvas-body', ['cart' => $cart, 'cartCount' => $cartCount, 'cartTotal' => $cartTotal], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasSearch" aria-labelledby="Search">
    <div class="offcanvas-header justify-content-end prosan-offcanvas-close-row"><button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button></div>
    <div class="offcanvas-body"><div class="order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3"><span class="text-primary">Search</span></h4>
        <form role="search" action="<?php echo e(route('shop.index')); ?>" method="get" class="d-flex mt-3 gap-0">
            <input class="form-control rounded-start rounded-0 bg-light" name="q" type="search" placeholder="Ramen, cleanser, coffee..." aria-label="Search">
            <button class="btn btn-dark rounded-end rounded-0" type="submit">Search</button>
        </form>
    </div></div>
</div>

<header class="prosan-site-header">
    <div class="container-fluid">
        <div class="row py-3 border-bottom align-items-center prosan-header-main">
            <div class="col-6 col-lg-3 prosan-logo-col">
                <div class="main-logo prosan-main-logo"><a href="<?php echo e(route('home')); ?>"><img src="<?php echo e($storeLogoUrl); ?>" alt="<?php echo e($storeSiteName); ?>" class="img-fluid"></a></div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="search-bar row bg-light p-2 my-2 rounded-4">
                    <div class="col-md-4 d-none d-md-block">
                        <select class="form-select border-0 bg-transparent" name="category" form="search-form">
                            <option value="">All Categories</option>
                            <?php $__currentLoopData = $menuCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $headerCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($headerCategory->slug); ?>" <?php if(request('category') === $headerCategory->slug): echo 'selected'; endif; ?>><?php echo e($headerCategory->name); ?></option>
                                <?php $__currentLoopData = $headerCategory->children ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($child->slug); ?>" <?php if(request('category') === $child->slug): echo 'selected'; endif; ?>>— <?php echo e($child->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-11 col-md-7">
                        <form id="search-form" class="text-center" action="<?php echo e(route('shop.index')); ?>" method="get">
                            <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control border-0 bg-transparent" placeholder="Search Korean food, cosmetics, cooking essentials" />
                        </form>
                    </div>
                    <div class="col-1"><button class="btn p-0" form="search-form" type="submit"><svg width="24" height="24"><use xlink:href="#search"></use></svg></button></div>
                </div>
            </div>
            <div class="col-6 col-lg-4 d-flex justify-content-end align-items-center prosan-header-actions">
                <div class="support-box text-end d-none d-xl-block"><span class="fs-6 text-muted">For Support?</span><h5 class="mb-0"><?php echo e($supportPhone); ?></h5></div>
                <ul class="header-icon-list d-flex justify-content-end align-items-center list-unstyled m-0">
                    <li><a href="<?php echo e(session('customer_id') ? route('customer.dashboard') : route('customer.login')); ?>" class="prosan-circle-icon" title="My Account"><svg width="24" height="24"><use xlink:href="#user"></use></svg></a></li>
                    <li><a href="<?php echo e(route('order.tracking')); ?>" class="prosan-circle-icon" title="Track Order"><svg width="24" height="24"><use xlink:href="#link"></use></svg></a></li>
                    <li class="d-lg-none"><a href="#" class="prosan-circle-icon prosan-cart-trigger" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" title="Cart"><svg width="24" height="24"><use xlink:href="#cart"></use></svg><span class="cart-count-badge" data-cart-count><?php echo e($cartCount); ?></span></a></li>
                    <li class="d-lg-none"><a href="#" class="prosan-circle-icon" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch" title="Search"><svg width="24" height="24"><use xlink:href="#search"></use></svg></a></li>
                    <li class="d-lg-none"><button type="button" class="prosan-circle-icon mobile-menu-trigger" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-label="Open menu"><span></span><span></span><span></span></button></li>
                </ul>
                <div class="cart text-end d-none d-lg-block dropdown ms-4">
                    <button class="border-0 bg-transparent d-flex flex-column gap-2 lh-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                        <span class="fs-6 text-muted dropdown-toggle">Your Cart <span class="cart-count-inline" data-cart-count><?php echo e($cartCount); ?></span></span><span class="cart-total fs-5 fw-bold" data-cart-total>৳<?php echo e(number_format($cartTotal, 0)); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid nav-wrap-prosan d-none d-lg-block">
        <div class="prosan-desktop-nav py-3">
            <div class="department-dropdown">
                <a class="filter-categories border-0 mb-0 d-inline-flex align-items-center" href="<?php echo e(route('shop.index')); ?>">Shop by Departments <svg width="18" height="18" class="ms-2"><use xlink:href="#arrow-right"></use></svg></a>
                <div class="department-mega shadow-lg">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <h5>Browse Categories</h5>
                            <div class="mega-grid-foodmart">
                                <?php $__currentLoopData = $menuCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $megaCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mega-item-foodmart">
                                        <a href="<?php echo e(route('category.show', $megaCategory->slug)); ?>" class="mega-title"><img src="<?php echo e($megaCategory->image_url); ?>" alt="<?php echo e($megaCategory->name); ?>"> <span><?php echo e($megaCategory->name); ?></span></a>
                                        <?php $__currentLoopData = ($megaCategory->children ?? collect())->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('category.show', $child->slug)); ?>"><?php echo e($child->name); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mega-offer-card">
                                <span>Fresh Picks</span><h4>Asian food + cosmetics in one place.</h4><a href="<?php echo e(route('shop.index')); ?>">Shop collection →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="prosan-desktop-menu navbar-nav menu-list list-unstyled mb-0">
                <li class="nav-item"><a href="<?php echo e(route('home')); ?>" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="<?php echo e(route('shop.index')); ?>" class="nav-link">Shop</a></li>
                <li class="nav-item"><a href="<?php echo e(route('shop.index', ['category' => 'ramen'])); ?>" class="nav-link">Ramen</a></li>
                <li class="nav-item"><a href="<?php echo e(route('shop.index', ['category' => 'cooking-essentials'])); ?>" class="nav-link">Cooking Essentials</a></li>
                <li class="nav-item"><a href="<?php echo e(route('shop.index', ['category' => 'cosmetics'])); ?>" class="nav-link">Cosmetics</a></li>
                <li class="nav-item"><a href="<?php echo e(route('order.tracking')); ?>" class="nav-link">Track Order</a></li>
                <li class="nav-item"><a href="<?php echo e(session('customer_id') ? route('customer.dashboard') : route('customer.login')); ?>" class="nav-link">My Account</a></li>
            </ul>
        </div>
    </div>

    <div class="offcanvas offcanvas-start prosan-mobile-offcanvas" tabindex="-1" id="offcanvasNavbar" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header mobile-offcanvas-head">
            <a href="<?php echo e(route('home')); ?>" class="mobile-offcanvas-logo"><img src="<?php echo e($storeLogoUrl); ?>" alt="<?php echo e($storeSiteName); ?>"></a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mobile-offcanvas-body">
            <a class="mobile-department-link" href="<?php echo e(route('shop.index')); ?>">Shop by Departments <span>→</span></a>
            <div class="mobile-category-list">
                <?php $__currentLoopData = $menuCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mobileCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('category.show', $mobileCategory->slug)); ?>"><img src="<?php echo e($mobileCategory->image_url); ?>" alt="<?php echo e($mobileCategory->name); ?>"><span><?php echo e($mobileCategory->name); ?></span></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <ul class="mobile-menu-list list-unstyled mb-0">
                <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
                <li><a href="<?php echo e(route('shop.index')); ?>">Shop</a></li>
                <li><a href="<?php echo e(route('shop.index', ['category' => 'ramen'])); ?>">Ramen</a></li>
                <li><a href="<?php echo e(route('shop.index', ['category' => 'cooking-essentials'])); ?>">Cooking Essentials</a></li>
                <li><a href="<?php echo e(route('shop.index', ['category' => 'cosmetics'])); ?>">Cosmetics</a></li>
                <li><a href="<?php echo e(route('order.tracking')); ?>">Track Order</a></li>
                <li><a href="<?php echo e(session('customer_id') ? route('customer.dashboard') : route('customer.login')); ?>">My Account</a></li>
                <?php if(session('customer_id')): ?><li><a href="<?php echo e(route('customer.wishlist')); ?>">Wishlist</a></li><?php endif; ?>
            </ul>
        </div>
    </div>
</header>


<main>
    <div class="container-fluid pt-3"><?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="py-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer-menu"><img src="<?php echo e($storeLogoUrl); ?>" alt="<?php echo e($storeSiteName); ?>" class="footer-logo-img"><p><?php echo e($footerText); ?></p><p><strong>Call/WhatsApp:</strong> <?php echo e($supportPhone); ?></p><?php if($footerSocials->isNotEmpty()): ?><div class="footer-socials" aria-label="Social links"><?php $__currentLoopData = $footerSocials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a href="<?php echo e($social['url']); ?>" class="footer-social-icon footer-social-<?php echo e($social['key']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo e($social['label']); ?>"><svg><use xlink:href="#<?php echo e($social['icon']); ?>"></use></svg><span class="visually-hidden"><?php echo e($social['label']); ?></span></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><?php endif; ?></div>
            </div>
            <div class="col-md-2 col-sm-6"><div class="footer-menu"><h5 class="widget-title">Shop</h5><ul class="menu-list list-unstyled"><li><a href="<?php echo e(route('shop.index')); ?>" class="nav-link">All Products</a></li><li><a href="<?php echo e(route('shop.index', ['category'=>'ramen'])); ?>" class="nav-link">Ramen</a></li><li><a href="<?php echo e(route('shop.index', ['category'=>'seaweed'])); ?>" class="nav-link">Seaweed</a></li><li><a href="<?php echo e(route('shop.index', ['category'=>'skin-care'])); ?>" class="nav-link">Skin Care</a></li></ul></div></div>
            <div class="col-md-2 col-sm-6"><div class="footer-menu"><h5 class="widget-title">Brands</h5><ul class="menu-list list-unstyled"><?php $__currentLoopData = $menuBrands->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><a href="<?php echo e(route('brand.show', $brand->slug)); ?>" class="nav-link"><?php echo e($brand->name); ?></a></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div></div>
            <div class="col-md-2 col-sm-6"><div class="footer-menu"><h5 class="widget-title">Customer Service</h5><ul class="menu-list list-unstyled"><li><a href="<?php echo e(route('cart.index')); ?>" class="nav-link">Cart</a></li><li><a href="<?php echo e(route('checkout.index')); ?>" class="nav-link">Checkout</a></li><li><a href="<?php echo e(route('order.tracking')); ?>" class="nav-link">Order Tracking</a></li><li><a href="<?php echo e(route('customer.login')); ?>" class="nav-link">My Account</a></li><li><a href="mailto:<?php echo e($supportEmail); ?>" class="nav-link">Contact</a></li></ul></div></div>
            <div class="col-lg-3 col-md-6 col-sm-6"><div class="footer-menu"><h5 class="widget-title">Request a Product</h5><p>Can’t find an item? Send us the product name and phone number.</p><form method="POST" action="<?php echo e(route('product_requests.store')); ?>" class="footer-request-form"><?php echo csrf_field(); ?><input type="hidden" name="source" value="footer"><div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true"><label>Website</label><input type="text" name="website" value="" tabindex="-1" autocomplete="off"></div><input class="form-control bg-light" name="customer_name" type="text" placeholder="Name" required><input class="form-control bg-light" name="phone" type="text" placeholder="Phone" required><input class="form-control bg-light" name="product_name" type="text" placeholder="Product name" required><button class="btn btn-dark" type="submit">Request</button></form></div></div>
        </div>
    </div>
</footer>
<div id="footer-bottom"><div class="container-fluid"><div class="row"><div class="col-md-6 copyright"><p><?php echo e($footerCopyright); ?></p></div><div class="col-md-6 credit-link text-start text-md-end"><p>Developed by <a href="<?php echo e($footerCreditUrl); ?>" target="_blank" rel="noopener noreferrer"><?php echo e($footerCreditText); ?></a>.</p></div></div></div></div>
<script src="<?php echo e(asset('foodmart/js/jquery-1.11.0.min.js')); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous" onerror="this.remove()"></script>
<script src="<?php echo e(asset('foodmart/js/plugins.js')); ?>"></script>
<script src="<?php echo e(asset('foodmart/js/script.js')); ?>"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script src="<?php echo e(asset('foodmart/js/prosan-foodmart.js')); ?>?v=20260729-1"></script>
</body>
</html>
<?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/layouts/store.blade.php ENDPATH**/ ?>