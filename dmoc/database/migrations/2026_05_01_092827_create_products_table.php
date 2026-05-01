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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique()->index();
            $table->longText('description')->nullable();
            $table->integer('price_xof');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->boolean('is_digital')->default(false);
            $table->string('image_path')->nullable();
            $table->integer('rating')->default(0);
            $table->timestamps();
            $table->index('category_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
