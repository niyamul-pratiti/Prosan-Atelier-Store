<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductImportExportController extends Controller
{
    public function index()
    {
        $latestProducts = Product::latest()->take(5)->get(['id', 'name', 'sku', 'updated_at']);

        return view('admin.products.import-export', compact('latestProducts'));
    }

    public function import(Request $request)
    {
        $data = $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:51200'],
            'mode' => ['required', 'in:create_update,update_only,create_only'],
            'image_mode' => ['nullable', 'in:keep,replace'],
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');

        if (! $handle) {
            return back()->with('error', 'Could not read the uploaded CSV file.');
        }

        $headers = fgetcsv($handle);
        if (! $headers) {
            fclose($handle);
            return back()->with('error', 'CSV file is empty or invalid.');
        }

        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $headers[0]);
        $normalizedHeaders = [];
        foreach ($headers as $index => $header) {
            $normalizedHeaders[$this->normalizeHeader($header)] = $index;
        }

        $summary = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'images_added' => 0,
            'categories_created' => 0,
            'brands_created' => 0,
            'errors' => [],
        ];

        $rowNumber = 1;
        $imageMode = $data['image_mode'] ?? 'keep';

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                if ($this->isBlankRow($row)) {
                    continue;
                }

                $type = strtolower($this->value($row, $normalizedHeaders, ['Type']));
                if ($type === 'variation') {
                    $summary['skipped']++;
                    continue;
                }

                $name = trim($this->value($row, $normalizedHeaders, ['Name', 'Product Name']));
                if ($name === '') {
                    $summary['skipped']++;
                    $summary['errors'][] = "Row {$rowNumber}: skipped because product name is missing.";
                    continue;
                }

                $sku = trim($this->value($row, $normalizedHeaders, ['SKU']));
                $slug = $this->safeSlug($this->value($row, $normalizedHeaders, ['Slug']) ?: $name);

                $existing = null;
                if ($sku !== '') {
                    $existing = Product::where('sku', $sku)->first();
                }

                if (! $existing) {
                    $existing = Product::where('slug', $slug)->first();
                }

                if ($data['mode'] === 'update_only' && ! $existing) {
                    $summary['skipped']++;
                    continue;
                }

                if ($data['mode'] === 'create_only' && $existing) {
                    $summary['skipped']++;
                    continue;
                }

                [$categoryIds, $createdCategories] = $this->resolveCategories($this->value($row, $normalizedHeaders, ['Categories', 'Category', 'Product Categories']));
                $summary['categories_created'] += $createdCategories;

                [$brandId, $brandCreated] = $this->resolveBrand($this->value($row, $normalizedHeaders, ['Brands', 'Brand']));
                $summary['brands_created'] += $brandCreated ? 1 : 0;

                $regularPrice = $this->numeric($this->value($row, $normalizedHeaders, ['Regular price', 'Regular Price', 'Price']));
                $salePrice = $this->numeric($this->value($row, $normalizedHeaders, ['Sale price', 'Sale Price']));
                $purchasePrice = $this->numeric($this->value($row, $normalizedHeaders, ['Purchase Price', 'Purchase price', 'Cost', 'Product Cost', 'purchase_price']));
                $stock = $this->integer($this->value($row, $normalizedHeaders, ['Stock', 'Stock quantity', 'stock_quantity']));
                $lowStock = $this->integer($this->value($row, $normalizedHeaders, ['Low stock amount', 'Low Stock', 'low_stock_alert']));
                $weight = $this->numeric($this->value($row, $normalizedHeaders, ['Weight (kg)', 'Weight', 'weight']));
                $published = $this->value($row, $normalizedHeaders, ['Published', 'Status']);
                $featured = $this->value($row, $normalizedHeaders, ['Is featured?', 'Featured', 'is_featured']);

                $payload = [
                    'category_id' => $categoryIds[0] ?? ($existing->category_id ?? null),
                    'brand_id' => $brandId ?: ($existing->brand_id ?? null),
                    'name' => $name,
                    'slug' => $existing ? $existing->slug : $this->uniqueSlug($slug),
                    'sku' => $sku !== '' ? $sku : ($existing->sku ?? null),
                    'barcode' => $this->value($row, $normalizedHeaders, ['GTIN, UPC, EAN, or ISBN', 'Barcode', 'GTIN']) ?: ($existing->barcode ?? null),
                    'short_description' => $this->value($row, $normalizedHeaders, ['Short description', 'Short Description']) ?: ($existing->short_description ?? null),
                    'description' => $this->value($row, $normalizedHeaders, ['Description']) ?: ($existing->description ?? null),
                    'regular_price' => $regularPrice ?? ($existing->regular_price ?? 0),
                    'sale_price' => $salePrice,
                    'purchase_price' => $purchasePrice ?? ($existing->purchase_price ?? 0),
                    'stock_quantity' => $stock ?? ($existing->stock_quantity ?? 0),
                    'low_stock_alert' => $lowStock ?? ($existing->low_stock_alert ?? 5),
                    'weight' => $weight,
                    'unit' => $weight !== null ? 'kg' : ($existing->unit ?? null),
                    'product_type' => in_array($type, ['variable', 'simple'], true) ? $type : ($existing->product_type ?? 'simple'),
                    'is_featured' => $this->truthy($featured),
                    'is_new_arrival' => $existing->is_new_arrival ?? true,
                    'is_best_seller' => $existing->is_best_seller ?? false,
                    'is_active' => $published === '' ? ($existing->is_active ?? true) : $this->truthy($published),
                    'meta_title' => $this->value($row, $normalizedHeaders, ['Meta Title', 'SEO Title']) ?: ($existing->meta_title ?? $name),
                    'meta_description' => $this->cleanText($this->value($row, $normalizedHeaders, ['Meta Description', 'SEO Description', 'Short description'])) ?: ($existing->meta_description ?? null),
                ];

                if ($existing) {
                    $existing->update($payload);
                    $product = $existing;
                    $summary['updated']++;
                } else {
                    $product = Product::create($payload);
                    $summary['created']++;
                }

                $this->syncCategories($product, $categoryIds);
                $summary['images_added'] += $this->syncImageUrls($product, $this->value($row, $normalizedHeaders, ['Images', 'Image', 'Gallery Images']), $imageMode);
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            fclose($handle);

            return back()->with('error', 'Import failed: ' . $exception->getMessage());
        }

        fclose($handle);

        return back()->with('success', "Import complete. Created: {$summary['created']}, Updated: {$summary['updated']}, Skipped: {$summary['skipped']}, Images added: {$summary['images_added']}.")
            ->with('import_errors', array_slice($summary['errors'], 0, 10));
    }

    public function export()
    {
        $filename = 'prosan-products-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');
            fputcsv($output, [
                'ID', 'Type', 'SKU', 'Name', 'Published', 'Is featured?', 'Short description', 'Description',
                'Stock', 'Low stock amount', 'Weight (kg)', 'Sale price', 'Regular price', 'Purchase Price',
                'Categories', 'Brands', 'Images', 'Meta Title', 'Meta Description',
            ]);

            Product::with(['categories.parent', 'category.parent', 'brand', 'images'])->orderBy('id')->chunk(100, function ($products) use ($output) {
                foreach ($products as $product) {
                    $categories = $product->categories->isNotEmpty()
                        ? $product->categories
                        : collect($product->category ? [$product->category] : []);

                    fputcsv($output, [
                        $product->id,
                        $product->product_type ?: 'simple',
                        $product->sku,
                        $product->name,
                        $product->is_active ? 1 : 0,
                        $product->is_featured ? 1 : 0,
                        $product->short_description,
                        $product->description,
                        $product->stock_quantity,
                        $product->low_stock_alert,
                        $product->weight,
                        $product->sale_price,
                        $product->regular_price,
                        $product->purchase_price,
                        $categories->map(fn ($category) => $this->categoryExportName($category))->join(', '),
                        $product->brand->name ?? '',
                        $product->images->map(fn ($image) => $image->url)->join(', '),
                        $product->meta_title,
                        $product->meta_description,
                    ]);
                }
            });

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function resolveCategories(?string $raw): array
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return [[], 0];
        }

        $created = 0;
        $ids = [];
        $items = preg_split('/\s*,\s*|\s*\|\s*|\s*;\s*/', $raw);

        foreach ($items as $item) {
            $item = trim($item);
            if ($item === '') {
                continue;
            }

            $parts = array_values(array_filter(array_map('trim', preg_split('/\s*>\s*|\s*\/\s*/', $item))));
            $parentId = null;
            $category = null;

            foreach ($parts as $part) {
                $category = Category::where('name', $part)->where('parent_id', $parentId)->first();

                if (! $category) {
                    $category = Category::create([
                        'parent_id' => $parentId,
                        'name' => $part,
                        'slug' => $this->uniqueCategorySlug($part, $parentId),
                        'sort_order' => 0,
                        'is_active' => true,
                    ]);
                    $created++;
                }

                $parentId = $category->id;
            }

            if ($category) {
                $ids[] = $category->id;
            }
        }

        return [array_values(array_unique($ids)), $created];
    }

    private function resolveBrand(?string $raw): array
    {
        $name = trim(preg_split('/\s*,\s*|\s*\|\s*|\s*;\s*/', (string) $raw)[0] ?? '');
        if ($name === '') {
            return [null, false];
        }

        $brand = Brand::where('name', $name)->first();
        if ($brand) {
            return [$brand->id, false];
        }

        $brand = Brand::create([
            'name' => $name,
            'slug' => $this->uniqueBrandSlug($name),
            'sort_order' => 0,
            'is_active' => true,
        ]);

        return [$brand->id, true];
    }

    private function syncCategories(Product $product, array $categoryIds): void
    {
        if (! Schema::hasTable('product_category') || empty($categoryIds)) {
            return;
        }

        $primaryId = $product->category_id ?: ($categoryIds[0] ?? null);
        $sync = [];
        foreach (array_unique($categoryIds) as $id) {
            $sync[(int) $id] = ['is_primary' => (int) $id === (int) $primaryId];
        }

        if ($primaryId) {
            $sync[(int) $primaryId] = ['is_primary' => true];
        }

        $product->categories()->sync($sync);
    }

    private function syncImageUrls(Product $product, ?string $raw, string $mode): int
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return 0;
        }

        $urls = collect(preg_split('/\s*,\s*/', $raw))
            ->map(fn ($url) => trim($url))
            ->filter(fn ($url) => Str::startsWith($url, ['http://', 'https://', '//']))
            ->unique()
            ->values();

        if ($urls->isEmpty()) {
            return 0;
        }

        if ($mode === 'replace') {
            $product->images()->delete();
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $baseSort = (int) $product->images()->max('sort_order');
        $added = 0;

        foreach ($urls as $index => $url) {
            if ($product->images()->where('path', $url)->exists()) {
                continue;
            }

            $makePrimary = ! $hasPrimary && $index === 0;
            $product->images()->create([
                'path' => $url,
                'alt_text' => $product->name,
                'sort_order' => $baseSort + $index + 1,
                'is_primary' => $makePrimary,
            ]);

            if ($makePrimary) {
                $hasPrimary = true;
            }

            $added++;
        }

        return $added;
    }

    private function value(array $row, array $headers, array $aliases): string
    {
        foreach ($aliases as $alias) {
            $key = $this->normalizeHeader($alias);
            if (array_key_exists($key, $headers)) {
                return trim((string) ($row[$headers[$key]] ?? ''));
            }
        }

        return '';
    }

    private function normalizeHeader(string $header): string
    {
        $header = strtolower(trim($header));
        $header = preg_replace('/[^a-z0-9]+/', '_', $header);
        return trim($header, '_');
    }

    private function isBlankRow(array $row): bool
    {
        return trim(implode('', $row)) === '';
    }

    private function numeric(?string $value): ?float
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $value = preg_replace('/[^0-9.\-]/', '', $value);
        return is_numeric($value) ? (float) $value : null;
    }

    private function integer(?string $value): ?int
    {
        $number = $this->numeric($value);
        return $number === null ? null : (int) $number;
    }

    private function truthy(?string $value): bool
    {
        $value = strtolower(trim((string) $value));
        return in_array($value, ['1', 'yes', 'true', 'active', 'published', 'visible', 'instock', 'in stock'], true);
    }

    private function safeSlug(string $value): string
    {
        return Str::slug($value) ?: 'product-' . Str::random(6);
    }

    private function uniqueSlug(string $value): string
    {
        $slug = $this->safeSlug($value);
        $original = $slug;
        $i = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }

    private function uniqueCategorySlug(string $name, ?int $parentId = null): string
    {
        $base = $this->safeSlug($name);
        if ($parentId) {
            $parent = Category::find($parentId);
            if ($parent) {
                $base = $this->safeSlug($parent->slug . '-' . $name);
            }
        }

        $slug = $base;
        $i = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function uniqueBrandSlug(string $name): string
    {
        $base = $this->safeSlug($name);
        $slug = $base;
        $i = 2;

        while (Brand::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function cleanText(?string $value): ?string
    {
        $value = trim(strip_tags((string) $value));
        return $value === '' ? null : Str::limit($value, 280, '');
    }

    private function categoryExportName(Category $category): string
    {
        return $category->parent ? $category->parent->name . ' > ' . $category->name : $category->name;
    }
}
