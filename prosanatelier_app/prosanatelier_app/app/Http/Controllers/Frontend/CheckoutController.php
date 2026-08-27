<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Rules\AllowedPublicEmail;
use App\Services\DeliveryChargeCalculator;
use App\Support\BangladeshLocations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $customer = $this->currentCustomer();
        $districtAreas = BangladeshLocations::districtAreas();
        $selectedDistrict = old('city', $customer?->city);
        $selectedDistrict = array_key_exists((string) $selectedDistrict, $districtAreas) ? $selectedDistrict : '';
        $selectedArea = old('area', $customer?->area);
        $selectedArea = in_array((string) $selectedArea, $districtAreas[$selectedDistrict] ?? [], true) ? $selectedArea : '';
        $shippingZone = $selectedDistrict
            ? BangladeshLocations::zoneForLocation($selectedDistrict, $selectedArea)
            : 'inside_dhaka';
        $totals = $this->calculateTotals($cart, $shippingZone);
        $shippingSettings = $this->shippingSettings();
        $dhakaSuburbanAreas = BangladeshLocations::dhakaSuburbanAreas();

        return view('frontend.checkout', compact(
            'cart',
            'totals',
            'customer',
            'districtAreas',
            'selectedDistrict',
            'selectedArea',
            'shippingSettings',
            'dhakaSuburbanAreas'
        ));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'website' => ['nullable', 'string', 'max:0'],
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email:rfc', 'max:150', new AllowedPublicEmail],
            'address_line' => ['required', 'string', 'max:1000'],
            'city' => ['required', 'string', Rule::in(BangladeshLocations::districts())],
            'area' => [
                'required',
                'string',
                'max:150',
                Rule::in(BangladeshLocations::areasFor($request->input('city'))),
            ],
            'customer_note' => ['nullable', 'string', 'max:1000'],
        ]);

        unset($data['website']);
        $data['shipping_zone'] = BangladeshLocations::zoneForLocation($data['city'], $data['area']);

        if (! empty($data['customer_email'])) {
            $data['customer_email'] = strtolower(trim($data['customer_email']));
        }

        $customer = $this->findOrCreateCustomerFromCheckout($data);
        $totals = $this->calculateTotals($cart, $data['shipping_zone']);
        $order = null;

        DB::transaction(function () use (&$order, $cart, $totals, $data, $customer) {
            $orderPayload = [
                ...$data,
                'customer_id' => $customer?->id,
                'order_number' => 'PA-' . now()->format('ymd') . '-' . Str::upper(Str::random(6)),
                'subtotal' => $totals['subtotal'],
                'discount_total' => $totals['discount'],
                'shipping_total' => $totals['shipping'],
                'grand_total' => $totals['grand_total'],
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
            ];

            if (Schema::hasColumn('orders', 'parcel_weight_grams')) {
                $orderPayload['parcel_weight_grams'] = $totals['parcel_weight_grams'];
            }

            if (Schema::hasColumn('orders', 'shipping_manually_set')) {
                $orderPayload['shipping_manually_set'] = false;
            }

            $order = Order::create($orderPayload);

            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $variation = null;

                if ($product->product_type === 'variable' && empty($item['variation_id'])) {
                    throw new \RuntimeException("Please select an option for {$product->name}.");
                }

                if (! empty($item['variation_id'])) {
                    $variation = ProductVariation::lockForUpdate()->where('product_id', $product->id)->where('is_active', true)->findOrFail($item['variation_id']);
                    if ($variation->stock_quantity < $item['quantity']) {
                        throw new \RuntimeException("{$product->name} stock is not available.");
                    }
                    $variation->decrement('stock_quantity', $item['quantity']);
                } else {
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \RuntimeException("{$product->name} stock is not available.");
                    }
                    $product->decrement('stock_quantity', $item['quantity']);
                }

                $unitPrice = $variation ? $variation->effective_price : $product->effective_price;
                $unitCost = $variation && isset($variation->purchase_price) && (float) $variation->purchase_price > 0
                    ? (float) $variation->purchase_price
                    : (float) ($product->purchase_price ?? 0);

                $orderItemPayload = [
                    'product_id' => $product->id,
                    'product_variation_id' => $variation?->id,
                    'product_name' => $product->name,
                    'variation_name' => $variation?->name,
                    'sku' => $variation?->sku ?: $product->sku,
                    'unit_price' => $unitPrice,
                    'quantity' => $item['quantity'],
                    'line_total' => $unitPrice * $item['quantity'],
                ];

                if (Schema::hasColumn('order_items', 'unit_cost')) {
                    $orderItemPayload['unit_cost'] = $unitCost;
                }

                if (Schema::hasColumn('order_items', 'cost_total')) {
                    $orderItemPayload['cost_total'] = $unitCost * $item['quantity'];
                }

                $order->items()->create($orderItemPayload);
            }
        });

        if ($customer) {
            session(['customer_id' => $customer->id]);
        }

        session()->forget('cart');

        return redirect()->route('checkout.thank_you', $order->order_number);
    }

    public function thankYou(string $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view('frontend.thank-you', compact('order'));
    }

    private function calculateTotals(array $cart, ?string $shippingZone = null): array
    {
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $delivery = app(DeliveryChargeCalculator::class);
        $productWeightGrams = $delivery->cartProductWeightGrams($cart);
        $parcelWeightGrams = $delivery->parcelWeightGrams($productWeightGrams);
        $shipping = $delivery->charge($shippingZone ?: 'inside_dhaka', $subtotal, $productWeightGrams);

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => 0,
            'grand_total' => $subtotal > 0 ? $subtotal + $shipping : 0,
            'product_weight_grams' => $productWeightGrams,
            'parcel_weight_grams' => $parcelWeightGrams,
        ];
    }

    private function shippingSettings(): array
    {
        return app(DeliveryChargeCalculator::class)->settings();
    }

    private function currentCustomer(): ?Customer
    {
        $id = session('customer_id');
        return $id ? Customer::find($id) : null;
    }

    private function findOrCreateCustomerFromCheckout(array $data): ?Customer
    {
        $customer = $this->currentCustomer();

        if (! $customer && ! empty($data['customer_phone'])) {
            $customer = Customer::where('phone', $data['customer_phone'])->first();
        }

        if (! $customer && ! empty($data['customer_email'])) {
            $customer = Customer::where('email', $data['customer_email'])->first();
        }

        if ($customer) {
            $customer->update([
                'name' => $data['customer_name'],
                'email' => $data['customer_email'] ?: $customer->email,
                'phone' => $data['customer_phone'],
                'address_line' => $data['address_line'],
                'area' => $data['area'] ?? null,
                'city' => $data['city'] ?? null,
                'shipping_zone' => $data['shipping_zone'],
            ]);
            return $customer;
        }

        return Customer::create([
            'name' => $data['customer_name'],
            'email' => $data['customer_email'] ?: null,
            'phone' => $data['customer_phone'],
            'password' => Str::random(16),
            'address_line' => $data['address_line'],
            'area' => $data['area'] ?? null,
            'city' => $data['city'] ?? null,
            'shipping_zone' => $data['shipping_zone'],
            'is_active' => true,
        ]);
    }
}
