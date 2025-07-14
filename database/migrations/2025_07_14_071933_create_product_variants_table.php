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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique(); // Unique identifier for this variant
            $table->decimal('original_price', 10, 2); // Regular price
            $table->decimal('sale_price', 10, 2)->nullable(); // Discounted price
            $table->boolean('is_on_sale')->default(false);
            $table->datetime('sale_start_date')->nullable();
            $table->datetime('sale_end_date')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->decimal('weight', 8, 2)->nullable(); // For shipping calculations
            $table->json('dimensions')->nullable(); // Length, width, height
            $table->string('barcode')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false); // One variant per product should be default
            $table->timestamps();
            
            $table->index(['product_id', 'is_active']);
            $table->index(['is_on_sale', 'sale_start_date', 'sale_end_date']);
            $table->index('stock_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
