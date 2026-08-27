<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SteadfastCourierService
{
    public function enabled(): bool
    {
        return $this->enabledFlag()
            && filled($this->apiKey())
            && filled($this->secretKey());
    }

    public function createOrder(Order $order): array
    {
        $this->ensureEnabled();

        $payload = [
            'invoice' => $order->order_number,
            'recipient_name' => $order->customer_name,
            'recipient_phone' => $order->customer_phone,
            'recipient_address' => $this->deliveryAddress($order),
            'cod_amount' => (int) round($order->codAmountForSteadfast()),
            'note' => $this->deliveryNote($order),
        ];

        $response = $this->client()->post($this->url('/create_order'), $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Steadfast order creation failed: ' . $response->body());
        }

        return $response->json() ?: [];
    }

    public function checkStatus(Order $order): array
    {
        $this->ensureEnabled();

        if ($order->steadfast_consignment_id) {
            $response = $this->client()->get($this->url('/status_by_cid/' . $order->steadfast_consignment_id));
        } elseif ($order->steadfast_tracking_code) {
            $response = $this->client()->get($this->url('/status_by_trackingcode/' . $order->steadfast_tracking_code));
        } else {
            $response = $this->client()->get($this->url('/status_by_invoice/' . urlencode($order->order_number)));
        }

        if (! $response->successful()) {
            throw new RuntimeException('Steadfast status check failed: ' . $response->body());
        }

        return $response->json() ?: [];
    }

    public function extractConsignment(array $response): array
    {
        $data = $response['consignment'] ?? $response['data'] ?? $response;

        if (isset($data[0]) && is_array($data[0])) {
            $data = $data[0];
        }

        return is_array($data) ? $data : [];
    }

    public function extractStatus(array $response): ?string
    {
        $data = $this->extractConsignment($response);

        return $data['delivery_status']
            ?? $data['status']
            ?? $response['delivery_status']
            ?? $response['status']
            ?? null;
    }

    private function client()
    {
        return Http::timeout(30)
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'Api-Key' => (string) $this->apiKey(),
                'Secret-Key' => (string) $this->secretKey(),
            ]);
    }

    private function url(string $path): string
    {
        return rtrim((string) $this->baseUrl(), '/') . '/' . ltrim($path, '/');
    }

    private function enabledFlag(): bool
    {
        $setting = SiteSetting::getValue('steadfast_enabled', '');
        if ($setting !== '') {
            return filter_var($setting, FILTER_VALIDATE_BOOLEAN);
        }

        return (bool) config('services.steadfast.enabled');
    }

    private function apiKey(): ?string
    {
        return filled(SiteSetting::getValue('steadfast_api_key'))
            ? SiteSetting::getValue('steadfast_api_key')
            : config('services.steadfast.api_key');
    }

    private function secretKey(): ?string
    {
        return filled(SiteSetting::getValue('steadfast_secret_key'))
            ? SiteSetting::getValue('steadfast_secret_key')
            : config('services.steadfast.secret_key');
    }

    private function baseUrl(): string
    {
        return filled(SiteSetting::getValue('steadfast_base_url'))
            ? SiteSetting::getValue('steadfast_base_url')
            : (string) config('services.steadfast.base_url');
    }

    private function ensureEnabled(): void
    {
        if (! $this->enabled()) {
            throw new RuntimeException('Steadfast is not enabled or API credentials are missing in .env.');
        }
    }

    private function deliveryAddress(Order $order): string
    {
        return collect([
            $order->address_line,
            $order->area,
            $order->city,
        ])->filter()->implode(', ');
    }

    private function deliveryNote(Order $order): string
    {
        $items = $order->items->map(function ($item) {
            $variation = $item->variation_name ? ' (' . $item->variation_name . ')' : '';
            return $item->product_name . $variation . ' x ' . $item->quantity;
        })->implode('; ');

        $payment = $order->payment_status === 'paid'
            ? 'Customer already paid. COD amount is 0.'
            : 'Collect COD amount from customer.';

        $noteParts = array_filter([
            $payment,
            $order->customer_note ? 'Customer note: ' . $order->customer_note : null,
            $order->admin_note ? 'Admin note: ' . $order->admin_note : null,
            $items ? 'Items: ' . $items : null,
        ]);

        return mb_substr(implode(' | ', $noteParts), 0, 950);
    }
}
