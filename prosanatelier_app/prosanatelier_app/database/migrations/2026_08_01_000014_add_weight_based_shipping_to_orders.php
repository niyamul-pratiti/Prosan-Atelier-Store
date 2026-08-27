<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $addParcelWeight = ! Schema::hasColumn('orders', 'parcel_weight_grams');
        $addManualFlag = ! Schema::hasColumn('orders', 'shipping_manually_set');

        Schema::table('orders', function (Blueprint $table) use ($addParcelWeight, $addManualFlag) {
            if ($addParcelWeight) {
                $table->unsignedInteger('parcel_weight_grams')->default(0)->after('shipping_zone');
            }

            if ($addManualFlag) {
                $table->boolean('shipping_manually_set')->default(false)->after('shipping_total');
            }
        });

        if (Schema::hasTable('site_settings')) {
            $now = now();
            $settings = [
                ['key' => 'weight_based_shipping_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'shipping', 'label' => 'Weight Based Shipping Enabled'],
                ['key' => 'shipping_included_weight_grams', 'value' => '1000', 'type' => 'number', 'group' => 'shipping', 'label' => 'Included Weight (grams)'],
                ['key' => 'shipping_packaging_weight_grams', 'value' => '200', 'type' => 'number', 'group' => 'shipping', 'label' => 'Packaging Weight Buffer (grams)'],
                ['key' => 'shipping_additional_per_kg', 'value' => '20', 'type' => 'number', 'group' => 'shipping', 'label' => 'Additional Charge per kg'],
            ];

            foreach ($settings as $setting) {
                DB::table('site_settings')->updateOrInsert(
                    ['key' => $setting['key']],
                    [...$setting, 'updated_at' => $now, 'created_at' => $now]
                );
            }

            DB::table('site_settings')
                ->where('key', 'inside_dhaka_shipping')
                ->where('value', '60')
                ->update(['value' => '70', 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        $columns = collect(['parcel_weight_grams', 'shipping_manually_set'])
            ->filter(fn ($column) => Schema::hasColumn('orders', $column))
            ->values()
            ->all();

        Schema::table('orders', function (Blueprint $table) use ($columns) {
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
