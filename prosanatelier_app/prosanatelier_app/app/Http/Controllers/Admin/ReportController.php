<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $orders = $this->baseOrders($from, $to);

        $summary = $this->summary($orders);
        $statusBreakdown = $this->statusBreakdown($from, $to);
        $paymentBreakdown = $this->paymentBreakdown($from, $to);
        $topProducts = $this->topProducts($from, $to);
        $lowStockProducts = Product::orderBy('stock_quantity')->take(12)->get();
        $recentCustomers = Customer::withCount('orders')->latest()->take(10)->get();
        $couponUsage = $this->couponUsage($from, $to);
        $steadfastOrders = $this->steadfastOrders($from, $to);
        $dailySales = $this->dailySales($from, $to);

        return view('admin.reports.index', compact(
            'from',
            'to',
            'summary',
            'statusBreakdown',
            'paymentBreakdown',
            'topProducts',
            'lowStockProducts',
            'recentCustomers',
            'couponUsage',
            'steadfastOrders',
            'dailySales'
        ));
    }

    public function export(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $type = $request->query('type', 'orders');
        $filename = 'prosan-' . $type . '-report-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($type, $from, $to) {
            $handle = fopen('php://output', 'w');

            if ($type === 'products') {
                fputcsv($handle, ['Product', 'SKU', 'Brand', 'Stock', 'Low Stock Alert', 'Regular Price', 'Sale Price', 'Purchase Price']);
                Product::with('brand')->orderBy('name')->chunk(200, function ($products) use ($handle) {
                    foreach ($products as $product) {
                        fputcsv($handle, [
                            $product->name,
                            $product->sku,
                            $product->brand->name ?? '',
                            $product->stock_quantity,
                            $product->low_stock_alert,
                            $product->regular_price,
                            $product->sale_price,
                            $product->purchase_price,
                        ]);
                    }
                });
            } elseif ($type === 'customers') {
                fputcsv($handle, ['Name', 'Phone', 'Email', 'District', 'Thana/Area', 'Orders', 'Joined']);
                Customer::withCount('orders')->orderBy('name')->chunk(200, function ($customers) use ($handle) {
                    foreach ($customers as $customer) {
                        fputcsv($handle, [
                            $customer->name,
                            $customer->phone,
                            $customer->email,
                            $customer->city,
                            $customer->area,
                            $customer->orders_count,
                            optional($customer->created_at)->format('Y-m-d'),
                        ]);
                    }
                });
            } else {
                fputcsv($handle, ['Order Number', 'Customer', 'Phone', 'Payment Method', 'Payment Status', 'Order Status', 'Subtotal', 'Discount', 'Shipping', 'Grand Total', 'Created At']);
                $this->baseOrders($from, $to)->latest()->chunk(200, function ($orders) use ($handle) {
                    foreach ($orders as $order) {
                        fputcsv($handle, [
                            $order->order_number,
                            $order->customer_name,
                            $order->customer_phone,
                            $order->payment_method,
                            $order->payment_status,
                            $order->order_status,
                            $order->subtotal,
                            $order->discount_total,
                            $order->shipping_total,
                            $order->grand_total,
                            optional($order->created_at)->format('Y-m-d H:i:s'),
                        ]);
                    }
                });
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function dateRange(Request $request): array
    {
        $from = $request->filled('from') ? Carbon::parse($request->query('from'))->startOfDay() : now()->subDays(29)->startOfDay();
        $to = $request->filled('to') ? Carbon::parse($request->query('to'))->endOfDay() : now()->endOfDay();

        return [$from, $to];
    }

    private function baseOrders(Carbon $from, Carbon $to)
    {
        return Order::whereBetween('created_at', [$from, $to])->where('order_status', '!=', 'cancelled');
    }

    private function summary($orders): array
    {
        $productSales = (clone $orders)->sum(DB::raw('GREATEST(subtotal - COALESCE(discount_total, 0), 0)'));
        $shippingCollected = (clone $orders)->sum('shipping_total');
        $discountTotal = (clone $orders)->sum('discount_total');
        $grandTotal = (clone $orders)->sum('grand_total');
        $orderCount = (clone $orders)->count();
        $paidOrders = (clone $orders)->where('payment_status', 'paid')->count();
        $pendingOrders = (clone $orders)->whereIn('order_status', ['pending', 'processing'])->count();
        $productCost = $this->productCost((clone $orders));

        return [
            'product_sales' => $productSales,
            'product_cost' => $productCost,
            'profit' => $productSales - $productCost,
            'shipping_collected' => $shippingCollected,
            'discount_total' => $discountTotal,
            'grand_total' => $grandTotal,
            'order_count' => $orderCount,
            'paid_orders' => $paidOrders,
            'pending_orders' => $pendingOrders,
            'average_order' => $orderCount > 0 ? $grandTotal / $orderCount : 0,
        ];
    }

    private function productCost($orders): float
    {
        if (! Schema::hasColumn('order_items', 'cost_total')) {
            return 0;
        }

        return (float) DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->when(Schema::hasColumn('products', 'purchase_price'), function ($query) {
                $query->leftJoin('products', 'products.id', '=', 'order_items.product_id');
            })
            ->whereIn('orders.id', $orders->pluck('id'))
            ->selectRaw($this->productCostExpression() . ' as total_cost')
            ->value('total_cost');
    }

    private function productCostExpression(): string
    {
        if (Schema::hasColumn('products', 'purchase_price')) {
            return 'SUM(CASE WHEN order_items.cost_total IS NOT NULL AND order_items.cost_total > 0 THEN order_items.cost_total ELSE COALESCE(products.purchase_price, 0) * order_items.quantity END)';
        }

        return 'SUM(COALESCE(order_items.cost_total, 0))';
    }

    private function statusBreakdown(Carbon $from, Carbon $to)
    {
        return Order::select('order_status', DB::raw('COUNT(*) as total'), DB::raw('SUM(grand_total) as amount'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('order_status')
            ->orderByDesc('total')
            ->get();
    }

    private function paymentBreakdown(Carbon $from, Carbon $to)
    {
        return Order::select('payment_method', DB::raw('COUNT(*) as total'), DB::raw('SUM(grand_total) as amount'))
            ->whereBetween('created_at', [$from, $to])
            ->where('order_status', '!=', 'cancelled')
            ->groupBy('payment_method')
            ->orderByDesc('amount')
            ->get();
    }

    private function topProducts(Carbon $from, Carbon $to)
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->where('orders.order_status', '!=', 'cancelled')
            ->select('order_items.product_name', 'order_items.sku', DB::raw('SUM(order_items.quantity) as qty'), DB::raw('SUM(order_items.line_total) as sales'))
            ->groupBy('order_items.product_name', 'order_items.sku')
            ->orderByDesc('qty')
            ->limit(12)
            ->get();
    }

    private function couponUsage(Carbon $from, Carbon $to)
    {
        if (! Schema::hasColumn('orders', 'coupon_code')) {
            return collect();
        }

        return Order::select('coupon_code', DB::raw('COUNT(*) as total'), DB::raw('SUM(discount_total) as discount'))
            ->whereBetween('created_at', [$from, $to])
            ->whereNotNull('coupon_code')
            ->where('coupon_code', '!=', '')
            ->groupBy('coupon_code')
            ->orderByDesc('discount')
            ->get();
    }

    private function steadfastOrders(Carbon $from, Carbon $to)
    {
        if (! Schema::hasColumn('orders', 'steadfast_consignment_id')) {
            return collect();
        }

        return Order::whereBetween('created_at', [$from, $to])
            ->where(function ($query) {
                $query->whereNotNull('steadfast_consignment_id')
                    ->orWhereNotNull('steadfast_tracking_code');
            })
            ->latest()
            ->take(15)
            ->get();
    }

    private function dailySales(Carbon $from, Carbon $to)
    {
        return Order::selectRaw('DATE(created_at) as report_date, COUNT(*) as orders, SUM(GREATEST(subtotal - COALESCE(discount_total, 0), 0)) as product_sales, SUM(shipping_total) as shipping, SUM(grand_total) as total')
            ->whereBetween('created_at', [$from, $to])
            ->where('order_status', '!=', 'cancelled')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('report_date')
            ->get();
    }
}
