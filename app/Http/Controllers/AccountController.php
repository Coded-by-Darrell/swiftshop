<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;  // Add this line
use Illuminate\Support\Facades\DB;    // Add this line
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderItem;
use Jenssegers\Agent\Agent;           // Add this line
use Exception;    

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
 * Updated markAsReceived method
 */
public function markAsReceived(Request $request, $orderId)
{
    $user = Auth::user();
    
    $order = Order::where('user_id', $user->id)
                 ->where('id', $orderId)
                 ->where('status', 'shipped')  // Changed from 'delivered' to 'shipped'
                 ->firstOrFail();
    
    // Update all order items to delivered
    $order->orderItems()->update([
        'status' => 'delivered',
        'delivered_at' => now()
    ]);
    
    // Update main order status to delivered
    $order->update([
        'status' => 'delivered',
        'delivered_at' => now()
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Order received! Thank you for confirming delivery.'
    ]);
}

/**
 * Updated cancelOrder method - only allow pending orders
 */
public function cancelOrder(Request $request, $orderId)
{
    $user = Auth::user();
    
    $order = Order::where('user_id', $user->id)
                 ->where('id', $orderId)
                 ->where('status', 'pending')  // Only pending orders can be cancelled
                 ->firstOrFail();
    
    // Update all order items to cancelled
    $order->orderItems()->update(['status' => 'cancelled']);
    
    // Update main order status
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
    $user = Auth::user();
    $addresses = Address::getUserAddresses($user->id);
    
    return view('account.address-book', compact('addresses'));
}
    
    /**
     * Show notifications page
     */
    public function notifications()
    {
        // We'll implement this later
        return view('account.notifications');
    }
    

    public function storeAddress(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'label' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'boolean'
        ]);
        
        if ($request->boolean('is_default')) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }
        
        $address = Address::create([
            'user_id' => $user->id,
            'label' => $request->label,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country ?? 'Philippines',
            'is_default' => $request->boolean('is_default')
        ]);
        
        if (Address::where('user_id', $user->id)->count() === 1) {
            $address->update(['is_default' => true]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Address added successfully!'
        ]);
    }

    public function getAddress($addressId)
    {
        $user = Auth::user();
        
        $address = Address::where('user_id', $user->id)
                        ->where('id', $addressId)
                        ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }

    public function updateAddress(Request $request, $addressId)
{
    $user = Auth::user();
    
    $address = Address::where('user_id', $user->id)
                     ->where('id', $addressId)
                     ->firstOrFail();
    
    $request->validate([
        'label' => 'required|string|max:255',
        'full_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address_line_1' => 'required|string|max:500',
        'address_line_2' => 'nullable|string|max:500',
        'city' => 'required|string|max:255',
        'postal_code' => 'required|string|max:20',
        'is_default' => 'boolean'
    ]);
    
    if ($request->boolean('is_default')) {
        Address::where('user_id', $user->id)
               ->where('id', '!=', $addressId)
               ->update(['is_default' => false]);
    }
    
    $address->update($request->only([
        'label', 'full_name', 'phone', 'address_line_1', 
        'address_line_2', 'city', 'postal_code', 'is_default'
    ]));
    
    return response()->json([
        'success' => true,
        'message' => 'Address updated successfully!'
    ]);
}

public function deleteAddress($addressId)
{
    $user = Auth::user();
    
    $address = Address::where('user_id', $user->id)
                     ->where('id', $addressId)
                     ->firstOrFail();
    
    if ($address->is_default && Address::where('user_id', $user->id)->count() === 1) {
        return response()->json([
            'success' => false,
            'message' => 'Cannot delete your only address.'
        ]);
    }
    
    if ($address->is_default) {
        $nextAddress = Address::where('user_id', $user->id)
                             ->where('id', '!=', $addressId)
                             ->first();
        if ($nextAddress) {
            $nextAddress->update(['is_default' => true]);
        }
    }
    
    $address->delete();
    
    return response()->json([
        'success' => true,
        'message' => 'Address deleted successfully!'
    ]);
}

/**
 * Show account settings page
 */
public function accountSettings()
{
    $user = Auth::user();
    $loginActivities = $this->getLoginActivities($user);
    
    return view('account.account-settings', compact('loginActivities'));
}

/**
 * Change user password
 */
public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
        'new_password_confirmation' => 'required',
    ]);

    $user = Auth::user();

    // Check if current password is correct
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'The current password is incorrect.']);
    }

    // Update password
    $user->update([
        'password' => Hash::make($request->new_password)
    ]);

    return back()->with('success', 'Password changed successfully!');
}

/**
 * Get user real login activities from sessions table
 */
private function getLoginActivities($user)
{
    $sessions = DB::table('sessions')
                 ->where('user_id', $user->id)
                 ->orderBy('last_activity', 'desc')
                 ->get();

    $activities = [];
    $currentSessionId = session()->getId();

    foreach ($sessions as $session) {
        $isCurrentSession = $session->id === $currentSessionId;
        
        $activities[] = [
            'session_id' => $session->id,
            'device' => $this->getDeviceInfo($session->user_agent ?? ''),
            'device_icon' => $this->getDeviceIcon($session->user_agent ?? ''),
            'location' => $this->getLocationFromIP($session->ip_address),
            'ip_address' => $session->ip_address,
            'login_time' => $this->formatLoginTime($session->last_activity),
            'is_current' => $isCurrentSession,
        ];
    }

    return collect($activities);
}

/**
 * Get device info from user agent
 */
private function getDeviceInfo($userAgent)
{
    if (empty($userAgent)) {
        return 'Unknown Device';
    }

    $agent = new Agent();
    $agent->setUserAgent($userAgent);
    
    $browser = $agent->browser() ?: 'Unknown Browser';
    $platform = $agent->platform() ?: 'Unknown OS';
    $device = $agent->device() ?: '';
    
    if ($device && $device !== 'WebKit') {
        return "{$browser} on {$device}";
    }
    
    return "{$browser} on {$platform}";
}

/**
 * Get device icon from user agent
 */
private function getDeviceIcon($userAgent)
{
    if (empty($userAgent)) {
        return 'desktop';
    }

    $agent = new Agent();
    $agent->setUserAgent($userAgent);
    
    if ($agent->isMobile()) {
        return 'mobile-alt';
    } elseif ($agent->isTablet()) {
        return 'tablet-alt';
    } else {
        return 'desktop';
    }
}

/**
 * Get location from IP address
 */
private function getLocationFromIP($ip)
{
    // For local development, return default location
    if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.')) {
        return 'Batangas, Philippines';
    }
    
    try {
        // Use a free IP geolocation service
        $response = @file_get_contents("http://ip-api.com/json/{$ip}");
        if ($response) {
            $data = json_decode($response, true);
            
            if ($data && $data['status'] === 'success') {
                return $data['city'] . ', ' . $data['country'];
            }
        }
    } catch (Exception $e) {
        // Fallback if service fails
    }
    
    return 'Unknown location';
}

/**
 * Format login time
 */
private function formatLoginTime($timestamp)
{
    $loginTime = \Carbon\Carbon::createFromTimestamp($timestamp)->setTimezone('Asia/Manila');
    $now = now()->setTimezone('Asia/Manila');
    
    if ($loginTime->isToday()) {
        return 'Today at ' . $loginTime->format('g:i A');
    } elseif ($loginTime->isYesterday()) {
        return 'Yesterday at ' . $loginTime->format('g:i A');
    } elseif ($loginTime->diffInDays($now) <= 7) {
        return $loginTime->diffInDays($now) . ' days ago at ' . $loginTime->format('g:i A');
    } else {
        return $loginTime->format('M j, Y \a\t g:i A');
    }
}

/**
 * Terminate a session
 */
public function terminateSession(Request $request)
{
    $sessionId = $request->session_id;
    $currentSessionId = session()->getId();
    
    // Don't allow terminating current session
    if ($sessionId === $currentSessionId) {
        return response()->json([
            'success' => false,
            'message' => 'Cannot terminate your current session!'
        ]);
    }
    
    // Delete the session from database
    DB::table('sessions')->where('id', $sessionId)->delete();
    
    return response()->json([
        'success' => true,
        'message' => 'Session terminated successfully!'
    ]);
}

}