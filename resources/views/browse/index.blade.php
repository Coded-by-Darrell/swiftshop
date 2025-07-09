@extends('layouts.auth')

@section('title', 'Browse - SwiftShop')

@section('content')
<div class="browse-page">
    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="welcome-title">Welcome back, {{ $userName }}!</h1>
                    <p class="welcome-subtitle">Discover amazing deals and new products from your favorite vendors</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Special Deals Section -->
    <section class="product-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h2 class="section-title">Special Deals</h2>
                <a href="#" class="view-more-btn">View More</a>
            </div>
            
            <div class="row g-3">
                @foreach($specialDeals as $product)
                <div class="col-10 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                    <div class="product-card">
                        <!-- Product Image -->
                        <div class="product-image">
                            @if(isset($product['badge']))
                                <span class="product-badge badge-sale">{{ $product['badge'] }}</span>
                            @endif
                            <button class="wishlist-btn">
                                <i class="far fa-heart"></i>
                            </button>
                            <!-- Placeholder for product image -->
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="product-details">
                            <h3 class="product-name">{{ $product['name'] }}</h3>
                            
                            <!-- Rating -->
                            <div class="product-rating">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product['rating']))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $product['rating'])
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-count">({{ rand(100, 999) }})</span>
                            </div>
                            
                            <!-- Store -->
                            <p class="product-store">{{ $product['store'] }}</p>
                            
                            <!-- Pricing -->
                            <div class="product-pricing">
                                <span class="current-price">₱{{ $product['price'] }}</span>
                                @if(isset($product['old_price']))
                                    <span class="old-price">₱{{ $product['old_price'] }}</span>
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
    </section>

    <!-- New Releases Section -->
    <section class="product-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h2 class="section-title">New Releases</h2>
                <a href="#" class="view-more-btn">View More</a>
            </div>
            
            <div class="row g-3">
                @foreach($newReleases as $product)
                <div class="col-12 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                    <div class="product-card">
                        <!-- Product Image -->
                        <div class="product-image">
                            @if(isset($product['badge']))
                                <span class="product-badge badge-new">{{ $product['badge'] }}</span>
                            @endif
                            <button class="wishlist-btn">
                                <i class="far fa-heart"></i>
                            </button>
                            <!-- Placeholder for product image -->
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="product-details">
                            <h3 class="product-name">{{ $product['name'] }}</h3>
                            
                            <!-- Rating -->
                            <div class="product-rating">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product['rating']))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $product['rating'])
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-count">({{ rand(50, 500) }})</span>
                            </div>
                            
                            <!-- Store -->
                            <p class="product-store">{{ $product['store'] }}</p>
                            
                            <!-- Pricing -->
                            <div class="product-pricing">
                                <span class="current-price">₱{{ $product['price'] }}</span>
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
    </section>

    <!-- Electronics Section -->
    <section class="product-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h2 class="section-title">Electronics</h2>
                <a href="#" class="view-more-btn">View More</a>
            </div>
            
            <div class="row g-3">
                @foreach($electronics as $product)
                <div class="col-12 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                    <div class="product-card">
                        <!-- Product Image -->
                        <div class="product-image">
                            <button class="wishlist-btn">
                                <i class="far fa-heart"></i>
                            </button>
                            <!-- Placeholder for product image -->
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="product-details">
                            <h3 class="product-name">{{ $product['name'] }}</h3>
                            
                            <!-- Rating -->
                            <div class="product-rating">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product['rating']))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $product['rating'])
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-count">({{ rand(200, 800) }})</span>
                            </div>
                            
                            <!-- Store -->
                            <p class="product-store">{{ $product['store'] }}</p>
                            
                            <!-- Pricing -->
                            <div class="product-pricing">
                                <span class="current-price">₱{{ $product['price'] }}</span>
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
    </section>
</div>
@endsection

