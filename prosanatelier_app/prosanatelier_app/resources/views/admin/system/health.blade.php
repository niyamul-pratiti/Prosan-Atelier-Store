@extends('layouts.admin')

@section('title', 'System Health')

@section('content')
<div class="admin-page-head prosan-tool-head">
    <div>
        <p class="admin-kicker">System Status</p>
        <h1>System Health</h1>
        <p class="text-muted">Quickly check whether the live store is ready for orders, uploads, email, and courier operations.</p>
    </div>
</div>

<div class="prosan-health-grid">
    @foreach($checks as $check)
        <div class="content-card prosan-health-card status-{{ $check['status'] }}">
            <div class="health-status-dot"></div>
            <div>
                <h3>{{ $check['label'] }}</h3>
                <strong>{{ $check['value'] }}</strong>
                @if($check['note'])
                    <p>{{ $check['note'] }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
