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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('method', ['cinet', 'stripe', 'paypal', 'cod']);
            $table->integer('amount_xof');
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->string('transaction_id')->nullable()->index();
            $table->string('reference')->unique();
            $table->json('response_data')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
