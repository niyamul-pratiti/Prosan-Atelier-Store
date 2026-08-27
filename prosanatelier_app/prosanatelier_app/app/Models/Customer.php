<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'address_line', 'area', 'city', 'shipping_zone', 'is_active',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'customer_wishlists')
            ->withTimestamps()
            ->orderByDesc('customer_wishlists.created_at');
    }

    public function setPasswordAttribute($value): void
    {
        if (! empty($value) && ! str_starts_with((string) $value, '$2y$')) {
            $this->attributes['password'] = Hash::make($value);
            return;
        }

        if (! empty($value)) {
            $this->attributes['password'] = $value;
        }
    }
}
