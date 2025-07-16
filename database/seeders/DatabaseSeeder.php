<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
{
    $this->call([
        UserSeeder::class,
        VendorSeeder::class,
        CategorySeeder::class,
        ProductSeeder::class,
        ProductAttributeSeeder::class,
        ProductAttributeValueSeeder::class,
        ProductVariantSeeder::class,
        ReviewSeeder::class,
        ProductSpecificationSeeder::class, // ADD THIS LINE
    ]);
}
}