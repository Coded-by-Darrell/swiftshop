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

                <!-- Subcategories Filter -->
                <div class="filter-section">
                    <h6 class="filter-section-title">Subcategories</h6>
                    <div class="filter-options">
                        @foreach($subcategories as $subcategory)
                        <div class="form-check filter-option">
                            <input class="form-check-input" type="checkbox" id="sub_{{ $subcategory }}" 
                                   name="subcategories[]" value="{{ $subcategory }}">
                            <label class="form-check-label" for="sub_{{ $subcategory }}">
                                {{ $subcategory }}
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
                        <div class="price-suggestions mt-2">
                            <button class="btn btn-sm btn-outline-secondary price-suggestion" data-min="0" data-max="10000">Under ₱10,000</button>
                            <button class="btn btn-sm btn-outline-secondary price-suggestion" data-min="10000" data-max="50000">₱10,000 - ₱50,000</button>
                            <button class="btn btn-sm btn-outline-secondary price-suggestion" data-min="50000" data-max="999999">Over ₱50,000</button>
                        </div>
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="filter-section">
                    <h6 class="filter-section-title">Brand</h6>
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

                <!-- Rating Filter -->
                <div class="filter-section">
                    <h6 class="filter-section-title">Customer Rating</h6>
                    <div class="filter-options">
                        <div class="form-check filter-option">
                            <input class="form-check-input" type="checkbox" id="rating_4_plus" 
                                   name="rating[]" value="4">
                            <label class="form-check-label" for="rating_4_plus">
                                <div class="rating-option">
                                    <div class="stars">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                    </div>
                                    <span class="ms-2">4 stars & up</span>
                                </div>
                            </label>
                        </div>
                        <div class="form-check filter-option">
                            <input class="form-check-input" type="checkbox" id="rating_3_plus" 
                                   name="rating[]" value="3">
                            <label class="form-check-label" for="rating_3_plus">
                                <div class="rating-option">
                                    <div class="stars">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                    </div>
                                    <span class="ms-2">3 stars & up</span>
                                </div>
                            </label>
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
            <!-- Products Header -->
            <div class="products-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="results-info">
                        <span class="results-count">Showing {{ $products->count() }} results</span>
                    </div>
                    <div class="view-options d-flex align-items-center gap-3">
                        <!-- View Toggle -->
                        <div class="view-toggle">
                            <button class="btn btn-outline-secondary view-btn active" data-view="grid" title="Grid View">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="btn btn-outline-secondary view-btn" data-view="list" title="List View">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="products-grid" id="productsGrid">
                <div class="row" id="productsList">
                    @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4 product-item">
                        <div class="product-card" data-product-id="{{ $product->id }}" onclick="window.location.href='{{ route('test.product', $product->id) }}'">
                            <!-- Product Image -->
                            <div class="product-image-wrapper">
                                @if($product->getDiscountPercentage() > 0)
                                <span class="product-badge discount-badge">{{ $product->getDiscountPercentage() }}% OFF</span>
                                @endif
                                
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}" class="product-image">
                                
                                <!-- Wishlist Button -->
                                <button class="wishlist-btn" onclick="event.stopPropagation(); toggleWishlist({{ $product->id }})">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>

                            <!-- Product Info -->
                            <div class="product-info">
                                <h6 class="product-name">{{ $product->name }}</h6>
                                
                                <!-- Rating -->
                                <div class="product-rating mb-2">
                                    <div class="stars">
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

                                <!-- Price -->
                                <div class="product-price mb-2">
                                    <span class="current-price">₱{{ number_format($product->getDisplayPrice(), 2) }}</span>
                                    @if($product->hasActiveDiscount())
                                        <span class="original-price">₱{{ number_format($product->getOriginalPrice(), 2) }}</span>
                                    @endif
                                </div>

                                <!-- Vendor -->
                                <div class="product-vendor mb-3">
                                    <span class="vendor-name">{{ $product->vendor->business_name }}</span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="product-actions">
                                    <button class="btn btn-primary btn-sm add-to-cart-btn" 
                                            onclick="event.stopPropagation(); addToCart({{ $product->id }})">
                                        Add to Cart
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