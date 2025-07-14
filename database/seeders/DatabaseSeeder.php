<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,                    // FIRST - create users
            VendorSeeder::class,                  // SECOND - create vendors (needs user_id)
            CategorySeeder::class,                // THIRD - create categories
            ProductSeeder::class,                 // FOURTH - create products (needs vendor_id and category_id)
            ProductAttributeSeeder::class,        // FIFTH - create attributes (Color, Size, etc.)
            ProductAttributeValueSeeder::class,   // SIXTH - create attribute values (Red, Blue, Large, etc.)
            ProductVariantSeeder::class,          // SEVENTH - create product variants with attributes
        ]);
    }
}