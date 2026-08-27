<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\OrderEmailNotificationService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class OrderObserver implements ShouldHandleEventsAfterCommit
{
    public function created(Order $order): void
    {
        app(OrderEmailNotificationService::class)->notifyOrderPlaced($order);
    }

    public function updated(Order $order): void
    {
        $statusChanged = $order->wasChanged('order_status') || $order->wasChanged('payment_status');
        $courierChanged = $order->wasChanged('steadfast_status') || $order->wasChanged('steadfast_tracking_code') || $order->wasChanged('steadfast_consignment_id');

        if ($statusChanged) {
            app(OrderEmailNotificationService::class)->notifyStatusChanged($order, [
                'order_status' => $order->wasChanged('order_status') ? $order->order_status : null,
                'payment_status' => $order->wasChanged('payment_status') ? $order->payment_status : null,
            ]);
        }

        if ($courierChanged) {
            app(OrderEmailNotificationService::class)->notifyCourierChanged($order);
        }
    }
}
