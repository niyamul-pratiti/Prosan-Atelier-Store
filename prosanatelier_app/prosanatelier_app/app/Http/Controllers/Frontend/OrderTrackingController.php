<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('frontend.order-tracking', ['order' => null, 'searched' => false]);
    }

    public function track(Request $request)
    {
        $data = $request->validate([
            'website' => ['nullable', 'string', 'max:0'],
            'order_number' => ['required', 'string', 'max:50'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $orderNumber = strtoupper(trim($data['order_number']));
        $phone = preg_replace('/\D+/', '', $data['customer_phone'] ?? '');

        $query = Order::with('items')
            ->whereRaw('UPPER(order_number) = ?', [$orderNumber]);

        if ($phone !== '') {
            $query->whereRaw("REPLACE(REPLACE(REPLACE(customer_phone, ' ', ''), '-', ''), '+', '') LIKE ?", ['%' . $phone . '%']);
        }

        $order = $query->first();

        return view('frontend.order-tracking', [
            'order' => $order,
            'searched' => true,
        ]);
    }
}
