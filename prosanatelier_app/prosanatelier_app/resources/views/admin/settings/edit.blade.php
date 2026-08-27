@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="admin-hero-card">
    <div>
        <span class="admin-kicker">Store control</span>
        <h1>Website Settings</h1>
        <p>Manage store identity, payment numbers, shipping, footer text and courier credentials without editing code.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="settings-form-wrap">
    @csrf
    @method('PUT')

    <div class="settings-grid">
        <section class="content-card settings-card">
            <h2>Brand & Contact</h2>
            <div class="form-grid two-col">
                <label>Website Name
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'Prosan Atelier') }}" required>
                </label>
                <label>Support Phone
                    <input type="text" name="support_phone" value="{{ old('support_phone', $settings['support_phone'] ?? '01410283178') }}" required>
                </label>
                <label>Support Email
                    <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email'] ?? 'hello@prosanatelier.com') }}">
                </label>
                <label>Website Logo
                    <input type="file" name="site_logo" accept="image/*">
                </label>
            </div>
            <label>Tagline
                <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}">
            </label>
            <div class="settings-logo-preview">
                <span>Current logo</span>
                <img src="{{ \App\Models\SiteSetting::imageUrl($settings['site_logo'] ?? null) }}" alt="Current logo">
            </div>
        </section>

        <section class="content-card settings-card">
            <h2>Payment Numbers</h2>
            <div class="form-grid two-col">
                <label>bKash Personal Number
                    <input type="text" name="bkash_number" value="{{ old('bkash_number', $settings['bkash_number'] ?? '01632283178') }}" required>
                </label>
                <label>Nagad Personal Number
                    <input type="text" name="nagad_number" value="{{ old('nagad_number', $settings['nagad_number'] ?? '01323574246') }}" required>
                </label>
            </div>
            <p class="muted">These numbers appear on checkout and admin order payment fields.</p>
        </section>

        <section class="content-card settings-card">
            <h2>Delivery Charge Settings</h2>
            <p class="muted">Area base rate + total parcel weight will apply automatically on cart, checkout and admin order forms. Set Free Delivery Minimum to 0 to disable automatic free delivery.</p>
            <div class="form-grid two-col">
                <label>Inside Dhaka Delivery Charge
                    <input type="number" name="inside_dhaka_shipping" value="{{ old('inside_dhaka_shipping', $settings['inside_dhaka_shipping'] ?? 70) }}" min="0" step="1" required>
                </label>
                <label>Dhaka Suburban Delivery Charge
                    <input type="number" name="dhaka_suburban_shipping" value="{{ old('dhaka_suburban_shipping', $settings['dhaka_suburban_shipping'] ?? 100) }}" min="0" step="1" required>
                </label>
                <label>Outside Dhaka Delivery Charge
                    <input type="number" name="outside_dhaka_shipping" value="{{ old('outside_dhaka_shipping', $settings['outside_dhaka_shipping'] ?? 130) }}" min="0" step="1" required>
                </label>
                <label>Weight Included in Base Rate (grams)
                    <input type="number" name="shipping_included_weight_grams" value="{{ old('shipping_included_weight_grams', $settings['shipping_included_weight_grams'] ?? 1000) }}" min="1" step="1" required>
                </label>
                <label>Packaging Weight Buffer (grams)
                    <input type="number" name="shipping_packaging_weight_grams" value="{{ old('shipping_packaging_weight_grams', $settings['shipping_packaging_weight_grams'] ?? 200) }}" min="0" step="1" required>
                </label>
                <label>Additional Charge per Extra kg
                    <input type="number" name="shipping_additional_per_kg" value="{{ old('shipping_additional_per_kg', $settings['shipping_additional_per_kg'] ?? 20) }}" min="0" step="1" required>
                    <small>Any part of an extra kilogram is rounded up.</small>
                </label>
                <label>Free Delivery Minimum Order
                    <input type="number" name="free_delivery_minimum" value="{{ old('free_delivery_minimum', $settings['free_delivery_minimum'] ?? 5000) }}" min="0" step="1" required>
                </label>
            </div>
            <label class="admin-check-row mt-3">
                <input type="checkbox" name="weight_based_shipping_enabled" value="1" @checked(old('weight_based_shipping_enabled', $settings['weight_based_shipping_enabled'] ?? '1'))>
                <span><strong>Enable weight-based delivery charge</strong><small>Uses each product/variation Weight + Unit. Products without weight still receive the area base rate.</small></span>
            </label>
            <p class="muted">Dhaka Suburban areas: Ashulia, Dhamrai, Dohar, Hemayetpur, Keraniganj Model, Nawabganj, Savar and South Keraniganj.</p>
        </section>

        <section class="content-card settings-card">
            <h2>Footer & SEO</h2>
            <label>Footer Text
                <textarea name="footer_text">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea>
            </label>
            <div class="form-grid two-col">
                <label>Copyright Text
                    <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings['footer_copyright'] ?? '© {year} Prosan Atelier. All rights reserved.') }}">
                    <small>Use <code>{year}</code> for current year.</small>
                </label>
                <label>Developer Credit Text
                    <input type="text" name="footer_credit_text" value="{{ old('footer_credit_text', $settings['footer_credit_text'] ?? 'Md Niyamul Pratiti') }}">
                </label>
                <label>Developer Credit URL
                    <input type="url" name="footer_credit_url" value="{{ old('footer_credit_url', $settings['footer_credit_url'] ?? 'https://niyamulpratiti.com') }}">
                </label>
                <label>Meta Title
                    <input type="text" name="meta_title" value="{{ old('meta_title', $settings['meta_title'] ?? '') }}">
                </label>
            </div>
            <label>Meta Description
                <textarea name="meta_description">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
            </label>
        </section>

        <section class="content-card settings-card">
            <h2>Footer Social Icons</h2>
            <p class="muted">Add links here and the icons will appear in the footer automatically. Leave any field empty to hide that icon.</p>
            <div class="form-grid two-col">
                <label>Facebook URL
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" placeholder="https://facebook.com/yourpage">
                </label>
                <label>Instagram URL
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" placeholder="https://instagram.com/yourpage">
                </label>
                <label>YouTube URL
                    <input type="url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" placeholder="https://youtube.com/@yourchannel">
                </label>
                <label>TikTok URL
                    <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}" placeholder="https://tiktok.com/@yourpage">
                </label>
                <label>WhatsApp URL
                    <input type="url" name="whatsapp_url" value="{{ old('whatsapp_url', $settings['whatsapp_url'] ?? '') }}" placeholder="https://wa.me/8801XXXXXXXXX">
                    <small>If blank, footer will use the support phone as WhatsApp link.</small>
                </label>
            </div>
        </section>



        <section class="content-card settings-card">
            <h2>Homepage Section Control</h2>
            <p class="muted">Control homepage layout, hero image, text, colors, section titles and visibility without editing code.</p>

            <div class="settings-check-list home-toggle-grid">
                @foreach([
                    'homepage_show_hero' => 'Hero section',
                    'homepage_show_categories' => 'Category carousel',
                    'homepage_show_brands' => 'Brand carousel',
                    'homepage_show_trending' => 'Trending products',
                    'homepage_show_best_selling' => 'Best selling products',
                    'homepage_show_offer' => 'Special offers block',
                    'homepage_show_new_arrivals' => 'New arrivals',
                    'homepage_show_services' => 'Service benefit boxes',
                ] as $key => $label)
                    <label class="checkbox-line">
                        <input type="checkbox" name="{{ $key }}" value="1" @checked(old($key, $settings[$key] ?? '1') == '1')>
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            <hr>
            <h3>Hero Layout & Style</h3>
            <div class="form-grid two-col">
                <label>Hero Layout
                    <select name="homepage_hero_layout">
                        @foreach([
                            'full_width_image' => 'Full Width Hero Image',
                            'centered_overlay' => 'Centered Text Overlay',
                            'contained_image' => 'Contained Rounded Hero',
                            'split_banner_grid' => 'Old Split Banner Grid',
                        ] as $value => $label)
                            <option value="{{ $value }}" @selected(old('homepage_hero_layout', $settings['homepage_hero_layout'] ?? 'full_width_image') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <small>Use Full Width Hero Image for the clean modern hero. Old layout is kept as an option.</small>
                </label>
                <label>Hero Text Alignment
                    <select name="homepage_hero_alignment">
                        @foreach(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('homepage_hero_alignment', $settings['homepage_hero_alignment'] ?? 'left') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Background Color
                    <input type="color" name="homepage_hero_bg_color" value="{{ old('homepage_hero_bg_color', $settings['homepage_hero_bg_color'] ?? '#f6efe5') }}">
                </label>
                <label>Text Color
                    <input type="color" name="homepage_hero_text_color" value="{{ old('homepage_hero_text_color', $settings['homepage_hero_text_color'] ?? '#1d1d1f') }}">
                </label>
                <label>Overlay Color
                    <input type="color" name="homepage_hero_overlay_color" value="{{ old('homepage_hero_overlay_color', $settings['homepage_hero_overlay_color'] ?? '#000000') }}">
                </label>
                <label>Overlay Opacity (%)
                    <input type="number" name="homepage_hero_overlay_opacity" min="0" max="90" value="{{ old('homepage_hero_overlay_opacity', $settings['homepage_hero_overlay_opacity'] ?? '15') }}">
                    <small>0 means no overlay. Use 10–35 when text needs more readability.</small>
                </label>
                <label>Hero Height
                    <input type="text" name="homepage_hero_height" value="{{ old('homepage_hero_height', $settings['homepage_hero_height'] ?? '560px') }}" placeholder="560px">
                    <small>Example: 520px, 70vh, 600px</small>
                </label>
                <label>Hero Background Image
                    <input type="file" name="homepage_hero_bg_image_upload" accept="image/*">
                    <small>Recommended size: 1920×700px or larger. Leave empty to keep current image.</small>
                </label>
            </div>
            <div class="settings-logo-preview settings-hero-preview-v37">
                <span>Current Hero Background</span>
                <img src="{{ \App\Models\SiteSetting::imageUrl($settings['homepage_hero_bg_image'] ?? ($settings['homepage_hero_1_image'] ?? 'foodmart/images/prosan-hero-food-beauty.svg'), 'foodmart/images/prosan-hero-food-beauty.svg') }}" alt="Hero background">
            </div>

            <hr>
            <h3>Hero Content</h3>
            <div class="form-grid two-col">
                <label>Kicker / Small Heading
                    <input type="text" name="homepage_hero_1_kicker" value="{{ old('homepage_hero_1_kicker', $settings['homepage_hero_1_kicker'] ?? 'Curated Asian Food') }}">
                </label>
                <label>Button Text
                    <input type="text" name="homepage_hero_1_button_text" value="{{ old('homepage_hero_1_button_text', $settings['homepage_hero_1_button_text'] ?? 'Shop Food') }}">
                </label>
                <label>Main H1 Title
                    <input type="text" name="homepage_hero_1_title" value="{{ old('homepage_hero_1_title', $settings['homepage_hero_1_title'] ?? 'Korean, Thai & Chinese food essentials') }}">
                </label>
                <label>Button URL
                    <input type="text" name="homepage_hero_1_button_url" value="{{ old('homepage_hero_1_button_url', $settings['homepage_hero_1_button_url'] ?? '/shop') }}">
                </label>
            </div>
            <label>Short Text
                <textarea name="homepage_hero_1_text">{{ old('homepage_hero_1_text', $settings['homepage_hero_1_text'] ?? 'Ramen, seaweed, kimchi, coffee, snacks, rice, sauce and daily pantry picks for your home.') }}</textarea>
            </label>

            <hr>
            <h3>Old Split Banner Layout Content</h3>
            <p class="muted">These fields are used only when Hero Layout is set to Old Split Banner Grid.</p>
            <div class="form-grid two-col align-start">
                <label>Hero Slide Image
                    <input type="file" name="homepage_hero_1_image_upload" accept="image/*">
                    <small>Leave empty to keep current image.</small>
                </label>
                <div class="settings-logo-preview"><span>Current image</span><img src="{{ \App\Models\SiteSetting::imageUrl($settings['homepage_hero_1_image'] ?? 'foodmart/images/prosan-hero-food-beauty.svg', 'foodmart/images/prosan-hero-food-beauty.svg') }}" alt="Hero 1"></div>
            </div>
            <div class="form-grid two-col">
                <label>Slide 2 Kicker
                    <input type="text" name="homepage_hero_2_kicker" value="{{ old('homepage_hero_2_kicker', $settings['homepage_hero_2_kicker'] ?? 'Beauty & Care') }}">
                </label>
                <label>Slide 2 Button Text
                    <input type="text" name="homepage_hero_2_button_text" value="{{ old('homepage_hero_2_button_text', $settings['homepage_hero_2_button_text'] ?? 'Shop Beauty') }}">
                </label>
                <label>Slide 2 Title
                    <input type="text" name="homepage_hero_2_title" value="{{ old('homepage_hero_2_title', $settings['homepage_hero_2_title'] ?? 'Skincare and cosmetics thoughtfully chosen') }}">
                </label>
                <label>Slide 2 Button URL
                    <input type="text" name="homepage_hero_2_button_url" value="{{ old('homepage_hero_2_button_url', $settings['homepage_hero_2_button_url'] ?? '/shop?category=cosmetics') }}">
                </label>
            </div>
            <label>Slide 2 Description
                <textarea name="homepage_hero_2_text">{{ old('homepage_hero_2_text', $settings['homepage_hero_2_text'] ?? '') }}</textarea>
            </label>
            <div class="form-grid two-col align-start">
                <label>Slide 2 Image
                    <input type="file" name="homepage_hero_2_image_upload" accept="image/*">
                    <small>Leave empty to keep current image.</small>
                </label>
                <div class="settings-logo-preview"><span>Current image</span><img src="{{ \App\Models\SiteSetting::imageUrl($settings['homepage_hero_2_image'] ?? 'foodmart/images/prosan-ad-beauty.svg', 'foodmart/images/prosan-ad-beauty.svg') }}" alt="Hero 2"></div>
            </div>

            <hr>
            <h3>Small Promo Banners</h3>
            <p class="muted">Used by the old split banner layout.</p>
            <div class="form-grid two-col">
                <label>Promo 1 Label
                    <input type="text" name="homepage_promo_1_label" value="{{ old('homepage_promo_1_label', $settings['homepage_promo_1_label'] ?? 'Popular') }}">
                </label>
                <label>Promo 2 Label
                    <input type="text" name="homepage_promo_2_label" value="{{ old('homepage_promo_2_label', $settings['homepage_promo_2_label'] ?? 'Essentials') }}">
                </label>
                <label>Promo 1 Title
                    <input type="text" name="homepage_promo_1_title" value="{{ old('homepage_promo_1_title', $settings['homepage_promo_1_title'] ?? 'Korean Food') }}">
                </label>
                <label>Promo 2 Title
                    <input type="text" name="homepage_promo_2_title" value="{{ old('homepage_promo_2_title', $settings['homepage_promo_2_title'] ?? 'Cooking Essentials') }}">
                </label>
                <label>Promo 1 Button Text
                    <input type="text" name="homepage_promo_1_button_text" value="{{ old('homepage_promo_1_button_text', $settings['homepage_promo_1_button_text'] ?? 'Shop Collection') }}">
                </label>
                <label>Promo 2 Button Text
                    <input type="text" name="homepage_promo_2_button_text" value="{{ old('homepage_promo_2_button_text', $settings['homepage_promo_2_button_text'] ?? 'Shop Collection') }}">
                </label>
                <label>Promo 1 URL
                    <input type="text" name="homepage_promo_1_button_url" value="{{ old('homepage_promo_1_button_url', $settings['homepage_promo_1_button_url'] ?? '/shop?category=ramen') }}">
                </label>
                <label>Promo 2 URL
                    <input type="text" name="homepage_promo_2_button_url" value="{{ old('homepage_promo_2_button_url', $settings['homepage_promo_2_button_url'] ?? '/shop?category=cooking-essentials') }}">
                </label>
                <label>Promo 1 Image
                    <input type="file" name="homepage_promo_1_image_upload" accept="image/*">
                </label>
                <label>Promo 2 Image
                    <input type="file" name="homepage_promo_2_image_upload" accept="image/*">
                </label>
            </div>

            <hr>
            <h3>Section Titles</h3>
            <div class="form-grid two-col">
                <label>Category Title
                    <input type="text" name="homepage_category_title" value="{{ old('homepage_category_title', $settings['homepage_category_title'] ?? 'Category') }}">
                </label>
                <label>Brand Title
                    <input type="text" name="homepage_brands_title" value="{{ old('homepage_brands_title', $settings['homepage_brands_title'] ?? 'Newly Arrived Brands') }}">
                </label>
                <label>Trending Title
                    <input type="text" name="homepage_trending_title" value="{{ old('homepage_trending_title', $settings['homepage_trending_title'] ?? 'Trending Products') }}">
                </label>
                <label>Best Selling Title
                    <input type="text" name="homepage_best_selling_title" value="{{ old('homepage_best_selling_title', $settings['homepage_best_selling_title'] ?? 'Best selling products') }}">
                </label>
                <label>New Arrivals Title
                    <input type="text" name="homepage_new_arrivals_title" value="{{ old('homepage_new_arrivals_title', $settings['homepage_new_arrivals_title'] ?? 'New arrivals') }}">
                </label>
                <label>Offer Title
                    <input type="text" name="homepage_offer_title" value="{{ old('homepage_offer_title', $settings['homepage_offer_title'] ?? 'Get special offers on new arrivals') }}">
                </label>
            </div>
            <label>Offer Text
                <textarea name="homepage_offer_text">{{ old('homepage_offer_text', $settings['homepage_offer_text'] ?? '') }}</textarea>
            </label>
        </section>

        <section class="content-card settings-card">
            <h2>Order Notifications</h2>
            <p class="muted">Send automatic email notifications for new orders, order status changes and courier updates. If SMTP is not configured yet, Laravel will use your current mail setting.</p>
            <div class="form-grid two-col">
                <label>Admin Notification Email
                    <input type="email" name="notification_admin_email" value="{{ old('notification_admin_email', $settings['notification_admin_email'] ?? ($settings['support_email'] ?? '')) }}" placeholder="orders@prosanatelier.com">
                    <small>If blank, Support Email will receive admin order notifications.</small>
                </label>
                <label>Email Sender Name
                    <input type="text" name="notification_from_name" value="{{ old('notification_from_name', $settings['notification_from_name'] ?? ($settings['site_name'] ?? 'Prosan Atelier')) }}">
                </label>
            </div>
            <div class="settings-check-list">
                <label class="checkbox-line">
                    <input type="checkbox" name="notify_admin_new_order" value="1" @checked(old('notify_admin_new_order', $settings['notify_admin_new_order'] ?? '1') == '1')>
                    Notify admin when a new order is placed
                </label>
                <label class="checkbox-line">
                    <input type="checkbox" name="notify_customer_order_placed" value="1" @checked(old('notify_customer_order_placed', $settings['notify_customer_order_placed'] ?? '1') == '1')>
                    Notify customer after order placement
                </label>
                <label class="checkbox-line">
                    <input type="checkbox" name="notify_customer_status_update" value="1" @checked(old('notify_customer_status_update', $settings['notify_customer_status_update'] ?? '1') == '1')>
                    Notify customer when order/payment status changes
                </label>
                <label class="checkbox-line">
                    <input type="checkbox" name="notify_customer_courier_update" value="1" @checked(old('notify_customer_courier_update', $settings['notify_customer_courier_update'] ?? '1') == '1')>
                    Notify customer when Steadfast courier status changes
                </label>
            </div>
        </section>

        <section class="content-card settings-card">
            <h2>Steadfast Courier</h2>
            <label class="checkbox-line">
                <input type="checkbox" name="steadfast_enabled" value="1" @checked(old('steadfast_enabled', $settings['steadfast_enabled'] ?? '') == '1')>
                Enable Steadfast Integration
            </label>
            <div class="form-grid two-col">
                <label>API Key
                    <input type="text" name="steadfast_api_key" value="{{ old('steadfast_api_key', $settings['steadfast_api_key'] ?? '') }}" autocomplete="off">
                </label>
                <label>Secret Key
                    <input type="password" name="steadfast_secret_key" value="{{ old('steadfast_secret_key', $settings['steadfast_secret_key'] ?? '') }}" autocomplete="new-password">
                </label>
                <label>Base URL
                    <input type="url" name="steadfast_base_url" value="{{ old('steadfast_base_url', $settings['steadfast_base_url'] ?? 'https://portal.packzy.com/api/v1') }}">
                </label>
            </div>
            <p class="muted">You can keep .env credentials too. Settings here will override .env when filled.</p>
        </section>
    </div>

    <div class="settings-save-bar">
        <button class="btn" type="submit">Save Settings</button>
    </div>
</form>

<section class="content-card settings-card notification-test-card" style="margin-top: 18px;">
    <h2>Test Notification Email</h2>
    <p class="muted">
        Send a test email before waiting for a real order. Current mailer:
        <strong>{{ $mailConfig['mailer'] ?? 'unknown' }}</strong>
        @if(!empty($mailConfig['host']))
            / Host: <strong>{{ $mailConfig['host'] }}</strong>
        @endif
        @if(!empty($mailConfig['from']))
            / From: <strong>{{ $mailConfig['from'] }}</strong>
        @endif
    </p>
    @if(($mailConfig['mailer'] ?? '') === 'log')
        <p class="alert alert-warning" style="margin: 10px 0;">
            MAIL_MAILER is still <strong>log</strong>. Test will be written to Laravel log only. Set MAIL_MAILER=smtp in .env to receive real email.
        </p>
    @endif
    <form method="POST" action="{{ route('admin.settings.test_email') }}" class="form-grid two-col">
        @csrf
        <label>Test Email Address
            <input type="email" name="test_email" value="{{ old('test_email', $settings['notification_admin_email'] ?? ($settings['support_email'] ?? '')) }}" placeholder="info@prosanatelier.com">
            <small>Blank hole Admin Notification Email / Support Email use korbe.</small>
        </label>
        <div style="display:flex;align-items:flex-end;gap:10px;">
            <button class="btn" type="submit">Send Test Email</button>
        </div>
    </form>
</section>

<section class="content-card settings-card notification-log-card" style="margin-top: 18px;">
    <h2>Recent Email Logs</h2>
    <p class="muted">Last 10 notification attempts. Failed hole error ekhane dekha jabe.</p>
    @if(($emailLogs ?? collect())->count())
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Event</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emailLogs as $log)
                        <tr>
                            <td>{{ optional($log->created_at)->format('d M Y, h:i A') }}</td>
                            <td>{{ str_replace('_', ' ', ucfirst($log->event)) }}</td>
                            <td>{{ $log->recipient_email }}</td>
                            <td>
                                <span class="badge {{ $log->status === 'sent' ? 'badge-success' : ($log->status === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td>{{ $log->error_message ? \Illuminate\Support\Str::limit($log->error_message, 120) : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="muted">No email log yet. Send a test email or place a test order.</p>
    @endif
</section>

@endsection
