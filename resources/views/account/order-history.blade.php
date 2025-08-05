@extends('layouts.account')

@section('title', 'Order History - SwiftShop')

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
                
                <!-- Light gray break line -->
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
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card account-main-card">
                <div class="card-header">
                    <h5>Order History</h5>
                    <div class="order-summary-stats">
                        <span class="total-orders">{{ $statusCounts['all'] }} total orders</span>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Search Bar -->
                    <div class="order-search-section mb-4">
                        <div class="search-container">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0 ps-0" 
                                       id="orderSearchInput"
                                       placeholder="Search orders by order number or vendor..."
                                       value="{{ $search }}">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Filter Tabs -->
                    <div class="order-status-tabs mb-4">
                        <ul class="nav nav-pills status-pills" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link status-pill {{ $status === 'all' ? 'active' : '' }}" 
                                        data-status="all" type="button">
                                    All Orders
                                    @if($statusCounts['all'] > 0)
                                        <span class="status-count">{{ $statusCounts['all'] }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link status-pill {{ $status === 'pending' ? 'active' : '' }}" 
                                        data-status="pending" type="button">
                                    Pending
                                    @if($statusCounts['pending'] > 0)
                                        <span class="status-count">{{ $statusCounts['pending'] }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link status-pill {{ $status === 'processing' ? 'active' : '' }}" 
                                        data-status="processing" type="button">
                                    Processing
                                    @if($statusCounts['processing'] > 0)
                                        <span class="status-count">{{ $statusCounts['processing'] }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link status-pill {{ $status === 'delivered' ? 'active' : '' }}" 
                                        data-status="delivered" type="button">
                                    Delivered
                                    @if($statusCounts['delivered'] > 0)
                                        <span class="status-count">{{ $statusCounts['delivered'] }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link status-pill {{ $status === 'cancelled' ? 'active' : '' }}" 
                                        data-status="cancelled" type="button">
                                    Cancelled
                                    @if($statusCounts['cancelled'] > 0)
                                        <span class="status-count">{{ $statusCounts['cancelled'] }}</span>
                                    @endif
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Orders List -->
                    <div id="ordersContainer">
                        @include('account.partials.order-list', ['orders' => $orders])
                    </div>
                    
                   <!-- Simple Pagination -->
                    <div id="paginationContainer">
                        @include('account.partials.simple-pagination', ['orders' => $orders])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading overlay -->
<div id="loadingOverlay" class="loading-overlay" style="display: none;">
    <div class="loading-spinner">
        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
        <p class="mt-2 text-muted">Loading orders...</p>
    </div>
</div>

<!-- Order Action Modals -->
<!-- Cancel Order Modal -->
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

<!-- Mark as Received Modal -->
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