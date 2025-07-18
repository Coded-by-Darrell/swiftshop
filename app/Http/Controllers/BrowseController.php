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
    if ($request->has('min_price') && $request->min_price !== null && $request->min_price !== '') {
        $minPrice = (float) $request->min_price;
        $query->where(function($q) use ($minPrice) {
            // Check variants first (for products with variants)
            $q->whereHas('variants', function($variantQuery) use ($minPrice) {
                $variantQuery->where(function($priceQuery) use ($minPrice) {
                    // Check sale price first, then original price
                    $priceQuery->where(function($saleQuery) use ($minPrice) {
                        $saleQuery->whereNotNull('sale_price')
                                  ->where('sale_price', '>=', $minPrice);
                    })->orWhere(function($originalQuery) use ($minPrice) {
                        $originalQuery->whereNull('sale_price')
                                      ->where('original_price', '>=', $minPrice);
                    });
                });
            })->orWhere(function($baseQuery) use ($minPrice) {
                // For products without variants, check base price
                $baseQuery->whereDoesntHave('variants')
                          ->where('price', '>=', $minPrice);
            });
        });
    }
    
    if ($request->has('max_price') && $request->max_price !== null && $request->max_price !== '') {
        $maxPrice = (float) $request->max_price;
        $query->where(function($q) use ($maxPrice) {
            // Check variants first (for products with variants)
            $q->whereHas('variants', function($variantQuery) use ($maxPrice) {
                $variantQuery->where(function($priceQuery) use ($maxPrice) {
                    // Check sale price first, then original price
                    $priceQuery->where(function($saleQuery) use ($maxPrice) {
                        $saleQuery->whereNotNull('sale_price')
                                  ->where('sale_price', '<=', $maxPrice);
                    })->orWhere(function($originalQuery) use ($maxPrice) {
                        $originalQuery->whereNull('sale_price')
                                      ->where('original_price', '<=', $maxPrice);
                    });
                });
            })->orWhere(function($baseQuery) use ($maxPrice) {
                // For products without variants, check base price
                $baseQuery->whereDoesntHave('variants')
                          ->where('price', '<=', $maxPrice);
            });
        });
    }
    
    // Apply brand filter if provided (vendor business names)
    if ($request->has('brands') && is_array($request->brands) && !empty($request->brands)) {
        $query->whereHas('vendor', function($q) use ($request) {
            $q->whereIn('business_name', $request->brands);
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
    
    // User account data for the layout
    $userAccount = [
        'firstName' => 'Darrell',
        'lastName' => 'Ocampo',
        'fullName' => 'Darrell Ocampo'
    ];
    
    return view('category', compact('category', 'products', 'subcategories', 'brands', 'userAccount'));
}

public function search(Request $request)
{
    $query = $request->input('q');
    $category = $request->input('category');
    $vendor = $request->input('vendor');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');
    $sortBy = $request->input('sort', 'name'); // default sort by name

    // Start building the query
    $productsQuery = Product::with(['vendor', 'category', 'variants.attributeValues.attribute']);

    // Search by name or description
    if ($query) {
        $productsQuery->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        });
    }

    // Filter by category
    if ($category) {
        $productsQuery->where('category_id', $category);
    }

    // Filter by vendor
    if ($vendor) {
        $productsQuery->where('vendor_id', $vendor);
    }

    // Filter by price range
    if ($minPrice) {
        $productsQuery->where(function($q) use ($minPrice) {
            $q->where('price', '>=', $minPrice)
              ->orWhereHas('variants', function($variant) use ($minPrice) {
                  $variant->where('price', '>=', $minPrice);
              });
        });
    }

    if ($maxPrice) {
        $productsQuery->where(function($q) use ($maxPrice) {
            $q->where('price', '<=', $maxPrice)
              ->orWhereHas('variants', function($variant) use ($maxPrice) {
                  $variant->where('price', '<=', $maxPrice);
              });
        });
    }

    // Sorting
    switch ($sortBy) {
        case 'price_low':
            $productsQuery->orderBy('price', 'asc');
            break;
        case 'price_high':
            $productsQuery->orderBy('price', 'desc');
            break;
        case 'newest':
            $productsQuery->orderBy('created_at', 'desc');
            break;
        case 'rating':
            $productsQuery->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
            break;
        default:
            $productsQuery->orderBy('name', 'asc');
    }

    $products = $productsQuery->paginate(12);

    // Get data for filters
    $categories = Category::all();
    $vendors = Vendor::all();

    $userAccount = [
        'firstName' => 'Darrell',
        'lastName' => 'Ocampo',
        'fullName' => 'Darrell Ocampo'
    ];

    

    return view('search', compact('products', 'categories', 'vendors', 'query', 'category', 'vendor', 'minPrice', 'maxPrice', 'sortBy','userAccount'));
}


}