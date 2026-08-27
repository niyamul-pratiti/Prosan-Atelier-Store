@extends('layouts.store')
@section('title', 'Order Tracking - Prosan Atelier')
@section('content')
<section class="py-5" style="background-image:url('{{ asset('foodmart/images/background-pattern.jpg') }}');background-size:cover;">
    <div class="container-fluid"><h1 class="display-5 fw-bold">Order Tracking</h1><p class="text-muted">Check your order status using order number and phone.</p></div>
</section>
<section class="py-5">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <form method="POST" action="{{ route('order.tracking.store') }}" class="row g-3">
                        @csrf
                        <div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true"><label>Website</label><input type="text" name="website" value="" tabindex="-1" autocomplete="off"></div>
                        <div class="col-md-7"><label class="form-label">Order Number</label><input class="form-control form-control-lg" name="order_number" value="{{ old('order_number') }}" placeholder="PA-260707-XXXXXX" required></div>
                        <div class="col-md-5"><label class="form-label">Phone</label><input class="form-control form-control-lg" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Optional but recommended"></div>
                        <div class="col-12"><button class="btn btn-primary btn-lg w-100 rounded-pill" type="submit">Track Order</button></div>
                    </form>

                    @if($searched ?? false)
                        @if($order)
                            <div class="order-track-result mt-4">
                                <div class="alert alert-success rounded-4 mb-4"><h4 class="mb-1">Order {{ $order->order_number }}</h4><p class="mb-0">Your order was found successfully.</p></div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4"><div class="track-stat"><span>Status</span><strong>{{ ucfirst($order->order_status) }}</strong></div></div>
                                    <div class="col-md-4"><div class="track-stat"><span>Payment</span><strong>{{ ucfirst($order->payment_status) }}</strong></div></div>
                                    <div class="col-md-4"><div class="track-stat"><span>Total</span><strong>৳{{ number_format($order->grand_total,0) }}</strong></div></div>
                                </div>
                                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Product</th><th>Qty</th><th>Total</th></tr></thead><tbody>@foreach($order->items as $item)<tr><td>{{ $item->product_name }} @if($item->variation_name)<small class="text-muted d-block">{{ $item->variation_name }}</small>@endif</td><td>{{ $item->quantity }}</td><td>৳{{ number_format($item->line_total,0) }}</td></tr>@endforeach</tbody></table></div>
                            </div>
                        @else
                            <div class="alert alert-danger rounded-4 mt-4 mb-0">No order found. Please check your order number and phone number.</div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
