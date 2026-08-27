<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Services\DeliveryChargeCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $totals = $this->calculateTotals($cart);

        return view('frontend.cart', compact('cart', 'totals'));
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'variation_id' => ['nullable', 'exists:product_variations,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $product = Product::active()->with(['images', 'activeVariations'])->findOrFail($data['product_id']);
        $variation = null;

        if ($product->is_variable) {
            if (empty($data['variation_id'])) {
                return $this->cartError($request, 'Please select a product option before adding to cart.');
            }

            $variation = ProductVariation::where('product_id', $product->id)
                ->where('is_active', true)
                ->findOrFail($data['variation_id']);
        } elseif (! empty($data['variation_id'])) {
            $variation = ProductVariation::where('product_id', $product->id)
                ->where('is_active', true)
                ->find($data['variation_id']);
        }

        $stock = $variation ? $variation->stock_quantity : $product->stock_quantity;
        if ($stock < $data['quantity']) {
            return $this->cartError($request, 'Requested quantity is not available in stock.');
        }

        $key = $product->id . ':' . ($variation?->id ?: 'simple');
        $cart = session('cart', []);
        $existingQty = $cart[$key]['quantity'] ?? 0;

        if (($existingQty + $data['quantity']) > $stock) {
            return $this->cartError($request, 'Cart quantity exceeds available stock.');
        }

        $price = $variation ? $variation->effective_price : $product->effective_price;

        $cart[$key] = [
            'key' => $key,
            'product_id' => $product->id,
            'variation_id' => $variation?->id,
            'name' => $product->name,
            'variation_name' => $variation?->name,
            'sku' => $variation?->sku ?: $product->sku,
            'price' => $price,
            'quantity' => $existingQty + $data['quantity'],
            'image' => $product->image_url,
            'stock' => $stock,
        ];

        session(['cart' => $cart]);

        if ($request->expectsJson() || $request->ajax()) {
            $cartCount = (int) collect($cart)->sum('quantity');
            $cartTotal = (float) collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

            return response()->json([
                'message' => 'Product added to cart.',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
                'cart_total_formatted' => '৳' . number_format($cartTotal, 0),
                'cart_html' => view('partials.cart-offcanvas-body', compact('cart', 'cartCount', 'cartTotal'))->render(),
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['integer', 'min:1', 'max:100'],
        ]);

        $cart = session('cart', []);

        foreach ($data['items'] as $key => $quantity) {
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = min($quantity, $cart[$key]['stock']);
            }
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $request->validate(['key' => ['required', 'string']]);
        $cart = session('cart', []);
        unset($cart[$request->key]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item removed.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }

    private function calculateTotals(array $cart): array
    {
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $delivery = app(DeliveryChargeCalculator::class);
        $productWeightGrams = $delivery->cartProductWeightGrams($cart);
        $shipping = $delivery->charge('inside_dhaka', $subtotal, $productWeightGrams);

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => 0,
            'grand_total' => $subtotal > 0 ? $subtotal + $shipping : 0,
            'parcel_weight_grams' => $delivery->parcelWeightGrams($productWeightGrams),
        ];
    }

    private function cartError(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => $message], 422);
        }

        return back()->with('error', $message);
    }
}
