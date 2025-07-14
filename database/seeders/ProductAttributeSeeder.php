<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;

class ProductAttributeSeeder extends Seeder
{
    public function run()
    {
        $attributes = [
            ['name' => 'Color', 'type' => 'select', 'required' => true, 'sort_order' => 1],
            ['name' => 'Size', 'type' => 'select', 'required' => false, 'sort_order' => 2],
            ['name' => 'Storage', 'type' => 'select', 'required' => false, 'sort_order' => 3],
            ['name' => 'Brand', 'type' => 'select', 'required' => false, 'sort_order' => 4],
            ['name' => 'Material', 'type' => 'select', 'required' => false, 'sort_order' => 5],
            ['name' => 'Connectivity', 'type' => 'select', 'required' => false, 'sort_order' => 6],
        ];

        foreach ($attributes as $attribute) {
            ProductAttribute::create($attribute);
        }
    }
}