<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'description', 'type', 'amount', 'minimum_order_amount',
        'usage_limit', 'used_count', 'starts_at', 'expires_at', 'applies_to', 'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'coupon_category');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = Str::upper(trim((string) $value));
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'percent' => 'Percentage Discount',
            'free_delivery' => 'Free Delivery',
            default => 'Fixed Discount',
        };
    }

    public function getAppliesToLabelAttribute(): string
    {
        return match ($this->applies_to) {
            'products' => 'Selected Products',
            'categories' => 'Selected Categories',
            default => 'All Products',
        };
    }

    public function isUsable(float|int $subtotal = 0): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && now()->gt($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ((float) $this->minimum_order_amount > 0 && $subtotal < (float) $this->minimum_order_amount) {
            return false;
        }

        return true;
    }
}
