<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->order_number }} - {{ $siteSettings['site_name'] ?? 'Prosan Atelier' }}</title>
    <style>
        :root { --gold:#cf8f1d; --brown:#5a3517; --ink:#111827; --muted:#6b7280; --line:#eadfcd; --soft:#fff8ea; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #f6f3ec; color: var(--ink); font-family: Arial, Helvetica, sans-serif; font-size: 14px; }
        .toolbar { max-width: 920px; margin: 18px auto 0; display:flex; justify-content:flex-end; gap:10px; }
        .btn { display:inline-flex; align-items:center; justify-content:center; min-height:38px; padding:9px 16px; border-radius:999px; border:1px solid var(--gold); background:var(--gold); color:#fff; font-weight:700; text-decoration:none; cursor:pointer; }
        .btn.ghost { background:#fff; color:var(--brown); }
        .sheet { width: 920px; max-width: calc(100% - 30px); margin: 18px auto 30px; background: #fff; padding: 34px; border: 1px solid var(--line); border-radius: 18px; box-shadow: 0 20px 60px rgba(0,0,0,.06); }
        .top { display:flex; justify-content:space-between; align-items:flex-start; gap:24px; border-bottom: 2px solid var(--line); padding-bottom: 24px; }
        .brand { display:flex; align-items:center; gap:16px; }
        .brand img { width:86px; height:86px; object-fit:contain; }
        .brand h1 { margin:0; color:var(--brown); font-size:28px; letter-spacing:.02em; }
        .brand p { margin:5px 0 0; color:var(--muted); }
        .doc-title { text-align:right; }
        .doc-title h2 { margin:0; font-size:34px; color:var(--gold); text-transform:uppercase; letter-spacing:.08em; }
        .doc-title p { margin:8px 0 0; color:var(--muted); }
        .meta-grid { display:grid; grid-template-columns:1fr 1fr; gap:22px; margin-top:26px; }
        .box { border:1px solid var(--line); border-radius:14px; padding:18px; background:#fffdf8; }
        .box h3 { margin:0 0 12px; color:var(--brown); font-size:16px; text-transform:uppercase; letter-spacing:.05em; }
        .box p { margin:6px 0; }
        .muted { color:var(--muted); }
        table { width:100%; border-collapse:collapse; margin-top:26px; }
        th { background:var(--soft); color:var(--brown); text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:.05em; padding:12px; border-bottom:1px solid var(--line); }
        td { padding:13px 12px; border-bottom:1px solid var(--line); vertical-align:top; }
        .num { text-align:right; white-space:nowrap; }
        .summary { width:340px; max-width:100%; margin-left:auto; margin-top:20px; border:1px solid var(--line); border-radius:14px; padding:16px 18px; background:#fffdf8; }
        .summary-row { display:flex; justify-content:space-between; gap:18px; padding:7px 0; }
        .summary-row.total { border-top:1px solid var(--line); margin-top:8px; padding-top:14px; font-size:22px; font-weight:800; color:var(--brown); }
        .note { margin-top:28px; padding:16px 18px; border-left:4px solid var(--gold); background:#fff8ea; border-radius:10px; color:var(--brown); }
        .footer { margin-top:30px; padding-top:18px; border-top:1px solid var(--line); display:flex; justify-content:space-between; gap:20px; color:var(--muted); font-size:13px; }
        @media print { body { background:#fff; } .toolbar { display:none !important; } .sheet { width:100%; max-width:100%; margin:0; padding:20px; border:0; border-radius:0; box-shadow:none; } @page { margin: 12mm; } }
        @media (max-width:700px) { .top, .meta-grid, .footer { grid-template-columns:1fr; flex-direction:column; } .doc-title { text-align:left; } .sheet { padding:22px; } table { font-size:12px; } th, td { padding:9px 7px; } }
    </style>
</head>
<body>
@php
    $docSiteName = $siteSettings['site_name'] ?? 'Prosan Atelier';
    $docTagline = $siteSettings['site_tagline'] ?? 'Everyday essentials, thoughtfully chosen.';
    $docLogoUrl = \App\Models\SiteSetting::imageUrl($siteSettings['site_logo'] ?? null, 'images/prosan-logo.jpg');
    $docSupportPhone = $siteSettings['support_phone'] ?? '01410283178';
@endphp
    <div class="toolbar">
        <a class="btn ghost" href="{{ $backUrl ?? route('admin.orders.show', $order) }}">Back to Order</a>
        <button class="btn" onclick="window.print()">Print / Save PDF</button>
    </div>
    <main class="sheet">
        <section class="top">
            <div class="brand">
                <img src="{{ $docLogoUrl }}" alt="{{ $docSiteName }}">
                <div>
                    <h1>{{ $docSiteName }}</h1>
                    <p>{{ $docTagline }}</p>
                    <p><strong>Support:</strong> {{ $docSupportPhone }}</p>
                </div>
            </div>
            <div class="doc-title">
                <h2>{{ $documentType ?? 'Invoice' }}</h2>
                <p><strong>{{ $order->order_number }}</strong></p>
                <p>{{ optional($order->created_at)->format('d M Y, h:i A') }}</p>
            </div>
        </section>

        <section class="meta-grid">
            <div class="box">
                <h3>Bill To</h3>
                <p><strong>{{ $order->customer_name }}</strong></p>
                <p>Phone: {{ $order->customer_phone }}</p>
                <p>Email: {{ $order->customer_email ?: 'N/A' }}</p>
                <p>Address: {{ $order->address_line ?: 'N/A' }}</p>
                <p>Thana / Area: {{ $order->area ?: 'N/A' }}</p>
                <p>District: {{ $order->city ?: 'Dhaka' }}</p>
            </div>
            <div class="box">
                <h3>Order & Payment</h3>
                <p>Status: <strong>{{ ucfirst($order->order_status) }}</strong></p>
                <p>Payment: <strong>{{ $order->paymentMethodLabel() }}</strong></p>
                <p>Payment Status: <strong>{{ ucfirst($order->payment_status) }}</strong></p>
                @if($order->payment_method !== 'cod')
                    <p>Payment Account: {{ $order->payment_account ?: 'N/A' }}</p>
                    <p>Sender Number: {{ $order->payment_sender_number ?: 'N/A' }}</p>
                    <p>Transaction ID: {{ $order->payment_transaction_id ?: 'N/A' }}</p>
                @endif
            </div>
        </section>

        <table>
            <thead>
                <tr><th>Product</th><th>SKU</th><th class="num">Price</th><th class="num">Qty</th><th class="num">Total</th></tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->variation_name)<br><span class="muted">{{ $item->variation_name }}</span>@endif
                        </td>
                        <td>{{ $item->sku ?: 'N/A' }}</td>
                        <td class="num">৳{{ number_format($item->unit_price, 0) }}</td>
                        <td class="num">{{ $item->quantity }}</td>
                        <td class="num">৳{{ number_format($item->line_total, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <section class="summary">
            <div class="summary-row"><span>Subtotal</span><strong>৳{{ number_format($order->subtotal, 0) }}</strong></div>
            @if($order->coupon_code)<div class="summary-row"><span>Coupon ({{ $order->coupon_code }})</span><strong>-৳{{ number_format($order->discount_total, 0) }}</strong></div>@else<div class="summary-row"><span>Discount</span><strong>৳{{ number_format($order->discount_total, 0) }}</strong></div>@endif
            <div class="summary-row"><span>Shipping</span><strong>{{ (float)$order->shipping_total === 0.0 ? 'Free' : '৳' . number_format($order->shipping_total, 0) }}</strong></div>
            <div class="summary-row total"><span>Total</span><span>৳{{ number_format($order->grand_total, 0) }}</span></div>
        </section>

        @if($order->customer_note || $order->admin_note)
            <div class="note">
                @if($order->customer_note)<p><strong>Customer Note:</strong> {{ $order->customer_note }}</p>@endif
                @if($order->admin_note)<p><strong>Admin Note:</strong> {{ $order->admin_note }}</p>@endif
            </div>
        @endif

        <section class="footer">
            <span>Thank you for shopping with {{ $docSiteName }}.</span>
            <span>Generated on {{ now()->format('d M Y, h:i A') }}</span>
        </section>
    </main>
</body>
</html>
