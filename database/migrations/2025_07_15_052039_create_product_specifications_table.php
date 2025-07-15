<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('spec_name', 100); // e.g., "Weight", "Dimensions", "Material"
            $table->text('spec_value'); // e.g., "1.2kg", "15cm x 10cm x 5cm", "Aluminum"
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index(['product_id', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};