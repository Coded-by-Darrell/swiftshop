@extends('layouts.account')

@section('title', 'My Wishlist - SwiftShop')

@section('content')
<div class="wishlist-page">
    <div class="container my-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="page-title">My Wishlist</h1>
                <p class="text-muted">{{ count($products) }} items saved</p>
            </div>
        </div>

        @if(count($products) > 0)
            <!-- Wishlist Items -->
            <div class="row g-3" id="wishlist-items">
                @foreach($products as $product)
                <div class="col-10 col-md-4 col-lg-3 col-xl-custom-5 mx-auto" data-product-id="{{ $product['id'] }}">
                    <div class="product-card" data-product-id="{{ $product['id'] }}">
                        <!-- Trash Button -->
                        <button class="btn btn-sm btn-outline-danger wishlist-remove-btn" 
                                data-product-id="{{ $product['id'] }}" 
                                title="Remove from wishlist">
                            <i class="fas fa-trash"></i>
                        </button>
                        
                        <a href="{{ route('test.product', $product['id']) }}" class="text-decoration-none">
                            <!-- Product Image with Badge -->
                            <div class="product-image">
                                @if($product['badge'])
                                    <span class="product-badge badge-sale">{{ $product['badge'] }}</span>
                                @endif
                                
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        </a>
                        
                        <!-- Product Details -->
                        <div class="product-details">
                            <h3 class="product-name">{{ $product['name'] }}</h3>
                            
                            <!-- Rating -->
                            <div class="product-rating">
                                <div class="rating-stars">
                                    @php
                                        $rating = $product['rating'];
                                        $fullStars = floor($rating);
                                        $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                    @endphp
                                    
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-count">({{ $product['reviewsCount'] }})</span>
                            </div>
                            
                            <!-- Store -->
                            <p class="product-store">{{ $product['store'] }}</p>
                            
                            <!-- Pricing -->
                            <div class="product-pricing">
                                <span class="current-price">₱{{ number_format($product['price'], 2) }}</span>
                                @if($product['old_price'])
                                    <span class="old-price">₱{{ number_format($product['old_price'], 2) }}</span>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="product-actions">
                                <button class="btn btn-buy-now" data-product-id="{{ $product['id'] }}">Buy Now</button>
                                <button class="btn btn-cart-icon" data-product-id="{{ $product['id'] }}" title="Add to Cart">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty Wishlist State -->
            <div class="row">
                <div class="col-12">
                    <div class="empty-wishlist text-center py-5">
                        <i class="far fa-heart fa-5x text-muted mb-4"></i>
                        <h3 class="text-muted mb-3">Your wishlist is empty</h3>
                        <p class="text-muted mb-4">Start adding items you love to your wishlist!</p>
                        <a href="{{ route('test.browse') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.wishlist-page .product-card {
    position: relative;
}

.wishlist-remove-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid #dc3545;
    color: #dc3545;
    transition: all 0.2s ease;
}

.wishlist-remove-btn:hover {
    background: #dc3545;
    color: white;
    transform: scale(1.1);
}

.wishlist-remove-btn i {
    font-size: 12px;
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0;
}

.empty-wishlist {
    min-height: 400px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

/* Fade out animation for removed items */
.product-card.removing {
    opacity: 0.5;
    transform: scale(0.95);
    transition: all 0.3s ease;
}

.product-card.removed {
    opacity: 0;
    transform: scale(0.8);
    height: 0;
    margin: 0;
    padding: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remove from wishlist functionality
    document.querySelectorAll('.wishlist-remove-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.dataset.productId;
            const productCard = this.closest('[data-product-id]');
            
            // Add removing class for visual feedback
            productCard.classList.add('removing');
            
            // Send AJAX request to remove from wishlist
            fetch('{{ route("test.wishlist.remove") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the product card with animation
                    productCard.classList.add('removed');
                    
                    setTimeout(() => {
                        productCard.remove();
                        
                        // Check if wishlist is empty
                        const remainingItems = document.querySelectorAll('#wishlist-items [data-product-id]');
                        if (remainingItems.length === 0) {
                            // Reload page to show empty state
                            window.location.reload();
                        } else {
                            // Update item count
                            const countElement = document.querySelector('.text-muted');
                            if (countElement) {
                                const newCount = remainingItems.length;
                                countElement.textContent = `${newCount} item${newCount !== 1 ? 's' : ''} saved`;
                            }
                        }
                    }, 300);
                    
                    // Show success message
                    showNotification(data.message, 'success');
                } else {
                    // Remove visual feedback if failed
                    productCard.classList.remove('removing');
                    showNotification(data.message || 'Failed to remove item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                productCard.classList.remove('removing');
                showNotification('An error occurred. Please try again.', 'error');
            });
        });
    });
});

// Notification function (reuse from show.js)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
@endpush