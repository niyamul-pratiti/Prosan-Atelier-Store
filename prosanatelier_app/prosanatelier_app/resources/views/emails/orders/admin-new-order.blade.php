@include('emails.orders.partials-style')
<div class="wrap">
    <div class="card">
        <div class="head"><h1>New order received</h1></div>
        <div class="body">
            <p>A new order has been placed on {{ \App\Models\SiteSetting::getValue('site_name', 'Prosan Atelier') }}.</p>
            <div class="summary">
                <strong>Order:</strong> {{ $order->order_number }}<br>
                <strong>Placed:</strong> {{ $order->created_at ? $order->created_at->copy()->timezone('Asia/Dhaka')->format('d M Y, h:i A') : 'N/A' }} (Dhaka time)<br>
                <strong>Customer:</strong> {{ $order->customer_name }}<br>
                <strong>Phone:</strong> {{ $order->customer_phone }}<br>
                @if(!empty($order->customer_email))<strong>Email:</strong> {{ $order->customer_email }}<br>@endif
                <strong>Address:</strong> {{ $order->address_line ?: 'N/A' }}<br>
                <strong>Area / District:</strong> {{ $order->area ?: 'N/A' }} @if($order->city) / {{ $order->city }} @endif<br>
                <strong>Delivery Zone:</strong> {{ ucwords(str_replace('_', ' ', (string) ($order->shipping_zone ?: 'N/A'))) }}<br>
                <strong>Parcel Weight:</strong> {{ (int) ($order->parcel_weight_grams ?? 0) > 0 ? number_format(((int) $order->parcel_weight_grams) / 1000, 2) . ' kg' : 'Not calculated' }}<br>
                <strong>Payment:</strong> {{ $order->paymentMethodLabel() }} — {{ ucfirst((string) $order->payment_status) }}<br>
                <strong>Order Status:</strong> {{ ucfirst((string) $order->order_status) }}
            </div>
            @if($order->items && $order->items->count())
                <table>
                    <thead><tr><th>Product</th><th>Unit Price</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                {{ $item->product_name }}
                                @if($item->variation_name) <span class="muted">({{ $item->variation_name }})</span> @endif
                                @if($item->sku) <br><span class="muted">SKU: {{ $item->sku }}</span> @endif
                            </td>
                            <td>৳{{ number_format((float) $item->unit_price, 0) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>৳{{ number_format((float) $item->line_total, 0) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
            <div class="summary">
                <strong>Subtotal:</strong> ৳{{ number_format((float) $order->subtotal, 0) }}<br>
                @if((float) $order->discount_total > 0)
                    <strong>Discount:</strong> -৳{{ number_format((float) $order->discount_total, 0) }}<br>
                @endif
                <strong>Delivery Charge:</strong> {{ (float) $order->shipping_total === 0.0 ? 'Free' : '৳' . number_format((float) $order->shipping_total, 0) }}<br>
                <strong>Grand Total:</strong> ৳{{ number_format((float) $order->grand_total, 0) }}
            </div>
            @if(!empty($order->customer_note))
                <div class="summary">
                    <strong>Customer Note:</strong><br>
                    {{ $order->customer_note }}
                </div>
            @endif
            @if($order->payment_method !== 'cod')
                <div class="summary">
                    @if($order->payment_account)<strong>Payment Account:</strong> {{ $order->payment_account }}<br>@endif
                    @if($order->payment_sender_number)<strong>Sender Number:</strong> {{ $order->payment_sender_number }}<br>@endif
                    @if($order->payment_transaction_id)<strong>Transaction ID:</strong> {{ $order->payment_transaction_id }}@endif
                </div>
            @endif
            <a class="btn" href="{{ route('admin.orders.show', $order) }}">Open Order</a>
        </div>
    </div>
    <div class="foot">Admin notification</div>
</div>
