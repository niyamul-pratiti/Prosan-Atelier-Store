<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ProductRequest as ProductRequestModel;
use App\Rules\AllowedPublicEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ProductRequestController extends Controller
{
    public function store(Request $request)
    {
        $hourlyKey = 'product-request-hourly:'.$request->ip();

        if (RateLimiter::tooManyAttempts($hourlyKey, 8)) {
            return back()
                ->withInput($request->except(['website']))
                ->with('error', 'Too many product requests were submitted. Please try again later.');
        }

        RateLimiter::hit($hourlyKey, 3600);

        $validated = $request->validate([
            'website' => ['nullable', 'string', 'max:0'],
            'customer_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email:rfc', 'max:150', new AllowedPublicEmail],
            'product_name' => ['required', 'string', 'max:180'],
            'brand' => ['nullable', 'string', 'max:120'],
            'product_link' => ['nullable', 'url:http,https', 'max:500'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'message' => ['nullable', 'string', 'max:1000'],
            'source' => ['nullable', 'string', 'max:80'],
        ], [
            'customer_name.required' => 'Please enter your name.',
            'phone.required' => 'Please enter your phone number.',
            'product_name.required' => 'Please enter the requested product name.',
        ]);

        unset($validated['website']);

        foreach (['customer_name', 'phone', 'product_name', 'brand', 'message', 'source'] as $field) {
            if (array_key_exists($field, $validated) && is_string($validated[$field])) {
                $validated[$field] = trim(strip_tags($validated[$field]));
            }
        }

        if (! empty($validated['email'])) {
            $validated['email'] = strtolower(trim($validated['email']));
        }

        $validated['status'] = 'new';
        $validated['source'] = $validated['source'] ?? 'website';

        ProductRequestModel::create($validated);

        return back()->with('success', 'Your product request has been submitted. We will check and contact you soon.');
    }
}
