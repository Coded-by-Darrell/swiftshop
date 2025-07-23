@extends('layouts.checkout')

@section('title', 'Order Confirmation - SwiftShop')

@section('content')
<div class="confirmation-container">
    <div class="container">
        <!-- Success Header -->
        <div class="confirmation-header">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1 class="confirmation-title">Order Placed Successfully!</h1>
                    <p class="confirmation-subtitle">
                        Thank you for your purchase. Your order has been confirmed and is being processed.
                    </p>
                    
                    <!-- Order Number -->
                    <div class="order-number-card">
                        <div class="order-number-label">Order Number</div>
                        <div class="order-number">{{ $orderData['order_number'] }}</div>
                        <div class="order-date">{{ $orderData['created_at'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Order Details -->
            <div class="col-lg-8">
                <!-- Order Items by Vendor -->
                <div class="confirmation-section">
                    <h3 class="section-title">
                        <i class="fas fa-box me-2"></i>
                        Order Items
                    </h3>
                    
                    @foreach($orderData['vendor_groups'] as $vendorCart)
                    <div class="vendor-order-section">
                        <div class="vendor-header">
                            <i class="fas fa-store me-2"></i>{{ $vendorCart['vendor_name'] }}
                            <span class="vendor-status-badge">
                                <i class="fas fa-clock me-1"></i>Processing
                            </span>
                        </div>
                        
                        @foreach($vendorCart['items'] as $item)
                        <div class="confirmation-item">
                            <div class="item-image">
                                @if($item['image'])
                                    <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}" 
                                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                @else
                                    <i class="fas fa-image text-muted"></i>
                                @endif
                            </div>
                            
                            <div class="item-details">
                                <div class="item-name">{{ $item['product_name'] }}</div>
                                <div class="item-qty-price">
                                    Quantity: {{ $item['quantity'] }} × ₱{{ number_format($item['price'], 2) }}
                                </div>
                                <div class="item-status">
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                </div>
                            </div>
                            
                            <div class="item-total">
                                ₱{{ number_format($item['total'], 2) }}
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="vendor-summary">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Vendor Subtotal:</span>
                                <span class="fw-bold">₱{{ number_format($vendorCart['subtotal'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Delivery Information -->
                <div class="confirmation-section">
                    <h3 class="section-title">
                        <i class="fas fa-truck me-2"></i>
                        Delivery Information
                    </h3>
                    
                    <div class="delivery-info-card">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Shipping Method</label>
                                    <div class="info-value">
                                        <i class="fas fa-shipping-fast me-2"></i>
                                        {{ ucfirst(str_replace('_', ' ', $orderData['shipping_method'])) }}
                                    </div>
                                </div>
                                
                                <div class="info-group">
                                    <label>Estimated Delivery</label>
                                    <div class="info-value">
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ $orderData['estimated_delivery'] }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Delivery Address</label>
                                    <div class="info-value">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        {{ $orderData['shipping_address']['full_name'] }}<br>
                                        {{ $orderData['shipping_address']['street_address'] }}<br>
                                        {{ $orderData['shipping_address']['city'] }}, {{ $orderData['shipping_address']['postal_code'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($orderData['order_notes'])
                        <div class="info-group">
                            <label>Order Notes</label>
                            <div class="info-value">
                                <i class="fas fa-sticky-note me-2"></i>
                                {{ $orderData['order_notes'] }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- What's Next Section -->
                <div class="confirmation-section">
                    <h3 class="section-title">
                        <i class="fas fa-list-check me-2"></i>
                        What's Next?
                    </h3>
                    
                    <div class="next-steps">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <div class="step-title">Order Processing</div>
                                <div class="step-desc">The seller will prepare and pack your order within 1-2 business days.</div>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <div class="step-title">Shipment</div>
                                <div class="step-desc">Your order will be handed over to the delivery service.</div>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <div class="step-title">Delivery</div>
                                <div class="step-desc">Your order will be delivered to your specified address. Pay cash on delivery.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Summary & Actions -->
            <div class="col-lg-4">
                <div class="confirmation-sidebar">
                    <!-- Order Summary -->
                    <div class="summary-card">
                        <div class="summary-header">
                            <h4 class="summary-title">Order Summary</h4>
                        </div>
                        
                        <div class="summary-body">
                            <div class="summary-row">
                                <span class="summary-label">Subtotal ({{ count($orderData['cart_items']) }} items)</span>
                                <span class="summary-value">₱{{ number_format($orderData['subtotal'], 2) }}</span>
                            </div>
                            
                            <div class="summary-row">
                                <span class="summary-label">Shipping Fee</span>
                                <span class="summary-value">₱{{ number_format($orderData['shipping_fee'], 2) }}</span>
                            </div>
                            
                            <div class="summary-row">
                                <span class="summary-label">VAT (12%)</span>
                                <span class="summary-value">₱{{ number_format($orderData['tax_amount'], 2) }}</span>
                            </div>
                            
                            <div class="summary-row summary-total">
                                <span class="summary-label">Total</span>
                                <span class="summary-value">₱{{ number_format($orderData['total_amount'], 2) }}</span>
                            </div>
                            
                            <div class="payment-method">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    <span>{{ $orderData['payment_method'] }} (Cash on Delivery)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <a href="{{ route('test.browse') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Continue Shopping
                        </a>
                        
                        <button class="btn btn-outline-primary btn-block" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>
                            Print Order Details
                        </button>
                        
                        <a href="mailto:{{ $orderData['customer_email'] }}" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-envelope me-2"></i>
                            Email Order Details
                        </a>
                    </div>

                    <!-- Support Info -->
                    <div class="support-card">
                        <div class="support-header">
                            <i class="fas fa-headset me-2"></i>
                            Need Help?
                        </div>
                        <div class="support-body">
                            <p>If you have any questions about your order, please contact us:</p>
                            <div class="support-contact">
                                <div class="contact-item">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    <span>+63 917 123 4567</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    <span>support@swiftshop.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Confirmation Modal (for print) -->
<div class="d-none" id="printable-order">
    <div class="print-header">
        <h2>SwiftShop Order Confirmation</h2>
        <p>Order #{{ $orderData['order_number'] }} - {{ $orderData['created_at'] }}</p>
    </div>
    <!-- Add printable version content here -->
</div>
@endsection