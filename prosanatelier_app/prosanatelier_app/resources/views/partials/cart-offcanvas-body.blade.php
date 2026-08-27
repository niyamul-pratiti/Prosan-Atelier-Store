<div class="order-md-last prosan-cart-panel-content">
    <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-primary">Your cart</span>
        <span class="badge bg-primary rounded-pill" data-cart-count>{{ $cartCount ?? 0 }}</span>
    </h4>
    @if(empty($cart))
        <p class="text-muted">Your cart is empty.</p>
        <a class="w-100 btn btn-primary btn-lg" href="{{ route('shop.index') }}">Start Shopping</a>
    @else
        <ul class="list-group mb-3">
            @foreach($cart as $item)
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">{{ Str::limit($item['name'], 42) }}</h6>
                        @if(!empty($item['variation_name']))<small class="text-body-secondary d-block">{{ $item['variation_name'] }}</small>@endif
                        <small class="text-body-secondary">Qty: {{ $item['quantity'] }}</small>
                    </div>
                    <span class="text-body-secondary">৳{{ number_format($item['price'] * $item['quantity'], 0) }}</span>
                </li>
            @endforeach
            <li class="list-group-item d-flex justify-content-between"><span>Total (BDT)</span><strong>৳{{ number_format($cartTotal ?? 0, 0) }}</strong></li>
        </ul>
        <a class="w-100 btn btn-primary btn-lg" href="{{ route('checkout.index') }}">Continue to checkout</a>
        <a class="w-100 btn btn-outline-dark mt-2" href="{{ route('cart.index') }}">View cart</a>
    @endif
</div>