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
        Schema::table('addresses', function (Blueprint $table) {
            // Add country column
            $table->string('country')->default('Philippines')->after('postal_code');
            
            // Change type enum to allow more labels
            $table->dropColumn('type');
        });
        
        // Add the label column as string instead of enum
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('label')->default('Home')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['country', 'label']);
            $table->enum('type', ['home', 'office'])->default('home')->after('user_id');
        });
    }
};