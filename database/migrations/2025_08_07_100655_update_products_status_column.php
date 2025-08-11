<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('status', 20)->change(); // Change from ENUM to VARCHAR
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
