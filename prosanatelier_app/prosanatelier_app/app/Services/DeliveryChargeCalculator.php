<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\SiteSetting;

class DeliveryChargeCalculator
{
    public function settings(): array
    {
        return [
            'weight_based_enabled' => SiteSetting::boolValue('weight_based_shipping_enabled', true),
            'inside_dhaka' => max(0, SiteSetting::intValue('inside_dhaka_shipping', 70)),
            'dhaka_suburban' => max(0, SiteSetting::intValue('dhaka_suburban_shipping', 100)),
            'outside_dhaka' => max(0, SiteSetting::intValue('outside_dhaka_shipping', 130)),
            'included_weight_grams' => max(1, SiteSetting::intValue('shipping_included_weight_grams', 1000)),
            'packaging_weight_grams' => max(0, SiteSetting::intValue('shipping_packaging_weight_grams', 200)),
            'additional_per_kg' => max(0, SiteSetting::intValue('shipping_additional_per_kg', 20)),
            'free_minimum' => max(0, SiteSetting::intValue('free_delivery_minimum', 5000)),
        ];
    }

    public function charge(string $zone, float|int $subtotal, int $productWeightGrams = 0, bool $forceFree = false): int
    {
        $settings = $this->settings();

        if ((float) $subtotal <= 0) {
            return 0;
        }

        if ($forceFree || ($settings['free_minimum'] > 0 && (float) $subtotal >= $settings['free_minimum'])) {
            return 0;
        }

        $baseCharge = match (strtolower(trim($zone))) {
            'outside_dhaka', 'outside dhaka', 'outside' => $settings['outside_dhaka'],
            'dhaka_suburban', 'dhaka suburban', 'sub_dhaka', 'sub dhaka', 'suburban' => $settings['dhaka_suburban'],
            default => $settings['inside_dhaka'],
        };

        if (! $settings['weight_based_enabled']) {
            return $baseCharge;
        }

        $parcelWeightGrams = $this->parcelWeightGrams($productWeightGrams);
        $excessGrams = max(0, $parcelWeightGrams - $settings['included_weight_grams']);
        $additionalKilograms = $excessGrams > 0 ? (int) ceil($excessGrams / 1000) : 0;

        return $baseCharge + ($additionalKilograms * $settings['additional_per_kg']);
    }

    public function parcelWeightGrams(int $productWeightGrams): int
    {
        $settings = $this->settings();

        if (! $settings['weight_based_enabled']) {
            return max(0, $productWeightGrams);
        }

        return max(0, $productWeightGrams) + $settings['packaging_weight_grams'];
    }

    public function cartProductWeightGrams(array $cart): int
    {
        if (empty($cart)) {
            return 0;
        }

        $products = Product::with('variations')
            ->whereIn('id', collect($cart)->pluck('product_id')->filter()->unique()->values())
            ->get()
            ->keyBy('id');

        $total = 0;

        foreach ($cart as $item) {
            $product = $products->get((int) ($item['product_id'] ?? 0));
            if (! $product) {
                continue;
            }

            $variation = ! empty($item['variation_id'])
                ? $product->variations->firstWhere('id', (int) $item['variation_id'])
                : null;

            $total += $this->productWeightGrams($product, $variation) * max(0, (int) ($item['quantity'] ?? 0));
        }

        return $total;
    }

    public function orderItemsProductWeightGrams(array $items): int
    {
        if (empty($items)) {
            return 0;
        }

        $products = Product::with('variations')
            ->whereIn('id', collect($items)->pluck('product_id')->filter()->unique()->values())
            ->get()
            ->keyBy('id');

        $total = 0;

        foreach ($items as $item) {
            $product = $products->get((int) ($item['product_id'] ?? 0));
            if (! $product) {
                continue;
            }

            $variationId = $item['product_variation_id'] ?? $item['variation_id'] ?? null;
            $variation = $variationId
                ? $product->variations->firstWhere('id', (int) $variationId)
                : null;

            $total += $this->productWeightGrams($product, $variation) * max(0, (int) ($item['quantity'] ?? 0));
        }

        return $total;
    }

    public function productWeightGrams(Product $product, ?ProductVariation $variation = null): int
    {
        if ($variation && (float) ($variation->weight ?? 0) > 0) {
            return $this->toGrams($variation->weight, $variation->unit);
        }

        return $this->toGrams($product->weight, $product->unit);
    }

    public function toGrams(float|int|string|null $weight, ?string $unit): int
    {
        $value = is_numeric($weight) ? (float) $weight : 0;
        if ($value <= 0) {
            return 0;
        }

        $normalizedUnit = strtolower(trim((string) $unit));
        $normalizedUnit = str_replace(['.', ' ', '-'], '', $normalizedUnit);

        $grams = match ($normalizedUnit) {
            'kg', 'kgs', 'kilogram', 'kilograms' => $value * 1000,
            'g', 'gm', 'gms', 'gram', 'grams' => $value,
            'mg', 'milligram', 'milligrams' => $value / 1000,
            'lb', 'lbs', 'pound', 'pounds' => $value * 453.59237,
            'oz', 'ounce', 'ounces' => $value * 28.349523,
            'ml', 'milliliter', 'milliliters', 'millilitre', 'millilitres' => $value,
            'l', 'ltr', 'liter', 'liters', 'litre', 'litres' => $value * 1000,
            default => 0,
        };

        return max(0, (int) round($grams));
    }
}
