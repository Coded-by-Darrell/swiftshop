<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorProductController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('homepage');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/{category}', [ProductController::class, 'category'])->name('products.category');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.id');

Route::get('/seller/{id}', function($id){
    return "Seller ID: " . $id ;
})->name('seller.show');

Route::get('/login', function(){
    return 'Welcome to Swiftshop, please log in';
})->name('login');

Route::get('/register', function(){
    return 'Welcome to swiftshop! New here? Sign up now';
})->name('register');

Route::get('password/reset', function(){
    return 'Reset Password';
})->name('password.reset');

Route::post('/contact', function() {
    return 'Contact form submitted successfully!';
})->name('contact.store');


//Customer Routes (Protected)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function() {
        return 'Customer Dashboard - Login Required';
    })->name('customer.dashboard');
    
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





