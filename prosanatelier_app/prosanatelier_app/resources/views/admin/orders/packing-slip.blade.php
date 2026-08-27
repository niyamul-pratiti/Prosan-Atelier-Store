<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Packing Slip {{ $order->order_number }} - {{ $siteSettings['site_name'] ?? 'Prosan Atelier' }}</title>
    <style>
        :root { --gold:#cf8f1d; --brown:#5a3517; --ink:#111827; --muted:#6b7280; --line:#eadfcd; --soft:#fff8ea; }
        * { box-sizing:border-box; }
        body { margin:0; background:#f6f3ec; color:var(--ink); font-family:Arial, Helvetica, sans-serif; font-size:14px; }
        .toolbar { max-width:920px; margin:18px auto 0; display:flex; justify-content:flex-end; gap:10px; }
        .btn { display:inline-flex; align-items:center; justify-content:center; min-height:38px; padding:9px 16px; border-radius:999px; border:1px solid var(--gold); background:var(--gold); color:#fff; font-weight:700; text-decoration:none; cursor:pointer; }
        .btn.ghost { background:#fff; color:var(--brown); }
        .sheet { width:920px; max-width:calc(100% - 30px); margin:18px auto 30px; background:#fff; padding:34px; border:1px solid var(--line); border-radius:18px; box-shadow:0 20px 60px rgba(0,0,0,.06); }
        .top { display:flex; justify-content:space-between; align-items:flex-start; gap:24px; border-bottom:2px solid var(--line); padding-bottom:24px; }
        .brand { display:flex; align-items:center; gap:16px; }
        .brand img { width:78px; height:78px; object-fit:contain; }
        h1, h2, h3 { color:var(--brown); margin-top:0; }
        .doc-title { text-align:right; }
        .doc-title h1 { color:var(--gold); text-transform:uppercase; letter-spacing:.08em; margin:0; }
        .meta-grid { display:grid; grid-template-columns:1fr 1fr; gap:22px; margin-top:26px; }
        .box { border:1px solid var(--line); border-radius:14px; padding:18px; background:#fffdf8; }
        .box p { margin:6px 0; }
        .muted { color:var(--muted); }
        table { width:100%; border-collapse:collapse; margin-top:26px; }
        th { background:var(--soft); color:var(--brown); text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:.05em; padding:12px; border-bottom:1px solid var(--line); }
        td { padding:14px 12px; border-bottom:1px solid var(--line); vertical-align:top; }
        .qty { font-size:22px; font-weight:800; text-align:center; color:var(--brown); }
        .check { width:48px; text-align:center; }
        .check-box { width:22px; height:22px; border:2px solid var(--brown); display:inline-block; border-radius:4px; }
        .note { margin-top:28px; padding:16px 18px; border-left:4px solid var(--gold); background:#fff8ea; border-radius:10px; color:var(--brown); }
        .footer { margin-top:34px; display:grid; grid-template-columns:1fr 1fr; gap:30px; }
        .sign { border-top:1px solid var(--line); padding-top:10px; color:var(--muted); text-align:center; }
        @media print { body { background:#fff; } .toolbar { display:none !important; } .sheet { width:100%; max-width:100%; margin:0; padding:20px; border:0; border-radius:0; box-shadow:none; } @page { margin: 12mm; } }
        @media (max-width:700px) { .top, .meta-grid, .footer { grid-template-columns:1fr; flex-direction:column; } .doc-title { text-align:left; } .sheet { padding:22px; } th,td{padding:9px 7px;} }
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
        <a class="btn ghost" href="{{ route('admin.orders.show', $order) }}">Back to Order</a>
        <button class="btn" onclick="window.print()">Print Packing Slip</button>
    </div>
    <main class="sheet">
        <section class="top">
            <div class="brand">
                <img src="{{ $docLogoUrl }}" alt="{{ $docSiteName }}">
                <div>
                    <h2>{{ $docSiteName }}</h2>
                    <p class="muted">Packing checklist for delivery.</p>
                    <p><strong>Support:</strong> {{ $docSupportPhone }}</p>
                </div>
            </div>
            <div class="doc-title">
                <h1>Packing Slip</h1>
                <p><strong>{{ $order->order_number }}</strong></p>
                <p>{{ optional($order->created_at)->format('d M Y, h:i A') }}</p>
            </div>
        </section>

        <section class="meta-grid">
            <div class="box">
                <h3>Delivery Information</h3>
                <p><strong>{{ $order->customer_name }}</strong></p>
                <p>Phone: {{ $order->customer_phone }}</p>
                <p>Address: {{ $order->address_line ?: 'N/A' }}</p>
                <p>Thana / Area: {{ $order->area ?: 'N/A' }}</p>
                <p>District: {{ $order->city ?: 'Dhaka' }}</p>
                <p>Shipping: {{ (float)$order->shipping_total === 0.0 ? 'Free' : '৳' . number_format($order->shipping_total, 0) }}</p>
            </div>
            <div class="box">
                <h3>Order Status</h3>
                <p>Order: <strong>{{ ucfirst($order->order_status) }}</strong></p>
                <p>Payment: <strong>{{ $order->paymentMethodLabel() }}</strong></p>
                <p>Payment Status: <strong>{{ ucfirst($order->payment_status) }}</strong></p>
                <p>Total Package Value: <strong>৳{{ number_format($order->grand_total, 0) }}</strong></p>
            </div>
        </section>

        <table>
            <thead>
                <tr><th class="check">Packed</th><th>Product</th><th>SKU</th><th class="qty">Qty</th></tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td class="check"><span class="check-box"></span></td>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->variation_name)<br><span class="muted">{{ $item->variation_name }}</span>@endif
                        </td>
                        <td>{{ $item->sku ?: 'N/A' }}</td>
                        <td class="qty">{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($order->customer_note || $order->admin_note)
            <div class="note">
                @if($order->customer_note)<p><strong>Customer Note:</strong> {{ $order->customer_note }}</p>@endif
                @if($order->admin_note)<p><strong>Admin Note:</strong> {{ $order->admin_note }}</p>@endif
            </div>
        @endif

        <section class="footer">
            <div class="sign">Packed By</div>
            <div class="sign">Checked / Dispatched By</div>
        </section>
    </main>
</body>
</html>
