<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorProductController extends Controller
{
    /**
     * Display vendor's products with search and filtering
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is a vendor
        $vendor = Vendor::where('user_id', $user->id)->first();
        
        if (!$vendor) {
            return redirect()->route('test.account.profile')->with('error', 'You are not registered as a seller.');
        }
        
        // Start query for vendor's products
        $query = Product::where('vendor_id', $vendor->id)
                       ->with(['category', 'images', 'defaultVariant']);
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

         // Only show products from active stores in search (add this line)
         $query->whereHas('vendor', function($vendorQuery) {
            $vendorQuery->where('store_active', true);
        });
        
        // Apply status filter
        $filter = $request->get('filter', 'all');
        switch ($filter) {
            case 'active':
                $query->where('status', 'active');
                break;
            case 'inactive':
                $query->where('status', 'inactive');
                break;
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'out_of_stock':
                $query->where(function($q) {
                    $q->where('stock_quantity', '<=', 0)
                      ->orWhereDoesntHave('variants')
                      ->orWhereHas('variants', function($variant) {
                          $variant->where('stock_quantity', '<=', 0);
                      });
                });
                break;
        }
        
        // Order by creation date (newest first)
        $query->orderBy('created_at', 'desc');
        
        // Paginate results (10 per page)
        $products = $query->paginate(10);
        
        // Get filter counts for UI
        $filterCounts = $this->getFilterCounts($vendor->id);

       
        
        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('account.seller.partials.products-list', compact('products'))->render(),
                'pagination' => view('account.seller.partials.products-pagination', compact('products'))->render()
            ]);
        }
        
        return view('account.seller.products', compact('products', 'vendor', 'filterCounts'));
    }
    
    /**
     * Get counts for each filter
     */
    private function getFilterCounts($vendorId)
    {
        $baseQuery = Product::where('vendor_id', $vendorId);
        
        return [
            'all' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('status', 'active')->count(),
            'inactive' => (clone $baseQuery)->where('status', 'inactive')->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'out_of_stock' => (clone $baseQuery)->where(function($q) {
                $q->where('stock_quantity', '<=', 0)
                  ->orWhereDoesntHave('variants')
                  ->orWhereHas('variants', function($variant) {
                      $variant->where('stock_quantity', '<=', 0);
                  });
            })->count(),
        ];
    }
    
    /**
     * Show form to create new product
     */
    public function create()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'html' => view('account.seller.partials.add-product-modal', compact('categories'))->render()
        ]);
    }
    
    /**
     * Store new product
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->first();
        
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'You are not registered as a seller.']);
        }
        
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        try {
            // Create product
            $product = Product::create([
                'vendor_id' => $vendor->id,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'status' => 'pending' // New products need admin approval
            ]);
            
            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('products', $imageName, 'public');
                    
                    $product->images()->create([
                        'image_path' => $imagePath,
                        'alt_text' => $product->name
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully! Please wait for admin approval.',
                'product_id' => $product->id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Toggle product visibility
     */
    public function toggleVisibility(Request $request, $id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->first();
        
        $product = Product::where('id', $id)
                         ->where('vendor_id', $vendor->id)
                         ->first();
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
        
        // Don't allow visibility toggle for pending products
        if ($product->status === 'pending') {
            return response()->json(['success' => false, 'message' => 'Cannot change visibility of pending products']);
        }
        
        // Toggle between active and inactive
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();
        
        return response()->json([
            'success' => true,
            'status' => $product->status,
            'message' => 'Product visibility updated'
        ]);
    }
    
    /**
     * Get product for editing
     */
    public function edit($id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->first();
        
        $product = Product::with(['category', 'images'])
                         ->where('id', $id)
                         ->where('vendor_id', $vendor->id)
                         ->first();
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
        
        // Don't allow editing of pending products
        if ($product->status === 'pending') {
            return response()->json(['success' => false, 'message' => 'Cannot edit pending products']);
        }
        
        $categories = Category::all();
        
        return response()->json([
            'success' => true,
            'html' => view('account.seller.partials.edit-product-modal', compact('product', 'categories'))->render()
        ]);
    }
    
    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->first();
        
        $product = Product::where('id', $id)
                         ->where('vendor_id', $vendor->id)
                         ->first();
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
        
        if ($product->status === 'pending') {
            return response()->json(['success' => false, 'message' => 'Cannot edit pending products']);
        }
        
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        try {
            // Update product
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
            ]);
            
            // Handle new image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('products', $imageName, 'public');
                    
                    $product->images()->create([
                        'image_path' => $imagePath,
                        'alt_text' => $product->name
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete product
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->first();
        
        $product = Product::with('images')->where('id', $id)
                         ->where('vendor_id', $vendor->id)
                         ->first();
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
        
        try {
            // Delete associated images from storage
            if ($product->images && $product->images->count() > 0) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            
            // Delete product (this will cascade delete images due to foreign key)
            $product->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ]);
        }
    }
}