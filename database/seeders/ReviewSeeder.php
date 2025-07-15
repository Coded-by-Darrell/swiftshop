<?php

namespace Database\Seeders;

use App\Models\ProductReview;
use App\Models\VendorReview;
use App\Models\User;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
{
    // Get some users, products, and vendors (without role filter)
    $users = User::take(10)->get();
    $products = Product::take(5)->get();
    $vendors = Vendor::take(3)->get();

    // Create product reviews
    foreach ($products as $product) {
        foreach ($users->random(rand(3, 7)) as $user) {
            ProductReview::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => rand(3, 5),
                'title' => $this->getRandomReviewTitle(),
                'content' => $this->getRandomReviewContent(),
                'verified_purchase' => rand(0, 1) ? true : false,
                'helpful_votes' => rand(0, 15),
            ]);
        }
    }

    // Create vendor reviews
    foreach ($vendors as $vendor) {
        foreach ($users->random(rand(5, 8)) as $user) {
            VendorReview::create([
                'user_id' => $user->id,
                'vendor_id' => $vendor->id,
                'rating' => rand(3, 5),
                'content' => $this->getRandomVendorReview(),
                'verified_purchase' => true,
            ]);
        }
    }
}

    private function getRandomReviewTitle()
    {
        $titles = [
            'Great product!',
            'Excellent quality',
            'Worth the money',
            'Highly recommended',
            'Perfect for my needs',
            'Good value',
            'Amazing quality'
        ];
        
        return $titles[array_rand($titles)];
    }

    private function getRandomReviewContent()
    {
        $reviews = [
            'This product exceeded my expectations. Great build quality and fast delivery.',
            'Very satisfied with this purchase. Works exactly as described.',
            'Good quality product. Arrived quickly and well packaged.',
            'Excellent value for money. Would definitely buy again.',
            'Perfect product! Exactly what I was looking for.',
            'Great quality and fast shipping. Highly recommend this seller.',
            'Amazing product quality. Very happy with my purchase.'
        ];
        
        return $reviews[array_rand($reviews)];
    }

    private function getRandomVendorReview()
    {
        $reviews = [
            'Excellent seller! Fast shipping and great communication.',
            'Very reliable vendor. Product was exactly as described.',
            'Great service! Quick responses and fast delivery.',
            'Trustworthy seller. Will definitely buy from them again.',
            'Professional service and quality products.',
            'Fast shipping and excellent customer service.',
            'Highly recommend this vendor. Great experience overall.'
        ];
        
        return $reviews[array_rand($reviews)];
    }
}