@extends('layouts.store')
@section('title', 'Shop - Prosan Atelier')

@section('content')
@php
    $currentCategorySlug = request('category');
    $flatCategories = collect();

    foreach ($categories as $category) {
        $flatCategories->push($category);
        foreach (($category->children ?? collect()) as $child) {
            $flatCategories->push($child);
        }
    }
@endphp

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
            <a href="{{ route('shop.index', request()->except(['category', 'page'])) }}">View all products</a>
        </div>

        <div class="shop-category-scroller" aria-label="Shop categories">
            <a class="shop-category-chip {{ ! $currentCategorySlug ? 'active' : '' }}" href="{{ route('shop.index', request()->except(['category', 'page'])) }}">
                <span class="shop-category-chip-icon">🛒</span>
                <strong>All Products</strong>
            </a>

            @foreach($flatCategories as $category)
                @php
                    $categoryParams = array_merge(request()->except(['page']), ['category' => $category->slug]);
                @endphp
                <a class="shop-category-chip {{ $currentCategorySlug === $category->slug ? 'active' : '' }}" href="{{ route('shop.index', $categoryParams) }}">
                    <span class="shop-category-chip-icon">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                    </span>
                    <strong>{{ $category->name }}</strong>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="shop-main-section">
    <div class="container-fluid">
        <div class="row g-5">
            <aside class="col-lg-3">
                <div class="shop-sidebar foodmart-sidebar">
                    <h4>Filter Products</h4>
                    <form action="{{ route('shop.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input class="form-control form-control-lg" type="search" name="q" value="{{ request('q') }}" placeholder="Search product">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select form-select-lg" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                                    @foreach($category->children as $child)
                                        <option value="{{ $child->slug }}" @selected(request('category') === $child->slug)>— {{ $child->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select class="form-select form-select-lg" name="brand">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sort</label>
                            <select class="form-select form-select-lg" name="sort">
                                <option value="">Latest</option>
                                <option value="price_low" @selected(request('sort') === 'price_low')>Price: Low to High</option>
                                <option value="price_high" @selected(request('sort') === 'price_high')>Price: High to Low</option>
                            </select>
                        </div>

                        <button class="btn btn-primary btn-lg w-100" type="submit">Apply Filter</button>
                        <a class="btn btn-outline-dark btn-lg w-100 mt-2" href="{{ route('shop.index') }}">Reset</a>
                    </form>
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="tabs-header shop-products-header d-flex justify-content-between border-bottom mb-5">
                    <div>
                        <h3 class="mb-0">{{ $products->total() }} Products Found</h3>
                    </div>
                </div>

                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4">
                    @forelse($products as $product)
                        <div class="col mb-4">@include('partials.product-card', ['product' => $product])</div>
                    @empty
                        <div class="col-12"><div class="alert alert-warning">No products found.</div></div>
                    @endforelse
                </div>

                <div class="pagination-wrap mt-5">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection
