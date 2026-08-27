<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $validOrders = Order::where('order_status', '!=', 'cancelled');

        // Product sales only. Shipping is intentionally excluded from sales/profit.
        $productSales = (clone $validOrders)->sum(DB::raw('GREATEST(subtotal - discount_total, 0)'));
        $shippingCollected = (clone $validOrders)->sum('shipping_total');
        $customerTotalCollected = (clone $validOrders)->sum('grand_total');
        $productCost = 0;

        if (Schema::hasColumn('order_items', 'cost_total')) {
            $productCost = DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->when(Schema::hasColumn('products', 'purchase_price'), function ($query) {
                    $query->leftJoin('products', 'products.id', '=', 'order_items.product_id');
                })
                ->where('orders.order_status', '!=', 'cancelled')
                ->selectRaw($this->productCostExpression() . ' as total_cost')
                ->value('total_cost') ?? 0;
        }

        $stats = [
            'total_sales' => $productSales,
            'customer_total_collected' => $customerTotalCollected,
            'product_sales' => $productSales,
            'product_cost' => $productCost,
            'shipping_collected' => $shippingCollected,
            'estimated_profit' => $productSales - $productCost,
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'products' => Product::count(),
            'customers' => Customer::count(),
            'low_stock' => Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')->count(),
        ];

        $recentOrders = Order::latest()->take(8)->get();
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }

    private function productCostExpression(): string
    {
        if (Schema::hasColumn('products', 'purchase_price')) {
            return 'SUM(CASE WHEN order_items.cost_total IS NOT NULL AND order_items.cost_total > 0 THEN order_items.cost_total ELSE COALESCE(products.purchase_price, 0) * order_items.quantity END)';
        }

        return 'SUM(COALESCE(order_items.cost_total, 0))';
    }
}
