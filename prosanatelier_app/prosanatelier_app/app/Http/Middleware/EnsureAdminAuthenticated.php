<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Support\AdminActivity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    private const SESSION_LIFETIME_SECONDS = 7200; // 2 hours

    public function handle(Request $request, Closure $next): Response
    {
        $adminId = $request->session()->get('admin_id');

        if (!$adminId) {
            return redirect()->route('admin.login')->with('error', 'Please login to access the admin panel.');
        }

        $loginAt = (int) $request->session()->get('admin_login_at', now()->timestamp);
        if (now()->timestamp - $loginAt > self::SESSION_LIFETIME_SECONDS) {
            $request->session()->forget(['admin_id', 'admin_login_at']);
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->with('error', 'Admin session expired. Please login again.');
        }

        $admin = Admin::where('is_active', true)->find($adminId);

        if (!$admin) {
            $request->session()->forget(['admin_id', 'admin_login_at']);
            return redirect()->route('admin.login')->with('error', 'Admin session expired. Please login again.');
        }

        View::share('currentAdmin', $admin);

        $response = $next($request);

        if ($this->shouldLog($request, $response)) {
            AdminActivity::record($request, $admin->id, AdminActivity::routeAction($request));
        }

        return $response;
    }

    private function shouldLog(Request $request, Response $response): bool
    {
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return false;
        }

        if ($response->getStatusCode() >= 500) {
            return false;
        }

        $routeName = (string) optional($request->route())->getName();

        if (str_starts_with($routeName, 'admin.activity_logs.')) {
            return false;
        }

        return str_starts_with($routeName, 'admin.') || str_starts_with('/' . ltrim($request->path(), '/'), '/admin');
    }
}
