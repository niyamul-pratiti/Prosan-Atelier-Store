<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_sender_number')) {
                $table->string('payment_sender_number', 50)->nullable()->after('payment_status');
            }
            if (! Schema::hasColumn('orders', 'payment_transaction_id')) {
                $table->string('payment_transaction_id', 120)->nullable()->after('payment_sender_number');
            }
            if (! Schema::hasColumn('orders', 'payment_account')) {
                $table->string('payment_account', 150)->nullable()->after('payment_transaction_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_account')) {
                $table->dropColumn('payment_account');
            }
            if (Schema::hasColumn('orders', 'payment_transaction_id')) {
                $table->dropColumn('payment_transaction_id');
            }
            if (Schema::hasColumn('orders', 'payment_sender_number')) {
                $table->dropColumn('payment_sender_number');
            }
        });
    }
};
