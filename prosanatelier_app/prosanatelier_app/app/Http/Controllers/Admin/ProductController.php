<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\Uploads\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'categories', 'brand', 'images', 'activeVariations'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = trim($request->q);
                $query->where(function ($searchQuery) use ($q) {
                    $searchQuery->where('name', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%");
                });
            })
            ->latest()->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data = $this->booleanData($data, $request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name']);

        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids'], $data['variations']);

        if (empty($data['category_id']) && ! empty($categoryIds)) {
            $data['category_id'] = collect($categoryIds)->filter()->first();
        }

        $product = Product::create($data);
        $this->syncCategories($product, $request, $categoryIds);
        $this->syncVariations($product, $request);
        $this->syncVariableProductSummary($product);
        $this->storeFeaturedImage($product, $request);
        $this->storeImages($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variations', 'categories']);
        return view('admin.products.edit', array_merge($this->formData(), compact('product')));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedData($request, $product->id);
        $data = $this->booleanData($data, $request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids'], $data['variations']);

        if (empty($data['category_id']) && ! empty($categoryIds)) {
            $data['category_id'] = collect($categoryIds)->filter()->first();
        }

        $product->update($data);
        $this->syncCategories($product, $request, $categoryIds);
        $this->syncVariations($product, $request);
        $this->syncVariableProductSummary($product);
        $this->storeFeaturedImage($product, $request);
        $this->storeImages($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    public function deleteImage(ProductImage $image)
    {
        ImageUploader::delete($image->path);

        $wasPrimary = $image->is_primary;
        $product = $image->product;

        $image->delete();

        if ($wasPrimary && $product) {
            $nextImage = $product->images()->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Image deleted.');
    }

    private function formData(): array
    {
        return [
            'categories' => Category::orderBy('parent_id')->orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ];
    }

    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        $isVariable = $request->input('product_type') === 'variable';

        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['nullable', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('products', 'slug')->ignore($ignoreId)],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($ignoreId)],
            'barcode' => ['nullable', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ingredients' => ['nullable', 'string'],
            'usage_instruction' => ['nullable', 'string'],
            'regular_price' => [$isVariable ? 'nullable' : 'required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => [$isVariable ? 'nullable' : 'required', 'integer', 'min:0'],
            'low_stock_alert' => ['nullable', 'integer', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:30'],
            'expiry_date' => ['nullable', 'date'],
            'product_type' => ['required', Rule::in(['simple', 'variable'])],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'featured_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:12288'],
            'images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:12288'],
            'variations' => ['nullable', 'array'],
            'variations.*.id' => ['nullable', 'integer'],
            'variations.*.name' => ['nullable', 'string', 'max:150'],
            'variations.*.sku' => ['nullable', 'string', 'max:100'],
            'variations.*.regular_price' => ['nullable', 'numeric', 'min:0'],
            'variations.*.sale_price' => ['nullable', 'numeric', 'min:0'],
            'variations.*.purchase_price' => ['nullable', 'numeric', 'min:0'],
            'variations.*.stock_quantity' => ['nullable', 'integer', 'min:0'],
            'variations.*.weight' => ['nullable', 'numeric', 'min:0'],
            'variations.*.unit' => ['nullable', 'string', 'max:30'],
            'variations.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'variations.*.is_active' => ['nullable'],
        ], [
            'regular_price.required' => 'Regular price is required for simple products.',
            'stock_quantity.required' => 'Stock is required for simple products.',
            'featured_image.uploaded' => 'The featured image failed to upload. Please use JPG/PNG/WebP/GIF and keep the image under the hosting upload limit.',
            'featured_image.max' => 'The featured image must be smaller than 12MB.',
            'images.*.uploaded' => 'One of the gallery images failed to upload. Please use a smaller image.',
            'images.*.max' => 'Each gallery image must be smaller than 12MB.',
        ]);

        if ($isVariable) {
            $this->validateVariableRows($request);
        }

        return $data;
    }

    private function validateVariableRows(Request $request): void
    {
        $filledRows = 0;

        foreach ($request->input('variations', []) as $index => $variation) {
            $name = trim((string) ($variation['name'] ?? ''));
            $regularPrice = $variation['regular_price'] ?? null;

            if ($name === '') {
                continue;
            }

            $filledRows++;

            if ($regularPrice === null || $regularPrice === '') {
                throw ValidationException::withMessages([
                    "variations.{$index}.regular_price" => 'Regular price is required for every variation.',
                ]);
            }
        }

        if ($filledRows === 0) {
            throw ValidationException::withMessages([
                'variations' => 'Please add at least one variation for a variable product.',
            ]);
        }
    }

    private function booleanData(array $data, Request $request): array
    {
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new_arrival'] = $request->boolean('is_new_arrival');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_active'] = $request->boolean('is_active');
        $data['low_stock_alert'] = $data['low_stock_alert'] ?? 5;

        if (($data['product_type'] ?? 'simple') === 'variable') {
            $data['regular_price'] = $data['regular_price'] ?? 0;
            $data['sale_price'] = null;
            $data['purchase_price'] = $data['purchase_price'] ?? 0;
            $data['stock_quantity'] = $data['stock_quantity'] ?? 0;
        } else {
            $data['purchase_price'] = $data['purchase_price'] ?? 0;
            $data['stock_quantity'] = $data['stock_quantity'] ?? 0;
        }

        return $data;
    }

    private function syncCategories(Product $product, Request $request, array $categoryIds = []): void
    {
        if (! Schema::hasTable('product_category')) {
            return;
        }

        $ids = collect($categoryIds)
            ->merge($request->input('category_ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($product->category_id) {
            $ids = $ids->prepend((int) $product->category_id)->unique()->values();
        }

        $sync = [];
        foreach ($ids as $id) {
            $sync[$id] = ['is_primary' => (int) $id === (int) $product->category_id];
        }

        $product->categories()->sync($sync);
    }

    private function syncVariations(Product $product, Request $request): void
    {
        if ($product->product_type !== 'variable') {
            $product->variations()->delete();
            return;
        }

        $keptIds = [];
        $existingIds = $product->variations()->pluck('id')->map(fn ($id) => (int) $id)->all();

        foreach ($request->input('variations', []) as $index => $variation) {
            if (empty($variation['name'])) {
                continue;
            }

            $payload = [
                'name' => trim($variation['name']),
                'sku' => ! empty($variation['sku']) ? trim($variation['sku']) : null,
                'regular_price' => ($variation['regular_price'] ?? '') !== '' ? $variation['regular_price'] : null,
                'sale_price' => ($variation['sale_price'] ?? '') !== '' ? $variation['sale_price'] : null,
                'purchase_price' => ($variation['purchase_price'] ?? '') !== '' ? $variation['purchase_price'] : null,
                'stock_quantity' => ($variation['stock_quantity'] ?? '') !== '' ? (int) $variation['stock_quantity'] : 0,
                'weight' => ($variation['weight'] ?? '') !== '' ? $variation['weight'] : null,
                'unit' => ! empty($variation['unit']) ? trim($variation['unit']) : null,
                'sort_order' => ($variation['sort_order'] ?? '') !== '' ? (int) $variation['sort_order'] : $index,
                'is_active' => array_key_exists('is_active', $variation) ? (bool) $variation['is_active'] : true,
            ];

            $variationId = isset($variation['id']) ? (int) $variation['id'] : 0;

            if ($variationId && in_array($variationId, $existingIds, true)) {
                $product->variations()->whereKey($variationId)->update($payload);
                $keptIds[] = $variationId;
            } else {
                $created = $product->variations()->create($payload);
                $keptIds[] = $created->id;
            }
        }

        $product->variations()->whereNotIn('id', $keptIds ?: [0])->delete();
    }

    private function syncVariableProductSummary(Product $product): void
    {
        if ($product->product_type !== 'variable') {
            return;
        }

        $variations = $product->variations()->where('is_active', true)->get();
        $priced = $variations->filter(fn ($variation) => (float) ($variation->regular_price ?? 0) > 0);

        if ($priced->isEmpty()) {
            $product->forceFill([
                'regular_price' => 0,
                'sale_price' => null,
                'stock_quantity' => 0,
            ])->saveQuietly();
            return;
        }

        $effectivePrices = $priced->map(fn ($variation) => $variation->effective_price)->filter(fn ($price) => $price > 0);
        $regularPrices = $priced->map(fn ($variation) => (float) $variation->regular_price)->filter(fn ($price) => $price > 0);
        $purchasePrices = $priced->map(fn ($variation) => (float) ($variation->purchase_price ?? 0))->filter(fn ($price) => $price > 0);

        $minRegular = (float) ($regularPrices->min() ?: 0);
        $minEffective = (float) ($effectivePrices->min() ?: $minRegular);

        $product->forceFill([
            'regular_price' => $minRegular,
            'sale_price' => $minEffective < $minRegular ? $minEffective : null,
            'purchase_price' => (float) ($purchasePrices->min() ?: 0),
            'stock_quantity' => (int) $variations->sum('stock_quantity'),
        ])->saveQuietly();
    }

    private function storeFeaturedImage(Product $product, Request $request): void
    {
        if (! $request->hasFile('featured_image')) {
            return;
        }

        $oldFeatured = $product->images()->where('is_primary', true)->get();

        foreach ($oldFeatured as $image) {
            if (! str_starts_with($image->path, 'http://') && ! str_starts_with($image->path, 'https://')) {
                ImageUploader::delete($image->path);
            }
        }

        $product->images()->where('is_primary', true)->delete();

        $product->images()->create([
            'path' => ImageUploader::store($request->file('featured_image'), 'products'),
            'alt_text' => $product->name,
            'sort_order' => 0,
            'is_primary' => true,
        ]);
    }

    private function storeImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $baseSort = (int) $product->images()->max('sort_order');

        foreach ($request->file('images') as $index => $image) {
            if (! $image) {
                continue;
            }

            $makePrimary = ! $hasPrimary && $index === 0;

            $product->images()->create([
                'path' => ImageUploader::store($image, 'products'),
                'alt_text' => $product->name,
                'sort_order' => $baseSort + $index + 1,
                'is_primary' => $makePrimary,
            ]);

            if ($makePrimary) {
                $hasPrimary = true;
            }
        }
    }

    private function uniqueSlug(string $value): string
    {
        $slug = Str::slug($value);
        $original = $slug;
        $i = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}
