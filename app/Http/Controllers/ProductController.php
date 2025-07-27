<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index(){
        // TODO: Fetch all products from database
        // TODO: Add search and filtering functionality
        // TODO: Add pagination for better performance
        return 'All Product page';
    }

    public function category($category){
        // TODO: Fetch products by category from database
        // TODO: Validate category exists
        // TODO: Add sorting options (price, name, date)
        return "Category: " . $category;
    }

    public function show($id)
{
    $userAccount = Auth::user();

    // Find the product by ID with all variant relationships
    $product = Product::with([
        'vendor', 
        'category', 
        'variants.attributeValues.attribute',
        'defaultVariant.attributeValues.attribute'
    ])->find($id);
    
    // If product doesn't exist, show 404
    if (!$product) {
        abort(404);
    }
    
    // Return view with product data
    return view('show', compact('product', 'userAccount'));
}

    
}