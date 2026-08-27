<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'image', 'description',
        'meta_title', 'meta_description', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function attachedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_category')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('foodmart/images/categories/category-default.svg');
        }

        if (Str::startsWith($this->image, ['http://', 'https://', '//'])) {
            return $this->image;
        }

        if (Str::startsWith($this->image, ['foodmart/', 'images/', 'uploads/'])) {
            return asset($this->image);
        }

        if (Str::startsWith($this->image, ['storage/'])) {
            return url('storage-files/' . Str::after($this->image, 'storage/'));
        }

        return url('storage-files/' . ltrim($this->image, '/'));
    }

    public function getFullNameAttribute(): string
    {
        return $this->parent ? $this->parent->name . ' / ' . $this->name : $this->name;
    }
}
