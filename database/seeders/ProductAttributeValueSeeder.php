<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;

class ProductAttributeValueSeeder extends Seeder
{
    public function run()
    {
        // Get attribute IDs
        $colorAttr = ProductAttribute::where('name', 'Color')->first();
        $sizeAttr = ProductAttribute::where('name', 'Size')->first();
        $storageAttr = ProductAttribute::where('name', 'Storage')->first();
        $brandAttr = ProductAttribute::where('name', 'Brand')->first();
        $materialAttr = ProductAttribute::where('name', 'Material')->first();
        $connectivityAttr = ProductAttribute::where('name', 'Connectivity')->first();

        // Color values
        $colors = [
            ['value' => 'Black', 'display_value' => 'Black', 'sort_order' => 1],
            ['value' => 'White', 'display_value' => 'White', 'sort_order' => 2],
            ['value' => 'Red', 'display_value' => 'Red', 'sort_order' => 3],
            ['value' => 'Blue', 'display_value' => 'Blue', 'sort_order' => 4],
            ['value' => 'Green', 'display_value' => 'Green', 'sort_order' => 5],
            ['value' => 'Gray', 'display_value' => 'Gray', 'sort_order' => 6],
            ['value' => 'Silver', 'display_value' => 'Silver', 'sort_order' => 7],
            ['value' => 'Gold', 'display_value' => 'Gold', 'sort_order' => 8],
            ['value' => 'Pink', 'display_value' => 'Pink', 'sort_order' => 9],
            ['value' => 'Purple', 'display_value' => 'Purple', 'sort_order' => 10],
        ];

        foreach ($colors as $color) {
            ProductAttributeValue::create(array_merge($color, ['product_attribute_id' => $colorAttr->id]));
        }

        // Size values
        $sizes = [
            ['value' => 'XS', 'display_value' => 'Extra Small', 'sort_order' => 1],
            ['value' => 'S', 'display_value' => 'Small', 'sort_order' => 2],
            ['value' => 'M', 'display_value' => 'Medium', 'sort_order' => 3],
            ['value' => 'L', 'display_value' => 'Large', 'sort_order' => 4],
            ['value' => 'XL', 'display_value' => 'Extra Large', 'sort_order' => 5],
            ['value' => 'XXL', 'display_value' => 'Double Extra Large', 'sort_order' => 6],
        ];

        foreach ($sizes as $size) {
            ProductAttributeValue::create(array_merge($size, ['product_attribute_id' => $sizeAttr->id]));
        }

        // Storage values
        $storage = [
            ['value' => '64GB', 'display_value' => '64GB', 'sort_order' => 1],
            ['value' => '128GB', 'display_value' => '128GB', 'sort_order' => 2],
            ['value' => '256GB', 'display_value' => '256GB', 'sort_order' => 3],
            ['value' => '512GB', 'display_value' => '512GB', 'sort_order' => 4],
            ['value' => '1TB', 'display_value' => '1TB', 'sort_order' => 5],
            ['value' => '2TB', 'display_value' => '2TB', 'sort_order' => 6],
        ];

        foreach ($storage as $stor) {
            ProductAttributeValue::create(array_merge($stor, ['product_attribute_id' => $storageAttr->id]));
        }

        // Brand values
        $brands = [
            ['value' => 'Apple', 'display_value' => 'Apple', 'sort_order' => 1],
            ['value' => 'Samsung', 'display_value' => 'Samsung', 'sort_order' => 2],
            ['value' => 'Sony', 'display_value' => 'Sony', 'sort_order' => 3],
            ['value' => 'Nike', 'display_value' => 'Nike', 'sort_order' => 4],
            ['value' => 'Adidas', 'display_value' => 'Adidas', 'sort_order' => 5],
            ['value' => 'Generic', 'display_value' => 'Generic', 'sort_order' => 6],
        ];

        foreach ($brands as $brand) {
            ProductAttributeValue::create(array_merge($brand, ['product_attribute_id' => $brandAttr->id]));
        }

        // Material values
        $materials = [
            ['value' => 'Cotton', 'display_value' => 'Cotton', 'sort_order' => 1],
            ['value' => 'Polyester', 'display_value' => 'Polyester', 'sort_order' => 2],
            ['value' => 'Plastic', 'display_value' => 'Plastic', 'sort_order' => 3],
            ['value' => 'Metal', 'display_value' => 'Metal', 'sort_order' => 4],
            ['value' => 'Leather', 'display_value' => 'Leather', 'sort_order' => 5],
            ['value' => 'Bamboo', 'display_value' => 'Bamboo', 'sort_order' => 6],
        ];

        foreach ($materials as $material) {
            ProductAttributeValue::create(array_merge($material, ['product_attribute_id' => $materialAttr->id]));
        }

        // Connectivity values
        $connectivity = [
            ['value' => 'Wireless', 'display_value' => 'Wireless', 'sort_order' => 1],
            ['value' => 'Bluetooth', 'display_value' => 'Bluetooth', 'sort_order' => 2],
            ['value' => 'USB-C', 'display_value' => 'USB-C', 'sort_order' => 3],
            ['value' => 'Lightning', 'display_value' => 'Lightning', 'sort_order' => 4],
            ['value' => '3.5mm', 'display_value' => '3.5mm Jack', 'sort_order' => 5],
            ['value' => 'WiFi', 'display_value' => 'WiFi', 'sort_order' => 6],
        ];

        foreach ($connectivity as $conn) {
            ProductAttributeValue::create(array_merge($conn, ['product_attribute_id' => $connectivityAttr->id]));
        }
    }
}