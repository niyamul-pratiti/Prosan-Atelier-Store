<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['images', 'brand', 'category', 'categories', 'activeVariations'])
            ->withCount(['variations as active_variations_count' => fn ($q) => $q->where('is_active', true)]);

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $category = Category::active()->where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = $category->children()->pluck('id')->push($category->id)->all();
                $query->where(function ($productQuery) use ($categoryIds) {
                    $productQuery->whereIn('category_id', $categoryIds)
                        ->orWhereHas('categories', fn ($categoryQuery) => $categoryQuery->whereIn('categories.id', $categoryIds));
                });
            }
        }

        if ($request->filled('brand')) {
            $brand = Brand::active()->where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        match ($request->get('sort')) {
            'price_low' => $query->orderByRaw('COALESCE(sale_price, regular_price) asc'),
            'price_high' => $query->orderByRaw('COALESCE(sale_price, regular_price) desc'),
            default => $query->latest(),
        };

        $products = $query->paginate(16)->withQueryString();
        $categories = Category::active()->whereNull('parent_id')->with('children')->orderBy('sort_order')->get();
        $brands = Brand::active()->orderBy('sort_order')->get();

        return view('frontend.shop', compact('products', 'categories', 'brands'));
    }

    public function show(string $slug)
    {
        $product = Product::active()->with(['images', 'brand', 'category', 'categories', 'activeVariations', 'variations' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('name')])
            ->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::active()->with(['images', 'activeVariations'])->withCount(['variations as active_variations_count' => fn ($q) => $q->where('is_active', true)])
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)->orWhere('brand_id', $product->brand_id);
            })
            ->take(4)->get();

        return view('frontend.product', compact('product', 'relatedProducts'));
    }

    public function category(string $slug)
    {
        return redirect()->route('shop.index', ['category' => $slug]);
    }

    public function brand(string $slug)
    {
        return redirect()->route('shop.index', ['brand' => $slug]);
    }
}
