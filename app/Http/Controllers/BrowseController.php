<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;

class BrowseController extends Controller
{
    public function index()
    {
        $userAccount = [
            'firstName' => 'Darrell',
            'lastName' => 'Ocampo',
            'fullName' => 'Darrell Ocampo'
        ];

        // Get products for Special Deals (products with active discounts)
        $specialDeals = Product::with(['vendor', 'category', 'defaultVariant.attributeValues'])
            ->whereHas('variants', function($q) {
                $q->onSale();
            })
            ->where('status', 'active')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $discount = $product->getDiscountPercentage();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getDisplayPrice(),
                    'old_price' => $discount > 0 ? $product->getOriginalPrice() : null,
                    'store' => $product->vendor->business_name,
                    'rating' => 4.5,
                    'badge' => $discount > 0 ? $discount . '% OFF' : null
                ];
            });

        // Get products for New Releases (next 4 products)
        $newReleases = Product::with(['vendor', 'category', 'defaultVariant.attributeValues'])
            ->where('status', 'active')
            ->offset(4)
            ->limit(4)
            ->get()
            ->map(function($product) {
                $discount = $product->getDiscountPercentage();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getDisplayPrice(),
                    'old_price' => $discount > 0 ? $product->getOriginalPrice() : null,
                    'store' => $product->vendor->business_name,
                    'rating' => 4.3,
                    'badge' => null // New releases don't need sale badges
                ];
            });

        // Get Electronics products
        $electronics = Product::with(['vendor', 'category', 'defaultVariant.attributeValues'])
            ->where('category_id', 1)
            ->where('status', 'active')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $discount = $product->getDiscountPercentage();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getDisplayPrice(),
                    'old_price' => $discount > 0 ? $product->getOriginalPrice() : null,
                    'store' => $product->vendor->business_name,
                    'rating' => 4.6,
                    'badge' => $discount > 0 ? $discount . '% OFF' : null
                ];
            });

        // Get Fashion products
        $fashionProducts = Product::with(['vendor', 'category', 'defaultVariant.attributeValues'])
            ->where('category_id', 2)
            ->where('status', 'active')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $discount = $product->getDiscountPercentage();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getDisplayPrice(),
                    'old_price' => $discount > 0 ? $product->getOriginalPrice() : null,
                    'store' => $product->vendor->business_name,
                    'rating' => 4.4,
                    'badge' => $discount > 0 ? $discount . '% OFF' : null
                ];
            });

        // Get Home & Garden products
        $homeGardenProducts = Product::with(['vendor', 'category', 'defaultVariant.attributeValues'])
            ->where('category_id', 3)
            ->where('status', 'active')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $discount = $product->getDiscountPercentage();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getDisplayPrice(),
                    'old_price' => $discount > 0 ? $product->getOriginalPrice() : null,
                    'store' => $product->vendor->business_name,
                    'rating' => 4.2,
                    'badge' => $discount > 0 ? $discount . '% OFF' : null
                ];
            });

        // Get Gaming products
        $gamingProducts = Product::with(['vendor', 'category', 'defaultVariant.attributeValues'])
            ->where('category_id', 4)
            ->where('status', 'active')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $discount = $product->getDiscountPercentage();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getDisplayPrice(),
                    'old_price' => $discount > 0 ? $product->getOriginalPrice() : null,
                    'store' => $product->vendor->business_name,
                    'rating' => 4.7,
                    'badge' => $discount > 0 ? $discount . '% OFF' : null
                ];
            });

        // Get Photography products
        $photographyProducts = Product::with(['vendor', 'category', 'defaultVariant.attributeValues'])
            ->where('category_id', 5)
            ->where('status', 'active')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $discount = $product->getDiscountPercentage();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getDisplayPrice(),
                    'old_price' => $discount > 0 ? $product->getOriginalPrice() : null,
                    'store' => $product->vendor->business_name,
                    'rating' => 4.5,
                    'badge' => $discount > 0 ? $discount . '% OFF' : null
                ];
            });

        // Get Audio products
        $audioProducts = Product::with(['vendor', 'category', 'defaultVariant.attributeValues'])
            ->where('category_id', 6)
            ->where('status', 'active')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $discount = $product->getDiscountPercentage();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getDisplayPrice(),
                    'old_price' => $discount > 0 ? $product->getOriginalPrice() : null,
                    'store' => $product->vendor->business_name,
                    'rating' => 4.8,
                    'badge' => $discount > 0 ? $discount . '% OFF' : null
                ];
            });

        // Get all categories for navigation
        $categories = Category::all();

        return view('browse.index', compact(
            'userAccount',
            'specialDeals',
            'newReleases', 
            'electronics',
            'fashionProducts',
            'homeGardenProducts',
            'gamingProducts',
            'photographyProducts',
            'audioProducts',
            'categories'
        ));
    }
}