<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CouponDiscountService
{
    public function calculateForCart(array $cart, ?string $code): array
    {
        $items = collect($cart)->map(fn ($item) => [
            'product_id' => $item['product_id'] ?? null,
            'quantity' => (int) ($item['quantity'] ?? 0),
            'unit_price' => (float) ($item['price'] ?? 0),
        ])->filter(fn ($item) => $item['product_id'] && $item['quantity'] > 0)->values()->all();

        return $this->calculateForItems($items, $code);
    }

    public function calculateForItems(array $items, ?string $code): array
    {
        $subtotal = collect($items)->sum(fn ($item) => (float) ($item['unit_price'] ?? 0) * (int) ($item['quantity'] ?? 0));
        $empty = $this->emptyResult($subtotal, $code);
        $code = Str::upper(trim((string) $code));

        if ($code === '') {
            return $empty;
        }

        if (! Schema::hasTable('coupons')) {
            return array_merge($empty, ['valid' => false, 'message' => 'Coupon system is not ready yet. Please try again later.']);
        }

        $couponWith = [];
        if (Schema::hasTable('coupon_category')) {
            $couponWith[] = 'categories:id';
        }
        if (Schema::hasTable('coupon_product')) {
            $couponWith[] = 'products:id';
        }

        $coupon = Coupon::with($couponWith)->where('code', $code)->first();

        if (! $coupon) {
            return array_merge($empty, ['valid' => false, 'message' => 'Invalid coupon code.']);
        }

        if (! $coupon->isUsable($subtotal)) {
            return array_merge($empty, ['coupon' => $coupon, 'coupon_id' => $coupon->id, 'valid' => false, 'message' => 'Coupon is inactive, expired, fully used or does not meet minimum order amount.']);
        }

        $eligibleSubtotal = $this->eligibleSubtotal($coupon, $items);

        if ($eligibleSubtotal <= 0 && $coupon->type !== 'free_delivery') {
            return array_merge($empty, ['coupon' => $coupon, 'coupon_id' => $coupon->id, 'valid' => false, 'message' => 'This coupon is not applicable to the selected products.']);
        }

        $discount = 0.0;
        $freeDelivery = false;

        if ($coupon->type === 'fixed') {
            $discount = min((float) $coupon->amount, $eligibleSubtotal > 0 ? $eligibleSubtotal : $subtotal);
        } elseif ($coupon->type === 'percent') {
            $discount = round(($eligibleSubtotal * (float) $coupon->amount) / 100, 2);
        } elseif ($coupon->type === 'free_delivery') {
            $freeDelivery = true;
        }

        $discount = min($discount, $subtotal);

        return [
            'valid' => true,
            'message' => $coupon->type === 'free_delivery' ? 'Free delivery coupon applied.' : 'Coupon applied successfully.',
            'coupon' => $coupon,
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
            'discount' => $discount,
            'free_delivery' => $freeDelivery,
            'eligible_subtotal' => $eligibleSubtotal,
            'subtotal' => $subtotal,
        ];
    }

    private function eligibleSubtotal(Coupon $coupon, array $items): float
    {
        $items = collect($items)->filter(fn ($item) => ! empty($item['product_id']) && (int) ($item['quantity'] ?? 0) > 0);

        if ($coupon->applies_to === 'all') {
            return $items->sum(fn ($item) => (float) ($item['unit_price'] ?? 0) * (int) ($item['quantity'] ?? 0));
        }

        $productIds = $items->pluck('product_id')->filter()->unique()->values();
        $hasProductCategoryTable = Schema::hasTable('product_category');
        $products = Product::query()
            ->when($hasProductCategoryTable, fn ($query) => $query->with('categories'))
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $couponProductIds = Schema::hasTable('coupon_product') ? $coupon->products->pluck('id')->map(fn ($id) => (int) $id)->all() : [];
        $couponCategoryIds = Schema::hasTable('coupon_category') ? $coupon->categories->pluck('id')->map(fn ($id) => (int) $id)->all() : [];

        return $items->sum(function ($item) use ($coupon, $products, $couponProductIds, $couponCategoryIds) {
            $productId = (int) $item['product_id'];
            $product = $products->get($productId);

            if (! $product) {
                return 0;
            }

            $matched = false;

            if ($coupon->applies_to === 'products') {
                $matched = in_array($productId, $couponProductIds, true);
            }

            if ($coupon->applies_to === 'categories') {
                $productCategoryIds = collect([$product->category_id]);
                if ($product->relationLoaded('categories')) {
                    $productCategoryIds = $productCategoryIds->merge($product->categories->pluck('id'));
                }
                $productCategoryIds = $productCategoryIds->filter()->unique()->map(fn ($id) => (int) $id)->all();
                $matched = count(array_intersect($productCategoryIds, $couponCategoryIds)) > 0;
            }

            return $matched ? (float) ($item['unit_price'] ?? 0) * (int) ($item['quantity'] ?? 0) : 0;
        });
    }

    private function emptyResult(float $subtotal, ?string $code): array
    {
        return [
            'valid' => false,
            'message' => null,
            'coupon' => null,
            'coupon_id' => null,
            'coupon_code' => $code ? Str::upper(trim((string) $code)) : null,
            'discount' => 0,
            'free_delivery' => false,
            'eligible_subtotal' => 0,
            'subtotal' => $subtotal,
        ];
    }
}
