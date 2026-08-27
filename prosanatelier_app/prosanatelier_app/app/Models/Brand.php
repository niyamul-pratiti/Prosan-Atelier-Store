<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'logo', 'description', 'meta_title', 'meta_description', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getLogoUrlAttribute(): string
    {
        if (! $this->logo) {
            return asset('foodmart/images/brands/brand-default.svg');
        }

        if (Str::startsWith($this->logo, ['http://', 'https://', '//'])) {
            return $this->logo;
        }

        if (Str::startsWith($this->logo, ['foodmart/', 'images/', 'uploads/'])) {
            return asset($this->logo);
        }

        if (Str::startsWith($this->logo, ['storage/'])) {
            return url('storage-files/' . Str::after($this->logo, 'storage/'));
        }

        // Uploaded brand logos are stored on the public disk, for example brands/logo.jpg.
        // Use the storage-files route so JPG/PNG/WebP logos work even when cPanel symlinks are blocked.
        return url('storage-files/' . ltrim($this->logo, '/'));
    }
}
