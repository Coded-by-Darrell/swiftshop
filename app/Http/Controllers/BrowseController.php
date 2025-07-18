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
                        'rating' => $product->averageRating(),
                        'reviewsCount' => $product->reviewsCount(),
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
                        'rating' => $product->averageRating(),
                        'reviewsCount' => $product->reviewsCount(),
                        'badge' => null
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
                        'rating' => $product->averageRating(),
                        'reviewsCount' => $product->reviewsCount(),
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
                        'rating' => $product->averageRating(),
                        'reviewsCount' => $product->reviewsCount(),
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
                        'rating' => $product->averageRating(),
                        'reviewsCount' => $product->reviewsCount(),
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
                        'rating' => $product->averageRating(),
                        'reviewsCount' => $product->reviewsCount(),
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
                        'rating' => $product->averageRating() > 0 ? $product->averageRating() : 4.5,
                        'reviewsCount' => $product->reviewsCount(),
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
                        'rating' => $product->averageRating(),
                        'reviewsCount' => $product->reviewsCount(),
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

    // Add this method to your BrowseController.php

        public function category($categorySlug, Request $request)
            {

                

                // Find the category by slug
                $category = Category::where('slug', $categorySlug)->firstOrFail();
                
                // Start building the query for products in this category
                $query = Product::with(['vendor', 'category', 'defaultVariant.attributeValues'])
                    ->where('category_id', $category->id)
                    ->where('status', 'active');
                
                // Apply price filter if provided
                if ($request->has('min_price') && $request->min_price !== null) {
                    $query->whereHas('variants', function($q) use ($request) {
                        $q->where('sale_price', '>=', $request->min_price)
                        ->orWhere(function($subQ) use ($request) {
                            $subQ->whereNull('sale_price')
                                ->where('original_price', '>=', $request->min_price);
                        });
                    });
                }
                
                if ($request->has('max_price') && $request->max_price !== null) {
                    $query->whereHas('variants', function($q) use ($request) {
                        $q->where('sale_price', '<=', $request->max_price)
                        ->orWhere(function($subQ) use ($request) {
                            $subQ->whereNull('sale_price')
                                ->where('original_price', '<=', $request->max_price);
                        });
                    });
                }
                
                // Apply rating filter if provided
                if ($request->has('rating') && is_array($request->rating)) {
                    $ratings = $request->rating;
                    $query->whereHas('reviews', function($q) use ($ratings) {
                        $q->whereIn('rating', $ratings);
                    });
                }
                
                // Apply brand filter if provided
                if ($request->has('brands') && is_array($request->brands)) {
                    $brands = $request->brands;
                    $query->whereHas('vendor', function($q) use ($brands) {
                        $q->whereIn('business_name', $brands);
                    });
                }
                
                // Get paginated products
                $products = $query->paginate(12)->appends($request->query());
                
                // Get subcategories for this category (using product names for now since we don't have subcategories table)
                $subcategories = Product::where('category_id', $category->id)
                    ->where('status', 'active')
                    ->pluck('name')
                    ->map(function($name) {
                        // Extract type from product name (e.g., "iPhone 15 Pro" -> "Smartphones")
                        if (str_contains(strtolower($name), 'iphone') || str_contains(strtolower($name), 'samsung galaxy')) {
                            return 'Smartphones & Tablets';
                        } elseif (str_contains(strtolower($name), 'macbook') || str_contains(strtolower($name), 'laptop')) {
                            return 'Laptops & Computers';
                        } elseif (str_contains(strtolower($name), 'headphone') || str_contains(strtolower($name), 'earphone')) {
                            return 'Audio & Headphones';
                        } elseif (str_contains(strtolower($name), 'gaming') || str_contains(strtolower($name), 'mouse')) {
                            return 'Gaming & Accessories';
                        } elseif (str_contains(strtolower($name), 'camera') || str_contains(strtolower($name), 'lens')) {
                            return 'Cameras & Photography';
                        } else {
                            return 'Smart Home';
                        }
                    })
                    ->unique()
                    ->values();
                
                // Get unique brands from vendors in this category
                $brands = Product::where('category_id', $category->id)
                    ->where('status', 'active')
                    ->with('vendor')
                    ->get()
                    ->pluck('vendor.business_name')
                    ->unique()
                    ->values();

                    $userAccount = [
                        'firstName' => 'Darrell',
                        'lastName' => 'Ocampo',
                        'fullName' => 'Darrell Ocampo'
                    ];
                
                return view('category', compact('category', 'products', 'subcategories', 'brands', 'userAccount'));
            }



}