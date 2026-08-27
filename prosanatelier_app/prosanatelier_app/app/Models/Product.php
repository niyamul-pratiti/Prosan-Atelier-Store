<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'sku', 'barcode',
        'short_description', 'description', 'ingredients', 'usage_instruction',
        'regular_price', 'sale_price', 'purchase_price', 'stock_quantity', 'low_stock_alert',
        'weight', 'unit', 'expiry_date', 'product_type',
        'is_featured', 'is_new_arrival', 'is_best_seller', 'is_active',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'low_stock_alert' => 'integer',
        'weight' => 'decimal:2',
        'expiry_date' => 'date',
        'is_featured' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category')
            ->withPivot('is_primary')
            ->withTimestamps()
            ->orderBy('categories.parent_id')
            ->orderBy('categories.name');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function wishlistedByCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_wishlists')->withTimestamps();
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class)->orderBy('sort_order')->orderBy('name')->orderBy('id');
    }

    public function activeVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->orderBy('id');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true)->orderBy('sort_order')->orderBy('id');
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', false)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getIsVariableAttribute(): bool
    {
        return $this->product_type === 'variable';
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->is_variable && $this->price_range) {
            return (float) $this->price_range['min'];
        }

        $regular = (float) ($this->regular_price ?? 0);
        $sale = (float) ($this->sale_price ?? 0);

        if ($sale > 0 && ($regular <= 0 || $sale < $regular)) {
            return $sale;
        }

        return $regular;
    }

    public function getHasDiscountAttribute(): bool
    {
        if ($this->is_variable) {
            return $this->activeVariationCollection()->contains(fn ($variation) => $variation->has_discount);
        }

        return (float) ($this->sale_price ?? 0) > 0
            && (float) ($this->regular_price ?? 0) > 0
            && (float) $this->sale_price < (float) $this->regular_price;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->relationLoaded('images')) {
            $image = $this->images->firstWhere('is_primary', true) ?: $this->images->first();
        } else {
            $image = $this->primaryImage()->first() ?: $this->images()->first();
        }

        return $image ? $image->url : asset('foodmart/images/product-placeholder.svg');
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->is_variable) {
            return (int) $this->activeVariationCollection()
                ->filter(fn ($variation) => $variation->has_discount && (float) $variation->regular_price > 0)
                ->map(fn ($variation) => round((1 - ($variation->effective_price / (float) $variation->regular_price)) * 100))
                ->max() ?: 0;
        }

        if (! $this->has_discount || (float) $this->regular_price <= 0) {
            return 0;
        }

        return (int) round((1 - ($this->effective_price / (float) $this->regular_price)) * 100);
    }

    public function getPriceRangeAttribute(): ?array
    {
        if (! $this->is_variable) {
            return null;
        }

        $variations = $this->activeVariationCollection()
            ->filter(fn ($variation) => (float) ($variation->regular_price ?? 0) > 0);

        if ($variations->isEmpty()) {
            return null;
        }

        $prices = $variations->map(fn ($variation) => $variation->effective_price)->filter(fn ($price) => $price > 0);
        $regularPrices = $variations->map(fn ($variation) => (float) $variation->regular_price)->filter(fn ($price) => $price > 0);

        if ($prices->isEmpty()) {
            return null;
        }

        return [
            'min' => (float) $prices->min(),
            'max' => (float) $prices->max(),
            'regular_min' => (float) $regularPrices->min(),
            'regular_max' => (float) $regularPrices->max(),
        ];
    }

    public function getPriceLabelAttribute(): string
    {
        $range = $this->price_range;

        if ($this->is_variable && $range) {
            if ((float) $range['min'] === (float) $range['max']) {
                return '৳' . number_format($range['min'], 0);
            }

            return '৳' . number_format($range['min'], 0) . ' – ৳' . number_format($range['max'], 0);
        }

        return '৳' . number_format($this->effective_price, 0);
    }

    public function getDisplayStockAttribute(): int
    {
        if ($this->is_variable) {
            return (int) $this->activeVariationCollection()->sum('stock_quantity');
        }

        return (int) $this->stock_quantity;
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->display_stock > 0;
    }

    private function activeVariationCollection()
    {
        if ($this->relationLoaded('activeVariations')) {
            return $this->activeVariations;
        }

        if ($this->relationLoaded('variations')) {
            return $this->variations->where('is_active', true)->values();
        }

        return $this->activeVariations()->get();
    }
}
