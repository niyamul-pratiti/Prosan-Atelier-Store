<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Wishlist;
use App\Rules\AllowedPublicEmail;
use App\Support\BangladeshLocations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;

class CustomerAccountController extends Controller
{
    public function showLogin()
    {
        if ($this->currentCustomer()) {
            return redirect()->route('customer.dashboard');
        }

        return view('frontend.account.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:150'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($data['login']);
        $throttleKey = 'customer-login:'.strtolower($login).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 6)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('login'))
                ->with('error', 'Too many login attempts. Please try again in '.ceil($seconds / 60).' minute(s).');
        }

        $customer = Customer::where('email', $login)->orWhere('phone', $login)->first();

        if (! $customer || ! $customer->is_active || ! Hash::check($data['password'], $customer->password)) {
            RateLimiter::hit($throttleKey, 900);

            return back()->withInput($request->only('login'))->with('error', 'Invalid account details.');
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        session(['customer_id' => $customer->id]);

        return redirect()->route('customer.dashboard')->with('success', 'Welcome back, '.$customer->name.'.');
    }

    public function showRegister()
    {
        if ($this->currentCustomer()) {
            return redirect()->route('customer.dashboard');
        }

        $districts = $this->districts();

        return view('frontend.account.register', compact('districts'));
    }

    public function register(Request $request)
    {
        $throttleKey = 'customer-register:'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withInput()->with('error', 'Too many registration attempts. Please try again in '.ceil($seconds / 60).' minute(s).');
        }

        RateLimiter::hit($throttleKey, 900);

        $data = $request->validate([
            'website' => ['nullable', 'string', 'max:0'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email:rfc', 'max:150', new AllowedPublicEmail, 'unique:customers,email'],
            'phone' => ['required', 'string', 'max:30', 'unique:customers,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address_line' => ['nullable', 'string', 'max:1000'],
            'area' => ['nullable', 'string', 'max:150'],
            'city' => ['nullable', 'string', 'max:150'],
        ]);

        unset($data['website']);

        if (! empty($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        $data['shipping_zone'] = BangladeshLocations::zoneForLocation($data['city'] ?? 'Dhaka', $data['area'] ?? null);

        $customer = Customer::create($data + ['is_active' => true]);
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        session(['customer_id' => $customer->id]);

        return redirect()->route('customer.dashboard')->with('success', 'Your account has been created.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('customer_id');
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You are logged out.');
    }

    public function dashboard()
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to access your account.');
        }

        $recentOrders = $customer->orders()->latest()->take(5)->get();
        $wishlistProducts = $customer->wishlistProducts()->with('images', 'brand', 'category')->take(4)->get();
        $stats = [
            'orders' => $customer->orders()->count(),
            'pending' => $customer->orders()->whereIn('order_status', ['pending', 'processing'])->count(),
            'completed' => $customer->orders()->where('order_status', 'completed')->count(),
            'wishlist' => $customer->wishlists()->count(),
            'total_spent' => (float) $customer->orders()->where('order_status', '!=', 'cancelled')->sum('grand_total'),
        ];

        return view('frontend.account.dashboard', compact('customer', 'recentOrders', 'wishlistProducts', 'stats'));
    }

    public function orders()
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to view your orders.');
        }

        $orders = $customer->orders()->withCount('items')->latest()->paginate(10);
        return view('frontend.account.orders', compact('customer', 'orders'));
    }

    public function orderShow(string $orderNumber)
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to view your order.');
        }

        $order = $customer->orders()->with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view('frontend.account.order-show', compact('customer', 'order'));
    }

    public function profile()
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to edit your profile.');
        }

        $districts = $this->districts();
        return view('frontend.account.profile', compact('customer', 'districts'));
    }

    public function updateProfile(Request $request)
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to edit your profile.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email:rfc', 'max:150', new AllowedPublicEmail, Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone' => ['required', 'string', 'max:30', Rule::unique('customers', 'phone')->ignore($customer->id)],
            'address_line' => ['nullable', 'string', 'max:1000'],
            'area' => ['nullable', 'string', 'max:150'],
            'city' => ['nullable', 'string', 'max:150'],
        ]);

        if (! empty($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        $data['shipping_zone'] = BangladeshLocations::zoneForLocation($data['city'] ?? 'Dhaka', $data['area'] ?? null);
        $customer->update($data);

        return back()->with('success', 'Profile and delivery address updated.');
    }

    public function updatePassword(Request $request)
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to change your password.');
        }

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], $customer->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $customer->update(['password' => $data['password']]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function wishlist()
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to view your wishlist.');
        }

        $wishlistProducts = $customer->wishlistProducts()->with('images', 'brand', 'category')->paginate(12);
        return view('frontend.account.wishlist', compact('customer', 'wishlistProducts'));
    }

    public function toggleWishlist(Request $request, Product $product)
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login first to use wishlist.');
        }

        if (! $product->is_active) {
            return back()->with('error', 'This product is unavailable.');
        }

        $existing = Wishlist::where('customer_id', $customer->id)->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Product removed from wishlist.');
        }

        Wishlist::create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Product added to wishlist.');
    }

    public function removeWishlist(Product $product)
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login first.');
        }

        Wishlist::where('customer_id', $customer->id)->where('product_id', $product->id)->delete();
        return back()->with('success', 'Product removed from wishlist.');
    }

    public function reorder(string $orderNumber)
    {
        $customer = $this->requireCustomer();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please login first.');
        }

        $order = $customer->orders()->with('items')->where('order_number', $orderNumber)->firstOrFail();
        $cart = session('cart', []);
        $added = 0;

        foreach ($order->items as $item) {
            $product = Product::active()->with('images')->find($item->product_id);
            if (! $product) {
                continue;
            }

            $variation = $item->product_variation_id ? ProductVariation::where('product_id', $product->id)->where('is_active', true)->find($item->product_variation_id) : null;
            $stock = $variation ? $variation->stock_quantity : $product->stock_quantity;
            if ($stock < 1) {
                continue;
            }

            $qty = max(1, min((int) $item->quantity, (int) $stock));
            $key = $product->id . ':' . ($variation?->id ?: 'simple');
            $price = $variation ? $variation->effective_price : $product->effective_price;

            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'variation_id' => $variation?->id,
                'name' => $product->name,
                'variation_name' => $variation?->name,
                'sku' => $variation?->sku ?: $product->sku,
                'price' => $price,
                'quantity' => min(($cart[$key]['quantity'] ?? 0) + $qty, (int) $stock),
                'image' => $product->image_url,
                'stock' => $stock,
            ];
            $added++;
        }

        session(['cart' => $cart]);

        if ($added === 0) {
            return back()->with('error', 'No available products found from this order.');
        }

        return redirect()->route('cart.index')->with('success', 'Order items added to cart. Please review before checkout.');
    }

    private function districts(): array
    {
        return BangladeshLocations::districts();
    }

    private function currentCustomer(): ?Customer
    {
        $id = session('customer_id');
        return $id ? Customer::find($id) : null;
    }

    private function requireCustomer(): ?Customer
    {
        $customer = $this->currentCustomer();
        if (! $customer || ! $customer->is_active) {
            session()->forget('customer_id');
            return null;
        }

        return $customer;
    }
}
