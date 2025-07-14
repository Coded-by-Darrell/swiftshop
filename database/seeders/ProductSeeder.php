<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Special Deals - Electronics
            ['vendor_id' => 1, 'category_id' => 1, 'name' => 'Wireless Headphones', 'description' => 'High quality wireless headphones', 'price' => 79.99, 'stock_quantity' => 50, 'status' => 'active'],
            ['vendor_id' => 2, 'category_id' => 1, 'name' => 'Smart Watch', 'description' => 'Advanced smartwatch with health tracking', 'price' => 199.99, 'stock_quantity' => 30, 'status' => 'active'],
            ['vendor_id' => 3, 'category_id' => 4, 'name' => 'Gaming Mouse', 'description' => 'Professional gaming mouse', 'price' => 39.99, 'stock_quantity' => 75, 'status' => 'active'],
            ['vendor_id' => 4, 'category_id' => 1, 'name' => 'USB-C Cable', 'description' => 'Fast charging USB-C cable', 'price' => 12.99, 'stock_quantity' => 100, 'status' => 'active'],
            
            // New Releases
            ['vendor_id' => 5, 'category_id' => 1, 'name' => 'iPhone 15 Case', 'description' => 'Protective case for iPhone 15', 'price' => 29.99, 'stock_quantity' => 80, 'status' => 'active'],
            ['vendor_id' => 2, 'category_id' => 4, 'name' => 'Mechanical Keyboard', 'description' => 'RGB mechanical gaming keyboard', 'price' => 129.99, 'stock_quantity' => 25, 'status' => 'active'],
            ['vendor_id' => 6, 'category_id' => 5, 'name' => '4K Webcam', 'description' => '4K resolution webcam', 'price' => 89.99, 'stock_quantity' => 40, 'status' => 'active'],
            ['vendor_id' => 7, 'category_id' => 1, 'name' => 'Wireless Charger', 'description' => 'Fast wireless charging pad', 'price' => 34.99, 'stock_quantity' => 60, 'status' => 'active'],
            
            // Electronics
            ['vendor_id' => 5, 'category_id' => 1, 'name' => 'MacBook Pro', 'description' => 'Latest MacBook Pro laptop', 'price' => 1999.99, 'stock_quantity' => 10, 'status' => 'active'],
            ['vendor_id' => 5, 'category_id' => 1, 'name' => 'iPad Air', 'description' => 'iPad Air tablet', 'price' => 599.99, 'stock_quantity' => 20, 'status' => 'active'],
            ['vendor_id' => 5, 'category_id' => 6, 'name' => 'AirPods Pro', 'description' => 'Noise cancelling earbuds', 'price' => 249.99, 'stock_quantity' => 35, 'status' => 'active'],
            ['vendor_id' => 8, 'category_id' => 1, 'name' => 'Samsung Galaxy S24', 'description' => 'Latest Samsung smartphone', 'price' => 899.99, 'stock_quantity' => 15, 'status' => 'active'],
            
            // Fashion
            ['vendor_id' => 9, 'category_id' => 2, 'name' => 'Chrome Hearts Hoodie', 'description' => 'Premium designer hoodie', 'price' => 17999.99, 'stock_quantity' => 5, 'status' => 'active'],
            ['vendor_id' => 9, 'category_id' => 2, 'name' => 'Chrome Hearts Shirt', 'description' => 'Designer t-shirt', 'price' => 12999.99, 'stock_quantity' => 8, 'status' => 'active'],
            ['vendor_id' => 10, 'category_id' => 2, 'name' => 'Hoodie Double Zip', 'description' => 'Stylish double zip hoodie', 'price' => 1200.00, 'stock_quantity' => 20, 'status' => 'active'],
            ['vendor_id' => 11, 'category_id' => 2, 'name' => 'Hotwheels Shirt', 'description' => 'Hotwheels themed shirt', 'price' => 1500, 'stock_quantity' => 15, 'status' => 'active'],
            
            // Home & Garden products
            ['vendor_id' => 12, 'category_id' => 3, 'name' => 'Smart Plant Watering System', 'description' => 'Automated plant care system', 'price' => 89.99, 'stock_quantity' => 25, 'status' => 'active'],
            ['vendor_id' => 13, 'category_id' => 3, 'name' => 'LED String Lights 20ft', 'description' => 'Decorative LED string lights', 'price' => 24.99, 'stock_quantity' => 50, 'status' => 'active'],
            ['vendor_id' => 14, 'category_id' => 3, 'name' => 'Bamboo Cutting Board Set', 'description' => 'Eco-friendly kitchen cutting boards', 'price' => 45.99, 'stock_quantity' => 30, 'status' => 'active'],
            ['vendor_id' => 15, 'category_id' => 3, 'name' => 'Indoor Herb Garden Kit', 'description' => 'Complete herb growing kit', 'price' => 67.99, 'stock_quantity' => 20, 'status' => 'active'],

            // Gaming products
            ['vendor_id' => 16, 'category_id' => 4, 'name' => 'RGB Mechanical Keyboard', 'description' => 'Professional gaming keyboard with RGB', 'price' => 129.99, 'stock_quantity' => 15, 'status' => 'active'],
            ['vendor_id' => 17, 'category_id' => 4, 'name' => 'Wireless Gaming Controller', 'description' => 'High-performance wireless controller', 'price' => 59.99, 'stock_quantity' => 40, 'status' => 'active'],
            ['vendor_id' => 18, 'category_id' => 4, 'name' => 'Gaming Chair Ergonomic', 'description' => 'Comfortable ergonomic gaming chair', 'price' => 199.99, 'stock_quantity' => 10, 'status' => 'active'],
            ['vendor_id' => 19, 'category_id' => 4, 'name' => 'High-DPI Gaming Mouse Pad', 'description' => 'Professional gaming mouse pad', 'price' => 29.99, 'stock_quantity' => 60, 'status' => 'active'],

            // Photography products
            ['vendor_id' => 20, 'category_id' => 5, 'name' => 'Professional Camera Tripod', 'description' => 'Sturdy professional tripod', 'price' => 149.99, 'stock_quantity' => 12, 'status' => 'active'],
            ['vendor_id' => 21, 'category_id' => 5, 'name' => 'LED Ring Light 18 inch', 'description' => 'Professional LED ring light', 'price' => 89.99, 'stock_quantity' => 18, 'status' => 'active'],
            ['vendor_id' => 22, 'category_id' => 5, 'name' => 'Camera Lens Cleaning Kit', 'description' => 'Complete lens care kit', 'price' => 19.99, 'stock_quantity' => 45, 'status' => 'active'],
            ['vendor_id' => 23, 'category_id' => 5, 'name' => 'Wireless Camera Remote', 'description' => 'Remote control for cameras', 'price' => 34.99, 'stock_quantity' => 35, 'status' => 'active'],

            // Audio products
            ['vendor_id' => 24, 'category_id' => 6, 'name' => 'Studio Monitor Speakers', 'description' => 'Professional studio monitors', 'price' => 299.99, 'stock_quantity' => 8, 'status' => 'active'],
            ['vendor_id' => 25, 'category_id' => 6, 'name' => 'Wireless Earbuds Pro', 'description' => 'Premium wireless earbuds', 'price' => 79.99, 'stock_quantity' => 50, 'status' => 'active'],
            ['vendor_id' => 26, 'category_id' => 6, 'name' => 'USB Audio Interface', 'description' => 'Professional audio interface', 'price' => 159.99, 'stock_quantity' => 15, 'status' => 'active'],
            ['vendor_id' => 27, 'category_id' => 6, 'name' => 'Noise Cancelling Headphones', 'description' => 'Premium noise cancelling headphones', 'price' => 189.99, 'stock_quantity' => 22, 'status' => 'active'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}