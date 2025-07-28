@extends('layouts.auth')

@section('title', 'Shopping Cart - SwiftShop')

@section('content')

<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
<div class="cart-page">
    <!-- Cart Header -->
    <section class="cart-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="cart-title">Shopping Cart</h1>
                            <p class="cart-subtitle">{{ count($cartByVendor) > 0 ? $cartTotals['total_items'] . ' items from ' . count($cartByVendor) . ' sellers' : 'Your cart is empty' }}</p>
                        </div>
                        <a href="{{ route('test.browse') }}" class="continue-shopping-btn">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Content -->
    @if(count($cartByVendor) > 0)
    <section class="cart-content">
        <div class="container">
            <div class="row">
                <!-- Cart Items (Left Column) -->
                <div class="col-lg-8">
                    @foreach($cartByVendor as $vendorData)
                    <div class="vendor-section">
                        <!-- Vendor Header -->
                        <div class="vendor-header">
                            <div class="vendor-info">
                                <h3 class="vendor-name">{{ $vendorData['vendor_name'] }}</h3>
                                <i class="fas fa-check-circle verified-badge" title="Verified Seller"></i>
                            </div>
                        </div>

                        <!-- Cart Items for this Vendor -->
                        @foreach($vendorData['items'] as $item)
                        <div class="cart-item" data-cart-key="{{ $item['cart_key'] }}">
                            <div class="item-image">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                            
                            <div class="item-details">
                                <h4 class="item-name">{{ $item['product_name'] }}</h4>
                                <div class="item-price">₱{{ number_format($item['price'], 2) }}</div>
                                <p class="item-stock">{{ $item['max_quantity'] }} in stock</p>
                            </div>
                            
                            <div class="quantity-controls">
                                <div class="quantity-selector">
                                    <button class="quantity-btn quantity-decrease" data-cart-key="{{ $item['cart_key'] }}">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" 
                                           class="quantity-input" 
                                           value="{{ $item['quantity'] }}" 
                                           min="1" 
                                           max="{{ $item['max_quantity'] }}"
                                           data-cart-key="{{ $item['cart_key'] }}">
                                    <button class="quantity-btn quantity-increase" data-cart-key="{{ $item['cart_key'] }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                
                                <button class="remove-item-btn" data-cart-key="{{ $item['cart_key'] }}" title="Remove item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach

                        <!-- Vendor Order Summary -->
                        <div class="vendor-summary">
                            <div class="vendor-total">
                                <span class="vendor-total-label">Subtotal ({{ count($vendorData['items']) }} items):</span>
                                <span class="vendor-total-amount">₱{{ number_format($vendorData['subtotal'], 2) }}</span>
                            </div>
                            <button class="btn vendor-checkout-btn" data-vendor-id="{{ $vendorData['vendor_id'] }}">
                                Checkout from {{ $vendorData['vendor_name'] }}
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary Sidebar (Right Column) -->
                <div class="col-lg-4">
                    <div class="cart-sidebar">
                        <div class="order-summary-card">
                            <div class="summary-header">
                                <h3 class="summary-title">Total Order Summary</h3>
                            </div>
                            
                            <div class="summary-body">
                                <div class="summary-row">
                                    <span class="summary-label">Subtotal ({{ $cartTotals['total_items'] }} items):</span>
                                    <span class="summary-value">₱{{ number_format($cartTotals['subtotal'], 2) }}</span>
                                </div>
                                
                                <div class="summary-row">
                                    <span class="summary-label">Shipping:</span>
                                    <span class="summary-value">
                                        @if($cartTotals['shipping'] == 0)
                                            FREE
                                        @else
                                            ₱{{ number_format($cartTotals['shipping'], 2) }}
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="summary-row">
                                    <span class="summary-label">Tax (12% VAT):</span>
                                    <span class="summary-value">₱{{ number_format($cartTotals['tax'], 2) }}</span>
                                </div>
                                
                                <div class="summary-row summary-total">
                                    <span class="summary-label">Total:</span>
                                    <span class="summary-value">₱{{ number_format($cartTotals['total'], 2) }}</span>
                                </div>
                                
                                <button class="btn checkout-all-btn" id="checkout-all-items">
                                    Checkout All Selected Items
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Suggested Products -->
    <section class="suggested-products">
        <div class="container">
            <h2 class="suggested-title">You might also like</h2>
            
            <div class="row g-3">
                @foreach($suggestedProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="suggested-product-card">
                        <div class="suggested-product-image">
                            <i class="fas fa-image fa-2x text-muted"></i>
                        </div>
                        
                        <div class="suggested-product-details">
                            <h4 class="suggested-product-name">{{ $product['name'] }}</h4>
                            <p class="suggested-product-store">{{ $product['store'] }}</p>
                            <div class="suggested-product-price">₱{{ number_format($product['price'], 2) }}</div>
                            
                            <div class="suggested-product-actions">
                                <button class="btn btn-add-suggested" data-product-id="{{ $product['id'] }}">
                                    Add to Cart
                                </button>
                                <button class="btn btn-buy-suggested" data-product-id="{{ $product['id'] }}">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @else
    <!-- Empty Cart State -->
    <section class="empty-cart-section mb-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="empty-cart">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h2 class="empty-cart-title">Your cart is empty</h2>
                        <p class="empty-cart-message">
                            Looks like you haven't added any items to your cart yet. 
                            Start shopping to fill it up!
                        </p>
                        <a href="{{ route('test.browse') }}" class="start-shopping-btn">
                            Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>

<script src="{{ asset('js/cart.js') }}"></script>

@endsection


