@extends('layouts.account')

@section('title', 'Seller Overview - SwiftShop')

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
                    <a href="{{ route('test.account.order-history') }}" class="list-group-item list-group-item-action">
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
                    <a href="{{ route('test.seller.overview') }}" class="list-group-item list-group-item-action active">
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
            <div class="card account-main-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>{{ $vendor->business_name }}</h5>
                            <p class="mb-0 text-muted">Your seller dashboard overview</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Business Metrics -->
                    <div class="seller-metrics-section mb-4">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <div class="metric-card">
                                    <div class="metric-value">₱{{ number_format($totalRevenue, 2) }}</div>
                                    <div class="metric-label">Total Revenue</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="metric-card">
                                    <div class="metric-value">{{ $soldOrders }}</div>
                                    <div class="metric-label">Sold Orders</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="metric-card">
                                    <div class="metric-value">{{ $activeProducts }}</div>
                                    <div class="metric-label">Active Products</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="metric-card">
                                    <div class="metric-value">{{ $pendingOrders }}</div>
                                    <div class="metric-label">Pending Orders</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Recent Orders -->
                        <div class="col-lg-8">
                            <div class="seller-section">
                                <div class="section-header">
                                    <h6>Recent Orders</h6>
                                    <a href="{{ route('test.seller.orders') }}" class="view-all-btn">View All</a>
                                </div>
                                
                                <div class="recent-orders-list">
                                    @forelse($recentOrders as $orderItem)
                                    <div class="order-item">
                                        <div class="order-info">
                                            <div class="order-number">{{ $orderItem->order->order_number }}</div>
                                            <div class="order-details">
                                                <span class="product-name">{{ $orderItem->product_name }}</span>
                                                <span class="order-quantity">Qty: {{ $orderItem->quantity }}</span>
                                            </div>
                                        </div>
                                        <div class="order-meta">
                                            <div class="order-amount">₱{{ number_format($orderItem->total_price, 2) }}</div>                                            <div class="order-status status-{{ $orderItem->order->status }}">
                                                {{ ucfirst($orderItem->order->status) }}
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="empty-state">
                                        <i class="fas fa-shopping-bag fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No recent orders found</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        <!-- Store Status -->
                        <div class="col-lg-4">
                            <div class="seller-section">
                                <div class="section-header">
                                    <h6>Store Status</h6>
                                </div>
                                
                                <div class="store-status-card">
                                    <div class="status-info">
                                        <div class="status-label">Your store is currently:</div>
                                        <div class="status-value {{ $vendor->store_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $vendor->store_active ? 'Active' : 'Inactive' }}
                                        </div>
                                    </div>
                                    
                                    <div class="store-toggle-section">
                                        <label class="store-toggle">
                                            <input type="checkbox" 
                                                   id="storeStatusToggle" 
                                                   {{ $vendor->store_active ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <div class="toggle-label">
                                            {{ $vendor->store_active ? 'Store is Online' : 'Store is Offline' }}
                                        </div>
                                    </div>
                                    
                                    <div class="status-description">
                                        @if($vendor->store_active)
                                        <p class="text-success mb-0">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Customers can view and purchase your products
                                        </p>
                                        @else
                                        <p class="text-warning mb-0">
                                            <i class="fas fa-pause-circle me-1"></i>
                                            Your products are temporarily hidden from customers
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/seller-dashboard.js') }}"></script>
@endpush