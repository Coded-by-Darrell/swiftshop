@extends('layouts.checkout')

@section('title', 'Secure Checkout - SwiftShop')

@section('content')
<!-- Checkout Header with Breadcrumbs - MOVED OUTSIDE -->
<div class="checkout-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb checkout-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('test.browse') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('test.cart.index') }}">Cart</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ol>
                </nav>
                <!-- Page Title -->
                <h1 class="checkout-title">Secure Checkout</h1>
            </div>
        </div>
    </div>
</div>

<div class="checkout-container">
    <div class="container">
        <div class="row">
            <!-- Left Column: Checkout Form -->
            <div class="col-lg-8">
                <form id="checkout-form">
                    @csrf
                    
                    <!-- Hidden fields that get populated by address selection -->
                    {{-- <input type="hidden" id="hidden_contact_email" name="contact_email" value=""> --}}
                    <input type="hidden" id="hidden_full_name" name="full_name" value="">
                    <input type="hidden" id="hidden_phone_number" name="phone_number" value="">
                    <input type="hidden" id="hidden_street_address" name="street_address" value="">
                    <input type="hidden" id="hidden_city" name="city" value="">
                    <input type="hidden" id="hidden_postal_code" name="postal_code" value="">

                    <!-- Shipping Address Section -->
                    <div class="checkout-form-section">
                        <h3 class="section-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Delivery Address
                        </h3>
                        
                        <!-- Saved Addresses -->
                        <div class="saved-addresses">
                            @if(count($savedAddresses) > 0)
                                @foreach($savedAddresses as $address)
                                    <div class="address-option {{ $address['is_default'] ? 'selected' : '' }}" 
                                        onclick="selectAddress({{ $address['id'] }})"
                                        data-full-name="{{ $address['full_name'] }}"
                                        data-phone="{{ $address['phone_number'] }}"
                                        data-street="{{ $address['street_address'] }}"
                                        data-city="{{ $address['city'] }}"
                                        data-postal="{{ $address['postal_code'] }}">
                                        <input type="radio" name="delivery_address" value="{{ $address['id'] }}" 
                                            {{ $address['is_default'] ? 'checked' : '' }} style="display: none;">
                                        <div class="address-label">
                                            {{ $address['label'] }}
                                            @if($address['is_default'])
                                                <span class="badge bg-primary ms-2">Default</span>
                                            @endif
                                        </div>
                                        <p class="address-details">
                                            {{ $address['full_name'] }}<br>
                                            {{ $address['street_address'] }}<br>
                                            {{ $address['city'] }}, {{ $address['postal_code'] }}<br>
                                            <small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $address['phone_number'] }}</small>
                                        </p>
                                    </div>
                                @endforeach
        
                                <!-- Link to Address Book for authenticated users -->
                                @auth
                                    <div class="text-center mt-3">
                                        <a href="{{ route('test.account.address-book') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit me-2"></i>Manage Addresses
                                        </a>
                                    </div>
                                @endauth
                            @else
                                @auth
                                    <!-- No addresses for logged in user -->
                                    <div class="no-addresses-prompt text-center py-4">
                                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">No saved addresses found</h6>
                                        <p class="text-muted mb-3">Please add an address to continue</p>
                                        <a href="{{ route('test.account.address-book') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Add Address
                                        </a>
                                    </div>
                                @endauth
                            @endif
                        </div>
                        
                       
                        
                       <!-- New Address Form (Hidden by default) -->
                        <div id="new-address-form" style="display: none;" class="mt-3">
                            <div class="mb-3">
                                <label for="address_label" class="form-label">Address Label</label>
                                <input type="text" class="form-control" id="address_label" name="address_label" 
                                    placeholder="e.g., Home, Office, etc.">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                        value="{{ $userAccount }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                        placeholder="+63 XXX XXX XXXX" required>
                                </div>
                            </div>
                            
                            {{-- <div class="mb-3">
                                <label for="contact_email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                    placeholder="john.doe@example.com" required>
                            </div> --}}
                            
                            <div class="mb-3">
                                <label for="street_address" class="form-label">Street Address *</label>
                                <input type="text" class="form-control" id="street_address" name="street_address" 
                                    placeholder="House/Unit No., Street Name, Barangay" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control" id="city" name="city" 
                                        value="Bauan" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="postal_code" class="form-label">Postal Code *</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                        value="4201" required>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="save_address" name="save_address">
                                <label class="form-check-label" for="save_address">
                                    Save this address for future orders
                                </label>
                            </div>
                            <!-- Add Save Address Button -->
                            <div class="d-flex gap-2 mt-3">
                                <button type="button" class="btn btn-primary" onclick="saveAndUseAddress()">
                                    <i class="fas fa-save me-2"></i>Save & Use This Address
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="hideNewAddressForm()">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method Section -->
                    <div class="checkout-form-section">
                        <h3 class="section-title">
                            <i class="fas fa-truck"></i>
                            Shipping Method
                        </h3>
                        
                        <div class="shipping-options">
                            <div class="shipping-option selected" onclick="selectShipping('standard')">
                                <input type="radio" name="shipping_method" value="standard" checked>
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="shipping-name">Standard Delivery</div>
                                        <p class="shipping-desc">3-5 business days</p>
                                    </div>
                                    <div class="shipping-price">₱95</div>
                                </div>
                            </div>
                            
                            <div class="shipping-option" onclick="selectShipping('express')">
                                <input type="radio" name="shipping_method" value="express">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="shipping-name">Express Delivery</div>
                                        <p class="shipping-desc">1-2 business days</p>
                                    </div>
                                    <div class="shipping-price">₱195</div>
                                </div>
                            </div>
                            
                            <div class="shipping-option" onclick="selectShipping('same_day')">
                                <input type="radio" name="shipping_method" value="same_day">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="shipping-name">Same Day Delivery</div>
                                        <p class="shipping-desc">Within 6 hours (Metro Manila only)</p>
                                    </div>
                                    <div class="shipping-price">₱295</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Section -->
                    <div class="checkout-form-section">
                        <h3 class="section-title">
                            <i class="fas fa-credit-card"></i>
                            Payment Method
                        </h3>
                        
                        <div class="payment-method-info">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-money-bill-wave text-success fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-1">Cash on Delivery (COD)</h5>
                                    <p class="mb-0 text-muted">Pay when you receive your order</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes Section -->
                    <div class="checkout-form-section">
                        <h3 class="section-title">
                            <i class="fas fa-sticky-note"></i>
                            Order Notes (Optional)
                        </h3>
                        
                        <div class="mb-3">
                            <textarea class="form-control" id="order_notes" name="order_notes" rows="3" 
                                      placeholder="Special instructions for delivery (e.g., gate code, landmark, preferred delivery time)"></textarea>
                        </div>
                    </div>

                    <!-- Security Indicators -->
                    <div class="security-indicators">
                        <div class="security-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>SSL Secured</span>
                        </div>
                        <div class="security-item">
                            <i class="fas fa-lock"></i>
                            <span>Safe Checkout</span>
                        </div>
                        <div class="security-item">
                            <i class="fas fa-user-shield"></i>
                            <span>Buyer Protection</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary-sidebar">
                    <div class="order-summary-card">
                        <!-- Summary Header -->
                        <div class="summary-header">
                            <h3 class="summary-title">Order Summary</h3>
                        </div>

                        <!-- Summary Body -->
                        <div class="summary-body">
                            <!-- Order Items by Vendor -->
                            @foreach($cartByVendor as $vendorCart)
                            <div class="vendor-order-section">
                                <div class="vendor-header">
                                    <i class="fas fa-store me-2"></i>{{ $vendorCart['vendor_name'] }}
                                </div>
                                
                                @foreach($vendorCart['items'] as $item)
                                <div class="order-item">
                                    <div class="item-image">
                                        @if($item['image'])
                                            <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}" 
                                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                                        @else
                                            <i class="fas fa-image text-muted"></i>
                                        @endif
                                    </div>
                                    
                                    <div class="item-details">
                                        <div class="item-name">{{ $item['product_name'] }}</div>
                                        <div class="item-qty-price">
                                            Qty: {{ $item['quantity'] }} × ₱{{ number_format($item['price'], 2) }}
                                        </div>
                                    </div>
                                    
                                    <div class="item-total">
                                        ₱{{ number_format($item['total'], 2) }}
                                    </div>
                                </div>
                                @endforeach
                                
                                <div class="px-3 py-2 bg-light">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Vendor Subtotal:</span>
                                        <span class="fw-bold">₱{{ number_format($vendorCart['subtotal'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Order Totals -->
                            <div class="mt-3">
                                <div class="summary-row">
                                    <span class="summary-label">Subtotal ({{ $cartTotals['total_items'] }} items)</span>
                                    <span class="summary-value">₱{{ number_format($cartTotals['subtotal'], 2) }}</span>
                                </div>
                                
                                <div class="summary-row">
                                    <span class="summary-label">Shipping Fee</span>
                                    <span class="summary-value shipping-fee">₱{{ number_format($cartTotals['shipping'], 2) }}</span>
                                </div>
                                
                                <div class="summary-row">
                                    <span class="summary-label">VAT (12%)</span>
                                    <span class="summary-value">₱{{ number_format($cartTotals['tax'], 2) }}</span>
                                </div>
                                
                                <div class="summary-row summary-total">
                                    <span class="summary-label">Total</span>
                                    <span class="summary-value total-amount">₱{{ number_format($cartTotals['total'], 2) }}</span>
                                </div>
                            </div>

                            <!-- Place Order Button -->
                            <button type="button" class="place-order-btn" onclick="placeOrder()">
                                <i class="fas fa-lock me-2"></i>
                                Place Order - ₱{{ number_format($cartTotals['total'], 2) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5>Processing Your Order...</h5>
                <p class="text-muted mb-0">Please don't close this window</p>
            </div>
        </div>
    </div>
</div>
@endsection