@if($orders->count() > 0)
    <div class="orders-list">
        @foreach($orders as $order)
            <div class="order-card mb-4">
                <div class="order-header">
                    <div class="order-info">
                        <h6 class="order-number">{{ $order->order_number }}</h6>
                        <div class="order-meta">
                            <span class="order-date">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Order Date: {{ $order->created_at->format('M d, Y') }}
                            </span>
                            @if($order->orderItems->count() > 0)
                                <span class="vendor-info">
                                    <i class="fas fa-store me-1"></i>
                                    @if($order->orderItems->groupBy('vendor_id')->count() === 1)
                                        Vendor: {{ $order->orderItems->first()->vendor_name }}
                                    @else
                                        Multiple Vendors ({{ $order->orderItems->groupBy('vendor_id')->count() }})
                                    @endif
                                </span>
                            @endif
                            <span class="item-count">
                                {{ $order->orderItems->count() }} item{{ $order->orderItems->count() > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-actions">
                        <div class="order-total">{{ $order->formatted_total }}</div>
                        <div class="order-status">
                            <span class="badge status-badge status-{{ $order->status }}">
                                @if($order->status === 'pending')
                                    <i class="fas fa-clock me-1"></i>Pending
                                @elseif($order->status === 'processing')
                                    <i class="fas fa-cog me-1"></i>Processing
                                @elseif($order->status === 'shipped')
                                    <i class="fas fa-truck me-1"></i>Shipped
                                @elseif($order->status === 'delivered')
                                    <i class="fas fa-check-circle me-1"></i>Delivered
                                @elseif($order->status === 'cancelled')
                                    <i class="fas fa-times-circle me-1"></i>Cancelled
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="order-body">
                    <!-- Order Items Preview (show first 2-3 items) -->
                    <div class="order-items-preview">
                        @foreach($order->orderItems->take(3) as $item)
                            <div class="order-item-preview">
                                <div class="item-image">
                                    @if($item->product && $item->product->images && $item->product->images->count() > 0)
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
                                    <p class="item-meta">
                                        Qty: {{ $item->quantity }} × {{ $item->formatted_unit_price }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($order->orderItems->count() > 3)
                            <div class="more-items-indicator">
                                <span class="text-muted">
                                    +{{ $order->orderItems->count() - 3 }} more item{{ $order->orderItems->count() - 3 > 1 ? 's' : '' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="order-footer">
                    <div class="order-actions-buttons">
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm view-details-btn" 
                                data-order-id="{{ $order->id }}">
                            <i class="fas fa-eye me-1"></i>View Details
                        </button>
                        
                        {{-- Cancel button: Only show for PENDING orders --}}
                        @if($order->status === 'pending')
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm cancel-order-btn" 
                                    data-order-id="{{ $order->id }}">
                                <i class="fas fa-times me-1"></i>Cancel Order
                            </button>
                        @endif
                        
                        {{-- Mark as Received button: Only show for SHIPPED orders --}}
                        @if($order->status === 'shipped')
                            <button type="button" 
                                    class="btn btn-success btn-sm mark-received-btn" 
                                    data-order-id="{{ $order->id }}">
                                <i class="fas fa-check me-1"></i>Mark as Received
                            </button>
                        @endif
                        
                        {{-- Leave Review button: Only show for DELIVERED orders --}}
                        @if($order->status === 'delivered')
                            <button type="button" 
                                    class="btn btn-primary btn-sm leave-review-btn" 
                                    data-order-id="{{ $order->id }}">
                                <i class="fas fa-star me-1"></i>Leave Review
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="empty-orders-state text-center py-5">
        <div class="empty-icon mb-3">
            <i class="fas fa-shopping-bag fa-4x text-muted"></i>
        </div>
        <h5 class="text-muted mb-2">No Orders Found</h5>
        @if(request('search') || request('status') !== 'all')
            <p class="text-muted mb-3">
                Try adjusting your search or filter criteria.
            </p>
            <a href="{{ route('test.account.order-history') }}" class="btn btn-outline-primary">
                Clear Filters
            </a>
        @else
            <p class="text-muted mb-3">
                You haven't placed any orders yet. Start shopping to see your order history here.
            </p>
            <a href="{{ route('test.browse') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart me-1"></i>
                Start Shopping
            </a>
        @endif
    </div>
@endif