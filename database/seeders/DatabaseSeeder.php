<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,      // FIRST - create users
            VendorSeeder::class,    // SECOND - create vendors (needs user_id)
            CategorySeeder::class,  // THIRD - create categories
            ProductSeeder::class,   // LAST - create products (needs vendor_id and category_id)
        ]);
    }
}