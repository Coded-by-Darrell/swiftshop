<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            // Note: These fields don't exist in your users table yet
            // You'll need to add them via migration if you want to store them
        ]);
        
        return redirect()->route('test.account.profile')->with('success', 'Profile updated successfully!');
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
    public function orderHistory()
    {
        // We'll implement this later
        return view('account.order-history');
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
