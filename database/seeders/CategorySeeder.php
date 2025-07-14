<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Clothing and fashion items'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home and garden products'],
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'Gaming equipment and accessories'],
            ['name' => 'Photography', 'slug' => 'photography', 'description' => 'Photography equipment'],
            ['name' => 'Audio', 'slug' => 'audio', 'description' => 'Audio equipment and accessories'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}