<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Support\AdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOGIN_LOCK_SECONDS = 900;

    public function showLogin()
    {
        if (session('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Too many login attempts. Please try again in '.ceil($seconds / 60).' minute(s).');
        }

        $admin = Admin::where('email', $credentials['email'])->where('is_active', true)->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            RateLimiter::hit($throttleKey, self::LOGIN_LOCK_SECONDS);

            return back()->withInput($request->only('email'))->with('error', 'Invalid admin credentials.');
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();
        session([
            'admin_id' => $admin->id,
            'admin_login_at' => now()->timestamp,
        ]);

        AdminActivity::record($request, $admin->id, 'admin_login', 'Admin logged in.');

        return redirect()->route('admin.dashboard')->with('success', 'Welcome back.');
    }

    public function showProfile(Request $request)
    {
        $admin = Admin::findOrFail($request->session()->get('admin_id'));

        return view('admin.auth.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Admin::findOrFail($request->session()->get('admin_id'));

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('admins', 'email')->ignore($admin->id)],
            'current_password' => ['required', 'string'],
            'password' => ['nullable', 'string', 'min:10', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], $admin->password)) {
            return back()->withInput($request->only('name', 'email'))->with('error', 'Current password is incorrect.');
        }

        $admin->name = $data['name'];
        $admin->email = $data['email'];

        if (! empty($data['password'])) {
            $admin->password = $data['password'];
        }

        $admin->save();

        $request->session()->regenerate();

        return back()->with('success', 'Admin account updated successfully.');
    }

    public function logout(Request $request)
    {
        $adminId = $request->session()->get('admin_id');
        if ($adminId) {
            AdminActivity::record($request, (int) $adminId, 'admin_logout', 'Admin logged out.');
        }

        $request->session()->forget(['admin_id', 'admin_login_at']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    private function throttleKey(Request $request): string
    {
        return 'admin-login:'.strtolower((string) $request->input('email')).'|'.$request->ip();
    }
}
