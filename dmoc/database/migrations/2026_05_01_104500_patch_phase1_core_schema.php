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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('email');
            }

            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('client')->after('phone');
                $table->index('role');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'delivery_zone_id')) {
                $table->foreignId('delivery_zone_id')->nullable()->after('payment_method');
            }

            $table->foreign('delivery_zone_id')->references('id')->on('zones')->nullOnDelete();
        });

        Schema::table('carts', function (Blueprint $table) {
            if (! Schema::hasColumn('carts', 'user_id')) {
                $table->foreignId('user_id')->unique()->after('id')->constrained()->cascadeOnDelete();
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (! Schema::hasColumn('cart_items', 'cart_id')) {
                $table->foreignId('cart_id')->after('id')->constrained('carts')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('cart_items', 'product_id')) {
                $table->foreignId('product_id')->after('cart_id')->constrained('products')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('cart_items', 'quantity')) {
                $table->unsignedInteger('quantity')->default(1)->after('product_id');
            }

            if (! Schema::hasColumn('cart_items', 'unit_price_xof')) {
                $table->unsignedInteger('unit_price_xof')->default(0)->after('quantity');
            }

            $table->unique(['cart_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'cart_id') && Schema::hasColumn('cart_items', 'product_id')) {
                $table->dropUnique('cart_items_cart_id_product_id_unique');
            }

            if (Schema::hasColumn('cart_items', 'cart_id')) {
                $table->dropConstrainedForeignId('cart_id');
            }

            if (Schema::hasColumn('cart_items', 'product_id')) {
                $table->dropConstrainedForeignId('product_id');
            }

            if (Schema::hasColumn('cart_items', 'quantity')) {
                $table->dropColumn('quantity');
            }

            if (Schema::hasColumn('cart_items', 'unit_price_xof')) {
                $table->dropColumn('unit_price_xof');
            }
        });

        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'delivery_zone_id')) {
                $table->dropForeign(['delivery_zone_id']);
                $table->dropColumn('delivery_zone_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropIndex(['role']);
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
};
