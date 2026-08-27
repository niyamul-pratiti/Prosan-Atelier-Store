<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryBrandProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryData = [
            [
                'slug' => 'asian-food',
                'name' => 'Korean & Asian Food',
                'parent' => null,
                'sort_order' => 1,
                'description' => 'Ramen, cup ramen, kimchi and seaweed.',
            ],
            [
                'slug' => 'ramen',
                'name' => 'Ramen',
                'parent' => 'asian-food',
                'sort_order' => 1,
                'description' => 'Korean and Asian ramen packs.',
            ],
            [
                'slug' => 'cup-ramen',
                'name' => 'Cup Ramen',
                'parent' => 'asian-food',
                'sort_order' => 2,
                'description' => 'Quick instant cup ramen.',
            ],
            [
                'slug' => 'seaweed',
                'name' => 'Seaweed',
                'parent' => 'asian-food',
                'sort_order' => 3,
                'description' => 'Seaweed snacks, nori and kimbap sheets.',
            ],
            [
                'slug' => 'kimchi',
                'name' => 'Kimchi',
                'parent' => 'asian-food',
                'sort_order' => 4,
                'description' => 'Ready-to-eat kimchi selections.',
            ],
            [
                'slug' => 'cooking-essentials',
                'name' => 'Cooking Essentials',
                'parent' => null,
                'sort_order' => 2,
                'description' => 'Sauce, rice, gochujang and pantry items.',
            ],
            [
                'slug' => 'gochujang',
                'name' => 'Gochujang',
                'parent' => 'cooking-essentials',
                'sort_order' => 1,
                'description' => 'Korean chili paste and cooking sauces.',
            ],
            [
                'slug' => 'rice',
                'name' => 'Rice',
                'parent' => 'cooking-essentials',
                'sort_order' => 2,
                'description' => 'Sushi rice and cooking rice.',
            ],
            [
                'slug' => 'snacks',
                'name' => 'Snacks & Coffee',
                'parent' => null,
                'sort_order' => 3,
                'description' => 'Coffee, cakes and snack items.',
            ],
            [
                'slug' => 'coffee',
                'name' => 'Coffee',
                'parent' => 'snacks',
                'sort_order' => 1,
                'description' => 'Coffee mixes and beverage snacks.',
            ],
            [
                'slug' => 'cakes',
                'name' => 'Cakes',
                'parent' => 'snacks',
                'sort_order' => 2,
                'description' => 'Packaged cakes and sweets.',
            ],
            [
                'slug' => 'cosmetics',
                'name' => 'Skin Care & Cosmetics',
                'parent' => null,
                'sort_order' => 4,
                'description' => 'Cleanser, essence, sunscreen and beauty care.',
            ],
            [
                'slug' => 'skin-care',
                'name' => 'Skin Care',
                'parent' => 'cosmetics',
                'sort_order' => 1,
                'description' => 'Daily skin care products.',
            ],
            [
                'slug' => 'face-wash-cleanser',
                'name' => 'Face Wash / Cleanser',
                'parent' => 'cosmetics',
                'sort_order' => 2,
                'description' => 'Face wash and cleanser products.',
            ],
            [
                'slug' => 'sunscreen',
                'name' => 'Sunscreen',
                'parent' => 'cosmetics',
                'sort_order' => 3,
                'description' => 'Sun care products.',
            ],
            [
                'slug' => 'lifestyle',
                'name' => 'Lifestyle',
                'parent' => null,
                'sort_order' => 5,
                'description' => 'Lifestyle and daily care items.',
            ],
        ];


        $categoryImages = [
            'asian-food' => 'foodmart/images/categories/category-asian-food.svg',
            'ramen' => 'foodmart/images/categories/category-ramen.svg',
            'cup-ramen' => 'foodmart/images/categories/category-cup-ramen.svg',
            'seaweed' => 'foodmart/images/categories/category-seaweed.svg',
            'kimchi' => 'foodmart/images/categories/category-kimchi.svg',
            'cooking-essentials' => 'foodmart/images/categories/category-cooking-essentials.svg',
            'gochujang' => 'foodmart/images/categories/category-gochujang.svg',
            'rice' => 'foodmart/images/categories/category-rice.svg',
            'snacks' => 'foodmart/images/categories/category-snacks.svg',
            'coffee' => 'foodmart/images/categories/category-coffee.svg',
            'cakes' => 'foodmart/images/categories/category-cakes.svg',
            'cosmetics' => 'foodmart/images/categories/category-cosmetics.svg',
            'skin-care' => 'foodmart/images/categories/category-skin-care.svg',
            'face-wash-cleanser' => 'foodmart/images/categories/category-face-wash-cleanser.svg',
            'sunscreen' => 'foodmart/images/categories/category-sunscreen.svg',
            'lifestyle' => 'foodmart/images/categories/category-lifestyle.svg',
        ];

        $categoryModels = [];
        foreach ($categoryData as $category) {
            if ($category['parent'] === null) {
                $categoryModels[$category['slug']] = Category::updateOrCreate(
                    ['slug' => $category['slug']],
                    ['name' => $category['name'], 'parent_id' => null, 'image' => $categoryImages[$category['slug']] ?? 'foodmart/images/categories/category-default.svg', 'description' => $category['description'], 'sort_order' => $category['sort_order'], 'is_active' => true]
                );
            }
        }

        foreach ($categoryData as $category) {
            if ($category['parent'] !== null) {
                $categoryModels[$category['slug']] = Category::updateOrCreate(
                    ['slug' => $category['slug']],
                    ['name' => $category['name'], 'parent_id' => $categoryModels[$category['parent']]->id ?? null, 'image' => $categoryImages[$category['slug']] ?? 'foodmart/images/categories/category-default.svg', 'description' => $category['description'], 'sort_order' => $category['sort_order'], 'is_active' => true]
                );
            }
        }

        $brandData = [
            [
                'slug' => '3w-clinic',
                'name' => '3W Clinic',
                'sort_order' => 1,
            ],
            [
                'slug' => 'ablue',
                'name' => 'ablue',
                'sort_order' => 2,
            ],
            [
                'slug' => 'bibigo',
                'name' => 'Bibigo',
                'sort_order' => 3,
            ],
            [
                'slug' => 'cj',
                'name' => 'CJ',
                'sort_order' => 4,
            ],
            [
                'slug' => 'cosrx',
                'name' => 'Cosrx',
                'sort_order' => 5,
            ],
            [
                'slug' => 'imee',
                'name' => 'iMee',
                'sort_order' => 6,
            ],
            [
                'slug' => 'lotus-rice',
                'name' => 'Lotus Rice',
                'sort_order' => 7,
            ],
            [
                'slug' => 'maxim',
                'name' => 'Maxim',
                'sort_order' => 8,
            ],
            [
                'slug' => 'missha',
                'name' => 'MISSHA',
                'sort_order' => 9,
            ],
            [
                'slug' => 'nongshim',
                'name' => 'Nongshim',
                'sort_order' => 10,
            ],
            [
                'slug' => 'orion',
                'name' => 'Orion',
                'sort_order' => 11,
            ],
            [
                'slug' => 'samyang',
                'name' => 'Samyang',
                'sort_order' => 12,
            ],
            [
                'slug' => 'sempio',
                'name' => 'Sempio',
                'sort_order' => 13,
            ],
            [
                'slug' => 'the-face-shop',
                'name' => 'The Face Shop',
                'sort_order' => 14,
            ],
        ];


        $brandLogos = [
            '3w-clinic' => 'foodmart/images/brands/brand-3w-clinic.svg',
            'ablue' => 'foodmart/images/brands/brand-ablue.svg',
            'bibigo' => 'foodmart/images/brands/brand-bibigo.svg',
            'cj' => 'foodmart/images/brands/brand-cj.svg',
            'cosrx' => 'foodmart/images/brands/brand-cosrx.svg',
            'imee' => 'foodmart/images/brands/brand-imee.svg',
            'lotus-rice' => 'foodmart/images/brands/brand-lotus-rice.svg',
            'maxim' => 'foodmart/images/brands/brand-maxim.svg',
            'missha' => 'foodmart/images/brands/brand-missha.svg',
            'nongshim' => 'foodmart/images/brands/brand-nongshim.svg',
            'orion' => 'foodmart/images/brands/brand-orion.svg',
            'samyang' => 'foodmart/images/brands/brand-samyang.svg',
            'sempio' => 'foodmart/images/brands/brand-sempio.svg',
            'the-face-shop' => 'foodmart/images/brands/brand-the-face-shop.svg',
        ];

        $brandModels = [];
        foreach ($brandData as $brand) {
            $brandModels[$brand['slug']] = Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                ['name' => $brand['name'], 'logo' => $brandLogos[$brand['slug']] ?? 'foodmart/images/brands/brand-default.svg', 'sort_order' => $brand['sort_order'], 'is_active' => true]
            );
        }

        $products = [
            [
                'source_id' => 22,
                'name' => 'Samyang Buldak Hot Chicken Habanero Lime Ramen Halal 135g',
                'slug' => 'samyang-buldak-hot-chicken-habanero-lime-ramen-halal-135g',
                'sku' => 'PA-0022',
                'category_slug' => 'ramen',
                'short_description' => '100% Halal certified imported food item.',
                'description' => '100% Halal certified imported food item.',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-Buldak-Hot-Chicken-Habanero-Lime-Ramen-Halal-135g.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 36,
                'name' => 'Samyang Buldak Quattro Cheese Flavor Ramen Halal 145g',
                'slug' => 'samyang-buldak-quattro-cheese-flavor-ramen-halal-145g',
                'sku' => 'PA-0036',
                'category_slug' => 'ramen',
                'short_description' => '100% Halal certified imported food item.',
                'description' => '100% Halal certified imported food item.',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-Buldak-Quattro-Cheese-Flavor-Ramen-Halal-145g.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 37,
                'name' => 'Samyang 3x Buldak Hot Chicken Ramen Halal 140g',
                'slug' => 'samyang-3x-buldak-hot-chicken-ramen-halal-140g',
                'sku' => 'PA-0037',
                'category_slug' => 'ramen',
                'short_description' => '100% Halal Certified',
                'description' => '<h2>Samyang 3x Buldak Hot Chicken Ramen (Halal) 140g – চরম ঝালের এক নতুন অভিজ্ঞতা!</h2>
\\n<p>আপনি কি ঝাল খেতে ভালোবাসেন? নিজের স্পাইসি ফুড টলারেন্স পরীক্ষা করতে চান? তাহলে সাময়্যাং-এর সবচেয়ে ঝাল নুডুলস <b>Samyang 3x Buldak Hot Chicken Ramen</b> আপনার জন্যই! এটি সাধারণ বুলদাক নুডুলসের চেয়ে ৩ গুণ বেশি ঝাল, যা আপনার টেস্ট বাডকে এক মুহূর্তের জন্য স্তব্ধ করে দেবে। যারা সত্যিকারের স্পাইসি চ্যালেঞ্জ খুঁজছেন, তাদের জন্য এটি পারফেক্ট চয়েস।</p>
\\n
\\n<h3>🌶️ মূল আকর্ষণসমূহ:</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>৩ গুণ তীব্র ঝাল (3x Spicy):</b> সাময়্যাং ব্র্যান্ডের অন্যতম শীর্ষ ঝাল নুডুলস, যা চরম ঝালপ্রেমীদের জন্য তৈরি।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি (KMF Halal Certified), তাই নিশ্চিন্তে উপভোগ করতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সিগনেচার কোরিয়ান টেক্সচার:</b> নুডুলসের থিক এবং চিউই (Chewy) টেক্সচার আপনাকে দেবে পারফেক্ট কোরিয়ান রামেন খাওয়ার অনুভূতি।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ঝটপট তৈরি:</b> মাত্র ৫ মিনিটে তৈরি করা যায়, যা আপনার মাঝরাতের ক্রেভিং বা বিকেলের নাস্তার জন্য দারুণ।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট ডিটেইলস:</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য</strong></td>
\\n<td><strong>বিবরণ</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Samyang (কোরিয়ান ব্র্যান্ড)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ফ্লেভার</b></span></td>
\\n<td><span>৩এক্স বুলদাক হট চিকেন (3x Buldak Hot Chicken)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ওজন</b></span></td>
\\n<td><span>১৪০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং</b></span></td>
\\n<td><span>সিঙ্গেল প্যাক</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>🍳 যেভাবে তৈরি করবেন (Cooking Instructions):</h3>
\\n<p>১. একটি পাত্রে ৬০০ মিলি (প্রায় ৩ কাপ) পানি ফুটিয়ে নিন।</p>
\\n<p>২. ফুটন্ত পানিতে নুডুলস দিয়ে দিন এবং ৫ মিনিট সেদ্ধ করুন।</p>
\\n<p>৩. সেদ্ধ হয়ে গেলে পাত্র থেকে পানি ছেঁকে ফেলে দিন (মনে রাখবেন, মাত্র ৮ চামচ বা সামান্য পানি নুডুলসে রেখে দেবেন)।</p>
\\n<p>৪. এবার প্যাকেটে থাকা স্পাইসি লিকুইড সস ও ফ্লেকস (তিল ও সামুদ্রিক শৈবাল) মিশিয়ে নিন।</p>
\\n<p>৫. মাঝারি আঁচে ৩০ সেকেন্ড ভালোমতো নেড়েচেড়ে নামিয়ে নিন এবং গরম গরম পরিবেশন করুন!</p>
\\n
\\n<blockquote>
\\n<p><b>সতর্কতা:</b> এই নুডুলসটি অত্যন্ত ঝাল। যাদের ঝাল খাওয়ার অভ্যাস কম, তাদের সাবধানে খাওয়ার পরামর্শ দেওয়া হচ্ছে। ঝাল কমাতে আপনি চাইলে এতে ডিম, চিজ অথবা মেয়নেজ যোগ করতে পারেন।</p>
\\n</blockquote>
\\n<p><b>আজই অর্ডার করুন এবং আপনার বন্ধুদের সাথে মেতে উঠুন চরম স্পাইসি চ্যালেঞ্জে!</b></p>',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-3x-Buldak-Hot-Chicken-Ramen-Halal-140g.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 38,
                'name' => 'Samyang Buldak Hot Chicken Flavor Ramen(halal) 140g',
                'slug' => 'samyang-buldak-hot-chicken-flavor-ramen-halal-140g',
                'sku' => 'PA-0038',
                'category_slug' => 'ramen',
                'short_description' => '100% Halal Certified',
                'description' => '<h2>Samyang Buldak Hot Chicken Flavor Ramen (Halal) 140g – আসল কোরিয়ান ঝালের স্বাদ!</h2>
\\n<p>কোরিয়ান স্পাইসি নুডুলসের দুনিয়ায় বিপ্লব এনে দেওয়া সেই বিখ্যাত অরিজিনাল ব্ল্যাক প্যাক! <b>Samyang Buldak Hot Chicken Flavor Ramen</b> চটপটা, ঝাল এবং চমৎকার চিকেন ফ্লেভারের এক দারুণ মিশ্রণ। যারা কোরিয়ান রামেনের আসল স্বাদ ও পারফেক্ট ঝালের অভিজ্ঞতা পেতে চান, তাদের জন্য এটি একটি মাস্ট-হ্যাভ (Must-have) খাবার।</p>
\\n
\\n<h3>🌶️ কেন এটি স্পেশাল?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>অরিজিনাল হট চিকেন ফ্লেভার:</b> ঝাল আর সুস্বাদু কোরিয়ান বার্বিকিউ চিকেন ফ্লেভারের এক অসাধারণ কম্বিনেশন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি, যা মুসলিম কনজিউমারদের জন্য একদম নিরাপদ।</p>
\\n</li>
\\n 	<li>
\\n<p><b>প্রিমিয়াম কোরিয়ান নুডুলস:</b> সাধারণ নুডুলসের চেয়ে বেশ মোটা, সফট এবং চিউই (Chewy) টেক্সচারের, যা প্রতিটি বাইটকে করে তোলে আকর্ষণীয়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সহজ ও ঝটপট:</b> মাত্র ৫ মিনিটে ঘরে বসেই পেয়ে যান রেস্তোরাঁ স্টাইলের কোরিয়ান রামেন।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট ডিটেইলস:</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য</strong></td>
\\n<td><strong>বিবরণ</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Samyang (সাউথ কোরিয়া)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ফ্লেভার</b></span></td>
\\n<td><span>অরিজিনাল হট চিকেন (Original Hot Chicken)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ওজন</b></span></td>
\\n<td><span>১৪০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal Certified)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>🍳 রান্নার নিয়ম (Cooking Instructions):</h3>
\\n<p>১. পাত্রে ৬০০ মিলি পানি ফুটিয়ে নিয়ে তাতে নুডুলস দিন এবং ৫ মিনিট সেদ্ধ করুন।</p>
\\n<p>২. সেদ্ধ হয়ে গেলে পানি ছেঁকে নিন (পাত্রে সামান্য ৮ চামচ পরিমাণ পানি রেখে দিন)।</p>
\\n<p>৩. এবার প্যাকেটের ভেতরের বিশেষ লাল লিকুইড সসটি দিয়ে ৩০ সেকেন্ড হালকা আঁচে নেড়েচেড়ে ভালো করে মিশিয়ে নিন।</p>
\\n<p>৪. চুলা থেকে নামিয়ে ওপর থেকে প্যাকেটে থাকা তিল ও শুকনো সামুদ্রিক শৈবালের (Seaweed) ফ্লেকস ছড়িয়ে গরম গরম পরিবেশন করুন!</p>',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-Buldak-Hot-Chicken-Flavor-Ramenhalal-140g.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 39,
                'name' => 'Samyang Buldak Fire Chicken Cheese Ramen Halal 140g',
                'slug' => 'samyang-buldak-fire-chicken-cheese-ramen-halal-140g',
                'sku' => 'PA-0039',
                'category_slug' => 'ramen',
                'short_description' => '100% Halal Certified',
                'description' => '<h2>Samyang Buldak Fire Chicken Cheese Ramen (Halal) 140g – ঝাল ও চিজের এক পারফেক্ট কম্বিনেশন!</h2>
\\n<p>যারা সাময়্যাং-এর সিগনেচার ঝাল সসের সাথে চিজি (Cheesy) ফ্লেভার ভালোবাসেন, তাদের জন্য <b>Samyang Buldak Cheese Ramen</b> এক স্বর্গীয় স্বাদ! এটি অরিজিনাল হট চিকেন ফ্লেভারের তীব্র ঝালকে প্রিমিয়াম চিজ পাউডারের সাহায্যে কিছুটা মাইল্ড বা সহনীয় করে তোলে, যা আপনাকে দেয় এক ক্রিমি, চটপটা এবং দারুণ সুস্বাদু অভিজ্ঞতা।</p>
\\n
\\n<h3>🧀 কেন এই চিজ রামেনটি সবার এত প্রিয়?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>ক্রিমি ও স্পাইসি ব্লেন্ড:</b> ঝাল কোরিয়ান চিকেন সস এবং রিচ চিজ পাউডারের মিশ্রণ, যা মুখে দিলেই মিলিয়ে যায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> মুসলিম ক্রেতাদের জন্য সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং সার্টিফাইড।</p>
\\n</li>
\\n 	<li>
\\n<p><b>মোটা ও চিউই নুডুলস:</b> সাময়্যাং-এর ঐতিহ্যবাহী থিক ও সফট নুডুলস, যা সস এবং চিজকে দারুণভাবে শোষণ করে নেয়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>মাইল্ড স্পাইসি:</b> অরিজিনাল ব্ল্যাক প্যাকের চেয়ে এটি কিছুটা কম ঝাল, তাই যারা মাঝারি ঝাল পছন্দ করেন তাদের জন্য এটি বেস্ট চয়েস।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট ডিটেইলস:</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য</strong></td>
\\n<td><strong>বিবরণ</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Samyang (সাউথ কোরিয়া)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ফ্লেভার</b></span></td>
\\n<td><span>হট চিকেন ও চিজ (Hot Chicken &amp; Cheese)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ওজন</b></span></td>
\\n<td><span>১৪০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal Certified)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>🍳 রান্নার নিয়ম (Cooking Instructions):</h3>
\\n<p>১. ৬০০ মিলি পানি ফুটিয়ে নিয়ে তাতে নুডুলস দিন এবং ৫ মিনিট সেদ্ধ করুন।</p>
\\n<p>২. সেদ্ধ হয়ে গেলে পানি ছেঁকে নিন (পাত্রে সামান্য ৮ চামচ পরিমাণ পানি রেখে দেবেন)।</p>
\\n<p>৩. এবার প্যাকেটের ভেতরের লাল লিকুইড স্পাইসি সসটি দিয়ে ৩০ সেকেন্ড ভালোমতো নেড়েচেড়ে মিশিয়ে নিন।</p>
\\n<p>৪. চুলা থেকে নামিয়ে ওপর থেকে প্যাকেটে থাকা চিজ পাউডার ফ্লেকসটি ছড়িয়ে দিন এবং গরম গরম চিজি রামেন উপভোগ করুন!</p>',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-Hot-Chicken-Cheese-Ramen.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 40,
                'name' => 'Samyang Buldak Fire Chicken Carbonara Ramen Halal 130g',
                'slug' => 'samyang-buldak-fire-chicken-carbonara-ramen-halal-130g',
                'sku' => 'PA-0040',
                'category_slug' => 'ramen',
                'short_description' => '100% Halal Certified',
                'description' => '<h2>Samyang Buldak Fire Chicken Carbonara Ramen (Halal) 130g – ক্রিমি ইতালিয়ান কার্বোনারা ও কোরিয়ান ঝালের যুগলবন্দী!</h2>
\\n<p>সাময়্যাং সিরিজের সবচেয়ে জনপ্রিয় এবং বেস্ট-সেলার ফ্লেভারগুলোর একটি হলো <b>Samyang Buldak Carbonara Ramen</b>। ইতালিয়ান কার্বোনারা ক্রাফটের রিচ, ক্রিমি ফ্লেভার এবং বুলদাকের সিগনেচার হট চিকেন সসের মিশ্রণে তৈরি এই নুডুলস। এটি তীব্র ঝালকে চিজ ও মিল্ক পাউডারের সাহায্যে অত্যন্ত স্মুথ ও সহনীয় করে তোলে, যা মুখে দেয় এক স্বর্গীয় স্বাদ।</p>
\\n
\\n<h3>🌸 কেন এটি বুলদাক সিরিজের সবচেয়ে জনপ্রিয় ফ্লেভার?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>ক্রিমি ও মাইল্ড স্পাইসি:</b> রিচ ক্রিম, চিজ এবং বাটারের মিশ্রণ এর ঝালকে অনেকটাই কমিয়ে আনে। তাই যারা অতিরিক্ত ঝাল খেতে পারেন না, তারাও এটি সহজে উপভোগ করতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> মুসলিম কনজিউমারদের জন্য এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং সার্টিফাইড।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ফ্ল্যাট ও চিউই টেক্সচার:</b> কার্বোনারা রামেনের নুডুলসগুলো অন্য প্যাকের চেয়ে কিছুটা চ্যাপ্টা বা ফ্ল্যাট (Flat) ডিজাইনের হয়, যা সস ও ক্রিমকে খুব সুন্দরভাবে ধরে রাখে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>অনন্য স্বাদ:</b> এর ক্রিমি, সুইট এবং স্পাইসি ফিউশন টেস্ট একবার খেলে বারবার খেতে ইচ্ছে করবে।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট ডিটেইলস:</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য</strong></td>
\\n<td><strong>বিবরণ</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Samyang (সাউথ কোরিয়া)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ফ্লেভার</b></span></td>
\\n<td><span>হট চিকেন কার্বোনারা (Hot Chicken Carbonara)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ওজন</b></span></td>
\\n<td><span>১৩০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal Certified)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>🍳 রান্নার নিয়ম (Cooking Instructions):</h3>
\\n<p>১. পাত্রে ৬০০ মিলি পানি ফুটিয়ে নিয়ে তাতে নুডুলস দিন এবং ৫ মিনিট সেদ্ধ করুন।</p>
\\n<p>২. সেদ্ধ হয়ে গেলে পানি ছেঁকে নিন (পাত্রে সামান্য ৮ চামচ পরিমাণ পানি রেখে দেবেন, যা ক্রিমটিকে মসৃণ করতে সাহায্য করবে)।</p>
\\n<p>৩. এবার প্যাকেটের ভেতরের লাল লিকুইড সস এবং কার্বোনারা হোয়াইট পাউডারটি একসাথে দিয়ে ৩০ সেকেন্ড ভালোমতো নেড়েচেড়ে মিশিয়ে নিন।</p>
\\n<p>৪. ব্যস, তৈরি হয়ে গেল আপনার অত্যন্ত ক্রিমি এবং সুস্বাদু কার্বো রামেন! গরম গরম পরিবেশন করুন।</p>',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-Buldak-Fire-Chicken-Carbonara-Ramen-Halal-130g.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 41,
                'name' => 'NONGSHIM Shin Ramen 120G (HALAL)',
                'slug' => 'nongshim-shin-ramen-120g-halal',
                'sku' => 'PA-0041',
                'category_slug' => 'ramen',
                'short_description' => '100% Halal Certified',
                'description' => '<p>এখানে বিশ্ববিখ্যাত <b>Nongshim Shin Ramyun / Ramen (Halal) 120g</b> (যা কোরিয়ার এক নম্বর এবং সবচেয়ে জনপ্রিয় ট্র্যাডিশনাল রামেন)-এর জন্য একটি আকর্ষণীয় ওয়েবসাইট প্রোডাক্ট ডেসক্রিপশন দেওয়া হলো:</p>
\\n
\\n<h2>Nongshim Shin Ramen (Halal) 120g – কোরিয়ার এক নম্বর ট্র্যাডিশনাল রামেনের আসল স্বাদ!</h2>
\\n<p>আপনি কি আসল কোরিয়ান সুপি রামেনের স্বাদ খুঁজছেন? তাহলে <b>Nongshim Shin Ramen</b> আপনার জন্য একদম পারফেক্ট চয়েস। এটি বিশ্বজুড়ে সবচেয়ে জনপ্রিয় এবং বিক্রিত কোরিয়ান নুডুলস। রিচ গরুর মাংসের ফ্লেভার (কৃত্রিম), প্রিমিয়াম মশলা এবং চমৎকার ঝালের কম্বিনেশনে তৈরি এর সিগনেচার স্পাইসি ব্রথ বা স্যুপ আপনার মন জয় করতে বাধ্য।</p>
\\n
\\n<h3>🍜 কেন এটি বিশ্বজুড়ে এত জনপ্রিয়?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>সিগনেচার স্পাইসি ব্রথ:</b> এর স্যুপটি ডিপ, রিচ এবং পারফেক্টলি স্পাইসি, যা ট্র্যাডিশনাল কোরিয়ান স্টাইলের আসল স্বাদ দেয়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং সার্টিফাইড, তাই মুসলিম কনজিউমাররা নিশ্চিন্তে উপভোগ করতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>প্রিমিয়াম কোরিয়ান টেক্সচার:</b> নুডুলসগুলো বেশ মোটা, নরম এবং চিউই (Chewy), যা স্যুপের স্বাদকে দারুণভাবে ধরে রাখে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>রিচ ভেজিটেবল ফ্লেক্স:</b> প্যাকেটের সাথেই পাচ্ছেন শুকনো মাশরুম, গাজর এবং গ্রিন অনিয়ন (পেঁয়াজ পাতা) ফ্লেক্স, যা রামেনের স্বাদ ও সৌন্দর্য বাড়িয়ে দেয়।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট ডিটেইলস:</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য</strong></td>
\\n<td><strong>বিবরণ</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Nongshim (সাউথ কোরিয়া)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ফ্লেভার</b></span></td>
\\n<td><span>সিগনেচার স্পাইসি শিন (Shin Spicy)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ওজন</b></span></td>
\\n<td><span>১২০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal Certified)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>টাইপ</b></span></td>
\\n<td><span>স্যুপ বা ব্রথ রামেন (Soup Ramen)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>🍳 রান্নার নিয়ম (Cooking Instructions):</h3>
\\n<p>১. একটি পাত্রে ৫৫০ মিলি (প্রায় আড়াই কাপ) পানি ফুটিয়ে নিন।</p>
\\n<p>২. পানি ফুটে উঠলে তাতে নুডুলস, ভেজিটেবল মিক্স এবং স্যুপ পাউডার একসাথে দিয়ে দিন।</p>
\\n<p>৩. মাঝারি আঁচে ৪ থেকে ৫ মিনিট সেদ্ধ করুন, যাতে নুডুলস নরম হয় এবং স্যুপটি ঘন হয়ে আসে।</p>
\\n<p>৪. চুলা থেকে নামিয়ে বাটিতে ঢেলে নিন। আরও দারুণ স্বাদের জন্য ওপর থেকে ডিম, পেঁয়াজ পাতা বা আপনার পছন্দের সবজি যোগ করে গরম গরম পরিবেশন করুন!</p>',
                'regular_price' => 180,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Nongshim-Shin-Ramyun.jpg',
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'nongshim',
            ],
            [
                'source_id' => 43,
                'name' => 'Samyang Buldak Fire Chicken Jjajang Ramen Halal 140g',
                'slug' => 'samyang-buldak-fire-chicken-jjajang-ramen-halal-140g',
                'sku' => 'PA-0043',
                'category_slug' => 'ramen',
                'short_description' => '100% Halal Certified',
                'description' => '<h2>Samyang Buldak Fire Chicken Jjajang Ramen (Halal) 140g – কোরিয়ান ব্ল্যাক বিন ও বুলদাক ঝালের অনন্য ফিউশন!</h2>
\\n<p>কোরিয়ার ঐতিহ্যবাহী ব্ল্যাক বিন সস (Jjajangmyeon) এবং বুলদাকের সিগনেচার ফায়ার চিকেন সসের এক অসাধারণ জুঁটি নিয়ে এলো <b>Samyang Buldak Jjajang Ramen</b>। ব্ল্যাক বিন সসের হালকা মিষ্টি ও নোনতা স্বাদের সাথে ফায়ার চিকেনের ঝাল মিলে এটি আপনাকে দেবে সম্পূর্ণ ভিন্নধর্মী এবং দারুণ চটপটা এক স্বাদ। যারা অতিরিক্ত তীব্র ঝাল ছাড়াই কোরিয়ান ট্র্যাডিশনাল ফ্লেভারের অভিজ্ঞতা নিতে চান, তাদের জন্য এটি পারফেক্ট চয়েস।</p>
\\n
\\n<h3>🟢 কেন এই জাজ্যাং রামেনটি ট্রাই করবেন?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>ইউনিক ফ্লেভার কম্বিনেশন:</b> ট্র্যাডিশনাল সুইট-স্যাভরি ব্ল্যাক বিন সস এবং স্পাইসি বুলদাক সসের পারফেক্ট ব্যালেন্স।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> মুসলিম কনজিউমারদের জন্য এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং সার্টিফাইড।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সহনীয় ঝাল:</b> অরিজিনাল ব্ল্যাক প্যাকের চেয়ে এটি কিছুটা কম ঝাল ও সুস্বাদু, তাই মাঝারি ঝাল পছন্দকারীদের জন্য এটি চমৎকার।</p>
\\n</li>
\\n 	<li>
\\n<p><b>রিচ টেক্সচার:</b> সাময়্যাং-এর সিগনেচার থিক ও চিউই (Chewy) নুডুলস, যা ঘন জাজ্যাং সসটিকে খুব ভালোভাবে মেখে নেয়।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট ডিটেইলস:</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য</strong></td>
\\n<td><strong>বিবরণ</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Samyang (সাউথ কোরিয়া)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ফ্লেভার</b></span></td>
\\n<td><span>হট চিকেন ও জাজ্যাং/ব্ল্যাক বিন (Hot Chicken &amp; Jjajang)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ওজন</b></span></td>
\\n<td><span>১৪০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal Certified)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>🍳 রান্নার নিয়ম (Cooking Instructions):</h3>
\\n<p>১. পাত্রে ৬০০ মিলি পানি ফুটিয়ে নিয়ে তাতে নুডুলস এবং শুকনো ভেজিটেবল ফ্লেকসগুলো দিয়ে ৫ মিনিট সেদ্ধ করুন।</p>
\\n<p>২. সেদ্ধ হয়ে গেলে পানি ছেঁকে নিন (পাত্রে সামান্য ৮ চামচ পরিমাণ পানি রেখে দেবেন, যা সসটিকে নুডুলসের সাথে মিশতে সাহায্য করবে)।</p>
\\n<p>৩. এবার প্যাকেটের ভেতরের লিকুইড জাজ্যাং স্পাইসি সসটি দিয়ে ৩০ সেকেন্ড হালকা আঁচে ভালোমতো নেড়েচেড়ে মিশিয়ে নিন।</p>
\\n<p>৪. চুলা থেকে নামিয়ে বাটিতে ঢেলে গরম গরম পরিবেশন করুন!</p>',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-Buldak-Jjajang-Hot-Chicken-Ramen.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 45,
                'name' => 'Samyang Buldak Cream Carbonara Flavour Ramen Halal 140g',
                'slug' => 'samyang-buldak-cream-carbonara-flavour-ramen-halal-140g',
                'sku' => 'PA-0045',
                'category_slug' => 'ramen',
                'short_description' => '100% Halal Certified',
                'description' => '<h2>Samyang Buldak Cream Carbonara Flavour Ramen (Halal) 140g – আরও ক্রিমি, আরও স্মুথ এবং পারফেক্টলি স্পাইসি!</h2>
\\n<p>আপনি কি কোরিয়ান রামেনের স্বাদ নিতে চান কিন্তু অতিরিক্ত ঝাল ভয় পান? তাহলে <b>Samyang Buldak Cream Carbonara Ramen</b> আপনার জন্য একদম পারফেক্ট! এটি সাধারণ কার্বোনারা প্যাকের চেয়েও দ্বিগুণ ক্রিমি এবং সফট। রিচ ক্রিম, মিল্ক এবং এক্সট্রা চিজের এক দারুণ ব্লেন্ড বুলদাকের সিগনেচার ফায়ার চিকেন সসের তীব্র ঝালকে একদম সহনীয় ও সুস্বাদু করে তোলে, যা মুখে দেয় এক রাজকীয় স্বাদ।</p>
\\n
\\n<h3>💗 কেন এই ক্রিম কার্বো রামেনটি এত স্পেশাল?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>এক্সট্রা ক্রিমি ও কম ঝাল:</b> এটি বুলদাক সিরিজের অন্যতম মাইল্ড বা কম ঝাল নুডুলস। অতিরিক্ত ক্রিম ও চিজ পাউডার থাকায় এটি অত্যন্ত স্মুথ, তাই শিশু থেকে শুরু করে যারা ঝাল কম খান তারাও এটি সহজে উপভোগ করতে পারবেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> মুসলিম কনজিউমারদের জন্য এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং কেএমএফ (KMF) দ্বারা সার্টিফাইড।</p>
\\n</li>
\\n 	<li>
\\n<p><b>প্রিমিয়াম চিউই নুডুলস:</b> সাময়্যাং-এর সিগনেচার থিক ও চিউই (Chewy) টেক্সচারের নুডুলস, যা ঘন ক্রিমি সসটিকে খুব সুন্দরভাবে প্রতিটি বাইটে ধরে রাখে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>পারফেক্ট কমফোর্ট ফুড:</b> এর চিজি, সুইট এবং লাইট স্পাইসি ফিউশন টেস্ট আপনার অল-টাইম ফেভারিট কমফোর্ট ফুড হতে বাধ্য।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট ডিটেইলস:</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য</strong></td>
\\n<td><strong>বিবরণ</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Samyang (সাউথ কোরিয়া)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ফ্লেভার</b></span></td>
\\n<td><span>ক্রিম কার্বোনারা (Cream Carbonara)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ওজন</b></span></td>
\\n<td><span>১৪০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal Certified)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>🍳 রান্নার নিয়ম (Cooking Instructions):</h3>
\\n<p>১. পাত্রে ৬০০ মিলি পানি ফুটিয়ে নিয়ে তাতে নুডুলস দিন এবং ৫ মিনিট সেদ্ধ করুন।</p>
\\n<p>২. সেদ্ধ হয়ে গেলে পানি ছেঁকে নিন (পাত্রে সামান্য ৮ চামচ পরিমাণ পানি রেখে দেবেন, যা ক্রিমটিকে মসৃণ করতে সাহায্য করবে)।</p>
\\n<p>৩. এবার প্যাকেটের ভেতরের লাল লিকুইড সস এবং স্পেশাল ক্রিম কার্বোনারা পাউডারটি একসাথে দিয়ে ৩০ সেকেন্ড ভালোমতো নেড়েচেড়ে মিশিয়ে নিন।</p>
\\n<p>৪. ব্যস, তৈরি হয়ে গেল আপনার জিভে জল আনা এক্সট্রা ক্রিমি ক্রিম কার্বো রামেন! গরম গরম পরিবেশন করুন।</p>',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-Carbonara-Ramen.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 112,
                'name' => 'ablue Curble Chair Correct Posture Wider',
                'slug' => 'ablue-curble-chair-correct-posture-wider',
                'sku' => 'PA-0112',
                'category_slug' => 'lifestyle',
                'short_description' => '[Ablue] Curble Chair Wider হলো একটি প্রিমিয়াম এরগোনোমিক পোস্টার কারেক্টর (Ergonomic Posture Corrector), যা লিভারেজ ইফেক্ট (Leverage Effect)-এর মাধ্যমে আপনার মেরুদণ্ডকে প্রাকৃতিকভাবে সোজা ও সচল রাখে। সাধারণ মডেলের চেয়ে ৫ সেমি চওড়া এই সিটটি আপনার পেলভি',
                'description' => '<h3>🌟 আপনার মেরুদণ্ডকে দিন সঠিক যত্ন ও আরাম!</h3>
\\n<p id="p-rc_a1a0b6862509a1d5-29">ভুল ভঙ্গিতে বসার কারণে আমাদের মেরুদণ্ডে অতিরিক্ত চাপ পড়ে, যা পরবর্তীতে দীর্ঘস্থায়ী কোমর ও পিঠের ব্যথার কারণ হয়ে দাঁড়ায়। <b><span class="citation-41">Ablue Curble Chair Wider</span></b><span class="citation-41 citation-end-41"> বিশেষভাবে ডিজাইন করা হয়েছে এই সমস্যার স্থায়ী সমাধানের জন্য।</span> আপনি যখনই এই চেয়ারে বসবেন, এটি স্বয়ংক্রিয়ভাবে আপনার ওজনকে ব্যবহার করে আপনার ব্যাকরেস্টকে সামনের দিকে পুশ করবে, যা আপনাকে একদম সোজা (S-shape Curve) হয়ে বসতে বাধ্য করবে।</p>
\\n
\\n<h3>💎 মূল বৈশিষ্ট্যসমূহ (Key Features):</h3>
\\n<ul>
\\n 	<li>
\\n<p id="p-rc_a1a0b6862509a1d5-30"><b><span class="citation-40">লিভারেজ প্রিন্সিপল (Leverage Effect):</span></b><span class="citation-40 citation-end-40"> এটি বিজ্ঞানসম্মত লিভার মেকানিজমে কাজ করে।</span> <span class="citation-39 citation-end-39">আপনার শরীরের ওজন সিটে পড়ার সাথে সাথেই এর ব্যাকরেস্ট আপনার কোমরকে প্রাকৃতিক উপায়ে সোজা ও সাপোর্ট করে।</span></p>
\\n</li>
\\n 	<li>
\\n<p id="p-rc_a1a0b6862509a1d5-31"><b><span class="citation-38">৫ সেমি চওড়া ডিজাইন (Wider Design):</span></b><span class="citation-38 citation-end-38"> কার্বেল চেয়ারের রেগুলার (Comfy) মডেলের চেয়ে এটি ৫ সেমি বেশি চওড়া এবং দীর্ঘ।</span> ফলে এটি বড় গড়নের প্রাপ্তবয়স্কদের জন্য অত্যন্ত আরামদায়ক এবং এতে ল্যাম্বার ও আর্মপিটের নিচের অংশ চমৎকার সাপোর্ট পায়।</p>
\\n</li>
\\n 	<li>
\\n<p id="p-rc_a1a0b6862509a1d5-32"><b><span class="citation-37">উচ্চ স্থিতিস্থাপকতা (High-Elastic Material):</span></b><span class="citation-37 citation-end-37"> এর ফ্রেমটি অত্যন্ত নমনীয় ও শক্তিশালী নাইলন রেজিন (Nylon Resin) দিয়ে তৈরি, যা ১৮০ কেজি পর্যন্ত ওজন ও চাপ অনায়াসে সহ্য করতে পারে।</span></p>
\\n</li>
\\n 	<li>
\\n<p id="p-rc_a1a0b6862509a1d5-33"><b><span class="citation-36">এয়ার ভেন্টিলেশন সিস্টেম (Air Pathway):</span></b><span class="citation-36 citation-end-36"> সিট এবং ব্যাকরেস্টে বিশেষ এয়ার হোল থাকার কারণে দীর্ঘক্ষণ বসে থাকলেও শরীর ঘেমে অস্বস্তি তৈরি হয় না।</span></p>
\\n</li>
\\n 	<li>
\\n<p><b>হালকা ও সহজে বহনযোগ্য:</b> মাত্র ৮৮০ গ্রাম ওজনের এই সিটটি আপনি যেকোনো সাধারণ অফিস চেয়ার, সোফা, ডাইনিং চেয়ার কিংবা মেঝেতেও ব্যবহার করতে পারবেন।</p>
\\n</li>
\\n</ul>
\\n<h3>🎯 এটি কাদের জন্য সবচেয়ে বেশি উপকারী?</h3>
\\n<ul>
\\n 	<li>
\\n<p>যারা অফিসে বা ডেস্কে প্রতিদিন ৮-১০ ঘণ্টা টানা বসে কাজ করেন।</p>
\\n</li>
\\n 	<li>
\\n<p>যেসকল শিক্ষার্থী দীর্ঘ সময় টেবিলে পড়াশোনা করে।</p>
\\n</li>
\\n 	<li>
\\n<p>যারা পিঠ, ঘাড়, কোমর বা মেরুদণ্ডের ব্যথায় (Back Pain &amp; Disc stiffness) ভুগছেন।</p>
\\n</li>
\\n 	<li>
\\n<p>যারা যেকোনো চেয়ারে বসেই পারফেক্ট পোশ্চার ধরে রাখতে চান।</p>
\\n</li>
\\n</ul>
\\n<h3>🛠️ কিভাবে ব্যবহার করবেন (How to Use):</h3>
\\n<ol start="1">
\\n 	<li>
\\n<p id="p-rc_a1a0b6862509a1d5-34"><span class="citation-35 citation-end-35">আপনার নিয়মিত বসার চেয়ারের ব্যাকরেস্ট থেকে ১০-১৫ সেমি দূরত্ব রেখে কার্বেল চেয়ারটি রাখুন।</span></p>
\\n</li>
\\n 	<li>
\\n<p id="p-rc_a1a0b6862509a1d5-35"><span class="citation-34 citation-end-34">দুই হাত দিয়ে কার্বেল চেয়ারটি ধরে একদম শেষ প্রান্ত পর্যন্ত গভীরভাবে বসুন।</span></p>
\\n</li>
\\n 	<li>
\\n<p id="p-rc_a1a0b6862509a1d5-36"><span class="citation-33 citation-end-33">আপনার শরীরের ওজনেই চেয়ারটির ব্যাকরেস্ট নিজে থেকেই আপনার কোমরকে সামনের দিকে পুশ করবে এবং আপনাকে সোজা করে বসিয়ে দেবে।</span></p>
\\n</li>
\\n</ol>
\\n<blockquote>
\\n<p><b>বিশেষ দ্রষ্টব্য:</b> প্রথম ১-২ সপ্তাহ সোজা হয়ে বসার অভ্যাসের কারণে কিছুটা নতুন অনুভূতি বা সামান্য অস্বস্তি হতে পারে। প্রতিদিন ৩০-৬০ মিনিট থেকে শুরু করে ধীরে ধীরে এর ব্যবহার সময় বাড়ান।</p>
\\n</blockquote>',
                'regular_price' => 2400,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/ablue-Curble-Chair-Correct-Posture-Wider.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'variations' => [
                ],
                'brand_slug' => 'ablue',
            ],
            [
                'source_id' => 114,
                'name' => '3W Clinic Cleansing Foam-100ml',
                'slug' => '3w-clinic-cleansing-foam-100ml',
                'sku' => 'PA-0114',
                'category_slug' => 'face-wash-cleanser',
                'short_description' => 'কোরিয়ান স্কিনকেয়ার ব্র্যান্ডের 3W Clinic Cleansing Foam (100ml) আপনার ত্বকের অতিরিক্ত তেল, মেকআপের অবশিষ্টাংশ এবং ধুলোবালি দূর করে ত্বককে করে তোলে একদম সতেজ ও প্রাণবন্ত। এর মৃদু ফর্মুলা ত্বককে অতিরিক্ত শুষ্ক না করে প্রাকৃতিক আর্দ্রতা বজায় রাখে। প্রত',
                'description' => '<h3>✨ কোরিয়ান স্কিনকেয়ারের ছোঁয়ায় ত্বক হোক নিখুঁত ও উজ্জ্বল!</h3>
\\n<p>সারাদিনের ধুলোবালি, দূষণ এবং মেকআপের কারণে আমাদের ত্বক ম্লান ও প্রাণহীন হয়ে পড়ে। <b>3W Clinic Cleansing Foam</b> একটি রিচ এবং ক্রিমি ফেসওয়াশ, যা পানির সংস্পর্শে এসে ঘন ফেনা (Foam) তৈরি করে। এই ফেনা ত্বকের রোমকূপের (Pores) গভীর থেকে ময়লা ও অতিরিক্ত সেবাম (Sebum) টেনে বের করে আনে, ফলে ত্বক দেখায় দাগহীন, পরিষ্কার এবং উজ্জ্বল।</p>
\\n
\\n<h3>💎 মূল বৈশিষ্ট্য ও উপকারিতাসমূহ (Key Benefits):</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>গভীরভাবে পরিষ্কার করে (Deep Cleansing):</b> ত্বকের উপরিভাগের ময়লা দূর করার পাশাপাশি এটি রোমকূপের ভেতরে জমে থাকা সেবাম ও ব্ল্যাকহেডস দূর করতে সাহায্য করে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>আর্দ্রতা বজায় রাখে (Moisture Retention):</b> সাধারণত ফেসওয়াশ ব্যবহারের পর ত্বক টানটান বা শুষ্ক হয়ে যায়, কিন্তু এর বিশেষ হাইড্রেটিং ফর্মুলা ত্বকের স্বাভাবিক আর্দ্রতা (Moisture) ধরে রাখে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>মৃদু ও নিরাপদ (Gentle Formula):</b> এতে থাকা প্রাকৃতিক উপাদান ত্বককে কোনো প্রকার ইরিটেশন বা অ্যালার্জি ছাড়াই কোমলভাবে পরিষ্কার করে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সব ধরনের ত্বকের জন্য উপযোগী:</b> ছেলে-মেয়ে উভয়ই এটি ব্যবহার করতে পারবেন এবং এটি সব ধরনের স্কিন টাইপের (All Skin Types) সাথে চমৎকারভাবে মানিয়ে যায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সহজে বহনযোগ্য (Travel-Friendly):</b> ১০০ মিলি-র কমপ্যাক্ট টিউব সাইজ হওয়ায় এটি ব্যাগে করে যেকোনো জায়গায় সহজে বহন করা যায়।</p>
\\n</li>
\\n</ul>
\\n<h3>🌿 প্রধান উপাদানসমূহ (Key Ingredients):</h3>
\\n<p>এতে রয়েছে বিভিন্ন প্রাকৃতিক উদ্ভিদের নির্যাস এবং পুষ্টিকর উপাদান (যেমন- ভ্যারিয়েন্ট অনুযায়ী কোলাজেন, গ্রিন টি, চারকোল বা রাইস এক্সট্র্যাক্ট), যা ত্বককে পুষ্টি জোগায় এবং ত্বকের টেক্সচার উন্নত করে।</p>
\\n
\\n<h3>🛠️ ব্যবহার বিধি (How to Use):</h3>
\\n<ol start="1">
\\n 	<li>
\\n<p>প্রথমে পরিষ্কার পানি দিয়ে মুখমণ্ডল ভালো করে ভিজিয়ে নিন।</p>
\\n</li>
\\n 	<li>
\\n<p>হাতের তালুতে সামান্য পরিমাণ (মটরদানা সাইজ) ক্লিনজিং ফোম নিন এবং সামান্য পানি মিশিয়ে ঘন ফেনা তৈরি করুন।</p>
\\n</li>
\\n 	<li>
\\n<p>এবার পুরো মুখে সার্কুলার মোশনে (বৃত্তাকারে) আলতোভাবে ম্যাসাজ করুন (চোখের চারপাশ এড়িয়ে চলুন)।</p>
\\n</li>
\\n 	<li>
\\n<p>১-২ মিনিট ম্যাসাজ করার পর হালকা কুসুম গরম পানি বা সাধারণ পানি দিয়ে মুখ ভালো করে ধুয়ে ফেলুন।</p>
\\n</li>
\\n</ol>
\\n<blockquote>
\\n<p><b>টিপস:</b> ভালো ফলাফলের জন্য প্রতিদিন সকালে এবং রাতে ঘুমানোর আগে এটি ব্যবহার করুন। ফেসওয়াশ ব্যবহারের পর আপনার পছন্দের টোনার বা ময়েশ্চারাইজার ব্যবহার করতে ভুলবেন না।</p>
\\n</blockquote>',
                'regular_price' => 500,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'variable',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/3W-Clinic-Cleansing-Foam-100ml.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'variations' => [
                    [
                        'name' => 'Brown Rice',
                        'sku' => 'PA-114-01',
                        'regular_price' => 500,
                        'sale_price' => null,
                        'stock_quantity' => 100,
                        'weight' => null,
                        'unit' => 'pcs',
                        'image_urls' => [
                            'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/imgi_44_4836de05cbf435e52138fbba1b60ce4f.jpg',
                        ],
                    ],
                    [
                        'name' => 'Charcoal',
                        'sku' => 'PA-114-02',
                        'regular_price' => 500,
                        'sale_price' => null,
                        'stock_quantity' => 100,
                        'weight' => null,
                        'unit' => 'pcs',
                        'image_urls' => [
                            'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/imgi_60_512efcc6a4904e7d0e84317c9306d967.jpg',
                        ],
                    ],
                    [
                        'name' => 'Collagen',
                        'sku' => 'PA-114-03',
                        'regular_price' => 500,
                        'sale_price' => null,
                        'stock_quantity' => 100,
                        'weight' => null,
                        'unit' => 'pcs',
                        'image_urls' => [
                            'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/imgi_61_4d06fd9c8b6e16a703cf31846c89146e.jpg',
                        ],
                    ],
                    [
                        'name' => 'Green Tea',
                        'sku' => 'PA-114-04',
                        'regular_price' => 500,
                        'sale_price' => null,
                        'stock_quantity' => 100,
                        'weight' => null,
                        'unit' => 'pcs',
                        'image_urls' => [
                            'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/imgi_67_5717e54be020d3ed15abf0258f310274.jpg',
                        ],
                    ],
                    [
                        'name' => 'Rose Water',
                        'sku' => 'PA-114-05',
                        'regular_price' => 500,
                        'sale_price' => null,
                        'stock_quantity' => 100,
                        'weight' => null,
                        'unit' => 'pcs',
                        'image_urls' => [
                            'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/imgi_55_87f15c084185798af69ffbbf6c47d4db.jpg',
                        ],
                    ],
                ],
                'brand_slug' => '3w-clinic',
            ],
            [
                'source_id' => 144,
                'name' => 'CJ Haechandle Delicious Gochujang (200g)',
                'slug' => 'cj-haechandle-delicious-gochujang-200g',
                'sku' => 'PA-0144',
                'category_slug' => 'gochujang',
                'short_description' => '\\n100% Halal Certified
\\n
\\n',
                'description' => '<p><i>পণ্যের বিস্তারিত তথ্যের জন্য নিচের অংশটি ব্যবহার করুন।</i></p>
\\n<p>Bring the true essence of Korean cuisine straight to your dining table! সিজে হ্যাচ্যান্ডেল ডেলিশিয়াস গোচুজাং-এর মাধ্যমে কোরিয়ান খাবারের আসল স্বাদ নিয়ে আসুন আপনার খাবার টেবিলে। ১৯৭৩ সাল থেকে কোরিয়ার শীর্ষস্থানীয় ঐতিহ্যবাহী ব্র্যান্ড \'হ্যাচ্যান্ডেল\' তাদের দীর্ঘ অভিজ্ঞতা ও ফারমেন্টেশন প্রযুক্তির মাধ্যমে তৈরি করেছে এই আকর্ষণীয় লাল মরিচের পেস্ট, যা প্রায় প্রতিটি আইকনিক কোরিয়ান খাবারের প্রধান ভিত্তি।</p>
\\n<p>উচ্চমানের উপাদান এবং প্রাকৃতিক ফারমেন্টেশন প্রক্রিয়ায় তৈরি এই গোচুজাং অত্যন্ত মসৃণ ও ঘন। এটি মরিচের ঝাল স্বাদের সাথে মৃদু মিষ্টি ও নোনতা স্বাদের এক দারুণ ভারসাম্য তৈরি করে, যা খাবারে আসল কোরিয়ান "উমামি" স্বাদ ফুটিয়ে তোলে।</p>
\\n<p><b>প্রধান বৈশিষ্ট্যসমূহ:</b></p>
\\n
\\n<ul>
\\n 	<li>
\\n<p><b>অফিসিয়াল হালাল সার্টিফাইড:</b> ইন্দোনেশিয়ার স্বনামধন্য MUI দ্বারা এটি হালাল সার্টিফাইড, তাই মুসলিম কোরিয়ান ফুড লাভাররা নিশ্চিন্তে এটি উপভোগ করতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>খাঁটি কোরিয়ান ঐতিহ্য:</b> বিশেষভাবে বাছাই করা লাল মরিচের গুঁড়ো, ফারমেন্টেড সয়াবিন এবং চালের সমন্বয়ে তৈরি আসল কোরিয়ান রেসিপি।</p>
\\n</li>
\\n 	<li>
\\n<p><b>বহুমুখী ব্যবহার:</b> বিবিলবাপ (Bibimbap), তিলবোকি (Tteokbokki), বুলগোগি (Bulgogi), কোরিয়ান ফ্রাইড চিকেন গ্লেজ, স্পাইসি স্টু কিংবা যেকোনো স্টার-ফ্রাই রান্নায় পারফেক্ট।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সুবিধাজনক প্যাক:</b> ২০০ গ্রামের এই কমপ্যাক্ট ডিব্বাটি যারা নতুন কোরিয়ান রান্না ট্রাই করছেন বা পরিমিত ব্যবহার করতে চান, তাদের জন্য একদম আইডিয়াল।</p>
\\n</li>
\\n</ul>
\\n<p><b>স্পেসিফিকেশন ও উপাদান:</b></p>
\\n
\\n<ul>
\\n 	<li>
\\n<p><b>পণ্যের ধরন:</b> কোরিয়ান হট পেপার পেস্ট (Gochujang)</p>
\\n</li>
\\n 	<li>
\\n<p><b>ব্র্যান্ড:</b> CJ Haechandle</p>
\\n</li>
\\n 	<li>
\\n<p><b>নেট ওজন:</b> ২০০ গ্রাম</p>
\\n</li>
\\n 	<li>
\\n<p><b>ডায়েটারি পলিসি:</b> হালাল সার্টিফাইড, ভেগান-ফ্রেন্ডলি</p>
\\n</li>
\\n 	<li>
\\n<p><b>উপাদান:</b> কর্ন সিরাপ, লাল মরিচের পেস্ট/গুঁড়ো, পানি, গমের আটা, চালের গুঁড়ো, গম, লবণ, ডিস্টিলড অ্যালকোহল, সয়াবিন, সয়াবিন পাউডার, আঠালো চাল (Glutinous Rice), কোজি। <i>(এতে গম, বার্লি এবং সয়া রয়েছে)</i></p>
\\n</li>
\\n</ul>
\\n<p><b>ব্যবহার বিধি:</b></p>
\\n
\\n<ul>
\\n 	<li>
\\n<p><b>কোরিয়ান ডিশে:</b> বিবিলবাপ-এর ভাতে সরাসরি মিশিয়ে কিংবা রাইস কেকের (Tteokbokki) সাথে ফুটিয়ে ঘন ঝাল সস তৈরি করতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ম্যারিনেশনে:</b> বার্বিকিউ বা চিকেন ফ্রাইয়ের জন্য এক চামচ গোচুজাং-এর সাথে সয়া সস, রসুন কুচি, তিলের তেল এবং সামান্য চিনি মিশিয়ে চমৎকার ম্যারিনেড তৈরি করুন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ডিপিং সস:</b> আপনার পছন্দমতো স্ন্যাক্স বা গ্রিল মাংসের স্বাদ বাড়াতে সামান্য ভিনেগার বা তিলের তেলের সাথে মিশিয়ে সস হিসেবে ডিপ করে খান।</p>
\\n</li>
\\n</ul>',
                'regular_price' => 380,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => 0.2,
                'unit' => 'kg',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/CJ-Haechandle-Delicious-Gochujang-Hot-Pepper-Paste-Halal-200g.png',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Gochujang-200g.png',
                ],
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'cj',
            ],
            [
                'source_id' => 147,
                'name' => 'Lotus Sushi Rice 1kg',
                'slug' => 'lotus-sushi-rice-1kg',
                'sku' => 'PA-0147',
                'category_slug' => 'rice',
                'short_description' => 'Premium Short-Grain Japonica Rice',
                'description' => '<p>জাপানি রান্নার ঐতিহ্য এবং সঠিক টেক্সচার নিয়ে আসুন আপনার রান্নাঘরে। <b>Lotus Sushi Rice 1kg</b> দিয়ে এখন ঘরেই খুব সহজে তৈরি করতে পারবেন প্রফেশনাল ও সুস্বাদু সুশি। এটি একটি প্রিমিয়াম শর্ট-গ্রেইন চাল, যা রান্নার পর প্রয়োজনীয় আর্দ্রতা ও আঠালো ভাব ধরে রাখে—যা একটি পারফেক্ট সুশি রোলের মূল রহস্য।</p>
\\n<p>এই চালের প্রতিটি দানা রান্নার পর তুলতুলে নরম হয় এবং সুশি ভিনেগারের (Sushi Vinegar) মিশ্রণটি খুব ভালোভাবে শোষণ করে নেয়। ফলে সুশিতে পাওয়া যায় একদম খাঁটি ও ঐতিহ্যবাহী জাপানি স্বাদ।</p>
\\n<p><b>প্রধান বৈশিষ্ট্যসমূহ:</b></p>
\\n
\\n<ul>
\\n 	<li>
\\n<p><b>পারফেক্ট স্টিকি টেক্সচার:</b> শর্ট-গ্রেইন চাল হওয়ায় এতে উচ্চমাত্রায় স্টার্চ থাকে, যা সুশি রোল করার সময় চালের দানাগুলোকে একসাথে ধরে রাখতে সাহায্য করে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>বহুমুখী ব্যবহার:</b> শুধুমাত্র সুশি রোল (Maki) বা নিগিরি (Nigiri) নয়, এটি দিয়ে জাপানিজ রাইস বোল (Donburi), ওনিগিরি (Onigiri) এবং বিভিন্ন কোরিয়ান ও এশিয়ান রাইস ডিশ চমৎকারভাবে রান্না করা যায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>উচ্চ গুণমান:</b> প্রতিটি দানা নিখুঁতভাবে প্রসেস ও প্যাক করা হয়েছে, যা রান্নার সময় চালের প্রাকৃতিক সুগন্ধ এবং স্বাদ বজায় রাখে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সাশ্রয়ী ১ কেজি প্যাক:</b> ১ কেজির এই প্যাকটি পারিবারিক গেট-টুগেদার, সুশি লাভার বা যারা প্রায়ই এশিয়ান রান্না করেন তাদের জন্য একদম আদর্শ।</p>
\\n</li>
\\n</ul>
\\n<p><b>স্পেসিফিকেশন:</b></p>
\\n
\\n<ul>
\\n 	<li>
\\n<p><b>পণ্যের নাম:</b> Lotus Sushi Rice</p>
\\n</li>
\\n 	<li>
\\n<p><b>পণ্যের ধরন:</b> সুশি রাইস (শর্ট-গ্রেইন)</p>
\\n</li>
\\n 	<li>
\\n<p><b>নেট ওজন:</b> ১ কেজি</p>
\\n</li>
\\n 	<li>
\\n<p><b>প্যাকেজিং:</b> প্রিমিয়াম ও হাইজেনিক প্যাক</p>
\\n</li>
\\n 	<li>
\\n<p><b>ডায়েটারি পলিসি:</b> ১০০% ন্যাচারাল, গ্লুটেন-ফ্রি (স্বাভাবিক চাল হিসেবে) এবং ভেগান-ফ্রেন্ডলি</p>
\\n</li>
\\n</ul>
\\n<p><b>রান্নার নিয়ম ও ব্যবহার বিধি:</b> ১. <b>ধোয়া:</b> চাল রান্নার আগে ঠান্ডা পানি দিয়ে ৩-৪ বার ভালো করে ধুয়ে নিন, যতক্ষণ না পানি পরিষ্কার দেখায়। এতে অতিরিক্ত স্টার্চ দূর হবে। ২. <b>ভিজিয়ে রাখা:</b> ধোয়ার পর চালটি ২০-৩০ মিনিট পানিতে ভিজিয়ে রাখুন। এতে চালের দানা সমানভাবে সেদ্ধ হয়। ৩. <b>পানি ও চালের অনুপাত:</b> ১ কাপ চালের জন্য ১.২ কাপ পানি ব্যবহার করুন। রাইস কুকার বা সসপ্যানে ঢেকে মাঝারি আঁচে রান্না করুন। ৪. <b>সুশি মিক্সিং:</b> ভাত রান্না শেষে একটি ছড়ানো পাত্রে নিয়ে হালকা গরম থাকা অবস্থায় সুশি ভিনেগার (ভিনেগার, চিনি ও লবণের মিশ্রণ) আলতো হাতে মিশিয়ে ফ্যানের বাতাসে ঠান্ডা করে নিন। এরপর আপনার পছন্দমতো সুশি তৈরি করুন!</p>',
                'regular_price' => 250,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => 1,
                'unit' => 'kg',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Lotus-Sushi-Rice-1kg.png',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Lotus-Sushi-Rice-1kg-raw-img.png',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'variations' => [
                ],
                'brand_slug' => 'lotus-rice',
            ],
            [
                'source_id' => 150,
                'name' => 'CJ Gochujang Hot Pepper Paste 500g (Halal)',
                'slug' => 'cj-gochujang-hot-pepper-paste-500g-halal',
                'sku' => 'PA-0150',
                'category_slug' => 'gochujang',
                'short_description' => '\\n100% Halal Certified
\\n
\\n',
                'description' => '<p>কোরিয়ান খাবারের খাঁটি ও ঐতিহ্যবাহী ফ্লেভার নিয়ে আসুন আপনার প্রতিদিনের রান্নায়। <b>CJ Haechandle Gochujang 500g</b> হলো কোরিয়ার লিডিং ব্র্যান্ড হ্যাচ্যান্ডেল-এর একটি প্রিমিয়াম ফারমেন্টেড হট পেপার পেস্ট। কয়েক দশকের ঐতিহ্য ও নিখুঁত ফারমেন্টেশন প্রযুক্তির মাধ্যমে তৈরি এই গোচুজাং কোরিয়ান রান্নার আসল প্রাণ, যা যেকোনো সাধারণ খাবারকে নিমেষেই অসাধারণ করে তোলে।</p>
\\n<p>উচ্চমানের লাল মরিচের গুঁড়ো, সয়াবিন এবং চালের মিশ্রণে তৈরি এই পেস্টটি অত্যন্ত ঘন, মসৃণ এবং ভেলভেটি টেক্সচারের। এর চিলি হিট বা ঝাল ফ্লেভারের পাশাপাশি একটি চমৎকার মিষ্টি ও নোনতা স্বাদ পাওয়া যায়, যা বিবিলবাপ থেকে শুরু করে কোরিয়ান ফ্রাইড চিকেন—সব তৈরিতেই অতুলনীয়।</p>
\\n<p><b>প্রধান বৈশিষ্ট্যসমূহ:</b></p>
\\n
\\n<ul>
\\n 	<li>
\\n<p><b>অফিসিয়াল হালাল সার্টিফাইড:</b> ইন্দোনেশিয়ার স্বনামধন্য MUI (Majelis Ulama Indonesia) দ্বারা সার্টিফাইড, যা সম্পূর্ণ ইসলামিক খাদ্যবিধি মেনে চলা নিশ্চিত করে। মুসলিম ফুড লাভাররা এটি নিশ্চিন্তে ব্যবহার করতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>রিচ ও ডিপ উমামি ফ্লেভার:</b> ১৯৭৩ সাল থেকে চলে আসা ঐতিহ্যবাহী কোরিয়ান রেসিপিতে তৈরি, যা খাবারে এনে দেয় একদম খাঁটি ও রেস্তোরাঁ স্টাইলের স্বাদ।</p>
\\n</li>
\\n 	<li>
\\n<p><b>বহুমুখী রান্নার উপাদান:</b> এটি বিবিলবাপ (Bibimbap), তিলবোকি (Tteokbokki), বুলগোগি (Bulgogi) ম্যারিনেশন, কোরিয়ান ফ্রাইড চিকেন সস, স্পাইসি জেগে (Stew) এবং যেকোনো স্টার-ফ্রাই ডিশের জন্য একটি আবশ্যক উপাদান।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সাশ্রয়ী ৫০০ গ্রাম প্যাক:</b> যারা নিয়মিত কোরিয়ান বা এশিয়ান ফিউশন রান্না করতে ভালোবাসেন, তাদের জন্য এই বড় ফ্যামিলি সাইজ প্যাকটি অত্যন্ত সাশ্রয়ী এবং দীর্ঘস্থায়ী।</p>
\\n</li>
\\n</ul>
\\n<p><b>স্পেসিফিকেশন ও উপাদান:</b></p>
\\n
\\n<ul>
\\n 	<li>
\\n<p><b>পণ্যের ধরন:</b> ফারমেন্টেড রেড পেপার পেস্ট (গোচুজাং)</p>
\\n</li>
\\n 	<li>
\\n<p><b>ব্র্যান্ড:</b> CJ Haechandle</p>
\\n</li>
\\n 	<li>
\\n<p><b>নেট ওজন:</b> ৫০০ গ্রাম</p>
\\n</li>
\\n 	<li>
\\n<p><b>ডায়েটারি পলিসি:</b> হালাল সার্টিফাইড, ভেগান-ফ্রেন্ডলি</p>
\\n</li>
\\n 	<li>
\\n<p><b>উপাদান:</b> কর্ন সিরাপ, লাল মরিচের পেস্ট/গুঁড়ো, পানি, গমের আটা, চালের গুঁড়ো, গম, লবণ, ডিস্টিলড অ্যালকোহল, সয়াবিন, সয়াবিন পাউডার, গ্লুটিনাস রাইস (আঠালো চাল), কোজি। <i>(এতে গম, বার্লি এবং সয়া রয়েছে)</i></p>
\\n</li>
\\n</ul>
\\n<p><b>ব্যবহার বিধি:</b></p>
\\n
\\n<ul>
\\n 	<li>
\\n<p><b>বিবিলবাপ ও কোরিয়ান সস:</b> গরম ভাতের বাটিতে সরাসরি মিক্স করুন অথবা রাইস কেকের সাথে ফুটিয়ে আকর্ষণীয় চকচকে তিলবোকি সস তৈরি করুন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>কে-বার্বিকিউ ম্যারিনেশন:</b> চিকেন, বিফ বা মাশরুম ম্যারিনেট করার জন্য গোচুজাং-এর সাথে সয়া সস, রসুন কুচি, তিলের তেল এবং সামান্য মধু বা চিনি মিশিয়ে নিন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>স্পাইসি ডিপ ও ডিপিং সস:</b> মেয়োনেজ, সামান্য ভিনেগার কিংবা তিলের তেলের সাথে এই পেস্টটি মিশিয়ে আপনার চিপস, ফ্রাই বা গ্রিল আইটেমের জন্য চমৎকার ডিপিং সস বানিয়ে নিন।</p>
\\n</li>
\\n</ul>',
                'regular_price' => 690,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => 0.5,
                'unit' => 'kg',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/CJ-Gochujang-Hot-Pepper-Paste-500g-Halal.png',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Gochujang-500g-halal-raw-img.png',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'cj',
            ],
            [
                'source_id' => 153,
                'name' => 'Samyang Rose Buldak Hot Chicken Flavor Ramen 130g',
                'slug' => 'samyang-rose-buldak-hot-chicken-flavor-ramen-130g',
                'sku' => 'PA-0153',
                'category_slug' => 'ramen',
                'short_description' => '\\n100% Halal Certified
\\n
\\n',
                'description' => '<p>কোরিয়ান ফুড ট্রেন্ডের দুনিয়ায় বর্তমানের সবচেয়ে বড় সেনসেশন হলো \'রোজ\' (Rosé) ফ্লেভার! আর সেই ট্রেন্ডকে আরও এক ধাপ এগিয়ে নিতে সাময়্যাং নিয়ে এলো <b>Samyang Rosé Buldak Ramen</b>। এটি তৈরি হয়েছে সিগনেচার বুলদাক হট চিকেন সস, রিচ ক্রিম এবং সুস্বাদু চিজের এক চমৎকার মিশ্রণে। চিরাচরিত কার্বোনারা ফ্লেভারের চেয়েও এটি আরও বেশি ক্রিমি, স্মুথ এবং এর ঝালের মাত্রা চমৎকারভাবে ব্যালেন্সড, যা আপনাকে দেবে একদম রেস্তোরাঁ স্টাইলের প্রিমিয়াম কোরিয়ান রোজ পাস্তার স্বাদ।</p>
\\n
\\n<h3>🌹 কেন এই রোজ বুলদাক রামেনটি এত ইউনিক?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>আল্ট্রা-ক্রিমি রোজ সস:</b> চিজ ও মিল্ক ক্রিমের পারফেক্ট কম্বিনেশনে তৈরি এর ঘন সসটি তীব্র ঝালকে একদম সহনীয় করে তোলে। ফলে যারা খুব বেশি ঝাল খেতে পারেন না, তারাও এটি দারুণ পছন্দ করবেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> মুসলিম কনজিউমারদের জন্য এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং কেএমএফ (KMF) দ্বারা সার্টিফাইড।</p>
\\n</li>
\\n 	<li>
\\n<p><b>কে-পপ ও সোশ্যাল মিডিয়া ট্রেন্ড:</b> এটি বর্তমানে গ্লোবাল ফুড ভ্লগার এবং কে-পপ ফ্যানদের অন্যতম শীর্ষ পছন্দের রামেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>পারফেক্ট টেক্সচার:</b> সাময়্যাং-এর স্পেশাল থিক এবং চিউই (Chewy) নুডুলস, যা প্রতিটি বাইটে রিচ রোজ সসের আসল স্বাদ পৌছে দেয়।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট ডিটেইলস:</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য</strong></td>
\\n<td><strong>বিবরণ</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Samyang (সাউথ কোরিয়া)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ফ্লেভার</b></span></td>
\\n<td><span>রোজ বুলদাক (Rosé Buldak)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ওজন</b></span></td>
\\n<td><span>১৩০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal Certified)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>🍳 রান্নার নিয়ম (Cooking Instructions):</h3>
\\n<p>১. পাত্রে ৬০০ মিলি পানি ফুটিয়ে নিয়ে তাতে নুডুলস দিন এবং ৫ মিনিট সেদ্ধ করুন।</p>
\\n<p>২. সেদ্ধ হয়ে গেলে পানি ছেঁকে নিন (পাত্রে সামান্য ৪-৫ চামচ পরিমাণ পানি রেখে দেবেন, যা সসটিকে ক্রিমি করতে সাহায্য করবে)।</p>
\\n<p>৩. এবার প্যাকেটের ভেতরের লাল রোজ লিকুইড সস এবং স্পেশাল রোজ পাউডারটি একসাথে দিয়ে ৩০ সেকেন্ড ভালোমতো নেড়েচেড়ে মিশিয়ে নিন।</p>
\\n<p>৪. ব্যস, তৈরি হয়ে গেল আপনার জিভে জল আনা ট্রেন্ডিং রোজ বুলদাক রামেন! গরম গরম পরিবেশন করুন।</p>',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-Rose-Buldak-Hot-Chicken-Flavor-Ramen.png',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Samyang-Rose.png',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 172,
                'name' => 'Bibigo Crispy Wasabi Seaweed Snack (3 pack) 15g',
                'slug' => 'bibigo-crispy-wasabi-seaweed-snack-3-pack-15g',
                'sku' => 'PA-0172',
                'category_slug' => 'seaweed',
                'short_description' => '100% halal certified.',
                'description' => '<h2>🌾 Bibigo Crispy Wasabi Seaweed Snack – স্বাদ ও স্বাস্থ্যের এক দারুণ কম্বিনেশন!</h2>
\\n<p>অতিরিক্ত তেল বা ক্যালোরির চিন্তা ছাড়াই কি চিপসের মতো কুড়মুড়ে কিছু খেতে মন চাইছে? কোরিয়ার বিখ্যাত ব্র্যান্ড CJ Bibigo আপনার জন্য নিয়ে এসেছে প্রিমিয়াম কোয়ালিটির <b>Crispy Wasabi Seaweed Snack</b>। পরিষ্কার সমুদ্রের পুষ্টিকর সিউইড (Seaweed) সংগ্রহ করে তা ওভেনে ক্রিস্পি করে রোস্ট করা হয়েছে এবং সাথে দেওয়া হয়েছে আসল জাপানিজ ওয়াসাবির স্পাইসি কিক।</p>
\\n<p>প্রতিটি বাইটে আপনি পাবেন দারুণ কুড়মুড়ে টেক্সচার, উমামি ফ্লেভার এবং ওয়াসাবির সেই চেনা হালকা ঝাঁঝালো স্বাদ— যা আপনার স্ন্যাক্স টাইমকে করে তুলবে আরও মজাদার ও রিফ্রেশিং।</p>
\\n
\\n<h3>🌟 কেন এই সিউইড স্ন্যাক্সটি আপনার এত পছন্দ হবে?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>প্রিমিয়াম কোয়ালিটি সিউইড:</b> সম্পূর্ণ প্রাকৃতিক এবং বাছাইকৃত সিউইড থেকে তৈরি, যা প্রাকৃতিকভাবেই ভিটামিন ও মিনারেল সমৃদ্ধ।</p>
\\n</li>
\\n 	<li>
\\n<p><b>পারফেক্ট ওয়াসাবি ফ্লেভার:</b> ওয়াসাবির ঝাঁঝালো ভাবটা একদম ব্যালেন্সড রাখা হয়েছে, যা প্রথম বাইটেই মুখে একটি দারুণ এক্সাইটিং স্বাদ এনে দেয়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০০% গিল্ট-ফ্রি ও হেলদি:</b> এটি একদমই সাধারণ চিপসের মতো তৈলাক্ত নয়। অত্যন্ত কম ক্যালোরি ও ফ্যাট থাকায় যারা ডায়েট করছেন, তারা নিশ্চিন্তে খেতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সুবিধাজনক ৩-ইন-১ প্যাক:</b> ১৫ গ্রামের এই মূল প্যাকেটের ভেতরে পাচ্ছেন ৩টি আলাদা ছোট হাইজেনিক ট্রে-প্যাক। ফলে সহজেই ব্যাগে ক্যারি করা যায় এবং প্রতিবার খোলার পর একদম ফ্রেশ ও ক্রিস্পি থাকে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>মাল্টি-পারপাস ব্যবহার:</b> শুধু স্ন্যাক্স হিসেবেই নয়, ধোঁয়া ওঠা গরম রামেন (Ramen), ফ্রাইড রাইস কিংবা সালাদের ওপর কুচি করে ছড়িয়ে দিয়ে খাবারের স্বাদ বাড়িয়ে নিতে পারেন বহুগুণ।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Bibigo (CJ CheilJedang)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Crispy Seaweed Snack (Wasabi Flavor)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>১৫ গ্রাম (৫ গ্রাম × ৩ প্যাকেট)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>মাল্টিপ্যাক ব্যাগ (ভেতরে আলাদা ট্রে যুক্ত)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ব্যবহার</b></span></td>
\\n<td><span>সরাসরি স্ন্যাক্স হিসেবে অথবা রামেন/রাইসের টপিং হিসেবে</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 খাওয়ার কিছু মজার উপায়:</h3>
\\n<p>১. <b>সরাসরি স্ন্যাক্স হিসেবে:</b> মুভি দেখতে দেখতে কিংবা বিকেলের হালকা আড্ডায় চিপসের বদলে সরাসরি এটি উপভোগ করুন।</p>
\\n<p>২. <b>রামেনের সাথে:</b> আপনার পছন্দের স্পাইসি কোরিয়ান নুডলস বা রামেনের বাটির পাশে সাইড ডিশ হিসেবে রাখুন অথবা ঝোলের ওপর দিয়ে দিন।</p>
\\n<p>৩. <b>মিনি রাইস রোল:</b> সামান্য গরম আঠালো ভাতের ওপর এই সিউইডের শিটটি জড়িয়ে মুখে পুরে দিন, চমৎকার লাগবে!</p>',
                'regular_price' => 280,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Bibigo-Crispy-Wasabi-Seaweed-Snack-3-pack-15g.webp',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Bibigo-Crispy-Wasabi-Seaweed-Snack-3-pack-15g-raw.png',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'bibigo',
            ],
            [
                'source_id' => 175,
                'name' => 'CJ Bibigo Roasted Seaweed For Wrap& Roll Kimbap Kim (Nori Sheet) 22g',
                'slug' => 'cj-bibigo-roasted-seaweed-for-wrap-roll-kimbap-kim-nori-sheet-22g',
                'sku' => 'PA-0175',
                'category_slug' => 'seaweed',
                'short_description' => 'Origin : South Korea',
                'description' => '<h2>🍙 CJ Bibigo Roasted Seaweed – বাসায় পারফেক্ট কিমবাপ ও সুশি তৈরির রহস্য!</h2>
\\n<p>কোরিয়ান ও জাপানিজ স্ট্রিট ফুড প্রেমীদের জন্য নিয়ে এলাম গ্লোবাল প্রিমিয়াম ব্র্যান্ড CJ-এর <b>Bibigo Roasted Seaweed (Nori Sheet)</b>। এটি বিশেষভাবে তৈরি করা হয়েছে একদম নিখুঁত টেক্সচারের কিমবাপ (Kimbap) বা সুশি রোল করার জন্য। এর রিচ, ক্রিস্পি এবং ট্র্যাডিশনাল ফ্লেভার আপনার হোমমেড কোরিয়ান ডিশকে দেবে একদম রেস্তোরাঁ স্টাইলের অথেনটিক স্বাদ।</p>
\\n
\\n<h3>🌟 কেন এই নোরি শিটটি আপনার কিচেনে থাকা চাই?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>পারফেক্ট ফর র‍্যাপ ও রোল:</b> এই নোরি শিটগুলো বেশ মজবুত এবং ফ্লেক্সিবল। ফলে ভেতরে অনেক রাইস বা ফিলিংস দিয়ে টাইট করে রোল করলেও এগুলো একদমই ছিঁড়ে যায় না।</p>
\\n</li>
\\n 	<li>
\\n<p><b>প্রিমিয়াম রোস্টেড কোয়ালিটি:</b> ১০০% ন্যাচারাল সিউইড থেকে তৈরি এবং নিখুঁত তাপমাত্রায় রোস্ট করা, যা খাবারে আনে দারুণ একটি ক্রাঞ্চি ও ফ্লেভারড ভাব।</p>
\\n</li>
\\n 	<li>
\\n<p><b>হেলদি ও লাইট স্ন্যাক:</b> এটি অত্যন্ত কম ক্যালোরি ও পুষ্টিগুণে ভরপুর। যারা হেলদি লাইফস্টাইল বা ডায়েট মেইনটেইন করছেন, তাদের রেসিপির জন্য এটি চমৎকার।</p>
\\n</li>
\\n 	<li>
\\n<p><b>মাল্টি-পারপাস ব্যবহার:</b> কিমবাপ বা সুশি রোল ছাড়াও কাঁচি দিয়ে কুচি কুচি করে কেটে আপনার ধোঁয়া ওঠা গরম রামেন (Ramen) বা রাইস বোলের ওপর টপিং হিসেবেও ব্যবহার করতে পারবেন।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>CJ Bibigo</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Roasted Seaweed For Wrap &amp; Roll Kimbap Kim</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>২২ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>জিপলক/প্লাস্টিক প্যাক (ফ্রেশ রাখার জন্য)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ব্যবহার</b></span></td>
\\n<td><span>কিমবাপ, সুশি রোল, রামেন টপিং বা সরাসরি</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 খাওয়ার কিছু মজার উপায়:</h3>
\\n<p>১. <b>অথেনটিক কিমবাপ:</b> নোরি শিটের ওপর সুশি রাইস, ডিম, গাজর, শসা ও আপনার পছন্দের মিট দিয়ে রোল করে কেটে পরিবেশন করুন।</p>
\\n<p>২. <b>রামেন টপিং:</b> রামেন বা যেকোনো কোরিয়ান নুডলসের বাটির পাশে ২/৩ টুকরো শিট সাজিয়ে দিন, দেখতেও সুন্দর লাগবে আর ঝোলে ভিজে স্বাদও বাড়বে।</p>
\\n<p>৩. <b>ঝটপট স্ন্যাক্স:</b> হালকা একটু তিলের তেল আর লবণ ব্রাশ করে ক্রিস্পি স্ন্যাক্স হিসেবেও সরাসরি চিবিয়ে খেতে পারেন।</p>',
                'regular_price' => 270,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/CJ-Bibigo-Roasted-Seaweed-For-Wrap-Roll-Kimbap-Kim-Nori-Sheet-22g.webp',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/CJ-Bibigo-Roasted-Seaweed-For-Wrap-Roll-Kimbap-Kim-Nori-Sheet-22g-Raw.webp',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'cj',
            ],
            [
                'source_id' => 193,
                'name' => 'Maxim White Gold Coffee Mix (20 Sticks) 234 gm',
                'slug' => 'maxim-white-gold-coffee-mix-20-sticks-234-gm',
                'sku' => 'PA-0193',
                'category_slug' => 'coffee',
                'short_description' => 'উৎপাদনকারী দেশ (Origin): সাউথ কোরিয়া (South Korea)',
                'description' => '<h2>☕ Maxim White Gold Coffee Mix – কোরিয়ার অল-টাইম ফেভারিট প্রিমিয়াম কফি ব্লেন্ড!</h2>
\\n<p>কফিপ্রেমীদের জন্য নিয়ে এলাম কোরিয়ার ঘরে ঘরে জনপ্রিয় এবং টপ-সিলিং ব্র্যান্ড Maxim-এর <b>White Gold Coffee Mix</b>। যারা কফিতে কড়া স্বাদের চেয়ে একটু বেশি ক্রিমি, স্মুথ এবং রিচ টেক্সচার পছন্দ করেন, এটি তাদের জন্য একদম পারফেক্ট চয়েস। প্রতি কাপে এটি আপনাকে দেবে ক্যাফে স্টাইলের ধোঁয়া ওঠা কফির আসল স্বাদ ও সুগন্ধ।</p>
\\n
\\n<h3>🌟 কেন এই কফি মিক্সটি এত স্পেশাল?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>নন-ফ্যাট মিল্ক ক্রিমের পারফেক্ট কম্বিনেশন:</b> সাধারণ দুধ বা হেভি ক্রিমের পরিবর্তে এতে ব্যবহার করা হয়েছে প্রিমিয়াম কোয়ালিটির নন-ফ্যাট মিল্ক (Non-fat Milk)। ফলে কফিটি হয় ভীষণ ক্রিমি ও সুস্বাদু, কিন্তু স্বাস্থ্যের জন্য একদমই ভারী বা ক্ষতিকর নয়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>স্পেশাল রোস্টিং টেকনোলজি:</b> কফির বীজগুলোকে এমনভাবে রোস্ট করা হয়েছে যাতে কফির নিজস্ব রিচ ফ্লেভার ও অ্যারোমা একটুও নষ্ট না হয়ে দুধের সাথে নিখুঁতভাবে মিশে যায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ইনস্ট্যান্ট ও ট্রাভেল ফ্রেন্ডলি:</b> ২৩৪ গ্রামের এই বক্সে পাচ্ছেন ২০টি আলাদা কফি স্টিক। ফলে অফিস, বাসা কিংবা ভ্রমণের সময় যেকোনো জায়গায় ঝটপট নিজের কাপটি তৈরি করে নেওয়া যায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সহজ সুইটনেস কন্ট্রোল:</b> প্রতিটি স্টিকের শেষ অংশে চিনি বা সুইটনার আলাদাভাবে অ্যাড করা থাকে, যা চাইলে আপনি আপনার স্বাদমতো কম বা বেশি করে নিতে পারেন।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Maxim (Dongsuh Foods)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>White Gold Coffee Mix</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>২৩৪ গ্রাম (১১.৭ গ্রাম × ২০টি স্টিক)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>বক্স (ভেতরে আলাদা ইন্ডিভিজুয়াল স্টিক যুক্ত)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উপাদান</b></span></td>
\\n<td><span>প্রিমিয়াম কফি বিন, নন-ফ্যাট মিল্ক ক্রাইম, চিনি</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 ঝটপট তৈরি করার নিয়ম:</h3>
\\n<p>১. একটি কফি কাপে ১টি <b>Maxim White Gold</b> কফি স্টিক সম্পূর্ণ ঢেলে নিন।</p>
\\n<p>২. কাপে প্রায় ৯০ মিলি গরম পানি (Hot Water) যোগ করুন।</p>
\\n<p>৩. চামচ দিয়ে ভালোভাবে নেড়ে মিশিয়ে নিলেই তৈরি আপনার দুর্দান্ত স্বাদের এক্সট্রা ক্রিমি কোরিয়ান কফি!</p>',
                'regular_price' => 700,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Maxim-White-Gold-Coffee-Mix-20-Sticks-234-gm.webp',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Maxim-White-Gold-Coffee-Mix-20-Sticks-234-gm-Raw.webp',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'maxim',
            ],
            [
                'source_id' => 196,
                'name' => 'Sempio Original Kimchi 160g',
                'slug' => 'sempio-original-kimchi-160g',
                'sku' => 'PA-0196',
                'category_slug' => 'kimchi',
                'short_description' => 'ফ্লেভার প্রোফাইল: পারফেক্ট টক-ঝাল এবং ট্র্যাডিশনাল ক্রাঞ্চি কিমচি ফ্লেভার
\\n',
                'description' => '<h2>🥢 Sempio Original Kimchi – খাবারের টেবিলে আসল কোরিয়ান কিমচির নিখুঁত স্বাদ!</h2>
\\n<p>কোরিয়ান খাবারের আসল ক্র্যাভিং মেটাতে নিয়ে এলাম সাউথ কোরিয়ার অন্যতম সেরা ও ঐতিহ্যবাহী ব্র্যান্ড Sempio-এর <b>Original Kimchi</b>। একদম নিখুঁত উপায়ে ফারমেন্টেড এই কিমচি কোরিয়ান সংস্কৃতির অত্যন্ত জনপ্রিয় একটি সাইড ডিশ। এটি আপনার যেকোনো সাধারণ খাবারের স্বাদ ও সুগন্ধকে এক নিমেষেই বাড়িয়ে দেবে বহুগুণ।</p>
\\n
\\n<h3>🌟 কেন এই কিমচিটি আপনার খাবার তালিকায় রাখবেন?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>অথেনটিক কোরিয়ান রেসিপি:</b> প্রিমিয়াম কোয়ালিটির কোরিয়ান বাঁধাকপি (Napa Cabbage) এবং আসল কোরিয়ান লাল মরিচের গুঁড়ো (Gochugaru) দিয়ে তৈরি, যা আপনাকে দেবে শতভাগ খাঁটি কোরিয়ান স্বাদ।</p>
\\n</li>
\\n 	<li>
\\n<p><b>দারুণ ক্রাঞ্চি ও ফ্লেভারড:</b> প্রতিটি বাইটে পাবেন পারফেক্ট ক্রাঞ্চিনেস এবং টক, ঝাল ও উমামি ফ্লেভারের এক দারুণ ব্যালেন্সড কম্বিনেশন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>রেডি-টু-ইট ও সুবিধাজনক প্যাকেজিং:</b> ১৬০ গ্রামের এই কমপ্যাক্ট ক্যান (Can) প্যাকেজিংটি সহজে বহনযোগ্য এবং জিপলক বা প্লাস্টিক কন্টেইনারের ঝামেলা ছাড়াই খোলার পর অনেকদিন পর্যন্ত কিমচিকে একদম ফ্রেশ ও ক্রিস্পি রাখে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>মাল্টি-পারপাস সাইড ডিশ:</b> এটি রামেন (Ramen), ফ্রাইড রাইস, কিমবাপ, স্টু কিংবা আমাদের দেশি গরম ভাতের সাথে সাইড ডিশ হিসেবে চমৎকার জমে যায়।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Sempio</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Original Kimchi</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>১৬০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>ইজি-ওপেন ক্যান (Can)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>ব্যবহার</b></span></td>
\\n<td><span>সরাসরি রেডি-টু-ইট অথবা রান্নায় ব্যবহারের জন্য</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 খাওয়ার কিছু মজার উপায়:</h3>
\\n<p>১. <b>রামেনের সাথে:</b> আপনার পছন্দের ধোঁয়া ওঠা গরম রামেনের বাটির সাথে সাইড ডিশ হিসেবে এটি মুখে দিন, স্বাদ দ্বিগুণ হয়ে যাবে।</p>
\\n<p>২. <b>কিমচি ফ্রাইড রাইস:</b> বেঁচে যাওয়া বাসি ভাতের সাথে সামান্য কিমচি এবং কিমচির জুস দিয়ে ঝটপট তৈরি করে ফেলুন রেস্তোরাঁ স্টাইলের কিমচি ফ্রাইড রাইস।</p>
\\n<p>৩. <b>স্টু বা কারি:</b> হালকা শীতের আমেজে যেকোনো স্যুপ বা স্টু-এর মাঝে কিছুটা কিমচি ছেড়ে দিলে দারুণ একটা স্পাইসি ও সওয়ার (Sour) কিক পাওয়া যায়।</p>',
                'regular_price' => 350,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Sempio-Original-Kimchi-160g.webp',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Sempio-Original-Kimchi-160g-raw.webp',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'variations' => [
                ],
                'brand_slug' => 'sempio',
            ],
            [
                'source_id' => 203,
                'name' => 'Maxim Original Coffee Mix(red) 20 Sticks',
                'slug' => 'maxim-original-coffee-mix-red-20-sticks',
                'sku' => 'PA-0203',
                'category_slug' => 'coffee',
                'short_description' => 'ফ্লেভার : পারফেক্ট ব্যালেন্সড, রিচ এবং ট্র্যাডিশনাল কফি ফ্লেভার',
                'description' => '<h2>☕ Maxim Original Coffee Mix (Red) – কোরিয়ার নাম্বার ওয়ান ক্লাসিক কফির আসল স্বাদ!</h2>
\\n<p>কোরিয়ান ড্রামা বা মুভিতে আমরা যে বিখ্যাত কফি মিক্সটি সবচেয়ে বেশি দেখে থাকি, সেটিই হলো এই <b>Maxim Original Coffee Mix (Red)</b>। সাউথ কোরিয়ার Dongsuh ব্র্যান্ডের এই কফিটি তাদের সিগনেচার এবং অল-টাইম বেস্ট-সেলার ব্লেন্ড। যারা কফিতে কড়া সুগন্ধ, পারফেক্ট সুইটনেস এবং নিখুঁত একটি ট্র্যাডিশনাল স্বাদ পছন্দ করেন, তাদের প্রতিদিনের সকাল বা বিকেলের জন্য এটি বেস্ট চয়েস।</p>
\\n
\\n<h3>🌟 কেন এই ক্লাসিক কফি মিক্সটি এত জনপ্রিয়?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>অথেনটিক কফি ব্লেন্ড:</b> এতে প্রিমিয়াম কোয়ালিটির কফি বিনের সাথে চিনি এবং ক্রিমের একদম নিখুঁত রেশিও বা ব্যালেন্স রাখা হয়েছে। প্রতি কাপে আপনি পাবেন আসল কফির রিচ ও স্ট্রং টেস্ট।</p>
\\n</li>
\\n 	<li>
\\n<p><b>তাৎক্ষণিক রিফ্রেশমেন্ট:</b> অফিসে কাজের চাপ, পড়াশোনার ক্লান্তি বা অলস দুপুরে এক কাপ ম্যাক্সিম অরিজিনাল কফি আপনার মুডকে মুহূর্তে চনমনে এবং এনার্জেটিক করে তুলবে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সহজ ও ঝটপট তৈরি:</b> বক্সে ২০টি আলাদা কফি স্টিক থাকায় সাথে চিনি বা দুধ আলাদা করার কোনো ঝামেলাই নেই। যেকোনো জায়গায় খুব সহজেই ক্যাফে স্টাইলের কফি উপভোগ করা যায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ইজি সুইটনেস কন্ট্রোল:</b> প্রতিটি স্টিকের শেষ মাথায় চিনি থাকে, তাই আপনি যদি কফিতে চিনি কিছুটা কম খেতে চান, তবে স্টিকের পেছনের অংশটি একটু চেপে ধরে চিনি কাপে পড়ার পরিমাণ নিয়ন্ত্রণ করতে পারবেন।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Maxim (Dongsuh Foods)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Original Coffee Mix (Red)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>২৩৪ গ্রাম (১১.৭ গ্রাম × ২০টি স্টিক)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>বক্স (ভেতরে আলাদা ইন্ডিভিজুয়াল স্টিক যুক্ত)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উপাদান</b></span></td>
\\n<td><span>প্রিমিয়াম কফি বিন, কফি ক্রিমিং পাউডার, চিনি</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 ঝটপট তৈরি করার নিয়ম:</h3>
\\n<p>১. একটি কফি কাপে ১টি <b>Maxim Original (Red)</b> কফি স্টিক সম্পূর্ণ ঢেলে নিন।</p>
\\n<p>২. কাপে প্রায় ৯০ মিলি ফুটন্ত গরম পানি (Hot Water) যোগ করুন।</p>
\\n<p>৩. চামচ দিয়ে ভালোভাবে নেড়ে মিশিয়ে নিলেই তৈরি আপনার ধোঁয়া ওঠা চমৎকার স্বাদের কোরিয়ান ক্লাসিক কফি!</p>',
                'regular_price' => 800,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Maxim-Original-Coffee-Mix-20-Sticks.webp',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Maxim-Original-Raw-Image.webp',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'maxim',
            ],
            [
                'source_id' => 204,
                'name' => 'Orion Custard 138g (6 pack) Halal',
                'slug' => 'orion-custard-138g-6-pack-halal',
                'sku' => 'PA-0204',
                'category_slug' => 'cakes',
                'short_description' => '১০০% হালাল সার্টিফাইড (Halal Certified)',
                'description' => '<h2>🍰 Orion Custard Cake – মুখে মিলিয়ে যাওয়া নরম ও হালাল কাস্টার্ড কেকের আসল স্বাদ!</h2>
\\n<p>যারা হালকা মিষ্টি, নরম এবং প্রিমিয়াম কোয়ালিটির স্ন্যাক্স পছন্দ করেন, তাদের জন্য সাউথ কোরিয়ার বিখ্যাত ব্র্যান্ড Orion নিয়ে এলো <b>Custard Cake</b>। এটি কোরিয়ার অন্যতম জনপ্রিয় এবং ক্লাসিক একটি বেকড স্ন্যাক্স, যা ছোট-বড় সবার ভীষণ প্রিয়। এর প্রতি কামড়ে আপনি পাবেন তুলতুলে স্পঞ্জি কেক এবং তার ভেতরে থাকা রিচ ও স্মুথ রিয়েল কাস্টার্ড ক্রিমের এক অপূর্ব ফ্লেভার।</p>
\\n
\\n<h3>🌟 কেন এই কাস্টার্ড কেকটি সবার প্রথম পছন্দ?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> মুসলিম কাস্টমারদের জন্য এটি সম্পূর্ণ নিরাপদ ও হালাল সার্টিফাইড। ফলে কোনো রকম দ্বিধা ছাড়াই নিশ্চিন্তে এটি উপভোগ করতে পারবেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>রিয়েল কাস্টার্ড ফিলিংস:</b> কেকটির ভেতরে রয়েছে আসল ডিম ও দুধের পুষ্টিগুণে তৈরি প্রিমিয়াম কাস্টার্ড ক্রিম। এটি কেকটিকে একদম ড্রাই হতে দেয় না, বরং মুখে দিলেই এক রিচ ও ক্রিমি স্বাদ এনে দেয়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>নরম ও স্পঞ্জি টেক্সচার:</b> ওভেনে নিখুঁতভাবে বেক করা এই কেকটি এতটাই সফট যে মুখে দেওয়ার সাথে সাথেই মিলিয়ে যায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>স্মার্ট ৬-প্যাক প্যাকেজিং:</b> ১৩৮ গ্রামের এই বক্সে পাচ্ছেন আলাদাভাবে র‍্যাপ করা ৬টি ইন্ডিভিজুয়াল কেক প্যাক। ফলে কেকগুলো অনেকদিন পর্যন্ত ফ্রেশ ও নরম থাকে এবং বাচ্চাদের স্কুলের টিফিনে, অফিসে বা ভ্রমণের সময় ব্যাগে ক্যারি করা সহজ হয়।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Orion</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Custard Cake (Halal)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>১৩৮ গ্রাম (২৩ গ্রাম × ৬টি প্যাকেট)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>বক্স (ভেতরে আলাদা ইন্ডিভিজুয়াল প্যাক যুক্ত)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 খাওয়ার কিছু মজার উপায়:</h3>
\\n<p>১. <b>টি-টাইম স্ন্যাক্স:</b> বিকেলের চা, কফি বা এক গ্লাস গরম দুধের সাথে হালকা স্ন্যাক্স হিসেবে এটি চমৎকার জমে যায়।</p>
\\n<p>২. <b>ঝটপট ডেজার্ট:</b> হুট করে মিষ্টি কিছু খেতে মন চাইলে ফ্রিজ থেকে বের করে সরাসরি এটি উপভোগ করুন।</p>
\\n<p>৩. <b>কিডস স্পেশাল:</b> বাচ্চাদের স্কুলের টিফিন বক্সে ১/২ টি প্যাক দিয়ে দিন, পুষ্টিকর এবং সুস্বাদু হওয়ায় তারা আনন্দের সাথে শেষ করবে!</p>',
                'regular_price' => 340,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Orion-Custard-138g-6-pack-Halal.webp',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Custard-6-pcs-raw-image.webp',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'variations' => [
                ],
                'brand_slug' => 'orion',
            ],
            [
                'source_id' => 205,
                'name' => 'Orion Chocopie 1 Box (12 pcs) 360g Halal',
                'slug' => 'orion-chocopie-1-box-12-pcs-360g-halal',
                'sku' => 'PA-0205',
                'category_slug' => 'cakes',
                'short_description' => '১০০% হালাল সার্টিফাইড (Halal Certified)',
                'description' => '<h2>🍫 Orion Chocopie – বিশ্বখ্যাত ও হালাল চকোপাইয়ের আসল রিচ ফ্লেভার!</h2>
\\n<p>চকলেট ও কেক প্রেমীদের কাছে সাউথ কোরিয়ার Orion ব্র্যান্ডের চকোপাই একটি অল-টাইম ক্লাসিক ইমোশন। প্রিমিয়াম কোয়ালিটির <b>Orion Chocopie (12 pcs Box)</b> নিয়ে এলো সেই চিরচেনা ও জাদুকরী স্বাদ। তিনটি লেয়ারের এই পারফেক্ট কম্বিনেশনে আছে ওভেনে নিখুঁতভাবে বেক করা নরম স্পঞ্জি কেক, তার ঠিক মাঝখানে নরম ও চিউই মার্শম্যালো (Marshmallow) ফিলিংস এবং পুরো কেকটির ওপর ছড়ানো রিচ ও প্রিমিয়াম ডার্ক চকলেটের গ্লেজড কোটিং।</p>
\\n
\\n<h3>🌟 কেন ওরিয়ন চকোপাই সবার এত প্রিয়?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> মুসলিম কাস্টমারদের জন্য এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং সার্টিফাইড। মার্শম্যালোর জেলি উপাদান নিয়ে কোনো রকম দ্বিধা ছাড়াই নিশ্চিন্তে এটি খেতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>তিনটি লেয়ারের ম্যাজিক:</b> কামড় দেওয়ার সাথে সাথেই চকলেটের রিচ টেস্ট, কেকের সফটনেস এবং মার্শম্যালোর চিউই টেক্সচার একসাথে মুখে এক দারুণ স্বাদের বিস্ফোরণ ঘটায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ফ্যামিলি সাইজ ১২-প্যাক:</b> ৩৬০ গ্রামের এই বড় বক্সে পাচ্ছেন আলাদাভাবে হাইজেনিক উপায়ে র‍্যাপ করা ১২টি চকোপাই। ফলে পুরো পরিবার একসাথে উপভোগ করার জন্য কিংবা মেহমানদারির জন্য এটি একদম পারফেক্ট।</p>
\\n</li>
\\n 	<li>
\\n<p><b>পারফেক্ট অন-দ্য-গো স্ন্যাক্স:</b> এটি বাচ্চাদের স্কুলের টিফিনে, বিকেলের নাস্তায়, অফিসের কাজের ফাঁকে কিংবা যেকোনো ভ্রমণের সময় সাথে রাখার জন্য অত্যন্ত সুবিধাজনক।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Orion</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Chocopie (Halal)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>৩৬০ গ্রাম (৩০ গ্রাম × ১২টি প্যাকেট)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>লার্জ বক্স (ভেতরে ১২টি ইন্ডিভিজুয়াল প্যাক যুক্ত)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 খাওয়ার কিছু মজার ও ইউনিক উপায়:</h3>
\\n<p>১. <b>ক্লাসিক স্টাইল:</b> প্যাকেট খুলে সরাসরি চকলেটের রিচ ও স্মুথ টেস্ট উপভোগ করুন।</p>
\\n<p>২. <b>মাইক্রোওয়েভ ম্যাজিক (হট চকোপাই):</b> চকোপাইটি প্যাকেট থেকে বের করে একটি প্লেটে নিয়ে মাত্র ১০-১৫ সেকেন্ড মাইক্রোওয়েভ ওভেনে গরম করে নিন। ভেতরের মার্শম্যালো গলে একদম লাভা কেকের মতো হয়ে যাবে, যা চামচ দিয়ে খেতে অসাধারণ লাগে!</p>
\\n<p>৩. <b>আইস-ক্রিম টপিং:</b> ছোট ছোট টুকরো করে কেটে আপনার ভ্যানিলা বা চকলেট আইসক্রিমের ওপর টপিং হিসেবে ছড়িয়ে দিন।</p>',
                'regular_price' => 650,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Orion-Chocopie-1-Box-12-pcs-360g-Halal.webp',
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Orion-Chocopie-1-Box-12-pcs-360g-Halal-raw.webp',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'variations' => [
                ],
                'brand_slug' => 'orion',
            ],
            [
                'source_id' => 209,
                'name' => 'Samyang 2x Buldak Fire Chicken Ramen Halal',
                'slug' => 'samyang-2x-buldak-fire-chicken-ramen-halal',
                'sku' => 'PA-0209',
                'category_slug' => 'ramen',
                'short_description' => '১০০% হালাল সার্টিফাইড (KMF/Halal Certified)',
                'description' => '<h2>🔥 Samyang 2x Spicy Buldak Ramen – ঝালের চরম অভিজ্ঞতা ও আসল কোরিয়ান ফায়ার রামেন!</h2>
\\n<p>আপনি যদি স্পাইসি ফুড লাভার হয়ে থাকেন এবং ঝালের আসল চ্যালেঞ্জ নিতে ভালোবাসেন, তবে সাউথ কোরিয়ার বিখ্যাত ব্র্যান্ড Samyang-এর <b>2x Spicy Buldak Fire Chicken Ramen</b> আপনার জন্যই তৈরি! এটি সাধারণ ফায়ার রামেনের চেয়ে দ্বিগুণ (2x) বেশি ঝাল ও চমৎকার উমামি চিকেন ফ্লেভারে ভরপুর। কোরিয়ান ‘হ্যাকবুলডাক-বোক্কুম-মিয়ন’ নামের এই নুডলসটি পুরো বিশ্বজুড়ে স্পাইসি রামেন চ্যালেঞ্জের জন্য অত্যন্ত জনপ্রিয়।</p>
\\n
\\n<h3>🌟 কেন এই ২x স্পাইসি রামেনটি এত ট্রেন্ডিং?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং কোরিয়া মুসলিম ফেডারেশন (KMF) দ্বারা অনুমোদিত। ফলে মুসলিম ক্রেতারা নিশ্চিন্তে এটি উপভোগ করতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>এক্সট্রিম স্পাইসি কিক:</b> এর সিগনেচার হট চিকেন ফ্লেভার সসটি আপনাকে দেবে একদম নেক্সট লেভেলের ঝাল ও স্মোকি টেস্ট, যা প্রথম বাইটেই মুখে আগুন এনে দেবে!</p>
\\n</li>
\\n 	<li>
\\n<p><b>পারফেক্ট কোরিয়ান টেক্সচার:</b> কোরিয়ান রামেনের সিগনেচার থিক (Thick) এবং চিউই (Chewy) নুডলস, যা সসের সাথে চমৎকারভাবে মিশে যায় এবং প্রতিটি বাইটে পারফেক্ট টেক্সচার দেয়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ঝটপট তৈরি:</b> মাত্র ৫ মিনিটে তৈরি করে নেওয়া যায়। বিকেলের নাস্তায়, মাঝরাতের ক্র্যাভিংয়ে কিংবা বন্ধুদের সাথে স্পাইসি নুডলস চ্যালেঞ্জের জন্য এটি একদম আইডিয়াল।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Samyang</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>2x Buldak Fire Chicken Ramen (Halal)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>১৪০ গ্রাম</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>সিঙ্গল প্যাকেট</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>টাইপ</b></span></td>
\\n<td><span>ড্রাই/স্টির-ফ্রাই (Stir-fried) নুডলস</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 সহজে তৈরি করার সঠিক নিয়ম:</h3>
\\n<p>১. একটি পাত্রে প্রায় ৬০০ মিলি পানি ফুটিয়ে নিন এবং তাতে নুডলসগুলো দিয়ে ৫ মিনিট সেদ্ধ করুন।</p>
\\n<p>২. সেদ্ধ হয়ে গেলে পাত্র থেকে পানি ছেঁকে ফেলে দিন (শুধু ৮ চামচ পরিমাণ পানি নুডলসের সাথে রেখে দেবেন)।</p>
\\n<p>৩. এবার তরল স্পাইসি সসের প্যাকেটটি নুডলসের সাথে ঢেলে দিয়ে মাঝারি আঁচে ৩০ সেকেন্ড ভালোভাবে নেড়ে স্টির-ফ্রাই (Stir-fry) করে নিন।</p>
\\n<p>৪. নামানোর পর ওপর থেকে ড্রাই ফ্লেক্স (তিল ও সিউইড) ছড়িয়ে দিয়ে গরম গরম উপভোগ করুন কোরিয়ার সবচেয়ে ঝাল রামেন!</p>',
                'regular_price' => 160,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/NONGSHIM-Shin-Ramen-120G-HALAL.png',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 232,
                'name' => 'Rice Water Bright Cleansing Foam Deeply Cleansing Skin',
                'slug' => 'rice-water-bright-cleansing-foam-deeply-cleansing-skin',
                'sku' => 'PA-0232',
                'category_slug' => 'face-wash-cleanser',
                'short_description' => 'Curated skin care and cosmetic product for daily routine.',
                'description' => 'Curated skin care and cosmetic product for daily routine.',
                'regular_price' => 520,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'variable',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Rice-Water-Bright-Cleansing-Foam-Deeply-Cleansing-Skin.png',
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'variations' => [
                    [
                        'name' => '150ml',
                        'sku' => 'PA-232-01',
                        'regular_price' => 1050,
                        'sale_price' => null,
                        'stock_quantity' => 100,
                        'weight' => null,
                        'unit' => 'pcs',
                        'image_urls' => [
                        ],
                    ],
                    [
                        'name' => '50ml',
                        'sku' => 'PA-232-02',
                        'regular_price' => 520,
                        'sale_price' => null,
                        'stock_quantity' => 100,
                        'weight' => null,
                        'unit' => 'pcs',
                        'image_urls' => [
                        ],
                    ],
                ],
                'brand_slug' => 'the-face-shop',
            ],
            [
                'source_id' => 241,
                'name' => 'Cosrx Advanced Snail Mucin Gel Cleanser - 150ml',
                'slug' => 'cosrx-advanced-snail-mucin-gel-cleanser-150ml',
                'sku' => 'PA-0241',
                'category_slug' => 'face-wash-cleanser',
                'short_description' => 'স্কিন টাইপ (Skin Type): সেনসিটিভ, ড্রাই ও অ্যাকনি-প্রোনসহ সব ধরনের ত্বকের জন্য উপযোগী
\\n',
                'description' => '<h2>✨ Cosrx Advanced Snail Mucin Gel Cleanser – গ্লোয়িং ও হাইড্রেটেড ত্বকের জন্য পারফেক্ট কোরিয়ান ক্লিনজার!</h2>
\\n<p>কোরিয়ান স্কিন কেয়ারের সবচেয়ে জনপ্রিয় এবং হাইপড উপাদান \'স্নেইল মিউসিন\' (Snail Mucin) নিয়ে যারা রেগুলার স্কিন কেয়ার করতে চান, তাদের জন্য কে-বিউটি ব্র্যান্ড Cosrx-এর <b>Advanced Snail Mucin Gel Cleanser</b> একটি আলটিমেট সলিউশন। এই আল্ট্রা-জেন্টল জেল ক্লিনজারটি ত্বকের স্বাভাবিক আর্দ্রতা (Natural Moisture) না কেড়ে এক ফোঁটা ড্রাইনেস ছাড়াই স্কিনকে খুব স্মুথ এবং নিখুঁতভাবে পরিষ্কার করে।</p>
\\n
\\n<h3>🌟 কেন এই ক্লিনজারটি আপনার স্কিন কেয়ার রুটিনে রাখবেন?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>ডিপ ক্লিনজিং উইথ হাইড্রেশন:</b> এর স্মুথ জেল টেক্সচার ত্বকের গভীর থেকে মেকআপের অবশিষ্টাংশ, সিবাম এবং ধুলাবালি দূর করে। ধোয়ার পর স্কিন একদমই টানটান বা ড্রাই লাগে না, বরং সফট ও প্লাম্পি থাকে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>১০,০০০ ppm স্নেইল মিউসিন:</b> এতে থাকা প্রিমিয়াম স্নেইল সিক্রেশন ফিল্ট্রেট ড্যামেজড স্কিন রিপেয়ার করে, অ্যাকনি স্কার বা দাগ কমাতে সাহায্য করে এবং স্কিনের টেক্সচার ইমপ্রুভ করে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>হাইপোলার্জেনিক ও জেন্টল:</b> ক্লিনজারটি ক্ষতিকর সালফেট বা প্যারাবেন মুক্ত এবং অত্যন্ত সুদিং (Soothing)। তাই অতিরিক্ত সেনসিটিভ বা লালচে ভাব (Redness) থাকা ত্বকেও এটি খুব নিরাপদে কাজ করে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>পারফেক্ট গ্লোয়িং এফেক্ট:</b> নিয়মিত ব্যবহারে এটি স্কিনের ন্যাচারাল ব্যারিয়ারকে মজবুত করে, যার ফলে ত্বক ভেতর থেকে হেলদি ও গ্লাস-স্কিনের মতো গ্লোয়িং হয়ে ওঠে।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Cosrx</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Advanced Snail Mucin Gel Cleanser</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ভলিউম</b></span></td>
\\n<td><span>১৫০ মিলি (150ml)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>ইজি-স্কুইজ টিউব প্যাক</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>মূল উপাদান</b></span></td>
\\n<td><span>Snail Secretion Filtrate, Arginine</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 ব্যবহারের সঠিক নিয়ম:</h3>
\\n<p>১. প্রথমে জল দিয়ে মুখ ভালো করে ভিজিয়ে নিন।</p>
\\n<p>২. হাতের তালুতে পর্যাপ্ত পরিমাণ জেল ক্লিনজার নিয়ে সামান্য জল মিশিয়ে ফেনা (Lather) তৈরি করুন।</p>
\\n<p>৩. পুরো মুখে বৃত্তাকার মোশনে (Circular Motion) আলতো করে ম্যাসাজ করুন।</p>
\\n<p>৪. হালকা কুসুম গরম জল বা সাধারণ জল দিয়ে মুখ ভালোভাবে ধুয়ে ফেলুন এবং আপনার পছন্দের টোনার ও ময়েশ্চারাইজার অ্যাপ্লাই করুন।</p>',
                'regular_price' => 1550,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Cosrx-Advanced-Snail-Mucin-Gel-Cleanser-150ml.png',
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'cosrx',
            ],
            [
                'source_id' => 243,
                'name' => 'Cosrx Advanced Snail 96 Mucin Power Essence - 100ml',
                'slug' => 'cosrx-advanced-snail-96-mucin-power-essence-100ml',
                'sku' => 'PA-0243',
                'category_slug' => 'skin-care',
                'short_description' => 'স্কিন টাইপ (Skin Type): ড্রাই, ডিহাইড্রেটেড, ডাল এবং অ্যাকনি-প্রোনসহ সব ধরনের ত্বকের জন্য পারফেক্ট',
                'description' => '<h2>✨ Cosrx Advanced Snail 96 Mucin Power Essence – কোরিয়ান গ্লাস স্কিন পাওয়ার আলটিমেট সিক্রেট!</h2>
\\n<p>বিশ্বজুড়ে স্কিনকেয়ার লাভারদের অল-টাইম ফেভারিট এবং সবচেয়ে হাইপড প্রোডাক্টের নাম বলতে গেলে সবার আগে আসে Cosrx-এর <b>Advanced Snail 96 Mucin Power Essence</b>। এটি কোনো সাধারণ সিরাম বা এসেন্স নয়, এতে রয়েছে সর্বোচ্চ ৯৬.৩% খাঁটি স্নেইল মিউসিন। এই লাইটওয়েট এসেন্সটি ত্বকের একদম গভীরের লেয়ারে শোষিত হয়ে ডিহাইড্রেটেড ও ড্যামেজড স্কিনকে মুহূর্তের মধ্যে প্রাণবন্ত, হাইড্রেটেড এবং প্লাম্পি করে তোলে।</p>
\\n
\\n<h3>🌟 কেন এই গ্লোবাল বেস্টসেলার এসেন্সটি আপনার চাই-ই চাই?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>৯৬.৩% স্নেইল মিউসিন সমৃদ্ধ:</b> উচ্চমাত্রার স্নেইল সিক্রেশন ফিল্ট্রেট ত্বকের কোলাজেন প্রোডাকশন বাড়াতে সাহায্য করে। ফলে স্কিনের ইলাস্টিসিটি বাড়ে এবং ফাইন লাইনস কমে আসে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ম্যাজিকাল স্কিন রিপেয়ারিং:</b> ভুল স্কিনকেয়ার প্রোডাক্ট ব্যবহার, রোদে পোড়া বা অ্যাকনির কারণে ড্যামেজ হয়ে যাওয়া স্কিন ব্যারিয়ারকে এটি খুব দ্রুত হিল ও রিপেয়ার করে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>দাগছোপ ও রেডনেস দূর করে:</b> নিয়মিত ব্যবহারে এটি অ্যাকনির জেদি কালো দাগ (Hyper-pigmentation), ডার্ক স্পটস এবং সেনসিটিভ স্কিনের লালচে ভাব (Redness) দূর করতে দারুণ কার্যকর।</p>
\\n</li>
\\n 	<li>
\\n<p><b>লং-লাস্টিং ময়েশ্চার লক:</b> এটি ত্বকের আর্দ্রতা ধরে রেখে ভেতর থেকে একটি ন্যাচারাল ‘প্লাম্পি’ এবং ‘ডিউই’ ফিনিশ দেয়, যা আপনাকে দেবে কাঙ্ক্ষিত কোরিয়ান গ্লাস স্কিন গ্লো।</p>
\\n</li>
\\n 	<li>
\\n<p><b>লাইটওয়েট ও নন-স্টিকি:</b> টেক্সচারটি একটু স্লাইমি বা চিটচিটে মনে হলেও স্কিনে দেওয়ার সাথে সাথেই কোনো রকম আঠালো ভাব ছাড়াই চমৎকারভাবে ব্লেন্ড হয়ে যায়।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Cosrx</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Advanced Snail 96 Mucin Power Essence</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ভলিউম</b></span></td>
\\n<td><span>১০০ মিলি (100ml)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>হাইজেনিক পাম্প বোতল</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>মূল উপাদান</b></span></td>
\\n<td><span>Snail Secretion Filtrate, Sodium Hyaluronate, Allantoin</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 ব্যবহারের সঠিক নিয়ম:</h3>
\\n<p>১. মুখ ভালোভাবে ক্লিন করার পর (এবং টোনার ব্যবহারের পর) হাতের তালুতে ১-২ পাম্প এসেন্স নিন।</p>
\\n<p>২. পুরো মুখে আলতো করে ড্যাব ড্যাব (Dab) বা ট্যাপ করে অ্যাপ্লাই করুন, যাতে স্কিন এটি পুরোপুরি শুষে নিতে পারে।</p>
\\n<p>৩. এসেন্সটি ভালোভাবে অ্যাবসর্ব হয়ে যাওয়ার পর আপনার পছন্দের ময়েশ্চারাইজার ব্যবহার করুন। (ভালো রেজাল্টের জন্য দিনে ও রাতে দুইবার ব্যবহার করুন)।</p>',
                'regular_price' => 1550,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/06/Cosrx-Advanced-Snail-96-Mucin-Power-Essence-100ml.png',
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'cosrx',
            ],
            [
                'source_id' => 247,
                'name' => 'MISSHA All Around Safe Block Aqua Sun Gel SPF50+ PA++++ 50ml',
                'slug' => 'missha-all-around-safe-block-aqua-sun-gel-spf50-pa-50ml',
                'sku' => 'PA-0247',
                'category_slug' => 'face-wash-cleanser',
                'short_description' => 'Curated skin care and cosmetic product for daily routine.',
                'description' => 'Curated skin care and cosmetic product for daily routine.',
                'regular_price' => 0,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'variations' => [
                ],
                'brand_slug' => 'missha',
            ],
            [
                'source_id' => 251,
                'name' => 'iMee Instant Cup - Tom Yum',
                'slug' => 'imee-instant-cup-tom-yum',
                'sku' => 'PA-0251',
                'category_slug' => 'cup-ramen',
                'short_description' => '📌 প্রোডাক্ট ডিটেইলস:
\\n
\\n
\\n 	
\\nনেট ওজন (Weight): ৭০ গ্রাম (70g)
\\n
\\n 	
\\nউৎপাদনকারী দেশ (Origin): থাইল্যান্ড (Thailand)
\\n
\\n 	
\\nফ্লেভার প্রোফাইল: স্পাইসি, সওয়ার (টক-ঝাল) এবং অথেনটিক থাই টম ইয়াম ফ্লেভার
\\n
\\n 	
\\nসার্টিফিকেশন: ১০০% হালাল সার্টিফ',
                'description' => '<h2>🍜 iMee Instant Cup Noodles (Tom Yum Flavour) – থাইল্যান্ডের আসল টক-ঝাল টম ইয়াম স্যুপের স্বাদ এখন কাপেই!</h2>
\\n<p>ঝটপট সুস্বাদু ও স্পাইসি কিছু খেতে মন চাইলে আপনার জন্য নিয়ে এলাম থাইল্যান্ডের জনপ্রিয় ব্র্যান্ড iMee-এর <b>Instant Cup Noodles - Tom Yum</b>। থাই রান্নার সিগনেচার লেমনগ্রাস, গ্যালাঙ্গাল, লেবু আর মরিচের পারফেক্ট ব্লেন্ডে তৈরি এই টম ইয়াম নুডলস। এর ধোঁয়া ওঠা গরম ও স্পাইসি স্যুপ আপনাকে দেবে একদম রেস্তোরাঁ স্টাইলের অথেনটিক থাই সুগন্ধ ও স্বাদ।</p>
\\n
\\n<h3>🌟 কেন এই টম ইয়াম কাপ নুডলসটি আপনার পছন্দের তালিকায় রাখবেন?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি, যা বিশ্বজুড়ে মুসলিম কনজিউমারদের কাছে অত্যন্ত জনপ্রিয় ও বিশ্বস্ত।</p>
\\n</li>
\\n 	<li>
\\n<p><b>অথেনটিক থাই টম ইয়াম টেস্ট:</b> নিখুঁত টক-ঝাল আর সাভারি ফ্লেভারের এক দারুণ কম্বিনেশন, যা প্রথম চামচ স্যুপ মুখেই দিলেই মন চনমনে করে তোলে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>অন-দ্য-গো প্যাকেজিং (Ready-to-Eat):</b> এর মজবুত কাপ প্যাকেজিংয়ের ভেতরে একটি ফর্ক (Fork) বা প্লাস্টিকের চামচ দেওয়াই থাকে। ফলে বাটি বা এক্সট্রা চামচের ঝামেলা ছাড়াই যেকোনো জায়গায় শুধু গরম জল দিয়েই এটি খাওয়া যায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>পারফেক্ট স্ন্যাক্স পার্টনার:</b> মাঝরাতের হালকা ক্ষুধা, অফিসের কাজের ফাঁকে কিংবা যেকোনো ট্যুরে সাথে রাখার জন্য এটি অত্যন্ত সুবিধাজনক এবং সময়সাশ্রয়ী।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>iMee</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Instant Cup Noodles (Tom Yum Flavour)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>৭০ গ্রাম (70g)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>ওয়ান-টাইম ইউজড কাপ (ভেতরে ফর্ক যুক্ত)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>থাইল্যান্ড (Thailand)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>টাইপ</b></span></td>
\\n<td><span>ইনস্ট্যান্ট স্যুপ নুডলস</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 সহজে তৈরি করার সঠিক নিয়ম:</h3>
\\n<p>১. কাপের ওপরের ঢাকনাটি অর্ধেক পর্যন্ত টেনে খুলুন।</p>
\\n<p>২. ভেতরে থাকা সিজনিং পাউডার ও সসের প্যাকেটগুলো কেটে নুডলসের ওপর ঢেলে দিন।</p>
\\n<p>৩. কাপের ভেতরের নির্দিষ্ট দাগ (Indication Line) পর্যন্ত ফুটন্ত গরম জল যোগ করুন।</p>
\\n<p>৪. ঢাকনাটি আবার বন্ধ করে ৩-৪ মিনিট অপেক্ষা করুন।</p>
\\n<p>৫. ঢাকনা পুরোটা খুলে চামচ দিয়ে ভালোভাবে নেড়ে মিশিয়ে নিলেই রেডি থাইল্যান্ডের স্পাইসি টম ইয়াম নুডলস!</p>',
                'regular_price' => 180,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/07/iMee-Instant-Cup-Tom-Yum.webp',
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => false,
                'variations' => [
                ],
                'brand_slug' => 'imee',
            ],
            [
                'source_id' => 253,
                'name' => 'Samyang Ramen Original Cup',
                'slug' => 'samyang-ramen-original-cup',
                'sku' => 'PA-0253',
                'category_slug' => 'cup-ramen',
                'short_description' => 'শর্ট ডেসক্রিপশন (Short Description)
\\n📌 প্রোডাক্ট ডিটেইলস:
\\n
\\n
\\n 	
\\nনেট ওজন (Weight): ৬৫ গ্রাম (65g)
\\n
\\n 	
\\nউৎপাদনকারী দেশ (Origin): সাউথ কোরিয়া (South Korea)
\\n
\\n 	
\\nফ্লেভার প্রোফাইল: মাইল্ড স্পাইসি, সাভারি এবং রিচ কোরিয়ান ট্র্যাডিশনাল হাম ',
                'description' => '<h2>🍜 Samyang Ramen Original Cup – কোরিয়ার প্রথম ও অল-টাইম ক্লাসিক রামেনের আসল স্বাদ এখন কাপে!</h2>
\\n<p>১৯৬৩ সালে তৈরি কোরিয়ার সর্বপ্রথম এবং ঐতিহ্যবাহী রামেন হলো এই অরিজিনাল ব্লেন্ড। সাউথ কোরিয়ার বিখ্যাত ব্র্যান্ড Samyang-এর <b>Ramen Original Cup</b> নিয়ে এলো সেই ক্লাসিক ও চিরচেনা স্বাদ, যা কোরিয়ান ডিশ প্রেমীদের কাছে অত্যন্ত প্রিয়। এর হালকা ঝাল, রিচ উмамি ব্রোথ (স্যুপ) এবং সিগনেচার কোরিয়ান নুডলসের কম্বিনেশন আপনাকে দেবে একদম অথেনটিক কোরিয়ান হোম-কুকড মিলের অনুভূতি।</p>
\\n
\\n<h3>🌟 কেন এই অরিজিনাল কাপ রামেনটি আপনার ট্রাই করা উচিত?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> মুসলিম কাস্টমারদের জন্য এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং অনুমোদিত। ফলে কোনো রকম দ্বিধা ছাড়াই নিশ্চিন্তে এটি উপভোগ করতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>মাইল্ড ও ব্যালেন্সড ফ্লেভার:</b> যারা অতিরিক্ত ঝাল (যেমন ২x বুলডাক) খেতে পারেন না, তাদের জন্য এটি পারফেক্ট চয়েস। এর স্যুপে রয়েছে হালকা ঝাল এবং চমৎকার সাভারি ফ্লেভারের এক নিখুঁত ব্যালেন্স।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ইনস্ট্যান্ট ও রেডি-টু-ইট:</b> কাপের ভেতরেই ফর্ক দেওয়া থাকে। বাটি বা এক্সট্রা চামচ ধোয়ার ঝামেলা ছাড়াই শুধু গরম জল দিয়েই মাত্র ৩ মিনিটে এটি তৈরি করে নেওয়া যায়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>অন-দ্য-গো স্ন্যাক্স:</b> সাইজে কমপ্যাক্ট এবং লাইটওয়েট হওয়ায় অফিস, হস্টেল, মাঝরাতের ক্ষুধা কিংবা যেকোনো ভ্রমণের সময় সাথে রাখার জন্য এটি অত্যন্ত সুবিধাজনক।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Samyang</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Ramen Original Cup</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>৬৫ গ্রাম (65g)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>ওয়ান-টাইম ইউজড কাপ (ভেটারে ফর্ক যুক্ত)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>টাইপ</b></span></td>
\\n<td><span>ইনস্ট্যান্ট স্যুপ নুডলস</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 সহজে তৈরি করার সঠিক নিয়ম:</h3>
\\n<p>১. কাপের ওপরের ঢাকনাটি অর্ধেক পর্যন্ত টেনে খুলুন।</p>
\\n<p>২. ভেতরে থাকা সিজনিং পাউডারের প্যাকেটটি কেটে নুডলসের ওপর ঢেলে দিন।</p>
\\n<p>৩. কাপের ভেতরের নির্দিষ্ট দাগ (Indication Line) পর্যন্ত ফুটন্ত গরম জল যোগ করুন।</p>
\\n<p>৪. ঢাকনাটি আবার বন্ধ করে ৩-৪ মিনিট অপেক্ষা করুন।</p>
\\n<p>৫. ঢাকনা পুরোটা খুলে চামচ দিয়ে ভালোভাবে নেড়ে মিশিয়ে নিলেই রেডি ধোঁয়া ওঠা কোরিয়ান ক্লাসিক অরিজিনাল রামেন!</p>',
                'regular_price' => 180,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/07/Samyang-Ramen-Original-Cup.webp',
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => false,
                'variations' => [
                ],
                'brand_slug' => 'samyang',
            ],
            [
                'source_id' => 255,
                'name' => 'Nongshim - Shin RED Cup (Halal)',
                'slug' => 'nongshim-shin-red-cup-halal',
                'sku' => 'PA-0255',
                'category_slug' => 'cup-ramen',
                'short_description' => 'শর্ট ডেসক্রিপশন (Short Description)
\\n📌 প্রোডাক্ট ডিটেইলস:
\\n
\\n
\\n 	
\\nনেট ওজন (Weight): ৬৮ গ্রাম (68g)
\\n
\\n 	
\\nউৎপাদনকারী দেশ (Origin): সাউথ কোরিয়া (South Korea)
\\n
\\n 	
\\nফ্লেভার প্রোফাইল: এক্সট্রা স্পাইসি, বোল্ড এবং রিচ উмамির সাথে ঝাল কোরিয়ান ',
                'description' => '<h2>🍜 Nongshim Shin RED Cup – ঝালের কড়া কিক ও বিশ্বখ্যাত শিন রামেনের এক্সট্রা স্পাইসি সংস্করণ!</h2>
\\n<p>স্পাইসি লাভারদের জন্য নিয়ে এলাম কোরিয়ার এক নম্বর ও বিশ্বখ্যাত রামেন ব্র্যান্ড Nongshim-এর সবচেয়ে হাইপড প্রোডাক্ট <b>Shin RED Cup Noodles</b>। এটি ক্লাসিক শিন রামেনের চেয়েও অনেক বেশি ঝাল, বোল্ড এবং রিচ ফ্লেভারে তৈরি। যারা কোরিয়ান স্যুপ নুডলসের অথেনটিক উмамির সাথে একটি কড়া স্পাইসি কিক খুঁজছেন, তাদের জন্য এই রেড কাপটি একদম পারফেক্ট চয়েস।</p>
\\n
\\n<h3>🌟 কেন এই শিন রেড কাপ রামেনটি এত স্পেশাল?</h3>
\\n<ul>
\\n 	<li>
\\n<p><b>১০০% হালাল সার্টিফাইড:</b> এটি সম্পূর্ণ হালাল উপাদান দিয়ে তৈরি এবং অনুমোদিত। ফলে মুসলিম ক্রেতারা কোনো রকম দ্বিধা ছাড়াই নিশ্চিন্তে কোরিয়ার এই টপ-নচ রামেন উপভোগ করতে পারেন।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সুপার স্পাইসি ও রিচ ব্রোথ:</b> এর স্পেশাল রেড সিজনিং পাউডার স্যুপটিকে দেয় একটি গাঢ় লাল রঙ এবং এক্সট্রা ঝাল স্বাদ, যা প্রথম চামচ মুখে দিলেই স্পাইসি লাভারদের মন জয় করে নেবে।</p>
\\n</li>
\\n 	<li>
\\n<p><b>সিগনেচার কোরিয়ান টেক্সচার:</b> নুডলসগুলো ওভেনে নিখুঁতভাবে তৈরি, যা সেদ্ধ হওয়ার পর বেশ সফট এবং চিউই (Chewy) হয় এবং সসের রিচ ফ্লেভার চমৎকারভাবে শুষে নেয়।</p>
\\n</li>
\\n 	<li>
\\n<p><b>ঝটপট ও সুবিধাজনক কাপ প্যাক:</b> কাপের ভেতরেই ফর্ক দেওয়া থাকে। আলাদা বাটি বা চামচের ঝামেলা ছাড়াই যেকোনো স্থানে শুধু গরম জল দিয়েই মাত্র ৩ মিনিটে এটি তৈরি করে নেওয়া যায়।</p>
\\n</li>
\\n</ul>
\\n<h3>📋 প্রোডাক্ট স্পেসিফিকেশন (Product Specifications):</h3>
\\n<table>
\\n<thead>
\\n<tr>
\\n<td><strong>বৈশিষ্ট্য (Specification)</strong></td>
\\n<td><strong>বিস্তারিত বিবরণ (Details)</strong></td>
\\n</tr>
\\n</thead>
\\n<tbody>
\\n<tr>
\\n<td><span><b>ব্র্যান্ড</b></span></td>
\\n<td><span>Nongshim</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্রোডাক্টের নাম</b></span></td>
\\n<td><span>Shin RED Cup (Super Spicy)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>নেট ওজন</b></span></td>
\\n<td><span>৬৮ গ্রাম (68g)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>প্যাকেজিং টাইপ</b></span></td>
\\n<td><span>ওয়ান-টাইম ইউজড কাপ (ভেতরে ফর্ক যুক্ত)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>উৎপাদনকারী দেশ</b></span></td>
\\n<td><span>সাউথ কোরিয়া (South Korea)</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>টাইপ</b></span></td>
\\n<td><span>ইনস্ট্যান্ট স্পাইসি স্যুপ নুডলস</span></td>
\\n</tr>
\\n<tr>
\\n<td><span><b>সার্টিফিকেশন</b></span></td>
\\n<td><span>হালাল (Halal)</span></td>
\\n</tr>
\\n</tbody>
\\n</table>
\\n<h3>💡 সহজে তৈরি করার সঠিক নিয়ম:</h3>
\\n<p>১. কাপের ওপরের ঢাকনাটি অর্ধেক পর্যন্ত টেনে খুলুন।</p>
\\n<p>২. ভেতরে থাকা সিজনিং পাউডারের প্যাকেটটি কেটে নুডলসের ওপর সম্পূর্ণ ঢেলে দিন।</p>
\\n<p>৩. কাপের ভেতরের নির্দিষ্ট দাগ (Indication Line) পর্যন্ত ফুটন্ত গরম জল যোগ করুন।</p>
\\n<p>৪. ঢাকনাটি আবার বন্ধ করে ৩ মিনিট অপেক্ষা করুন।</p>
\\n<p>৫. ঢাকনা পুরোটা ফেলে দিয়ে চামচ দিয়ে ভালোভাবে নেড়ে মিশিয়ে নিলেই রেডি ধোঁয়া ওঠা এক্সট্রা স্পাইসি শিন রেড রামেন!</p>',
                'regular_price' => 180,
                'sale_price' => null,
                'stock_quantity' => 100,
                'weight' => null,
                'unit' => 'pcs',
                'product_type' => 'simple',
                'images' => [
                    'https://prosanatelier.niyamulpratiti.com/wp-content/uploads/2026/07/Nongshim-Shin-RED-Cup-Halal.webp',
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'variations' => [
                ],
                'brand_slug' => 'nongshim',
            ],
        ];

        foreach ($products as $item) {
            $product = Product::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $categoryModels[$item['category_slug']]->id ?? null,
                    'brand_id' => $brandModels[$item['brand_slug']]->id ?? null,
                    'name' => $item['name'],
                    'sku' => $item['sku'],
                    'short_description' => $item['short_description'],
                    'description' => $item['description'],
                    'regular_price' => $item['regular_price'],
                    'sale_price' => $item['sale_price'],
                    'stock_quantity' => $item['stock_quantity'],
                    'low_stock_alert' => 5,
                    'weight' => $item['weight'],
                    'unit' => $item['unit'],
                    'product_type' => $item['product_type'],
                    'is_featured' => $item['is_featured'],
                    'is_new_arrival' => $item['is_new_arrival'],
                    'is_best_seller' => $item['is_best_seller'],
                    'is_active' => true,
                    'meta_title' => $item['name'],
                    'meta_description' => $item['short_description'],
                ]
            );

            $product->images()->delete();
            foreach ($item['images'] as $index => $imageUrl) {
                $product->images()->create([
                    'path' => $imageUrl,
                    'alt_text' => $product->name,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }

            $product->variations()->delete();
            foreach ($item['variations'] as $variation) {
                $product->variations()->create([
                    'name' => $variation['name'],
                    'sku' => $variation['sku'],
                    'regular_price' => $variation['regular_price'],
                    'sale_price' => $variation['sale_price'],
                    'stock_quantity' => $variation['stock_quantity'],
                    'weight' => $variation['weight'],
                    'unit' => $variation['unit'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
