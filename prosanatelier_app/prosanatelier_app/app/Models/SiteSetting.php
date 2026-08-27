<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    public const DEFAULTS = [
        'site_name' => 'Prosan Atelier',
        'site_tagline' => 'Everyday essentials, thoughtfully chosen.',
        'meta_title' => 'Prosan Atelier - Food, Cosmetics & Cooking Essentials',
        'meta_description' => 'Prosan Atelier - Korean, Thai, Chinese food items, cooking essentials and cosmetics.',
        'support_phone' => '01410283178',
        'support_email' => 'hello@prosanatelier.com',
        'bkash_number' => '01632283178',
        'nagad_number' => '01323574246',
        'inside_dhaka_shipping' => '70',
        'dhaka_suburban_shipping' => '100',
        'outside_dhaka_shipping' => '130',
        'weight_based_shipping_enabled' => '1',
        'shipping_included_weight_grams' => '1000',
        'shipping_packaging_weight_grams' => '200',
        'shipping_additional_per_kg' => '20',
        'free_delivery_minimum' => '5000',
        'footer_text' => 'Everyday essentials, thoughtfully chosen. Korean, Thai and Chinese food items, cooking essentials and cosmetics.',
        'footer_copyright' => '© {year} Prosan Atelier. All rights reserved.',
        'footer_credit_text' => 'Md Niyamul Pratiti',
        'footer_credit_url' => 'https://niyamulpratiti.com',
        'facebook_url' => '',
        'instagram_url' => '',
        'youtube_url' => '',
        'tiktok_url' => '',
        'whatsapp_url' => '',
        'steadfast_enabled' => '',
        'steadfast_api_key' => '',
        'steadfast_secret_key' => '',
        'steadfast_base_url' => 'https://portal.packzy.com/api/v1',
        'notification_admin_email' => 'info@prosanatelier.com',
        'notify_admin_new_order' => '1',
        'notify_customer_order_placed' => '1',
        'notify_customer_status_update' => '1',
        'notify_customer_courier_update' => '1',
        'notification_from_name' => 'Prosan Atelier',

        'homepage_show_hero' => '1',
        'homepage_show_categories' => '1',
        'homepage_show_brands' => '1',
        'homepage_show_trending' => '1',
        'homepage_show_best_selling' => '1',
        'homepage_show_offer' => '1',
        'homepage_show_new_arrivals' => '1',
        'homepage_show_services' => '1',
        'homepage_hero_layout' => 'full_width_image',
        'homepage_hero_bg_color' => '#f6efe5',
        'homepage_hero_bg_image' => 'foodmart/images/prosan-hero-food-beauty.svg',
        'homepage_hero_overlay_color' => '#000000',
        'homepage_hero_overlay_opacity' => '15',
        'homepage_hero_text_color' => '#1d1d1f',
        'homepage_hero_alignment' => 'left',
        'homepage_hero_height' => '560px',
        'homepage_hero_1_kicker' => 'Curated Asian Food',
        'homepage_hero_1_title' => 'Korean, Thai & Chinese food essentials',
        'homepage_hero_1_text' => 'Ramen, seaweed, kimchi, coffee, snacks, rice, sauce and daily pantry picks for your home.',
        'homepage_hero_1_button_text' => 'Shop Food',
        'homepage_hero_1_button_url' => '/shop?category=ramen',
        'homepage_hero_1_image' => 'foodmart/images/prosan-hero-food-beauty.svg',
        'homepage_hero_2_kicker' => 'Beauty & Care',
        'homepage_hero_2_title' => 'Skincare and cosmetics thoughtfully chosen',
        'homepage_hero_2_text' => 'Cleanser, essence, sun care and daily cosmetic products from trusted brands.',
        'homepage_hero_2_button_text' => 'Shop Beauty',
        'homepage_hero_2_button_url' => '/shop?category=cosmetics',
        'homepage_hero_2_image' => 'foodmart/images/prosan-ad-beauty.svg',
        'homepage_promo_1_label' => 'Popular',
        'homepage_promo_1_title' => 'Korean Food',
        'homepage_promo_1_button_text' => 'Shop Collection',
        'homepage_promo_1_button_url' => '/shop?category=ramen',
        'homepage_promo_1_image' => 'foodmart/images/prosan-ad-kfood.svg',
        'homepage_promo_2_label' => 'Essentials',
        'homepage_promo_2_title' => 'Cooking Essentials',
        'homepage_promo_2_button_text' => 'Shop Collection',
        'homepage_promo_2_button_url' => '/shop?category=cooking-essentials',
        'homepage_promo_2_image' => 'foodmart/images/prosan-ad-cooking.svg',
        'homepage_category_title' => 'Category',
        'homepage_brands_title' => 'Newly Arrived Brands',
        'homepage_trending_title' => 'Trending Products',
        'homepage_best_selling_title' => 'Best selling products',
        'homepage_offer_title' => 'Can’t find your product?',
        'homepage_offer_text' => 'Request your favorite Korean, Thai or Asian product. We’ll try to source it for you.',
        'homepage_new_arrivals_title' => 'New arrivals',
    ];

    public static function allAsKeyValue(): array
    {
        if (! Schema::hasTable('site_settings')) {
            return self::DEFAULTS;
        }

        return Cache::remember('site_settings.key_value', 300, function () {
            return array_merge(self::DEFAULTS, self::query()->pluck('value', 'key')->toArray());
        });
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $settings = self::allAsKeyValue();

        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    public static function intValue(string $key, int $default = 0): int
    {
        $value = self::getValue($key, $default);
        return is_numeric($value) ? (int) $value : $default;
    }

    public static function boolValue(string $key, bool $default = false): bool
    {
        $value = self::getValue($key, null);

        if ($value === null || $value === '') {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            self::query()->updateOrCreate(
                ['key' => $key],
                ['value' => is_null($value) ? '' : (string) $value]
            );
        }

        Cache::forget('site_settings.key_value');
    }

    public static function imageUrl(?string $path, string $defaultAsset = 'foodmart/images/prosan-logo.jpg'): string
    {
        if (! $path) {
            return asset($defaultAsset);
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, ['foodmart/', 'images/', 'uploads/'])) {
            return asset($path);
        }

        if (Str::startsWith($path, ['storage/'])) {
            return url('storage-files/' . Str::after($path, 'storage/'));
        }

        return url('storage-files/' . ltrim($path, '/'));
    }
}
