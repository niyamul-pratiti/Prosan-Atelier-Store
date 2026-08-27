<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()->with(['images', 'brand', 'category', 'activeVariations'])->withCount(['variations as active_variations_count' => fn ($q) => $q->where('is_active', true)])
            ->where('is_featured', true)->latest()->take(10)->get();

        $newProducts = Product::active()->with(['images', 'brand', 'category', 'activeVariations'])->withCount(['variations as active_variations_count' => fn ($q) => $q->where('is_active', true)])
            ->where('is_new_arrival', true)->latest()->take(10)->get();

        $bestSellerProducts = Product::active()->with(['images', 'brand', 'category', 'activeVariations'])->withCount(['variations as active_variations_count' => fn ($q) => $q->where('is_active', true)])
            ->where('is_best_seller', true)->latest()->take(12)->get();

        $popularProducts = Product::active()->with(['images', 'brand', 'category', 'activeVariations'])->withCount(['variations as active_variations_count' => fn ($q) => $q->where('is_active', true)])
            ->latest()->take(12)->get();

        $beautyCategoryIds = Category::active()
            ->where(function ($query) {
                $query->where('slug', 'cosmetics')
                    ->orWhereHas('parent', fn ($parentQuery) => $parentQuery->where('slug', 'cosmetics'));
            })
            ->pluck('id');

        $beautyProducts = collect();

        if ($beautyCategoryIds->isNotEmpty()) {
            $beautyProducts = Product::active()
                ->with(['images', 'brand', 'category', 'activeVariations'])
                ->withCount(['variations as active_variations_count' => fn ($q) => $q->where('is_active', true)])
                ->where(function ($query) use ($beautyCategoryIds) {
                    $query->whereIn('category_id', $beautyCategoryIds)
                        ->orWhereHas('categories', fn ($categoryQuery) => $categoryQuery->whereIn('categories.id', $beautyCategoryIds));
                })
                ->latest()
                ->take(10)
                ->get();
        }

        $categories = Category::active()->whereNull('parent_id')->with('children')->orderBy('sort_order')->get();
        $brands = Brand::active()->orderBy('sort_order')->take(12)->get();
        $homeSettings = SiteSetting::allAsKeyValue();

        return view('frontend.home', compact('featuredProducts', 'newProducts', 'bestSellerProducts', 'popularProducts', 'beautyProducts', 'categories', 'brands', 'homeSettings'));
    }
}
