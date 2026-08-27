<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SystemHealthController extends Controller
{
    public function index()
    {
        $checks = [];

        $checks[] = $this->check('PHP Version', version_compare(PHP_VERSION, '8.2.0', '>=') ? 'ok' : 'warning', PHP_VERSION, 'Laravel 12 needs PHP 8.2 or newer.');
        $checks[] = $this->check('APP Debug', config('app.debug') ? 'warning' : 'ok', config('app.debug') ? 'ON' : 'OFF', config('app.debug') ? 'Turn APP_DEBUG=false on live site.' : 'Debug details are hidden.');
        $checks[] = $this->check('Environment', app()->environment('production') ? 'ok' : 'warning', app()->environment(), 'Live site should normally be production.');

        try {
            DB::select('SELECT 1');
            $checks[] = $this->check('Database', 'ok', DB::connection()->getDatabaseName(), 'Connected successfully.');
        } catch (Throwable $e) {
            $checks[] = $this->check('Database', 'danger', 'Failed', $e->getMessage());
        }

        $checks[] = $this->writableCheck('Storage folder', storage_path());
        $checks[] = $this->writableCheck('Cache folder', base_path('bootstrap/cache'));
        $checks[] = $this->writableCheck('Public uploads folder', public_path('uploads'));

        $mailHost = config('mail.mailers.smtp.host') ?: env('MAIL_HOST');
        $mailUser = config('mail.mailers.smtp.username') ?: env('MAIL_USERNAME');
        $checks[] = $this->check('Mail Config', ($mailHost && $mailUser && config('mail.default') === 'smtp') ? 'ok' : 'warning', config('mail.default') . ' / ' . ($mailHost ?: 'no host'), $mailUser ? 'SMTP username configured.' : 'SMTP may not be fully configured.');

        $settings = class_exists(SiteSetting::class) ? SiteSetting::allAsKeyValue() : [];
        $steadfastEnabled = ($settings['steadfast_enabled'] ?? env('STEADFAST_ENABLED')) ? true : false;
        $steadfastKey = $settings['steadfast_api_key'] ?? env('STEADFAST_API_KEY');
        $checks[] = $this->check('Steadfast', ($steadfastEnabled && $steadfastKey) ? 'ok' : 'warning', $steadfastEnabled ? 'Enabled' : 'Disabled', $steadfastKey ? 'API key found.' : 'API key missing.');

        $errorLog = storage_path('logs/laravel.log');
        $checks[] = $this->check('Laravel Log', file_exists($errorLog) ? 'ok' : 'warning', file_exists($errorLog) ? $this->formatBytes(filesize($errorLog)) : 'Not found', 'Large log files can be downloaded/cleaned from cPanel when needed.');

        return view('admin.system.health', compact('checks'));
    }

    private function writableCheck(string $label, string $path): array
    {
        if (! is_dir($path)) {
            @mkdir($path, 0775, true);
        }

        return $this->check($label, is_writable($path) ? 'ok' : 'danger', is_writable($path) ? 'Writable' : 'Not writable', $path);
    }

    private function check(string $label, string $status, string $value, string $note = ''): array
    {
        return compact('label', 'status', 'value', 'note');
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}
