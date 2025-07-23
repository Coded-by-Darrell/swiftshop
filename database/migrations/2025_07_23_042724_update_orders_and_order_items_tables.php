<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update orders table
        Schema::table('orders', function (Blueprint $table) {
            // Add missing columns for checkout system
            $table->string('order_number')->unique()->after('id');
            $table->string('customer_name')->after('user_id');
            $table->string('customer_email')->after('customer_name');
            $table->string('customer_phone')->after('customer_email');
            $table->string('shipping_method')->after('shipping_address');
            $table->text('order_notes')->nullable()->after('shipping_method');
            $table->decimal('subtotal', 10, 2)->after('order_notes');
            $table->decimal('shipping_fee', 10, 2)->after('subtotal');
            $table->decimal('tax_amount', 10, 2)->after('shipping_fee');
            $table->timestamp('estimated_delivery')->nullable()->after('delivered_at');
            
            // Modify existing columns
            $table->json('shipping_address')->change();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending')->change();
            
            // Add indexes
            $table->index(['customer_email', 'status']);
            $table->index('order_number');
        });

        // Update order_items table  
        Schema::table('order_items', function (Blueprint $table) {
            // Add missing columns
            $table->unsignedBigInteger('variant_id')->nullable()->after('vendor_id');
            $table->string('product_name')->after('variant_id');
            $table->string('vendor_name')->after('product_name');
            $table->json('product_snapshot')->nullable()->after('total_price');
            $table->enum('status', ['pending', 'processing', 'ready_for_pickup', 'shipped', 'delivered', 'cancelled'])->default('pending')->after('product_snapshot');
            $table->timestamp('vendor_confirmed_at')->nullable()->after('status');
            $table->timestamp('shipped_at')->nullable()->after('vendor_confirmed_at');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            
            // Rename column to match our system
            $table->renameColumn('price_per_item', 'unit_price');
            
            // Add indexes
            $table->index(['order_id', 'vendor_id']);
            $table->index(['vendor_id', 'status']);
        });
    }

    public function down()
    {
        // Remove added columns from orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'customer_name', 
                'customer_email',
                'customer_phone',
                'shipping_method',
                'order_notes',
                'subtotal',
                'shipping_fee', 
                'tax_amount',
                'estimated_delivery'
            ]);
            
            $table->dropIndex(['customer_email', 'status']);
            $table->dropIndex(['order_number']);
        });

        // Remove added columns from order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'variant_id',
                'product_name',
                'vendor_name', 
                'product_snapshot',
                'status',
                'vendor_confirmed_at',
                'shipped_at',
                'delivered_at'
            ]);
            
            $table->renameColumn('unit_price', 'price_per_item');
            
            $table->dropIndex(['order_id', 'vendor_id']);
            $table->dropIndex(['vendor_id', 'status']);
        });
    }
};