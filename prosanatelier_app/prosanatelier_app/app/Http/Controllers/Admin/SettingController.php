<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailNotificationLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Support\Uploads\ImageUploader;
use App\Services\OrderEmailNotificationService;
use Illuminate\Support\Facades\Schema;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = SiteSetting::allAsKeyValue();

        // Keep current .env Steadfast credentials visible as fallback so saving settings does not disable courier accidentally.
        $settings['steadfast_enabled'] = $settings['steadfast_enabled'] !== ''
            ? $settings['steadfast_enabled']
            : (config('services.steadfast.enabled') ? '1' : '');
        $settings['steadfast_api_key'] = $settings['steadfast_api_key'] ?: (config('services.steadfast.api_key') ?: '');
        $settings['steadfast_secret_key'] = $settings['steadfast_secret_key'] ?: (config('services.steadfast.secret_key') ?: '');
        $settings['steadfast_base_url'] = $settings['steadfast_base_url'] ?: (config('services.steadfast.base_url') ?: 'https://portal.packzy.com/api/v1');

        $emailLogs = Schema::hasTable('email_notification_logs')
            ? EmailNotificationLog::query()->latest()->take(10)->get()
            : collect();

        $mailConfig = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'from' => config('mail.from.address'),
        ];

        return view('admin.settings.edit', compact('settings', 'emailLogs', 'mailConfig'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:150'],
            'site_tagline' => ['nullable', 'string', 'max:220'],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'support_phone' => ['required', 'string', 'max:30'],
            'support_email' => ['nullable', 'email', 'max:150'],
            'bkash_number' => ['required', 'string', 'max:30'],
            'nagad_number' => ['required', 'string', 'max:30'],
            'inside_dhaka_shipping' => ['required', 'integer', 'min:0', 'max:10000'],
            'dhaka_suburban_shipping' => ['required', 'integer', 'min:0', 'max:10000'],
            'outside_dhaka_shipping' => ['required', 'integer', 'min:0', 'max:10000'],
            'weight_based_shipping_enabled' => ['nullable', 'boolean'],
            'shipping_included_weight_grams' => ['required', 'integer', 'min:1', 'max:100000'],
            'shipping_packaging_weight_grams' => ['required', 'integer', 'min:0', 'max:100000'],
            'shipping_additional_per_kg' => ['required', 'integer', 'min:0', 'max:10000'],
            'free_delivery_minimum' => ['required', 'integer', 'min:0', 'max:1000000'],
            'footer_text' => ['nullable', 'string', 'max:500'],
            'footer_copyright' => ['nullable', 'string', 'max:250'],
            'footer_credit_text' => ['nullable', 'string', 'max:150'],
            'footer_credit_url' => ['nullable', 'url', 'max:250'],
            'facebook_url' => ['nullable', 'url', 'max:250'],
            'instagram_url' => ['nullable', 'url', 'max:250'],
            'youtube_url' => ['nullable', 'url', 'max:250'],
            'tiktok_url' => ['nullable', 'url', 'max:250'],
            'whatsapp_url' => ['nullable', 'url', 'max:250'],
            'steadfast_enabled' => ['nullable', 'boolean'],
            'steadfast_api_key' => ['nullable', 'string', 'max:250'],
            'steadfast_secret_key' => ['nullable', 'string', 'max:250'],
            'steadfast_base_url' => ['nullable', 'url', 'max:250'],
            'notification_admin_email' => ['nullable', 'email', 'max:150'],
            'notification_from_name' => ['nullable', 'string', 'max:150'],
            'notify_admin_new_order' => ['nullable', 'boolean'],
            'notify_customer_order_placed' => ['nullable', 'boolean'],
            'notify_customer_status_update' => ['nullable', 'boolean'],
            'notify_customer_courier_update' => ['nullable', 'boolean'],
            'homepage_show_hero' => ['nullable', 'boolean'],
            'homepage_show_categories' => ['nullable', 'boolean'],
            'homepage_show_brands' => ['nullable', 'boolean'],
            'homepage_show_trending' => ['nullable', 'boolean'],
            'homepage_show_best_selling' => ['nullable', 'boolean'],
            'homepage_show_offer' => ['nullable', 'boolean'],
            'homepage_show_new_arrivals' => ['nullable', 'boolean'],
            'homepage_show_services' => ['nullable', 'boolean'],
            'homepage_hero_layout' => ['nullable', 'string', 'in:full_width_image,contained_image,centered_overlay,split_banner_grid'],
            'homepage_hero_bg_color' => ['nullable', 'string', 'max:30'],
            'homepage_hero_overlay_color' => ['nullable', 'string', 'max:30'],
            'homepage_hero_overlay_opacity' => ['nullable', 'integer', 'min:0', 'max:90'],
            'homepage_hero_text_color' => ['nullable', 'string', 'max:30'],
            'homepage_hero_alignment' => ['nullable', 'string', 'in:left,center,right'],
            'homepage_hero_height' => ['nullable', 'string', 'max:30'],
            'homepage_hero_1_kicker' => ['nullable', 'string', 'max:120'],
            'homepage_hero_1_title' => ['nullable', 'string', 'max:180'],
            'homepage_hero_1_text' => ['nullable', 'string', 'max:400'],
            'homepage_hero_1_button_text' => ['nullable', 'string', 'max:80'],
            'homepage_hero_1_button_url' => ['nullable', 'string', 'max:250'],
            'homepage_hero_2_kicker' => ['nullable', 'string', 'max:120'],
            'homepage_hero_2_title' => ['nullable', 'string', 'max:180'],
            'homepage_hero_2_text' => ['nullable', 'string', 'max:400'],
            'homepage_hero_2_button_text' => ['nullable', 'string', 'max:80'],
            'homepage_hero_2_button_url' => ['nullable', 'string', 'max:250'],
            'homepage_promo_1_label' => ['nullable', 'string', 'max:80'],
            'homepage_promo_1_title' => ['nullable', 'string', 'max:130'],
            'homepage_promo_1_button_text' => ['nullable', 'string', 'max:80'],
            'homepage_promo_1_button_url' => ['nullable', 'string', 'max:250'],
            'homepage_promo_2_label' => ['nullable', 'string', 'max:80'],
            'homepage_promo_2_title' => ['nullable', 'string', 'max:130'],
            'homepage_promo_2_button_text' => ['nullable', 'string', 'max:80'],
            'homepage_promo_2_button_url' => ['nullable', 'string', 'max:250'],
            'homepage_category_title' => ['nullable', 'string', 'max:120'],
            'homepage_brands_title' => ['nullable', 'string', 'max:120'],
            'homepage_trending_title' => ['nullable', 'string', 'max:120'],
            'homepage_best_selling_title' => ['nullable', 'string', 'max:120'],
            'homepage_offer_title' => ['nullable', 'string', 'max:180'],
            'homepage_offer_text' => ['nullable', 'string', 'max:400'],
            'homepage_new_arrivals_title' => ['nullable', 'string', 'max:120'],
            'homepage_hero_bg_image_upload' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:12288'],
            'homepage_hero_1_image_upload' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:12288'],
            'homepage_hero_2_image_upload' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:12288'],
            'homepage_promo_1_image_upload' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:12288'],
            'homepage_promo_2_image_upload' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:12288'],
            'site_logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:12288'],
        ]);

        foreach ([
            'steadfast_enabled',
            'weight_based_shipping_enabled',
            'notify_admin_new_order',
            'notify_customer_order_placed',
            'notify_customer_status_update',
            'notify_customer_courier_update',
            'homepage_show_hero',
            'homepage_show_categories',
            'homepage_show_brands',
            'homepage_show_trending',
            'homepage_show_best_selling',
            'homepage_show_offer',
            'homepage_show_new_arrivals',
            'homepage_show_services',
        ] as $booleanKey) {
            $data[$booleanKey] = $request->boolean($booleanKey) ? '1' : '0';
        }

        foreach ([
            'homepage_hero_bg_image_upload' => 'homepage_hero_bg_image',
            'homepage_hero_1_image_upload' => 'homepage_hero_1_image',
            'homepage_hero_2_image_upload' => 'homepage_hero_2_image',
            'homepage_promo_1_image_upload' => 'homepage_promo_1_image',
            'homepage_promo_2_image_upload' => 'homepage_promo_2_image',
        ] as $uploadField => $settingKey) {
            if ($request->hasFile($uploadField)) {
                $currentImage = SiteSetting::getValue($settingKey);
                if ($currentImage && ! str_starts_with($currentImage, 'foodmart/') && ! str_starts_with($currentImage, 'images/')) {
                    ImageUploader::delete($currentImage);
                }
                $data[$settingKey] = ImageUploader::store($request->file($uploadField), 'homepage');
            }
            unset($data[$uploadField]);
        }

        if ($request->hasFile('site_logo')) {
            $currentLogo = SiteSetting::getValue('site_logo');
            if ($currentLogo && ! str_starts_with($currentLogo, 'foodmart/') && ! str_starts_with($currentLogo, 'images/')) {
                ImageUploader::delete($currentLogo);
            }
            $data['site_logo'] = ImageUploader::store($request->file('site_logo'), 'settings');
        } else {
            unset($data['site_logo']);
        }

        SiteSetting::setMany($data);

        return back()->with('success', 'Settings updated successfully.');
    }

    public function sendTestEmail(Request $request, OrderEmailNotificationService $notifications)
    {
        $data = $request->validate([
            'test_email' => ['nullable', 'email', 'max:150'],
        ]);

        $result = $notifications->sendTestEmail($data['test_email'] ?? null);

        return back()->with($result['ok'] ? 'success' : 'error', $result['message']);
    }

}
