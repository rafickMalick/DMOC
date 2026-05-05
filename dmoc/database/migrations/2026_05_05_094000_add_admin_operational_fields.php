<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('image_path');
            }
        });

        Schema::table('couriers', function (Blueprint $table) {
            if (! Schema::hasColumn('couriers', 'delivery_zone')) {
                $table->string('delivery_zone')->nullable()->after('vehicle_plate');
            }

            if (! Schema::hasColumn('couriers', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('current_location');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'cancelled_reason')) {
                $table->text('cancelled_reason')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'cancelled_reason')) {
                $table->dropColumn('cancelled_reason');
            }
        });

        Schema::table('couriers', function (Blueprint $table) {
            if (Schema::hasColumn('couriers', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }

            if (Schema::hasColumn('couriers', 'delivery_zone')) {
                $table->dropColumn('delivery_zone');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
