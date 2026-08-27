<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = trim((string) $request->q);
                $query->where(function ($inner) use ($q) {
                    $inner->where('admin_name', 'like', "%{$q}%")
                        ->orWhere('action', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('path', 'like', "%{$q}%")
                        ->orWhere('ip_address', 'like', "%{$q}%");
                });
            })
            ->when($request->filled('action'), fn ($query) => $query->where('action', 'like', '%' . $request->action . '%'))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.activity_logs.index', compact('logs'));
    }

    public function clear(Request $request)
    {
        $request->validate([
            'confirm' => ['required', 'in:CLEAR'],
        ]);

        ActivityLog::query()->delete();

        return back()->with('success', 'Activity logs cleared.');
    }
}
