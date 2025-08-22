<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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

    /**
 * Updated orders method with better filtering and search
 */
public function orders(Request $request)
{
    $user = Auth::user();
    
    // Check if user is a vendor
    $vendor = Vendor::where('user_id', $user->id)->first();
    
    if (!$vendor) {
        return redirect()->route('test.account.profile')->with('error', 'You are not registered as a seller.');
    }
    
    // Get filter parameters
    $status = $request->get('status', 'all');
    $search = $request->get('search', '');
    
    // Build query for orders containing seller's products
    $query = Order::whereHas('orderItems', function($q) use ($vendor) {
        $q->where('vendor_id', $vendor->id);
    })
    ->with(['orderItems' => function($q) use ($vendor) {
        $q->where('vendor_id', $vendor->id)->with('product');
    }, 'customer'])
    ->orderBy('created_at', 'desc');
    
    // Apply status filter - filter by order items status
    if ($status !== 'all') {
        $query->whereHas('orderItems', function($q) use ($vendor, $status) {
            $q->where('vendor_id', $vendor->id)->where('status', $status);
        });
    }
    
    // Apply search filter
    if (!empty($search)) {
        $query->where(function($q) use ($search, $vendor) {
            $q->where('order_number', 'LIKE', "%{$search}%")
              ->orWhere('customer_name', 'LIKE', "%{$search}%")
              ->orWhere('customer_email', 'LIKE', "%{$search}%")
              ->orWhereHas('orderItems', function($orderItemQuery) use ($search, $vendor) {
                  $orderItemQuery->where('vendor_id', $vendor->id)
                                ->where('product_name', 'LIKE', "%{$search}%");
              });
        });
    }
    
    // Paginate results (10 per page)
    $orders = $query->paginate(10)->withQueryString();
    
    // Get status counts for filter pills
    $statusCounts = $this->getSellerStatusCounts($vendor->id);
    
    // Handle AJAX requests
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'html' => view('account.seller.partials.orders-list', compact('orders', 'vendor'))->render(),
            'pagination' => view('account.seller.partials.orders-pagination', compact('orders'))->render()
        ]);
    }
    
    return view('account.seller.orders', compact('orders', 'vendor', 'statusCounts', 'status', 'search'));
}

/**
 * Updated status counts method with better performance
 */
private function getSellerStatusCounts($vendorId)
{
    // Get all order items for this vendor
    $orderItems = OrderItem::where('vendor_id', $vendorId)
                          ->select('status', DB::raw('count(*) as count'))
                          ->groupBy('status')
                          ->pluck('count', 'status');
    
    // Calculate total unique orders containing vendor's items
    $totalOrders = Order::whereHas('orderItems', function($q) use ($vendorId) {
        $q->where('vendor_id', $vendorId);
    })->count();
    
    return [
        'all' => $totalOrders,
        'pending' => $orderItems->get('pending', 0),
        'processing' => $orderItems->get('processing', 0),
        'shipped' => $orderItems->get('shipped', 0),
        'delivered' => $orderItems->get('delivered', 0),
        'cancelled' => $orderItems->get('cancelled', 0),
    ];
}

/**
 * Update order item status and sync order status
 */
public function updateOrderStatus(Request $request, $orderItemId)
{
    $user = Auth::user();
    $vendor = Vendor::where('user_id', $user->id)->first();
    
    if (!$vendor) {
        return response()->json(['success' => false, 'message' => 'Vendor not found']);
    }
    
    $orderItem = OrderItem::where('id', $orderItemId)
                         ->where('vendor_id', $vendor->id)
                         ->with('order')
                         ->first();
    
    if (!$orderItem) {
        return response()->json(['success' => false, 'message' => 'Order item not found']);
    }
    
    $newStatus = $request->get('status');
    
    // Validate status transitions
    $allowedTransitions = [
        'pending' => 'processing',
        'processing' => 'shipped'
    ];
    
    if (!isset($allowedTransitions[$orderItem->status]) || 
        $allowedTransitions[$orderItem->status] !== $newStatus) {
        return response()->json(['success' => false, 'message' => 'Invalid status transition']);
    }
    
    // Update the order item status
    $orderItem->update([
        'status' => $newStatus,
        'vendor_confirmed_at' => $newStatus === 'processing' ? now() : $orderItem->vendor_confirmed_at,
        'shipped_at' => $newStatus === 'shipped' ? now() : $orderItem->shipped_at,
    ]);
    
    // Update the main order status based on all order items
    $this->updateMainOrderStatus($orderItem->order);
    
    $statusMessages = [
        'processing' => 'Order marked as processing',
        'shipped' => 'Order marked as shipped'
    ];
    
    return response()->json([
        'success' => true,
        'message' => $statusMessages[$newStatus],
        'new_status' => $newStatus
    ]);
}

/**
 * Update main order status based on all order items
 */
private function updateMainOrderStatus($order)
{
    // Get all order items for this order
    $allOrderItems = $order->orderItems;
    
    // Count items by status
    $statusCounts = $allOrderItems->groupBy('status')->map->count();
    $totalItems = $allOrderItems->count();
    
    // Determine order status based on item statuses
    $newOrderStatus = 'pending'; // default
    
    if ($statusCounts->get('cancelled', 0) === $totalItems) {
        // All items cancelled
        $newOrderStatus = 'cancelled';
    } elseif ($statusCounts->get('delivered', 0) === $totalItems) {
        // All items delivered
        $newOrderStatus = 'delivered';
    } elseif ($statusCounts->get('shipped', 0) + $statusCounts->get('delivered', 0) === $totalItems) {
        // All items shipped or delivered
        $newOrderStatus = 'shipped';
    } elseif ($statusCounts->get('processing', 0) + $statusCounts->get('shipped', 0) + $statusCounts->get('delivered', 0) === $totalItems) {
        // All items processing or higher
        $newOrderStatus = 'processing';
    }
    // else remains 'pending' if any item is still pending
    
    // Update order status if it changed
    if ($order->status !== $newOrderStatus) {
        $order->update(['status' => $newOrderStatus]);
    }
}

/**
 * AJAX search for seller orders
 */
public function searchOrders(Request $request)
{
    return $this->orders($request);
}
}