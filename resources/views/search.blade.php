@extends('layouts.auth')

@section('title', 'Search Results - SwiftShop')
@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
@endpush

@section('content')
<link rel="stylesheet" href="{{ asset('css/category.css') }}">

<div class="container my-4">
    <!-- Search Header -->
    <div class="search-header mb-4">
        <div class="d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4>Search Results</h4>
                    @if($query)
                        <p class="text-muted mb-0">
                            Showing results for "<strong>{{ $query }}</strong>" 
                            <span class="text-primary">({{ $products->total() }} products found)</span>
                        </p>
                    @endif
                </div>
                
                <!-- Sort Options (Desktop Only) -->
                <div class="sort-section d-none d-md-block">
                    <form method="GET" action="{{ route('search') }}" id="sortForm">
                        <input type="hidden" name="q" value="{{ $query }}">
                        
                        <select name="sort" class="form-select" onchange="document.getElementById('sortForm').submit()">
                            <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }}>Price (Low to High)</option>
                            <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>Price (High to Low)</option>
                            <option value="newest" {{ $sortBy == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="rating" {{ $sortBy == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        </select>
                    </form>
                </div>
            </div>
            
            <!-- Sort Options (Mobile Only - Below) -->
            <div class="sort-section d-md-none">
                <form method="GET" action="{{ route('search') }}" id="sortFormMobile">
                    <input type="hidden" name="q" value="{{ $query }}">
                    
                    <select name="sort" class="form-select" onchange="document.getElementById('sortFormMobile').submit()">
                        <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Sort: Name (A-Z)</option>
                        <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }}>Sort: Price (Low to High)</option>
                        <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>Sort: Price (High to Low)</option>
                        <option value="newest" {{ $sortBy == 'newest' ? 'selected' : '' }}>Sort: Newest</option>
                        <option value="rating" {{ $sortBy == 'rating' ? 'selected' : '' }}>Sort: Highest Rated</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Grid (Full Width) -->
    <div class="row">
        <div class="col-12">
            @if($products->count() > 0)
                <div class="products-grid" id="productsGrid">
                    <div class="row" id="productsList">
                        @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 product-item">
                            <div class="product-card" data-product-id="{{ $product['id'] }}">
                                <a href="{{ route('test.product', $product->id) }}" class="text-decoration-none">
                                    <!-- Product Image with Badge -->
                                    <div class="product-image">
                                        @if($product->getDiscountPercentage() > 0)
                                            <span class="product-badge badge-sale">{{ $product->getDiscountPercentage() }}% OFF</span>
                                        @endif
                                        
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                </a>
                                
                                <!-- Product Details -->
                                <div class="product-details">
                                    <h3 class="product-name">{{ $product->name }}</h3>
                                    
                                    <!-- Rating -->
                                    <div class="product-rating">
                                        <div class="rating-stars">
                                            @php
                                                $rating = $product->averageRating();
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
                                        <span class="rating-count">({{ $product->reviewsCount() }})</span>
                                    </div>
                                    
                                    <!-- Store -->
                                    <p class="product-store">{{ $product->vendor->business_name }}</p>
                                    
                                    <!-- Pricing -->
                                    <div class="product-pricing">
                                        <span class="current-price">₱{{ number_format($product->getDisplayPrice(), 2) }}</span>
                                        @if($product->hasActiveDiscount())
                                            <span class="old-price">₱{{ number_format($product->getOriginalPrice(), 2) }}</span>
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
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="pagination-wrapper mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
                @endif
            @else
                <div class="no-results">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>No products found</h4>
                        <p class="text-muted">Try adjusting your search terms</p>
                        <a href="{{ route('test.browse') }}" class="btn btn-primary">Browse All Products</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection