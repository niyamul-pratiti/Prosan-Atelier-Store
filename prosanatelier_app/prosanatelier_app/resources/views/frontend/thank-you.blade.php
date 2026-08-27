@extends('layouts.store')
@section('title', 'Thank You - Prosan Atelier')
@section('content')
@php
    $method = $paymentMethods[$order->payment_method] ?? ['label' => $order->paymentMethodLabel(), 'account' => $order->payment_account, 'note' => ''];
@endphp
<section class="py-5" style="background-image:url('{{ asset('foodmart/images/background-pattern.jpg') }}');background-size:cover;">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5 thank-you-card-prosan">
                    <svg width="64" height="64" class="text-primary mx-auto mb-3"><use xlink:href="#check"></use></svg>
                    <h1>Thank you for your order!</h1>
                    <p>Your order number is <strong>{{ $order->order_number }}</strong>.</p>
                    @if($order->coupon_code)<p>Coupon: <strong>{{ $order->coupon_code }}</strong> saved you ৳{{ number_format($order->discount_total,0) }}</p>@endif
                    <p>Total: <strong>৳{{ number_format($order->grand_total,0) }}</strong></p>
                    <div class="payment-summary-box-prosan text-start mx-auto my-4">
                        <h4>Payment</h4>
                        <p><strong>Method:</strong> {{ $method['label'] }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                        @if($order->payment_method !== 'cod')
                            <p><strong>Pay To:</strong> {{ $order->payment_account ?: ($method['account'] ?? 'N/A') }}</p>
                            <p><strong>Sender/Account Number:</strong> {{ $order->payment_sender_number ?: 'N/A' }}</p>
                            <p><strong>Transaction/Reference ID:</strong> {{ $order->payment_transaction_id ?: 'N/A' }}</p>
                            <div class="alert alert-warning rounded-4 mb-0">We received your payment information. Admin will verify it and mark the order as paid.</div>
                        @else
                            <div class="alert alert-info rounded-4 mb-0">Please pay with cash when you receive your products.</div>
                        @endif
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a class="btn btn-primary btn-lg" target="_blank" href="{{ route('checkout.invoice', $order->order_number) }}">Download Invoice</a>
                        <a class="btn btn-outline-dark btn-lg" href="{{ route('order.tracking') }}">Track Order</a>
                        <a class="btn btn-outline-dark btn-lg" href="{{ route('shop.index') }}">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
