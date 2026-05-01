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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('total_xof');
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'shipped', 'delivered'])->default('pending');
            $table->enum('payment_method', ['cinet', 'stripe', 'paypal', 'cod'])->nullable();
            $table->foreignId('delivery_zone_id')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
