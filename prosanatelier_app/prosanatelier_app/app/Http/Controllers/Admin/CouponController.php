<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::query()
            ->when($request->filled('q'), fn ($q) => $q->where('code', 'like', '%' . trim($request->q) . '%'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $categoryIds = $data['category_ids'] ?? [];
        $productIds = $data['product_ids'] ?? [];
        unset($data['category_ids'], $data['product_ids']);

        $coupon = Coupon::create($data);
        $this->syncApplicability($coupon, $categoryIds, $productIds);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $coupon->load(['categories', 'products']);
        return view('admin.coupons.edit', array_merge($this->formData(), compact('coupon')));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validatedData($request, $coupon->id);
        $categoryIds = $data['category_ids'] ?? [];
        $productIds = $data['product_ids'] ?? [];
        unset($data['category_ids'], $data['product_ids']);

        $coupon->update($data);
        $this->syncApplicability($coupon, $categoryIds, $productIds);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted successfully.');
    }

    private function formData(): array
    {
        return [
            'categories' => Category::orderBy('parent_id')->orderBy('name')->get(),
            'products' => Product::active()->orderBy('name')->get(['id', 'name', 'sku']),
        ];
    }

    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', Rule::unique('coupons', 'code')->ignore($ignoreId)],
            'description' => ['nullable', 'string', 'max:500'],
            'type' => ['required', Rule::in(['fixed', 'percent', 'free_delivery'])],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'applies_to' => ['required', Rule::in(['all', 'categories', 'products'])],
            'is_active' => ['nullable', 'boolean'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['nullable', 'exists:categories,id'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['nullable', 'exists:products,id'],
        ]);

        $data['code'] = Str::upper(trim($data['code']));
        $data['amount'] = $data['type'] === 'free_delivery' ? 0 : (float) ($data['amount'] ?? 0);
        $data['minimum_order_amount'] = (float) ($data['minimum_order_amount'] ?? 0);
        $data['usage_limit'] = $data['usage_limit'] ?: null;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function syncApplicability(Coupon $coupon, array $categoryIds, array $productIds): void
    {
        $coupon->categories()->sync($coupon->applies_to === 'categories' ? array_filter($categoryIds) : []);
        $coupon->products()->sync($coupon->applies_to === 'products' ? array_filter($productIds) : []);
    }
}
