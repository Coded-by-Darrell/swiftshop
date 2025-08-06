<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class SellerController extends Controller
{
    /**
     * Show seller overview dashboard
     */
    public function overview()
    {
        $user = Auth::user();
        
        // Check if user is a vendor
        $vendor = Vendor::where('user_id', $user->id)->first();
        
        if (!$vendor) {
            return redirect()->route('test.account.profile')->with('error', 'You are not registered as a seller.');
        }
        
        // Calculate metrics
        $totalRevenue = $this->calculateTotalRevenue($vendor->id);
        $activeProducts = $this->getActiveProductsCount($vendor->id);
        $soldOrders = $this->getSoldOrdersCount($vendor->id);
        $pendingOrders = $this->getPendingOrdersCount($vendor->id);
        
        // Get recent orders (last 5)
        $recentOrders = $this->getRecentOrders($vendor->id);
        
        return view('account.seller.overview', compact(
            'vendor', 
            'totalRevenue', 
            'activeProducts', 
            'soldOrders', 
            'pendingOrders',
            'recentOrders'
        ));
    }
    
    /**
     * Toggle store status
     */
    public function toggleStoreStatus(Request $request)
    {
        $user = Auth::user();
        $vendor = Vendor::where('user_id', $user->id)->first();
        
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor not found']);
        }
        
        $vendor->store_active = !$vendor->store_active;
        $vendor->save();
        
        $status = $vendor->store_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true,
            'message' => "Store has been {$status}",
            'store_active' => $vendor->store_active
        ]);
    }
    
/**
 * Calculate total revenue for vendor
 */
private function calculateTotalRevenue($vendorId)
{
    return OrderItem::where('vendor_id', $vendorId)
                   ->whereHas('order', function($query) {
                       $query->where('status', 'delivered');
                   })
                   ->sum('total_price');
}
    
    /**
     * Get active products count
     */
    private function getActiveProductsCount($vendorId)
    {
        return Product::where('vendor_id', $vendorId)
                     ->where('status', 'active')
                     ->count();
    }
    
    /**
     * Get sold orders count (delivered orders)
     */
    private function getSoldOrdersCount($vendorId)
    {
        return OrderItem::where('vendor_id', $vendorId)
                       ->whereHas('order', function($query) {
                           $query->where('status', 'delivered');
                       })
                       ->count();
    }
    
    /**
     * Get pending orders count (pending + processing)
     */
    private function getPendingOrdersCount($vendorId)
    {
        return OrderItem::where('vendor_id', $vendorId)
                       ->whereHas('order', function($query) {
                           $query->whereIn('status', ['pending', 'processing']);
                       })
                       ->count();
    }
    
    /**
     * Get recent orders for vendor
     */
    private function getRecentOrders($vendorId)
    {
        return OrderItem::where('vendor_id', $vendorId)
                       ->with(['order', 'product'])
                       ->orderBy('created_at', 'desc')
                       ->limit(5)
                       ->get();
    }
}