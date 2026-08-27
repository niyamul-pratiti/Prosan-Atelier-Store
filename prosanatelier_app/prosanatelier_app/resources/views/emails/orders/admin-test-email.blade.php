@include('emails.orders.partials-style')
<div class="wrap">
    <div class="card">
        <div class="head"><h1>Test email successful</h1></div>
        <div class="body">
            <p>This is a test notification email from {{ $siteName ?? 'Prosan Atelier' }}.</p>
            <div class="summary">
                <strong>Mailer:</strong> {{ $mailer ?? 'unknown' }}<br>
                <strong>Host:</strong> {{ $host ?? 'N/A' }}<br>
                <strong>Time:</strong> {{ now()->format('d M Y, h:i A') }}
            </div>
            <p class="muted">If you received this email, admin new order notification should work when SMTP is configured correctly.</p>
        </div>
    </div>
    <div class="foot">Notification test</div>
</div>
