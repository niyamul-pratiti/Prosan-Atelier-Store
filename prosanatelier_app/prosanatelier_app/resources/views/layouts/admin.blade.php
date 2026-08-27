<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - {{ $siteSettings['site_name'] ?? 'Prosan Atelier' }}</title>
    <link rel="stylesheet" href="{{ asset('css/prosan-atelier.css') }}">
    <link rel="stylesheet" href="{{ asset('css/prosan-admin-tools.css') }}">

    <style>
    /* V43 pagination reset inside admin layout */
    .admin-main nav[role="navigation"]{margin-top:22px!important;display:flex!important;align-items:center!important;justify-content:space-between!important;gap:14px!important;flex-wrap:wrap!important;width:100%!important;clear:both!important}
    .admin-main nav[role="navigation"]>div:first-child:not(:only-child){display:none!important}
    .admin-main nav[role="navigation"]>div:last-child{display:flex!important;align-items:center!important;justify-content:space-between!important;gap:14px!important;flex-wrap:wrap!important;width:100%!important}
    .admin-main nav[role="navigation"]>div:last-child>div:last-child{margin-left:auto!important}
    .admin-main nav[role="navigation"] ul,.admin-main .pagination{list-style:none!important;margin:0!important;padding:0!important;display:flex!important;align-items:center!important;justify-content:flex-end!important;gap:7px!important;flex-wrap:wrap!important}
    .admin-main nav[role="navigation"] li,.admin-main .pagination li{list-style:none!important;margin:0!important;padding:0!important}.admin-main nav[role="navigation"] li::marker,.admin-main .pagination li::marker{content:""!important}
    .admin-main nav[role="navigation"] p{margin:0!important;color:#667085!important;font-size:13px!important;font-weight:700!important;line-height:1.4!important}
    .admin-main nav[role="navigation"] a,.admin-main nav[role="navigation"] span[aria-current]>span,.admin-main nav[role="navigation"] li>span,.admin-main .pagination a,.admin-main .pagination span{min-width:38px!important;height:38px!important;padding:0 12px!important;border-radius:12px!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;border:1px solid rgba(139,94,32,.14)!important;background:#fff!important;color:#5f461b!important;text-decoration:none!important;font-size:13px!important;font-weight:900!important;line-height:1!important;box-shadow:0 8px 18px rgba(23,32,51,.04)!important;overflow:hidden!important}
    .admin-main nav[role="navigation"] span[aria-current]>span,.admin-main .pagination .active span{background:linear-gradient(135deg,#d79514,#9f6908)!important;color:#fff!important;border-color:transparent!important}.admin-main nav[role="navigation"] svg{width:14px!important;height:14px!important;max-width:14px!important;max-height:14px!important}
    .admin-main nav[role="navigation"] .sr-only{position:absolute!important;width:1px!important;height:1px!important;padding:0!important;margin:-1px!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;white-space:nowrap!important;border:0!important}

    /* V44 custom paginator final reset */
    .admin-main nav.prosan-pagination{margin-top:22px!important;display:flex!important;flex-direction:column!important;align-items:flex-start!important;justify-content:flex-start!important;gap:12px!important;width:100%!important;clear:both!important}
    .admin-main nav.prosan-pagination .prosan-pagination-info{width:100%!important;margin:0!important;color:#667085!important;font-size:13px!important;font-weight:800!important;line-height:1.45!important}
    .admin-main nav.prosan-pagination .prosan-pagination-list{list-style:none!important;margin:0!important;padding:0!important;display:flex!important;align-items:center!important;justify-content:flex-start!important;gap:8px!important;flex-wrap:wrap!important;width:100%!important}
    .admin-main nav.prosan-pagination .prosan-pagination-list li{list-style:none!important;margin:0!important;padding:0!important}.admin-main nav.prosan-pagination .prosan-pagination-list li::marker{content:""!important}
    .admin-main nav.prosan-pagination .prosan-page-link{min-width:38px!important;height:38px!important;padding:0 13px!important;border-radius:12px!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;border:1px solid rgba(139,94,32,.14)!important;background:#fff!important;color:#5f461b!important;text-decoration:none!important;font-size:13px!important;font-weight:900!important;line-height:1!important;box-shadow:0 8px 18px rgba(23,32,51,.04)!important;white-space:nowrap!important}
    .admin-main nav.prosan-pagination .prosan-page-link.is-active{background:linear-gradient(135deg,#d79514,#9f6908)!important;color:#fff!important;border-color:transparent!important}.admin-main nav.prosan-pagination .prosan-page-link.is-disabled{opacity:.52!important;cursor:not-allowed!important;background:#f7f4ef!important;color:#98a2b3!important}
    @media (min-width:768px){.admin-main nav.prosan-pagination{flex-direction:row!important;align-items:center!important;justify-content:space-between!important}.admin-main nav.prosan-pagination .prosan-pagination-info{width:auto!important}.admin-main nav.prosan-pagination .prosan-pagination-list{width:auto!important;justify-content:flex-end!important}}
    </style>
</head>
<body class="admin-body">
@php
    $adminSiteName = $siteSettings['site_name'] ?? 'Prosan Atelier';
    $adminLogoUrl = \App\Models\SiteSetting::imageUrl($siteSettings['site_logo'] ?? null, 'images/prosan-logo.jpg');
@endphp
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ $adminLogoUrl }}" alt="{{ $adminSiteName }}">
            <span><strong>{{ $adminSiteName }}</strong><small>Admin Panel</small></span>
        </a>
        <a href="{{ route('admin.dashboard') }}"><span class="admin-nav-icon">📊</span> Dashboard</a>
        <a href="{{ route('admin.categories.index') }}"><span class="admin-nav-icon">🗂️</span> Categories & Subcategories</a>
        <a href="{{ route('admin.brands.index') }}"><span class="admin-nav-icon">🏷️</span> Brands</a>
        <a href="{{ route('admin.products.index') }}"><span class="admin-nav-icon">🧴</span> Products</a>
        <a href="{{ route('admin.products.import_export') }}"><span class="admin-nav-icon">📥</span> Product Import/Export</a>
        <a href="{{ route('admin.coupons.index') }}"><span class="admin-nav-icon">🎟️</span> Coupons</a>
        <a href="{{ route('admin.orders.index') }}"><span class="admin-nav-icon">📦</span> Orders</a>
        <a href="{{ route('admin.customers.index') }}"><span class="admin-nav-icon">👥</span> Customers</a>
        <a href="{{ route('admin.product_requests.index') }}"><span class="admin-nav-icon">🛒</span> Product Requests</a>
        <a href="{{ route('admin.reports.index') }}"><span class="admin-nav-icon">📈</span> Reports</a>
        <a href="{{ route('admin.activity_logs.index') }}"><span class="admin-nav-icon">🧾</span> Activity Logs</a>
        <a href="{{ route('admin.backups.index') }}"><span class="admin-nav-icon">💾</span> Backups</a>
        <a href="{{ route('admin.system_health.index') }}"><span class="admin-nav-icon">🩺</span> System Health</a>
        <a href="{{ route('admin.settings.edit') }}"><span class="admin-nav-icon">⚙️</span> Settings</a>
        <a href="{{ route('admin.profile') }}"><span class="admin-nav-icon">🔐</span> Admin Account</a>
        <a href="{{ route('home') }}" target="_blank"><span class="admin-nav-icon">🌐</span> View Store</a>
        <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
            @csrf
            <button type="submit"><span class="admin-nav-icon">↩</span> Logout</button>
        </form>
    </aside>
    <section class="admin-main">
        <div class="admin-topbar">
            <div>
                <span class="admin-kicker">{{ $adminSiteName }}</span>
                <strong>@yield('title', 'Admin Panel')</strong>
            </div>
            <a class="admin-account-link" href="{{ route('admin.profile') }}">{{ $currentAdmin->name ?? 'Admin' }}</a>
        </div>
        @include('partials.flash')
        @yield('content')
    </section>
</div>
</body>
</html>
