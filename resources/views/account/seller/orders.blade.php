@extends('layouts.account')

@section('title', 'Seller Orders - SwiftShop')

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
                    <a href="{{ route('test.seller.overview') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Seller Overview
                    </a>
                    <a href="{{ route('test.seller.products') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-box me-2"></i>
                        My Products
                    </a>
                    <a href="{{ route('test.seller.orders') }}" class="list-group-item list-group-item-action active">
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
                        <h5>Seller Orders</h5>
                        <div class="text-muted">
                            {{ $statusCounts['all'] }} total orders
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Search Bar -->
                    <div class="orders-search-section mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="search-input-container">
                                    <input type="text" 
                                           class="form-control" 
                                           id="orderSearch" 
                                           placeholder="Search orders by order number, customer, or product..."
                                           value="{{ request('search') }}">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Pills -->
                    <div class="orders-filter-section mb-4">
                        <div class="filter-pills">
                            <button class="filter-pill {{ $status === 'all' ? 'active' : '' }}" data-filter="all">
                                All Orders <span class="pill-count">{{ $statusCounts['all'] }}</span>
                            </button>
                            <button class="filter-pill {{ $status === 'pending' ? 'active' : '' }}" data-filter="pending">
                                Pending <span class="pill-count">{{ $statusCounts['pending'] }}</span>
                            </button>
                            <button class="filter-pill {{ $status === 'processing' ? 'active' : '' }}" data-filter="processing">
                                Processing <span class="pill-count">{{ $statusCounts['processing'] }}</span>
                            </button>
                            <button class="filter-pill {{ $status === 'shipped' ? 'active' : '' }}" data-filter="shipped">
                                Shipped <span class="pill-count">{{ $statusCounts['shipped'] }}</span>
                            </button>
                            <button class="filter-pill {{ $status === 'delivered' ? 'active' : '' }}" data-filter="delivered">
                                Delivered <span class="pill-count">{{ $statusCounts['delivered'] }}</span>
                            </button>
                            <button class="filter-pill {{ $status === 'cancelled' ? 'active' : '' }}" data-filter="cancelled">
                                Cancelled <span class="pill-count">{{ $statusCounts['cancelled'] }}</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Orders List -->
                    <div id="ordersListContainer">
                        @include('account.seller.partials.orders-list', ['orders' => $orders, 'vendor' => $vendor])
                    </div>
                    
                    <!-- Pagination -->
                    <div id="ordersPaginationContainer">
                        @if($orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav>
                                <ul class="pagination">
                                    @if($orders->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                    @else
                                    <li class="page-item">
                                        <button class="page-link pagination-btn" data-page="{{ $orders->currentPage() - 1 }}">Previous</button>
                                    </li>
                                    @endif
                                    
                                    @if($orders->hasMorePages())
                                    <li class="page-item">
                                        <button class="page-link pagination-btn" data-page="{{ $orders->currentPage() + 1 }}">Next</button>
                                    </li>
                                    @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/seller-orders.js') }}"></script>
@endpush

