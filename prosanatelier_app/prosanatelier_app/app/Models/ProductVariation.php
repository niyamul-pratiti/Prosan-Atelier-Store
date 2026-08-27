<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'regular_price',
        'sale_price',
        'purchase_price',
        'stock_quantity',
        'weight',
        'unit',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'weight' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getEffectivePriceAttribute(): float
    {
        $regular = (float) ($this->regular_price ?? 0);
        $sale = (float) ($this->sale_price ?? 0);

        if ($sale > 0 && ($regular <= 0 || $sale < $regular)) {
            return $sale;
        }

        return $regular > 0 ? $regular : (float) ($this->product?->regular_price ?? 0);
    }

    public function getHasDiscountAttribute(): bool
    {
        return (float) ($this->sale_price ?? 0) > 0
            && (float) ($this->regular_price ?? 0) > 0
            && (float) $this->sale_price < (float) $this->regular_price;
    }
}
