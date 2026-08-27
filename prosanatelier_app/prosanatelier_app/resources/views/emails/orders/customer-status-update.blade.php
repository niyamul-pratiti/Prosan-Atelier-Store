@include('emails.orders.partials-style')
<div class="wrap">
    <div class="card">
        <div class="head"><h1>Your order has been updated</h1></div>
        <div class="body">
            <p>Dear {{ $order->customer_name }},</p>
            <p>Your order status has been updated.</p>
            <div class="summary">
                <strong>Order:</strong> {{ $order->order_number }}<br>
                <strong>Order Status:</strong> {{ ucfirst((string) $order->order_status) }}<br>
                <strong>Payment Status:</strong> {{ ucfirst((string) $order->payment_status) }}<br>
                <strong>Total:</strong> ৳{{ number_format((float) $order->grand_total, 0) }}
            </div>
            <a class="btn" href="{{ route('order.tracking', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}">Track Order</a>
        </div>
    </div>
    <div class="foot">{{ \App\Models\SiteSetting::getValue('site_name', 'Prosan Atelier') }}</div>
</div>
