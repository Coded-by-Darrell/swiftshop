<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\OrderItem;

class AccountController extends Controller
{
    /**
     * Show the profile information page
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('account.profile', compact('user'));
    }
    
    /**
     * Show edit profile page
     */
    public function editProfile()
    {
        $user = Auth::user();
        
        return view('account.edit-profile', compact('user'));
    }
    
    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
        ]);
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            // Note: These fields don't exist in your users table yet
            // You'll need to add them via migration if you want to store them
        ];
        
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $this->handleProfilePictureUpload($request->file('profile_picture'), $user);
            $updateData['profile_picture'] = $profilePicture;
        }
        
        $user->update($updateData);
        
        return redirect()->route('test.account.profile')->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Handle profile picture upload and processing (Using PHP GD Library)
     */
    private function handleProfilePictureUpload($file, $user)
    {
        // Delete old profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        
        // Generate unique filename
        $filename = 'profile_' . $user->id . '_' . time() . '.jpg'; // Always save as JPG
        $path = 'profile-pictures/' . $filename;
        
        // Create directory if it doesn't exist
        Storage::disk('public')->makeDirectory('profile-pictures');
        
        // Process and resize image using PHP GD
        $this->resizeImage($file->getPathname(), Storage::disk('public')->path($path), 300, 300);
        
        return $path;
    }
    
    /**
     * Resize image using PHP GD library
     */
    private function resizeImage($sourcePath, $destinationPath, $newWidth, $newHeight)
    {
        // Get original image info
        list($originalWidth, $originalHeight, $imageType) = getimagesize($sourcePath);
        
        // Create image resource from original
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $originalImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $originalImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $originalImage = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new \Exception('Unsupported image type');
        }
        
        // Calculate crop dimensions for square aspect ratio
        $cropSize = min($originalWidth, $originalHeight);
        $cropX = ($originalWidth - $cropSize) / 2;
        $cropY = ($originalHeight - $cropSize) / 2;
        
        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Enable alpha blending for PNG transparency
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        
        // Resize and crop image
        imagecopyresampled(
            $newImage, $originalImage,
            0, 0, $cropX, $cropY,
            $newWidth, $newHeight, $cropSize, $cropSize
        );
        
        // Save the new image as JPEG
        imagejpeg($newImage, $destinationPath, 90); // 90% quality
        
        // Clean up memory
        imagedestroy($originalImage);
        imagedestroy($newImage);
    }
    
    /**
     * Get user's profile picture URL
     */
    public function getProfilePictureUrl($user)
    {
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            return Storage::url($user->profile_picture);
        }
        
        return asset('images/default-profile-pic.jpg');
    }
    
    /**
     * Show apply as seller page
     */
    public function applyAsSeller()
    {
        $user = Auth::user();
        
        return view('account.apply-seller', compact('user'));
    }
    
    /**
     * Process seller application
     */
    public function submitSellerApplication(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string',
            'business_address' => 'required|string|max:500',
            'business_phone' => 'required|string|max:20',
            'business_email' => 'required|email|max:255',
            'tax_id' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'bank_name' => 'required|string|max:255',
            'years_in_business' => 'required|integer|min:0',
            'product_categories' => 'required|array|min:1',
            'business_description' => 'required|string|max:1000',
            'why_sell_here' => 'required|string|max:1000',
        ]);
        
        // Here you would typically create a seller application record
        // For now, we'll just redirect with a success message
        
        return redirect()->route('test.account.profile')->with('success', 'Seller application submitted successfully! We will review your application within 3-5 business days.');
    }
    
    /**
     * Show order history page
     */
    public function orderHistory(Request $request)
    {
        $user = Auth::user();
        
        // Get filter parameters
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        
        // Build query
        $query = Order::where('user_id', $user->id)
                     ->with(['orderItems.product', 'orderItems.vendor'])
                     ->orderBy('created_at', 'desc');
        
        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('orderItems', function($orderItemQuery) use ($search) {
                      $orderItemQuery->where('vendor_name', 'LIKE', "%{$search}%")
                                   ->orWhere('product_name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Paginate results
        $orders = $query->paginate(10)->withQueryString();
        
        // Get status counts for tabs
        $statusCounts = $this->getStatusCounts($user->id);
        
        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('account.partials.order-list', compact('orders'))->render(),
                'pagination' => $orders->links()->toHtml()
            ]);
        }
        
        return view('account.order-history', compact('orders', 'statusCounts', 'status', 'search'));
    }
    
    /**
     * Get count of orders by status
     */
    private function getStatusCounts($userId)
    {
        $baseQuery = Order::where('user_id', $userId);
        
        return [
            'all' => $baseQuery->count(),
            'pending' => $baseQuery->where('status', 'pending')->count(),
            'processing' => $baseQuery->where('status', 'processing')->count(),
            'delivered' => $baseQuery->where('status', 'delivered')->count(),
            'cancelled' => $baseQuery->where('status', 'cancelled')->count(),
        ];
    }
    
    /**
     * Show order details
     */
    public function showOrder($orderId)
    {
        $user = Auth::user();
        
        $order = Order::where('user_id', $user->id)
                     ->where('id', $orderId)
                     ->with(['orderItems.product', 'orderItems.vendor'])
                     ->firstOrFail();
        
        return view('account.order-details', compact('order'));
    }
    /**
     * Mark order as received (for delivered orders)
     */
    public function markAsReceived(Request $request, $orderId)
    {
        $user = Auth::user();
        
        $order = Order::where('user_id', $user->id)
                     ->where('id', $orderId)
                     ->where('status', 'delivered')
                     ->firstOrFail();
        
        // You can add a 'received_at' column to orders table if needed
        // For now, we'll just return a success response
        
        return response()->json([
            'success' => true,
            'message' => 'Order marked as received! Thank you for your confirmation.'
        ]);
    }
    
    /**
     * Cancel order (only for pending/processing orders)
     */
    public function cancelOrder(Request $request, $orderId)
    {
        $user = Auth::user();
        
        $order = Order::where('user_id', $user->id)
                     ->where('id', $orderId)
                     ->whereIn('status', ['pending', 'processing'])
                     ->firstOrFail();
        
        $order->update(['status' => 'cancelled']);
        
        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully!'
        ]);
    }
    
    /**
     * AJAX search for orders (separate endpoint for better organization)
     */
    public function searchOrders(Request $request)
    {
        return $this->orderHistory($request);
    }
    
    /**
     * Show address book page
     */
    public function addressBook()
    {
        // We'll implement this later
        return view('account.address-book');
    }
    
    /**
     * Show notifications page
     */
    public function notifications()
    {
        // We'll implement this later
        return view('account.notifications');
    }
    
    /**
     * Show account settings page
     */
    public function accountSettings()
    {
        // We'll implement this later (includes security settings)
        return view('account.account-settings');
    }
}