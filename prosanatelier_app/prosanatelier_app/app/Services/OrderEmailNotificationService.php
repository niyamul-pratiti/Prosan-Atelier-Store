<?php

namespace App\Services;

use App\Models\EmailNotificationLog;
use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OrderEmailNotificationService
{
    public function notifyOrderPlaced(Order $order): void
    {
        $order->loadMissing(['items', 'customer']);

        if (SiteSetting::boolValue('notify_customer_order_placed', true)) {
            $this->sendToCustomer(
                $order,
                'Order received: ' . $order->order_number,
                'emails.orders.customer-placed',
                ['order' => $order],
                'customer_order_placed'
            );
        }

        if (SiteSetting::boolValue('notify_admin_new_order', true)) {
            $this->sendToAdmin(
                'New order received: ' . $order->order_number,
                'emails.orders.admin-new-order',
                ['order' => $order],
                'admin_new_order',
                $order
            );
        }
    }

    public function notifyStatusChanged(Order $order, array $changes = []): void
    {
        if (! SiteSetting::boolValue('notify_customer_status_update', true)) {
            return;
        }

        $order->loadMissing(['items', 'customer']);

        $this->sendToCustomer(
            $order,
            'Order update: ' . $order->order_number,
            'emails.orders.customer-status-update',
            ['order' => $order, 'changes' => $changes],
            'customer_status_update'
        );
    }

    public function notifyCourierChanged(Order $order): void
    {
        if (! SiteSetting::boolValue('notify_customer_courier_update', true)) {
            return;
        }

        $order->loadMissing(['items', 'customer']);

        $this->sendToCustomer(
            $order,
            'Courier update: ' . $order->order_number,
            'emails.orders.customer-courier-update',
            ['order' => $order],
            'customer_courier_update'
        );
    }

    public function sendTestEmail(?string $email = null): array
    {
        $to = trim((string) ($email ?: $this->adminEmail()));

        if (! $to || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return [
                'ok' => false,
                'message' => 'No valid test email found. Add Admin Notification Email or Support Email first.',
            ];
        }

        $sent = $this->safeSend(
            $to,
            'Prosan Atelier test notification email',
            'emails.orders.admin-test-email',
            [
                'siteName' => SiteSetting::getValue('site_name', config('app.name', 'Prosan Atelier')),
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
            ],
            'test_email',
            'admin',
            null
        );

        if (! $sent) {
            return [
                'ok' => false,
                'message' => 'Test email failed. Check Admin → Settings recent email logs or Laravel log.',
            ];
        }

        if (config('mail.default') === 'log') {
            return [
                'ok' => true,
                'message' => 'Test notification created, but MAIL_MAILER is log. Set MAIL_MAILER=smtp to receive real emails.',
            ];
        }

        return [
            'ok' => true,
            'message' => 'Test email sent to ' . $to . '. Please check inbox/spam.',
        ];
    }

    private function sendToCustomer(Order $order, string $subject, string $view, array $data, string $event): void
    {
        $email = trim((string) ($order->customer_email ?: optional($order->customer)->email));

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->writeLog($order, $event, 'customer', $email, $subject, 'skipped', 'Customer email is empty or invalid.');
            return;
        }

        $this->safeSend($email, $subject, $view, $data, $event, 'customer', $order);
    }

    private function sendToAdmin(string $subject, string $view, array $data, string $event, ?Order $order = null): void
    {
        $email = $this->adminEmail();

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->writeLog($order, $event, 'admin', $email, $subject, 'skipped', 'Admin notification email/support email is empty or invalid.');
            return;
        }

        $this->safeSend($email, $subject, $view, $data, $event, 'admin', $order);
    }

    private function adminEmail(): string
    {
        $email = trim((string) SiteSetting::getValue('notification_admin_email', ''));

        if (! $email) {
            $email = trim((string) SiteSetting::getValue('support_email', config('mail.from.address')));
        }

        if (! $email) {
            $email = trim((string) config('mail.from.address'));
        }

        return $email;
    }

    private function safeSend(string $to, string $subject, string $view, array $data, string $event, string $recipientType, ?Order $order): bool
    {
        try {
            Mail::send($view, $data, function ($message) use ($to, $subject) {
                $fromAddress = config('mail.from.address');
                $fromName = SiteSetting::getValue('notification_from_name', SiteSetting::getValue('site_name', config('mail.from.name', 'Prosan Atelier')));

                if ($fromAddress) {
                    $message->from($fromAddress, $fromName);
                }

                $message->to($to)->subject($subject);
            });

            $this->writeLog($order, $event, $recipientType, $to, $subject, 'sent');
            return true;
        } catch (\Throwable $e) {
            $this->writeLog($order, $event, $recipientType, $to, $subject, 'failed', $e->getMessage());

            Log::warning('Prosan notification email failed', [
                'to' => $this->maskEmail($to),
                'event' => $event,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function writeLog(?Order $order, string $event, string $recipientType, ?string $email, string $subject, string $status, ?string $error = null): void
    {
        try {
            if (! Schema::hasTable('email_notification_logs')) {
                return;
            }

            EmailNotificationLog::create([
                'order_id' => $order?->id,
                'event' => $event,
                'recipient_type' => $recipientType,
                'recipient_email' => $email,
                'subject' => $subject,
                'status' => $status,
                'error_message' => $error ? Str::limit($error, 1000) : null,
                'sent_at' => $status === 'sent' ? now() : null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Could not write notification email log', ['error' => $e->getMessage()]);
        }
    }

    private function maskEmail(string $email): string
    {
        return Str::mask($email, '*', 2, max(1, strlen($email) - 6));
    }
}
