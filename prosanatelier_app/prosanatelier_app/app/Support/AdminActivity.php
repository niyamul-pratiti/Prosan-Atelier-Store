<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AdminActivity
{
    public static function record(Request $request, ?int $adminId, string $action, ?string $description = null, array $extra = []): void
    {
        try {
            if (! Schema::hasTable('activity_logs')) {
                return;
            }

            $adminName = null;
            if ($adminId) {
                $adminName = optional(Admin::find($adminId))->name;
            }

            ActivityLog::create([
                'admin_id' => $adminId,
                'admin_name' => $adminName,
                'action' => $action,
                'method' => $request->method(),
                'route_name' => optional($request->route())->getName(),
                'path' => '/' . ltrim($request->path(), '/'),
                'description' => $description ?: self::humanAction($request, $action),
                'request_data' => array_merge(self::safeInput($request->all()), $extra),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            ]);
        } catch (Throwable $e) {
            // Activity logging must never break the admin panel.
        }
    }

    public static function routeAction(Request $request): string
    {
        $route = (string) optional($request->route())->getName();
        $method = strtolower($request->method());

        if ($route) {
            return $method . ':' . $route;
        }

        return $method . ':' . trim($request->path(), '/');
    }

    public static function humanAction(Request $request, string $action): string
    {
        $route = (string) optional($request->route())->getName();
        $method = strtoupper($request->method());

        if ($route) {
            return $method . ' ' . $route;
        }

        return $method . ' /' . trim($request->path(), '/');
    }

    private static function safeInput(array $input): array
    {
        $sensitive = [
            '_token', '_method', 'password', 'password_confirmation', 'current_password',
            'secret', 'secret_key', 'api_secret', 'token', 'access_token', 'remember_token',
            'steadfast_secret_key', 'STEADFAST_SECRET_KEY', 'MAIL_PASSWORD', 'mail_password',
            'db_password', 'DB_PASSWORD', 'bkash_pin', 'nagad_pin', 'payment_password',
        ];

        $clean = [];

        foreach ($input as $key => $value) {
            if (in_array($key, $sensitive, true) || str_contains(strtolower((string) $key), 'password') || str_contains(strtolower((string) $key), 'secret')) {
                $clean[$key] = '[hidden]';
                continue;
            }

            if (is_array($value)) {
                $clean[$key] = self::safeInput($value);
                continue;
            }

            if (is_object($value)) {
                $clean[$key] = '[object]';
                continue;
            }

            $text = (string) $value;
            $clean[$key] = mb_strlen($text) > 250 ? mb_substr($text, 0, 250) . '…' : $text;
        }

        return $clean;
    }
}
