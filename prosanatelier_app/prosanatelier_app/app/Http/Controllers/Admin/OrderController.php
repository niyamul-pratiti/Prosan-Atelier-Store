<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\SiteSetting;
use App\Models\Coupon;
use App\Services\CouponDiscountService;
use App\Services\DeliveryChargeCalculator;
use App\Services\SteadfastCourierService;
use App\Support\BangladeshLocations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->when($request->filled('status'), fn ($q) => $q->where('order_status', $request->status))
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = trim($request->q);
                $q->where(function ($inner) use ($search) {
                    $inner->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%");
                });
            })
            ->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('admin.orders.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validatedOrderData($request);

        $order = DB::transaction(function () use ($data) {
            $customer = $this->resolveCustomer($data);
            $totals = $this->calculateOrderTotals($data);

            $order = Order::create($this->filterOrderPayload([
                'customer_id' => $customer?->id,
                'coupon_id' => $totals['coupon_id'] ?? null,
                'coupon_code' => $totals['coupon_code'] ?? null,
                'order_number' => 'PA-' . now()->format('ymd') . '-' . Str::upper(Str::random(6)),
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'] ?? null,
                'address_line' => $data['address_line'],
                'area' => $data['area'] ?? null,
                'city' => $data['city'],
                'shipping_zone' => $data['shipping_zone'],
                'subtotal' => $totals['subtotal'],
                'discount_total' => $totals['discount'],
                'shipping_total' => $totals['shipping'],
                'parcel_weight_grams' => $totals['parcel_weight_grams'],
                'shipping_manually_set' => $totals['shipping_manually_set'],
                'grand_total' => $totals['grand_total'],
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_status'],
                'payment_sender_number' => $data['payment_sender_number'] ?? null,
                'payment_transaction_id' => $data['payment_transaction_id'] ?? null,
                'payment_account' => $data['payment_account'] ?? null,
                'order_status' => $data['order_status'],
                'customer_note' => $data['customer_note'] ?? null,
                'admin_note' => $data['admin_note'] ?? null,
            ]));

            $this->createItemsAndAdjustStock($order, $data['items']);

            return $order;
        });

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['items', 'customer']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('items');
        return view('admin.orders.form', $this->formData($order));
    }

    public function update(Request $request, Order $order)
    {
        $data = $this->validatedOrderData($request);

        DB::transaction(function () use ($order, $data) {
            $order->load('items');
            $this->restoreStockFromOrder($order);

            $customer = $this->resolveCustomer($data);
            $totals = $this->calculateOrderTotals($data);

            $order->update($this->filterOrderPayload([
                'customer_id' => $customer?->id,
                'coupon_id' => $totals['coupon_id'] ?? null,
                'coupon_code' => $totals['coupon_code'] ?? null,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'] ?? null,
                'address_line' => $data['address_line'],
                'area' => $data['area'] ?? null,
                'city' => $data['city'],
                'shipping_zone' => $data['shipping_zone'],
                'subtotal' => $totals['subtotal'],
                'discount_total' => $totals['discount'],
                'shipping_total' => $totals['shipping'],
                'parcel_weight_grams' => $totals['parcel_weight_grams'],
                'shipping_manually_set' => $totals['shipping_manually_set'],
                'grand_total' => $totals['grand_total'],
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_status'],
                'payment_sender_number' => $data['payment_sender_number'] ?? null,
                'payment_transaction_id' => $data['payment_transaction_id'] ?? null,
                'payment_account' => $data['payment_account'] ?? null,
                'order_status' => $data['order_status'],
                'customer_note' => $data['customer_note'] ?? null,
                'admin_note' => $data['admin_note'] ?? null,
            ]));

            $order->items()->delete();
            $this->createItemsAndAdjustStock($order, $data['items']);
        });

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'free_delivery' => ['nullable', 'boolean'],
            'recalculate_delivery' => ['nullable', 'boolean'],
            'shipping_total' => ['required', 'numeric', 'min:0', 'max:100000'],
            'order_status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'completed', 'cancelled'])],
            'payment_status' => ['required', Rule::in(['unpaid', 'paid', 'refunded'])],
            'payment_sender_number' => ['nullable', 'string', 'max:50'],
            'payment_transaction_id' => ['nullable', 'string', 'max:120'],
            'payment_account' => ['nullable', 'string', 'max:150'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->loadMissing('items');
        $delivery = app(DeliveryChargeCalculator::class);
        $subtotal = (float) $order->subtotal;
        $discount = (float) $order->discount_total;
        $zone = $order->shipping_zone ?: $this->zoneFromLocation($order->city ?: 'Dhaka', $order->area);
        $productWeightGrams = $delivery->orderItemsProductWeightGrams($order->items->map(fn ($item) => [
            'product_id' => $item->product_id,
            'product_variation_id' => $item->product_variation_id,
            'quantity' => $item->quantity,
        ])->all());
        $parcelWeightGrams = (int) ($order->parcel_weight_grams ?: $delivery->parcelWeightGrams($productWeightGrams));
        $forceFree = (bool) ($data['free_delivery'] ?? false);
        $recalculate = (bool) ($data['recalculate_delivery'] ?? false);

        if ($forceFree) {
            $shipping = 0;
            $shippingManuallySet = true;
        } elseif ($recalculate) {
            $shipping = $delivery->charge($zone, $subtotal, $productWeightGrams);
            $parcelWeightGrams = $delivery->parcelWeightGrams($productWeightGrams);
            $shippingManuallySet = false;
        } else {
            $shipping = max(0, (float) $data['shipping_total']);
            $shippingManuallySet = true;
        }

        $order->update($this->filterOrderPayload([
            'shipping_zone' => $zone,
            'shipping_total' => $shipping,
            'parcel_weight_grams' => $parcelWeightGrams,
            'shipping_manually_set' => $shippingManuallySet,
            'grand_total' => max(0, $subtotal - $discount + $shipping),
            'order_status' => $data['order_status'],
            'payment_status' => $data['payment_status'],
            'payment_sender_number' => $data['payment_sender_number'] ?? $order->payment_sender_number,
            'payment_transaction_id' => $data['payment_transaction_id'] ?? $order->payment_transaction_id,
            'payment_account' => $data['payment_account'] ?? $order->payment_account,
            'admin_note' => $data['admin_note'] ?? null,
        ]));

        return back()->with('success', 'Order updated.');
    }

    public function invoice(Order $order)
    {
        $order->load(['items', 'customer']);

        return view('admin.orders.invoice', [
            'order' => $order,
            'documentType' => 'Invoice',
            'backUrl' => route('admin.orders.show', $order),
        ]);
    }

    public function packingSlip(Order $order)
    {
        $order->load(['items', 'customer']);

        return view('admin.orders.packing-slip', [
            'order' => $order,
            'documentType' => 'Packing Slip',
        ]);
    }


    public function sendToSteadfast(Order $order, SteadfastCourierService $steadfast)
    {
        if (! $steadfast->enabled()) {
            return back()->with('error', 'Steadfast API credentials are missing or disabled in .env.');
        }

        $order->load('items');

        if (! $order->customer_name || ! $order->customer_phone || ! $order->address_line || ! $order->city) {
            return back()->with('error', 'Customer name, phone, full address and district are required before sending to Steadfast.');
        }

        try {
            $response = $steadfast->createOrder($order);
            $consignment = $steadfast->extractConsignment($response);

            $order->update($this->filterSteadfastPayload([
                'steadfast_consignment_id' => $consignment['consignment_id'] ?? $consignment['id'] ?? $order->steadfast_consignment_id,
                'steadfast_tracking_code' => $consignment['tracking_code'] ?? $consignment['tracking_id'] ?? $order->steadfast_tracking_code,
                'steadfast_status' => $steadfast->extractStatus($response) ?: ($consignment['status'] ?? 'sent'),
                'steadfast_response' => json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'steadfast_sent_at' => now(),
                'steadfast_last_checked_at' => now(),
                'courier_note' => 'COD amount sent to Steadfast: ৳' . number_format($order->codAmountForSteadfast(), 0),
            ]));

            return back()->with('success', 'Order sent to Steadfast successfully. COD amount: ৳' . number_format($order->codAmountForSteadfast(), 0));
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function refreshSteadfast(Order $order, SteadfastCourierService $steadfast)
    {
        if (! $steadfast->enabled()) {
            return back()->with('error', 'Steadfast API credentials are missing or disabled in .env.');
        }

        try {
            $response = $steadfast->checkStatus($order);
            $consignment = $steadfast->extractConsignment($response);

            $order->update($this->filterSteadfastPayload([
                'steadfast_consignment_id' => $consignment['consignment_id'] ?? $consignment['id'] ?? $order->steadfast_consignment_id,
                'steadfast_tracking_code' => $consignment['tracking_code'] ?? $consignment['tracking_id'] ?? $order->steadfast_tracking_code,
                'steadfast_status' => $steadfast->extractStatus($response) ?: $order->steadfast_status,
                'steadfast_response' => json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'steadfast_last_checked_at' => now(),
            ]));

            return back()->with('success', 'Steadfast status refreshed.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }



    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->load('items');

            if ($order->order_status !== 'completed') {
                $this->restoreStockFromOrder($order);
            }

            $order->items()->delete();
            $order->delete();
        });

        return redirect()->route('admin.orders.index')->with('success', 'Order removed successfully. Stock was restored for non-completed orders.');
    }

    private function filterOrderPayload(array $payload): array
    {
        foreach (['payment_sender_number', 'payment_transaction_id', 'payment_account', 'coupon_id', 'coupon_code', 'parcel_weight_grams', 'shipping_manually_set'] as $column) {
            if (! Schema::hasColumn('orders', $column)) {
                unset($payload[$column]);
            }
        }

        return $payload;
    }



    private function filterSteadfastPayload(array $payload): array
    {
        foreach (array_keys($payload) as $column) {
            if (! Schema::hasColumn('orders', $column)) {
                unset($payload[$column]);
            }
        }

        return $payload;
    }

    private function formData(?Order $order = null): array
    {
        $delivery = app(DeliveryChargeCalculator::class);
        $products = Product::active()
            ->with(['variations' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
            ->orderBy('name')
            ->get();

        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $districts = $this->districts();
        $dhakaSuburbanAreas = BangladeshLocations::dhakaSuburbanAreas();
        $paymentMethods = $this->paymentMethods();

        $customerOptions = $customers->map(fn ($customer) => [
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'email' => $customer->email,
            'address_line' => $customer->address_line,
            'area' => $customer->area,
            'city' => $customer->city,
            'district' => $customer->city,
            'shipping_zone' => $customer->shipping_zone,
        ])->values();

        $productOptions = $products->map(fn ($product) => [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => (float) $product->effective_price,
            'cost_price' => (float) ($product->purchase_price ?? 0),
            'stock' => (int) $product->stock_quantity,
            'weight_grams' => $delivery->productWeightGrams($product),
            'search' => Str::lower($product->name . ' ' . $product->sku),
            'variations' => $product->variations->map(fn ($variation) => [
                'id' => $variation->id,
                'name' => $variation->name,
                'sku' => $variation->sku ?: $product->sku,
                'price' => (float) $variation->effective_price,
                'cost_price' => (float) ($product->purchase_price ?? 0),
                'stock' => (int) $variation->stock_quantity,
                'weight_grams' => $delivery->productWeightGrams($product, $variation),
            ])->values(),
        ])->values();

        $deliverySettings = $delivery->settings();

        return compact('order', 'products', 'customers', 'customerOptions', 'productOptions', 'districts', 'dhakaSuburbanAreas', 'paymentMethods', 'deliverySettings');
    }

    private function validatedOrderData(Request $request): array
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'address_line' => ['required', 'string', 'max:1000'],
            'area' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:150'],
            'free_delivery' => ['nullable', 'boolean'],
            'shipping_manually_set' => ['nullable', 'boolean'],
            'shipping_total' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'discount_total' => ['nullable', 'numeric', 'min:0'],
            'coupon_code' => ['nullable', 'string', 'max:80'],
            'payment_method' => ['required', Rule::in(['cod', 'manual_bkash', 'manual_nagad'])],
            'payment_status' => ['required', Rule::in(['unpaid', 'paid', 'refunded'])],
            'payment_sender_number' => ['nullable', 'string', 'max:50'],
            'payment_transaction_id' => ['nullable', 'string', 'max:120'],
            'payment_account' => ['nullable', 'string', 'max:150'],
            'order_status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'completed', 'cancelled'])],
            'customer_note' => ['nullable', 'string', 'max:1000'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.product_variation_id' => ['nullable', 'exists:product_variations,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.cost_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['address_line'] = trim($data['address_line']);
        $data['area'] = trim($data['area']);
        $data['shipping_zone'] = $this->zoneFromLocation($data['city'], $data['area']);
        $data['free_delivery'] = (bool) ($data['free_delivery'] ?? false);
        $data['shipping_manually_set'] = (bool) ($data['shipping_manually_set'] ?? false);
        $data['shipping_total'] = max(0, (float) ($data['shipping_total'] ?? 0));
        $data['discount_total'] = (float) ($data['discount_total'] ?? 0);
        $data['coupon_code'] = trim((string) ($data['coupon_code'] ?? ''));
        $data['payment_account'] = $data['payment_account'] ?: ($this->paymentMethods()[$data['payment_method']]['account'] ?? null);

        return $data;
    }

    private function resolveCustomer(array $data): ?Customer
    {
        $customer = ! empty($data['customer_id']) ? Customer::find($data['customer_id']) : null;

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
                'area' => $data['area'],
                'city' => $data['city'],
                'shipping_zone' => $data['shipping_zone'],
            ]);
            return $customer;
        }

        return Customer::create([
            'name' => $data['customer_name'],
            'email' => $data['customer_email'] ?? null,
            'phone' => $data['customer_phone'],
            'password' => Str::random(16),
            'address_line' => $data['address_line'],
            'area' => $data['area'],
            'city' => $data['city'],
            'shipping_zone' => $data['shipping_zone'],
            'is_active' => true,
        ]);
    }

    private function calculateOrderTotals(array $data): array
    {
        $delivery = app(DeliveryChargeCalculator::class);
        $subtotal = collect($data['items'])->sum(fn ($item) => (float) $item['unit_price'] * (int) $item['quantity']);
        $coupon = app(CouponDiscountService::class)->calculateForItems($data['items'], $data['coupon_code'] ?? null);
        $couponDiscount = (float) ($coupon['discount'] ?? 0);
        $manualDiscount = (float) ($data['discount_total'] ?? 0);
        $discount = min($manualDiscount + $couponDiscount, $subtotal);
        $productWeightGrams = $delivery->orderItemsProductWeightGrams($data['items']);
        $parcelWeightGrams = $delivery->parcelWeightGrams($productWeightGrams);
        $forceFree = (bool) ($data['free_delivery'] ?? false) || (bool) ($coupon['free_delivery'] ?? false);
        $shippingManuallySet = (bool) ($data['shipping_manually_set'] ?? false);

        if ($forceFree) {
            $shipping = 0;
            $shippingManuallySet = true;
        } elseif ($shippingManuallySet) {
            $shipping = max(0, (float) ($data['shipping_total'] ?? 0));
        } else {
            $shipping = $delivery->charge($data['shipping_zone'], $subtotal, $productWeightGrams);
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'parcel_weight_grams' => $parcelWeightGrams,
            'shipping_manually_set' => $shippingManuallySet,
            'grand_total' => max(0, $subtotal - $discount + $shipping),
            'coupon_id' => ($coupon['valid'] ?? false) ? ($coupon['coupon_id'] ?? null) : null,
            'coupon_code' => ($coupon['valid'] ?? false) ? ($coupon['coupon_code'] ?? null) : null,
            'coupon_valid' => (bool) ($coupon['valid'] ?? false),
            'coupon_message' => $coupon['message'] ?? null,
        ];
    }

    private function zoneFromLocation(?string $district, ?string $area = null): string
    {
        return BangladeshLocations::zoneForLocation($district, $area);
    }

    private function createItemsAndAdjustStock(Order $order, array $items): void
    {
        $hasCostColumns = Schema::hasColumn('order_items', 'cost_price') && Schema::hasColumn('order_items', 'cost_total');

        foreach ($items as $item) {
            $product = Product::lockForUpdate()->findOrFail($item['product_id']);
            $variation = null;
            $qty = (int) $item['quantity'];

            if (! empty($item['product_variation_id'])) {
                $variation = ProductVariation::lockForUpdate()
                    ->where('product_id', $product->id)
                    ->findOrFail($item['product_variation_id']);

                if ($variation->stock_quantity < $qty) {
                    throw ValidationException::withMessages(['items' => "Not enough stock for {$product->name} ({$variation->name})."]);
                }

                $variation->decrement('stock_quantity', $qty);
            } else {
                if ($product->stock_quantity < $qty) {
                    throw ValidationException::withMessages(['items' => "Not enough stock for {$product->name}."]);
                }

                $product->decrement('stock_quantity', $qty);
            }

            $unitPrice = (float) $item['unit_price'];
            $row = [
                'product_id' => $product->id,
                'product_variation_id' => $variation?->id,
                'product_name' => $product->name,
                'variation_name' => $variation?->name,
                'sku' => $variation?->sku ?: $product->sku,
                'unit_price' => $unitPrice,
                'quantity' => $qty,
                'line_total' => $unitPrice * $qty,
            ];

            if ($hasCostColumns) {
                $costPrice = (float) ($item['cost_price'] ?? ($product->purchase_price ?? 0));
                if ($costPrice <= 0 && isset($product->purchase_price)) {
                    $costPrice = (float) $product->purchase_price;
                }
                $row['cost_price'] = $costPrice;
                $row['cost_total'] = $costPrice * $qty;
            }

            $order->items()->create($row);
        }
    }

    private function restoreStockFromOrder(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_variation_id) {
                ProductVariation::whereKey($item->product_variation_id)->increment('stock_quantity', $item->quantity);
                continue;
            }

            if ($item->product_id) {
                Product::whereKey($item->product_id)->increment('stock_quantity', $item->quantity);
            }
        }
    }

    private function paymentMethods(): array
    {
        return [
            'cod' => [
                'label' => 'Cash on Delivery',
                'account' => null,
                'note' => 'Customer will pay cash after receiving products.',
            ],
            'manual_bkash' => [
                'label' => 'bKash Personal Payment',
                'account' => SiteSetting::getValue('bkash_number', '01632283178'),
                'note' => 'Customer sends money and provides transaction ID.',
            ],
            'manual_nagad' => [
                'label' => 'Nagad Personal Payment',
                'account' => SiteSetting::getValue('nagad_number', '01323574246'),
                'note' => 'Customer sends money and provides transaction ID.',
            ],
        ];
    }

    private function districts(): array
    {
        return BangladeshLocations::districts();
    }
}
