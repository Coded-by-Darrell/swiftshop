@extends('layouts.auth')

@section('content')
<!-- Include Category Specific Styles -->
<link rel="stylesheet" href="{{ asset('css/category.css') }}">

<div class="container my-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('test.browse') }}" class="breadcrumb-link">
                    <i class="fas fa-home me-1"></i>Products
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="category-header mb-4">
        <div class="d-flex align-items-center mb-2">
            <div class="category-icon me-3">
                <i class="fas fa-mobile-alt fa-2x text-primary"></i>
            </div>
            <div>
                <h1 class="category-title mb-1">{{ $category->name }}</h1>
                <p class="category-description mb-0">{{ $category->description }}</p>
            </div>
        </div>
        <div class="results-count">
            <span class="text-muted">Showing {{ $products->count() }} products</span>
        </div>
    </div>

    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="filter-sidebar">
                <div class="filter-header">
                    <h5 class="filter-title">Filter Products</h5>
                </div>

                <!-- Vendor Filter -->
                <div class="filter-section">
                    <h6 class="filter-section-title">Vendor</h6>
                    <div class="filter-options">
                        @foreach($brands as $brand)
                        <div class="form-check filter-option">
                            <input class="form-check-input" type="checkbox" id="brand_{{ $loop->index }}" 
                                   name="brands[]" value="{{ $brand }}">
                            <label class="form-check-label" for="brand_{{ $loop->index }}">
                                {{ $brand }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div class="filter-section">
                    <h6 class="filter-section-title">Price Range</h6>
                    <div class="price-range-inputs">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="price-input-group">
                                    <span class="price-currency">₱</span>
                                    <input type="number" class="form-control price-input" 
                                           id="min_price" placeholder="0" min="0">
                                </div>
                                <small class="text-muted">Min</small>
                            </div>
                            <div class="col-6">
                                <div class="price-input-group">
                                    <span class="price-currency">₱</span>
                                    <input type="number" class="form-control price-input" 
                                           id="max_price" placeholder="50,000" min="0">
                                </div>
                                <small class="text-muted">Max</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Apply Filters Button -->
                <div class="filter-actions mt-4">
                    <button class="btn btn-primary w-100 mb-2" id="applyFilters">Apply Filters</button>
                    <button class="btn btn-outline-secondary w-100" id="clearFilters">Clear All</button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9 col-md-8">
            <!-- Products Grid -->
            <div class="products-grid" id="productsGrid">
                <div class="row" id="productsList">
                    @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4 product-item">
                        <div class="product-card">
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
                                    <button class="btn btn-buy-now">Buy Now</button>
                                    <button class="btn btn-cart-icon" title="Add to Cart">
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
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Include Category Specific JavaScript -->
<script src="{{ asset('js/category.js') }}"></script>
@endsection