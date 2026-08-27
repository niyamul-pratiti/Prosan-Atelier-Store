@include('emails.orders.partials-style')
<div class="wrap">
    <div class="card">
        <div class="head"><h1>Thank you for your order</h1></div>
        <div class="body">
            <p>Dear {{ $order->customer_name }},</p>
            <p>Your order has been received successfully. We will contact you if anything else is needed.</p>
            <div class="summary">
                <strong>Order:</strong> {{ $order->order_number }}<br>
                <strong>Status:</strong> {{ ucfirst((string) $order->order_status) }}<br>
                <strong>Payment:</strong> {{ ucwords(str_replace('_', ' ', (string) $order->payment_method)) }} — {{ ucfirst((string) $order->payment_status) }}<br>
                <strong>Total:</strong> ৳{{ number_format((float) $order->grand_total, 0) }}
            </div>
            @if($order->items && $order->items->count())
                <table>
                    <thead><tr><th>Product</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }} @if($item->variation_name) <span class="muted">({{ $item->variation_name }})</span> @endif</td>
                            <td>{{ $item->quantity }}</td>
                            <td>৳{{ number_format((float) $item->line_total, 0) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
            <a class="btn" href="{{ route('order.tracking', ['order_number' => $order->order_number, 'phone' => $order->customer_phone]) }}">Track Order</a>
        </div>
    </div>
    <div class="foot">{{ \App\Models\SiteSetting::getValue('site_name', 'Prosan Atelier') }}</div>
</div>
