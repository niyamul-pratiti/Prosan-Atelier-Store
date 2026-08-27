<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\SiteSetting;
use App\Observers\OrderObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        Paginator::defaultView('vendor.pagination.prosan');

        try {
            View::share('siteSettings', SiteSetting::allAsKeyValue());
        } catch (\Throwable $e) {
            View::share('siteSettings', SiteSetting::DEFAULTS);
        }

        View::composer('layouts.store', function ($view) {
            $navCategories = collect();
            $navBrands = collect();

            if (Schema::hasTable('categories')) {
                $navCategories = Category::active()
                    ->whereNull('parent_id')
                    ->with(['children' => function ($query) {
                        $query->where('is_active', true)->orderBy('sort_order');
                    }])
                    ->orderBy('sort_order')
                    ->take(8)
                    ->get();
            }

            if (Schema::hasTable('brands')) {
                $navBrands = Brand::active()->orderBy('sort_order')->take(10)->get();
            }

            $view->with('navCategories', $navCategories)->with('navBrands', $navBrands);
        });
    }
}
