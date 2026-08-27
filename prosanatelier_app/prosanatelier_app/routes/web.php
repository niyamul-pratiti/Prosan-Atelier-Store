<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderTrackingController;
use App\Http\Controllers\Frontend\CouponController as FrontendCouponController;
use App\Http\Controllers\Frontend\CustomerAccountController;
use App\Http\Controllers\Frontend\ProductRequestController as FrontendProductRequestController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImportExportController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\SystemHealthController;
use App\Http\Controllers\Admin\ProductRequestController as AdminProductRequestController;
use App\Http\Middleware\EnsureAdminAuthenticated;


Route::get('/storage-files/{path}', function (string $path) {
    $path = trim($path, '/');

    abort_if(str_contains($path, '..') || str_starts_with($path, '.'), 404);

    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    abort_unless(in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'], true), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);

    return response()->file(Storage::disk('public')->path($path), [
        'X-Content-Type-Options' => 'nosniff',
        'Cache-Control' => 'public, max-age=604800',
    ]);
})->where('path', '.*')->name('storage.file');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category.show');
Route::get('/brand/{slug}', [ShopController::class, 'brand'])->name('brand.show');
Route::get('/order-tracking', [OrderTrackingController::class, 'index'])->name('order.tracking');
Route::post('/order-tracking', [OrderTrackingController::class, 'track'])->middleware('throttle:12,1')->name('order.tracking.store');
Route::post('/product-request', [FrontendProductRequestController::class, 'store'])->middleware('throttle:4,1')->name('product_requests.store');

Route::get('/account/login', [CustomerAccountController::class, 'showLogin'])->name('customer.login');
Route::post('/account/login', [CustomerAccountController::class, 'login'])->name('customer.login.store');
Route::get('/account/register', [CustomerAccountController::class, 'showRegister'])->name('customer.register');
Route::post('/account/register', [CustomerAccountController::class, 'register'])->middleware('throttle:5,1')->name('customer.register.store');
Route::post('/account/logout', [CustomerAccountController::class, 'logout'])->name('customer.logout');
Route::get('/account', [CustomerAccountController::class, 'dashboard'])->name('customer.dashboard');
Route::get('/account/orders', [CustomerAccountController::class, 'orders'])->name('customer.orders');
Route::get('/account/orders/{orderNumber}', [CustomerAccountController::class, 'orderShow'])->name('customer.orders.show');
Route::post('/account/orders/{orderNumber}/reorder', [CustomerAccountController::class, 'reorder'])->name('customer.orders.reorder');
Route::get('/account/profile', [CustomerAccountController::class, 'profile'])->name('customer.profile');
Route::put('/account/profile', [CustomerAccountController::class, 'updateProfile'])->name('customer.profile.update');
Route::put('/account/password', [CustomerAccountController::class, 'updatePassword'])->name('customer.password.update');
Route::get('/account/wishlist', [CustomerAccountController::class, 'wishlist'])->name('customer.wishlist');
Route::post('/wishlist/{product}/toggle', [CustomerAccountController::class, 'toggleWishlist'])->name('wishlist.toggle');
Route::delete('/wishlist/{product}', [CustomerAccountController::class, 'removeWishlist'])->name('wishlist.remove');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/coupon/apply', [FrontendCouponController::class, 'apply'])->name('coupon.apply');
Route::post('/coupon/remove', [FrontendCouponController::class, 'remove'])->name('coupon.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');
Route::get('/thank-you/{orderNumber}', [CheckoutController::class, 'thankYou'])->name('checkout.thank_you');
Route::get('/thank-you/{orderNumber}/invoice', [CheckoutController::class, 'invoice'])->name('checkout.invoice');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.store');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware([EnsureAdminAuthenticated::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [AdminAuthController::class, 'showProfile'])->name('profile');
    Route::put('profile', [AdminAuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/test-email', [SettingController::class, 'sendTestEmail'])->name('settings.test_email');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('brands', BrandController::class)->except(['show']);

    Route::get('products/import-export', [ProductImportExportController::class, 'index'])->name('products.import_export');
    Route::post('products/import', [ProductImportExportController::class, 'import'])->name('products.import');
    Route::get('products/export', [ProductImportExportController::class, 'export'])->name('products.export');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('coupons', CouponController::class)->except(['show']);
    Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.destroy');
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('product-requests', [AdminProductRequestController::class, 'index'])->name('product_requests.index');
    Route::get('product-requests/export', [AdminProductRequestController::class, 'export'])->name('product_requests.export');
    Route::patch('product-requests/{productRequest}', [AdminProductRequestController::class, 'update'])->name('product_requests.update');
    Route::delete('product-requests/{productRequest}', [AdminProductRequestController::class, 'destroy'])->name('product_requests.destroy');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
    Route::delete('activity-logs/clear', [ActivityLogController::class, 'clear'])->name('activity_logs.clear');
    Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
    Route::get('backups/database', [BackupController::class, 'database'])->name('backups.database');
    Route::get('backups/orders', [BackupController::class, 'orders'])->name('backups.orders');
    Route::get('backups/products', [BackupController::class, 'products'])->name('backups.products');
    Route::get('backups/customers', [BackupController::class, 'customers'])->name('backups.customers');
    Route::get('system-health', [SystemHealthController::class, 'index'])->name('system_health.index');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::patch('orders/{order}', [OrderController::class, 'update'])->name('orders.patch');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/steadfast/send', [OrderController::class, 'sendToSteadfast'])->name('orders.steadfast.send');
    Route::post('orders/{order}/steadfast/refresh', [OrderController::class, 'refreshSteadfast'])->name('orders.steadfast.refresh');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('orders/{order}/packing-slip', [OrderController::class, 'packingSlip'])->name('orders.packing_slip');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
