<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CouponDiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function apply(Request $request, CouponDiscountService $coupons)
    {
        $data = $request->validate([
            'coupon_code' => ['required', 'string', 'max:80'],
        ]);

        $code = Str::upper(trim($data['coupon_code']));
        $result = $coupons->calculateForCart(session('cart', []), $code);

        if (! $result['valid']) {
            session()->forget('coupon_code');
            return back()->with('error', $result['message'] ?: 'Coupon could not be applied.');
        }

        session(['coupon_code' => $code]);
        return back()->with('success', $result['message'] ?: 'Coupon applied successfully.');
    }

    public function remove()
    {
        session()->forget('coupon_code');
        return back()->with('success', 'Coupon removed.');
    }
}
