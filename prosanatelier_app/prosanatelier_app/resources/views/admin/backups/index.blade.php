@extends('layouts.admin')

@section('title', 'Backups')

@section('content')
<div class="admin-page-head prosan-tool-head">
    <div>
        <p class="admin-kicker">Backup Center</p>
        <h1>Backups</h1>
        <p class="text-muted">Download safe copies before product updates, patch uploads, or major campaign changes.</p>
    </div>
</div>

<div class="prosan-alert-gold">
    <strong>Before every new patch:</strong> download the database backup first. Keep it on your computer or Google Drive.
</div>

<div class="prosan-tools-grid">
    <div class="content-card prosan-tool-card">
        <div class="tool-icon">🗄️</div>
        <h3>Full Database Backup</h3>
        <p>Exports all database tables as an SQL file. This is the most important backup.</p>
        <p class="text-muted">Database: <strong>{{ $databaseName }}</strong> · Tables: <strong>{{ $tableCount }}</strong></p>
        <a href="{{ route('admin.backups.database') }}" class="btn">Download SQL Backup</a>
    </div>
    <div class="content-card prosan-tool-card">
        <div class="tool-icon">📦</div>
        <h3>Orders CSV</h3>
        <p>Download order list for accounting, checking sales, and keeping customer order history safe.</p>
        <a href="{{ route('admin.backups.orders') }}" class="btn btn-light-outline">Download Orders</a>
    </div>
    <div class="content-card prosan-tool-card">
        <div class="tool-icon">🧴</div>
        <h3>Products CSV</h3>
        <p>Download product price, SKU, purchase cost, and stock information.</p>
        <a href="{{ route('admin.backups.products') }}" class="btn btn-light-outline">Download Products</a>
    </div>
    <div class="content-card prosan-tool-card">
        <div class="tool-icon">👥</div>
        <h3>Customers CSV</h3>
        <p>Download customer names, phone, email, district, thana, and address data.</p>
        <a href="{{ route('admin.backups.customers') }}" class="btn btn-light-outline">Download Customers</a>
    </div>
</div>

<div class="content-card mt-4">
    <h3>Manual full file backup guide</h3>
    <p>Database backup is handled here. For a complete website backup, also download these folders from cPanel File Manager:</p>
    <pre class="prosan-code-block">/home/niyamulp/prosanatelier_app
/home/niyamulp/prosanatelier.com</pre>
</div>
@endsection
