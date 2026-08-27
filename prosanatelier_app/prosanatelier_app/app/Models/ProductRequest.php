<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    protected $fillable = [
        'customer_name',
        'phone',
        'email',
        'product_name',
        'brand',
        'product_link',
        'quantity',
        'message',
        'status',
        'admin_note',
        'source',
    ];

    public const STATUSES = [
        'new' => 'New',
        'checking' => 'Checking',
        'available_soon' => 'Available Soon',
        'not_available' => 'Not Available',
        'completed' => 'Completed',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->phone);

        if (! $digits) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '88' . substr($digits, 1);
        } elseif (! str_starts_with($digits, '88')) {
            $digits = '88' . $digits;
        }

        $text = rawurlencode('Hello ' . $this->customer_name . ', about your product request: ' . $this->product_name);

        return 'https://wa.me/' . $digits . '?text=' . $text;
    }
}
