<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'coupon_id', 'coupon_code', 'order_number', 'customer_name', 'customer_phone', 'customer_email',
        'address_line', 'area', 'city', 'shipping_zone', 'parcel_weight_grams',
        'subtotal', 'discount_total', 'shipping_total', 'grand_total',
        'shipping_manually_set',
        'payment_method', 'payment_status', 'payment_sender_number', 'payment_transaction_id', 'payment_account', 'order_status',
        'customer_note', 'admin_note',
        'steadfast_consignment_id', 'steadfast_tracking_code', 'steadfast_status',
        'steadfast_response', 'steadfast_sent_at', 'steadfast_last_checked_at', 'courier_note',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'parcel_weight_grams' => 'integer',
        'shipping_manually_set' => 'boolean',
        'steadfast_sent_at' => 'datetime',
        'steadfast_last_checked_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }


    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            'manual_bkash' => 'bKash Manual Payment',
            'manual_nagad' => 'Nagad Manual Payment',
            'bank_transfer' => 'Bank Transfer',
            default => 'Cash on Delivery',
        };
    }

    public function requiresManualPaymentReview(): bool
    {
        return in_array($this->payment_method, ['manual_bkash', 'manual_nagad', 'bank_transfer'], true);
    }


    public function courierStatusLabel(): string
    {
        return $this->steadfast_status ? ucfirst(str_replace('_', ' ', (string) $this->steadfast_status)) : 'Not sent';
    }

    public function codAmountForSteadfast(): float
    {
        return $this->payment_status === 'paid' ? 0.0 : (float) $this->grand_total;
    }
}
