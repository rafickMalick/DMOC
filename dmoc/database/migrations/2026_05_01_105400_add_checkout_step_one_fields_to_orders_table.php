<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'delivery_fee_xof')) {
                $table->unsignedInteger('delivery_fee_xof')->default(0)->after('total_xof');
            }

            if (! Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('estimated_delivery');
            }

            if (! Schema::hasColumn('orders', 'shipping_phone')) {
                $table->string('shipping_phone', 30)->nullable()->after('shipping_address');
            }

            if (! Schema::hasColumn('orders', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('shipping_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'recipient_name')) {
                $table->dropColumn('recipient_name');
            }

            if (Schema::hasColumn('orders', 'shipping_phone')) {
                $table->dropColumn('shipping_phone');
            }

            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }

            if (Schema::hasColumn('orders', 'delivery_fee_xof')) {
                $table->dropColumn('delivery_fee_xof');
            }
        });
    }
};
