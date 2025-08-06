@extends('layouts.account')

@section('title', 'Order Details - SwiftShop')

@section('content')
<div class="container account-container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card account-sidebar">
                <div class="card-header">
                    <h5>My Account</h5>
                    <p>Manage your account settings and preferences</p>
                </div>
                
                <div class="sidebar-divider"></div>
                
                <div class="list-group list-group-flush">
                    <a href="{{ route('test.account.profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i>
                        Profile Information
                    </a>
                    <a href="{{ route('test.account.order-history') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-history me-2"></i>
                        Order History
                    </a>
                    <a href="{{ route('test.account.address-book') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Address Book
                    </a>
                    
                    <a href="{{ route('test.account.settings') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-cog me-2"></i>
                        Account Settings
                    </a>

                    @if(Auth::user()->isVendor())
                    <div class="sidebar-divider"></div>
                    <a href="{{ route('test.seller.overview') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Seller Overview
                    </a>
                    <a href="{{ route('test.seller.products') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-box me-2"></i>
                        My Products
                    </a>
                    <a href="{{ route('test.seller.orders') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Seller Orders
                    </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Back to Order History -->
            <div class="mb-3">
                <a href="{{ route('test.account.order-history') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Order History
                </a>
            </div>
            
            <div class="card account-main-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Order Details</h5>
                            <p class="text-muted mb-0">{{ $order->order_number }}</p>
                        </div>
                        
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Order Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="section-title">Order Information</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">Order Date:</span>
                                    <span class="value">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Payment Method:</span>
                                    <span class="value">{{ $order->payment_method }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Shipping Method:</span>
                                    <span class="value">{{ ucfirst(str_replace('_', ' ', $order->shipping_method)) }}</span>
                                </div>
                                @if($order->estimated_delivery)
                                <div class="info-item">
                                    <span class="label">Estimated Delivery:</span>
                                    <span class="value">{{ $order->estimated_delivery->format('M d, Y') }}</span>
                                </div>
                                @endif
                                @if($order->delivered_at)
                                <div class="info-item">
                                    <span class="label">Delivered:</span>
                                    <span class="value">{{ $order->delivered_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="section-title">Shipping Address</h6>
                            <div class="shipping-address">
                                @php $address = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address; @endphp
                                <p class="mb-1"><strong>{{ $address['full_name'] ?? $order->customer_name }}</strong></p>
                                <p class="mb-1">{{ $address['street_address'] ?? '' }}</p>
                                <p class="mb-1">{{ $address['city'] ?? '' }} {{ $address['postal_code'] ?? '' }}</p>
                                <p class="mb-0">{{ $address['phone'] ?? $order->customer_phone }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <div class="order-items-section">
                        <h6 class="section-title">Order Items</h6>
                        
                        @foreach($order->orderItems->groupBy('vendor_id') as $vendorId => $items)
                            <div class="vendor-section mb-4">
                                <div class="vendor-header">
                                    <h6 class="vendor-name">
                                        <i class="fas fa-store me-2"></i>{{ $items->first()->vendor_name }}
                                    </h6>
                                </div>
                                
                                <div class="items-list">
                                    @foreach($items as $item)
                                    <div class="item-image">
                                        @if($item->product && method_exists($item->product, 'images') && $item->product->images && $item->product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="img-fluid">
                                        @else
                                            <div class="no-image-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                            
                                            <div class="item-details">
                                                <h6 class="item-name">{{ $item->product_name }}</h6>
                                                <div class="item-meta">
                                                    <span class="quantity">Qty: {{ $item->quantity }}</span>
                                                    
                                                </div>
                                            </div>
                                            
                                            <div class="item-total">
                                                {{ $item->formatted_total }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Order Totals -->
                    <div class="order-totals">
                        <div class="totals-row">
                            <span class="label">Subtotal:</span>
                            <span class="value">₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="totals-row">
                            <span class="label">Shipping Fee:</span>
                            <span class="value">₱{{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        <div class="totals-row">
                            <span class="label">Tax:</span>
                            <span class="value">₱{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="totals-row total-row">
                            <span class="label">Total:</span>
                            <span class="value">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                    
                    @if($order->order_notes)
                    <!-- Order Notes -->
                    <div class="order-notes mt-4">
                        <h6 class="section-title">Order Notes</h6>
                        <p class="notes-text">{{ $order->order_notes }}</p>
                    </div>
                    @endif
                    
                    <!-- Order Actions -->
                    <div class="order-actions mt-4">
                        @if($order->status === 'delivered')
                            <button type="button" 
                                    class="btn btn-success mark-received-btn" 
                                    data-order-id="{{ $order->id }}">
                                <i class="fas fa-check me-1"></i>Mark as Received
                            </button>
                        @endif
                        
                        @if(in_array($order->status, ['pending', 'processing']))
                            <button type="button" 
                                    class="btn btn-outline-danger cancel-order-btn" 
                                    data-order-id="{{ $order->id }}">
                                <i class="fas fa-times me-1"></i>Cancel Order
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Action Modals (same as order history) -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this order?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                <button type="button" class="btn btn-danger" id="confirmCancelOrder">Cancel Order</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="markReceivedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark as Received</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Have you received this order in good condition?</p>
                <p class="text-muted small">Marking as received will confirm the delivery was successful.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Not Yet</button>
                <button type="button" class="btn btn-success" id="confirmMarkReceived">Mark as Received</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/order-history.js') }}"></script>
@endpush