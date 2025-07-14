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

        // Get products for Special Deals (first 4 products)
        $specialDeals = Product::with('vendor', 'category')
            ->where('status', 'active')
            ->limit(4)
            ->get();

        // Get products for New Releases (next 4 products)
        $newReleases = Product::with('vendor', 'category')
            ->where('status', 'active')
            ->offset(4)
            ->limit(4)
            ->get();

        // Get Electronics products
        $electronics = Product::with('vendor', 'category')
            ->where('category_id', 1) // Electronics category ID
            ->where('status', 'active')
            ->limit(5)
            ->get();

        // Get Fashion products
        $fashionProducts = Product::with('vendor', 'category')
            ->where('category_id', 2) // Fashion category ID
            ->where('status', 'active')
            ->limit(4)
            ->get();

        // Get Home & Garden products
        $homeGardenProducts = Product::with('vendor', 'category')
            ->where('category_id', 3) // Home & Garden category ID
            ->where('status', 'active')
            ->limit(4)
            ->get();

        // Get Gaming products
        $gamingProducts = Product::with('vendor', 'category')
            ->where('category_id', 4) // Gaming category ID
            ->where('status', 'active')
            ->limit(4)
            ->get();

        // Get Photography products
        $photographyProducts = Product::with('vendor', 'category')
            ->where('category_id', 5) // Photography category ID
            ->where('status', 'active')
            ->limit(4)
            ->get();

        // Get Audio products
        $audioProducts = Product::with('vendor', 'category')
            ->where('category_id', 6) // Audio category ID
            ->where('status', 'active')
            ->limit(4)
            ->get();

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