@extends('layouts.account')

@section('title', 'My Products - SwiftShop')

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
                    <a href="{{ route('test.seller.products') }}" class="list-group-item list-group-item-action active">
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
                        <h5>My Products</h5>
                        <button type="button" class="btn btn-primary" id="addProductBtn">
                            <i class="fas fa-plus me-2"></i>Add New Product
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Search Bar -->
                    <div class="products-search-section mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="search-input-container">
                                    <input type="text" 
                                           class="form-control" 
                                           id="productSearch" 
                                           placeholder="Search products..."
                                           value="{{ request('search') }}">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Buttons -->
                    <div class="products-filter-section mb-4">
                        <div class="filter-pills">
                            <button class="filter-pill active" data-filter="all">
                                All Products <span class="pill-count">{{ $filterCounts['all'] }}</span>
                            </button>
                            <button class="filter-pill" data-filter="active">
                                Active <span class="pill-count">{{ $filterCounts['active'] }}</span>
                            </button>
                            <button class="filter-pill" data-filter="inactive">
                                Inactive <span class="pill-count">{{ $filterCounts['inactive'] }}</span>
                            </button>
                            <button class="filter-pill" data-filter="pending">
                                Pending <span class="pill-count">{{ $filterCounts['pending'] }}</span>
                            </button>
                            <button class="filter-pill" data-filter="out_of_stock">
                                Out of Stock <span class="pill-count">{{ $filterCounts['out_of_stock'] }}</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Products List -->
                    <div id="productsListContainer">
                        @include('account.seller.partials.products-list', ['products' => $products])
                    </div>
                    
                    <!-- Pagination -->
                    <div id="productsPaginationContainer">
                        @include('account.seller.partials.products-pagination', ['products' => $products])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals will be dynamically loaded here -->
<div id="modalContainer"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/seller-products.js') }}"></script>
@endpush