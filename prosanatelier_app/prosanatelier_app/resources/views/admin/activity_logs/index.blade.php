@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="admin-page-head prosan-tool-head">
    <div>
        <p class="admin-kicker">Security & Audit</p>
        <h1>Activity Logs</h1>
        <p class="text-muted">Track admin actions such as order updates, product edits, settings changes, and login/logout.</p>
    </div>
    <form method="POST" action="{{ route('admin.activity_logs.clear') }}" onsubmit="return confirm('This will clear all activity logs. Continue?')" class="prosan-inline-form">
        @csrf
        @method('DELETE')
        <input type="hidden" name="confirm" value="CLEAR">
        <button type="submit" class="btn btn-danger-soft">Clear Logs</button>
    </form>
</div>

<div class="content-card prosan-filter-card">
    <form method="GET" class="prosan-filter-grid">
        <div>
            <label>Search</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Admin, action, path, IP...">
        </div>
        <div>
            <label>Action</label>
            <input type="text" name="action" value="{{ request('action') }}" placeholder="admin.orders">
        </div>
        <div>
            <label>From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
        </div>
        <div>
            <label>To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}">
        </div>
        <div class="prosan-filter-actions">
            <button class="btn" type="submit">Filter</button>
            <a class="btn btn-light-outline" href="{{ route('admin.activity_logs.index') }}">Reset</a>
        </div>
    </form>
</div>

<div class="table-card prosan-table-card">
    <div class="table-responsive">
        <table class="admin-table prosan-admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Path</th>
                    <th>IP</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td><strong>{{ optional($log->created_at)->format('d M Y') }}</strong><br><small>{{ optional($log->created_at)->format('h:i A') }}</small></td>
                        <td>{{ $log->admin_name ?: 'System' }}</td>
                        <td><span class="badge-soft">{{ $log->action }}</span><br><small>{{ $log->method }} / {{ $log->route_name }}</small></td>
                        <td><code>{{ $log->path }}</code></td>
                        <td>{{ $log->ip_address }}</td>
                        <td>
                            <details>
                                <summary>{{ $log->description ?: 'View details' }}</summary>
                                @if(!empty($log->request_data))
                                    <pre class="prosan-log-json">{{ json_encode($log->request_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @else
                                    <small>No request data stored.</small>
                                @endif
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No activity logs found yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $logs->links() }}</div>
</div>
@endsection
