<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorProductController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('homepage');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/{category}', [ProductController::class, 'category'])->name('products.category');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.id');

Route::get('/seller/{id}', function($id){
    return "Seller ID: " . $id ;
})->name('seller.show');


Route::post('/contact', function() {
    return 'Contact form submitted successfully!';
})->name('contact.store');

//Customer Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/browse', [BrowseController::class, 'index'])->name('browse');

    Route::get('/cart', function() {
        return 'Shopping Cart - Login Required';
    })->name('cart.index');

    Route::get('/checkout', function() {
        return 'Checkout Process - Login Required';
    })->name('checkout.index');
    
    Route::get('/orders', function() {
        return 'Order History - Login Required';
    })->name('orders.index');
    
    Route::get('/wishlist', function() {
        return 'My Wishlist - Login Required';
    })->name('wishlist.index');
    
    Route::get('/account', function() {
        return 'Account Management - Login Required';
    })->name('account.index');
});

//Vendor Routes (Protected):
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [VendorController::class, 'orders'])->name('orders.index');
    Route::get('/products', [VendorProductController::class, 'index'])->name('products.index');
});

//Admin Routes (Protected):
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function() {
        return 'Admin Dashboard';
    })->name('dashboard');
    
    Route::get('/vendors', function() {
        return 'Vendor Management';
    })->name('vendors.index');
    
    Route::get('/users', function() {
        return 'User Management';
    })->name('users.index');
});


/// Test routes for category functionality
Route::get('/test-browse', [BrowseController::class, 'index'])->name('test.browse');
Route::get('/category/{categorySlug}', [BrowseController::class, 'category'])->name('category.show');
Route::get('/test-product/{id}', [ProductController::class, 'show'])->name('test.product'); // Changed this
Route::get('/search', [BrowseController::class, 'search'])->name('search');
/// Test Cart Routes (Public for development phase)
Route::prefix('test-cart')->name('test.cart.')->group(function () {
    // Cart display page
    Route::get('/', [CartController::class, 'index'])->name('index');
    
    // Cart AJAX operations
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update', [CartController::class, 'update'])->name('update');
    Route::delete('/remove', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    
    // Get cart count for navbar badge
    Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
});

/// Test Checkout Routes (Public for development phase)
Route::prefix('test-checkout')->name('test.checkout.')->group(function () {
    // Checkout page
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    
    // Buy Now functionality
    Route::post('/buy-now', [CheckoutController::class, 'buyNow'])->name('buy-now');
    
    // Cart checkout functionality
    Route::post('/checkout-vendor', [CheckoutController::class, 'checkoutVendor'])->name('checkout-vendor');
    Route::post('/checkout-all', [CheckoutController::class, 'checkoutAll'])->name('checkout-all');
    
    // Process checkout
    Route::post('/place-order', [CheckoutController::class, 'store'])->name('place-order');
    
    // Order confirmation
    Route::get('/confirmation', [CheckoutController::class, 'confirmation'])->name('confirmation');
    
    // Address management
    Route::post('/save-address', [CheckoutController::class, 'saveAddress'])->name('save-address');
    Route::get('/addresses', [CheckoutController::class, 'getAddresses'])->name('addresses');
    Route::post('/update-shipping', [CheckoutController::class, 'updateShipping'])->name('update-shipping');
});

/// Test Account Routes (Public for development phase)
Route::prefix('test-account')->name('test.account.')->group(function () {
    // Account profile page
    Route::get('/profile', [App\Http\Controllers\AccountController::class, 'profile'])->name('profile');
    
    // Other account pages (we'll implement these later)
    Route::get('/order-history', [App\Http\Controllers\AccountController::class, 'orderHistory'])->name('order-history');
    Route::get('/address-book', [App\Http\Controllers\AccountController::class, 'addressBook'])->name('address-book');
    Route::get('/notifications', [App\Http\Controllers\AccountController::class, 'notifications'])->name('notifications');
    Route::get('/settings', [App\Http\Controllers\AccountController::class, 'accountSettings'])->name('settings');
});

// Test account redirect
Route::get('/test-account', function() {
    return redirect()->route('test.account.profile');
})->name('test.account.index');

/// Test Account Routes (Public for development phase)
Route::prefix('test-account')->name('test.account.')->group(function () {
    // Account profile page
    Route::get('/profile', [App\Http\Controllers\AccountController::class, 'profile'])->name('profile');
    
    // Edit profile routes
    Route::get('/edit-profile', [App\Http\Controllers\AccountController::class, 'editProfile'])->name('edit-profile');
    Route::put('/update-profile', [App\Http\Controllers\AccountController::class, 'updateProfile'])->name('update-profile');
    
    // Other account pages (we'll implement these later)
    Route::get('/order-history', [App\Http\Controllers\AccountController::class, 'orderHistory'])->name('order-history');
    Route::get('/address-book', [App\Http\Controllers\AccountController::class, 'addressBook'])->name('address-book');
    Route::get('/notifications', [App\Http\Controllers\AccountController::class, 'notifications'])->name('notifications');
    Route::get('/settings', [App\Http\Controllers\AccountController::class, 'accountSettings'])->name('settings');
});

require __DIR__.'/auth.php';
