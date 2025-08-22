@if($orders->count() > 0)
    @foreach($orders as $order)
        @php
            // Get only seller's items from this order
            $sellerItems = $order->orderItems->where('vendor_id', $vendor->id);
            $totalSellerAmount = $sellerItems->sum('total_price');
        @endphp
        
        <div class="order-card mb-3">
            <div class="order-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="order-number mb-1">{{ $order->order_number }}</h6>
                        <div class="order-meta">
                            <span class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Order Date: {{ $order->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <div class="order-meta">
                            <span class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                Customer: {{ $order->customer_name }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="order-amount mb-2">
                            <strong>₱{{ number_format($totalSellerAmount, 2) }}</strong>
                        </div>
                        @if($sellerItems->count() == 1)
                            <span class="text-muted">1 Item</span>
                        @else
                            <span class="text-muted">{{ $sellerItems->count() }} Items</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="order-items">
                @foreach($sellerItems as $item)
                <div class="order-item">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="item-image-placeholder me-3">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="item-details">
                                    <h6 class="item-name mb-1">{{ $item->product_name }}</h6>
                                    <div class="item-meta text-muted">
                                        <span>Qty: {{ $item->quantity }} × ₱{{ number_format($item->unit_price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="item-status-and-actions">
                                <div class="item-status mb-2">
                                    <span class="status-badge status-{{ $item->status }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                                
                                @if($item->status === 'pending')
                                <button class="btn btn-sm btn-primary status-btn" 
                                        data-item-id="{{ $item->id }}" 
                                        data-action="processing">
                                    <i class="fas fa-play me-1"></i>Process Order
                                </button>
                                @elseif($item->status === 'processing')
                                <button class="btn btn-sm btn-info status-btn" 
                                        data-item-id="{{ $item->id }}" 
                                        data-action="shipped">
                                    <i class="fas fa-shipping-fast me-1"></i>Mark as Shipped
                                </button>
                                @elseif($item->status === 'shipped')
                                <div class="text-muted small">
                                    <i class="fas fa-clock me-1"></i>Waiting for customer confirmation
                                </div>
                                @elseif($item->status === 'delivered')
                                <div class="text-success small">
                                    <i class="fas fa-check-circle me-1"></i>Order completed
                                </div>
                                @elseif($item->status === 'cancelled')
                                <div class="text-danger small">
                                    <i class="fas fa-times-circle me-1"></i>Order cancelled
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endforeach
@else
    <div class="empty-state text-center py-5">
        <div class="empty-icon mb-3">
            <i class="fas fa-shopping-bag fa-3x text-muted"></i>
        </div>
        <h5 class="text-muted">No orders found</h5>
        <p class="text-muted mb-0">
            @if(request('search'))
                No orders match your search criteria.
            @elseif(request('status') && request('status') !== 'all')
                No {{ request('status') }} orders found.
            @else
                You haven't received any orders yet.
            @endif
        </p>
    </div>
@endif