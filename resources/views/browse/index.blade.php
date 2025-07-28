@extends('layouts.auth')

@section('title', 'Browse - SwiftShop')


@section('content')
<div class="browse-page">
    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="welcome-title">Welcome back, {{ $userAccount ? $userAccount->name : 'Guest' }}!👋🏻</h1>
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
                    <div class="product-card" data-product-id="{{ $product['id'] }}">
                            <a href="{{ route('test.product', $product['id'])}}" class="text-decoration-none">
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
                                    <span class="rating-count">({{ $product['reviewsCount'] }})</span>                                </div>
                                
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
                <div class="col-10 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                    <div class="product-card" data-product-id="{{ $product['id'] }}">
                        <a href="{{ route('test.product', $product['id'])}}" class="text-decoration-none">
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
                                <span class="rating-count">({{ $product['reviewsCount'] }})</span>                            </div>
                            
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
    </div>
    </section>

    <!-- Electronics Section -->
    <section class="product-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h2 class="section-title">Electronics</h2>
                <a href="{{ route('category.show', '1') }}" class="view-more-btn">View More</a>            </div>
            
            <div class="row g-3">
                @foreach($electronics as $product)
                <div class="col-10 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                    <div class="product-card" data-product-id="{{ $product['id'] }}">
                        <a href="{{ route('test.product', $product['id'])}}" class="text-decoration-none">
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
                                <span class="rating-count">({{ $product['reviewsCount'] }})</span>                            </div>
                            
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
    </div>
    </section>


        <!-- Fashion Category -->
        <section class="product-section">
            <div class="container">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2 class="section-title">Fashion</h2>
                    <a href="{{ route('category.show', '2') }}" class="view-more-btn">View More</a>                </div>
                
                <div class="row g-3">
                    @foreach($fashionProducts as $product)
                    <div class="col-10 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                        <div class="product-card" data-product-id="{{ $product['id'] }}">
                            <a href="{{ route('test.product', $product['id'])}}" class="text-decoration-none">
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
                                    <span class="rating-count">({{ $product['reviewsCount'] }})</span>                                </div>
                                
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
        </div>
        </section>

        <!-- Home and Garden Category -->
        <section class="product-section">
            <div class="container">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2 class="section-title">Home and Garden</h2>
                    <a href="{{ route('category.show', '3') }}" class="view-more-btn">View More</a>                </div>
                
                <div class="row g-3">
                    @foreach($homeGardenProducts as $product)
                    <div class="col-10 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                        <div class="product-card" data-product-id="{{ $product['id'] }}">
                            <a href="{{ route('test.product', $product['id'])}}" class="text-decoration-none">
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
                                    <span class="rating-count">({{ $product['reviewsCount'] }})</span>                                </div>
                                
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
        </div>
        </section>

        <!-- Gaming Category -->
        <section class="product-section">
            <div class="container">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2 class="section-title">Gaming</h2>
                    <a href="{{ route('category.show', '4') }}" class="view-more-btn">View More</a>                </div>
                
                <div class="row g-3">
                    @foreach($gamingProducts as $product)
                    <div class="col-10 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                        <div class="product-card" data-product-id="{{ $product['id'] }}">
                            <a href="{{ route('test.product', $product['id'])}}" class="text-decoration-none">
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
        </div>
        </section>

        <!-- Photography Category -->
        <section class="product-section">
            <div class="container">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2 class="section-title">Photography</h2>
                    <a href="{{ route('category.show', '5') }}" class="view-more-btn">View More</a>
                </div>
                
                <div class="row g-3">
                    @foreach($photographyProducts as $product)
                    <div class="col-10 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                        <div class="product-card" data-product-id="{{ $product['id'] }}">
                            <a href="{{ route('test.product', $product['id'])}}" class="text-decoration-none">
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
        </div>
        </section>

        <!-- Audio Category -->
        <section class="product-section">
            <div class="container">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h2 class="section-title">Audio</h2>
                    <a href="{{ route('category.show', '6') }}" class="view-more-btn">View More</a>                </div>
                
                <div class="row g-3">
                    @foreach($audioProducts as $product)
                    <div class="col-10 col-md-4 col-lg-3 col-xl-custom-5 mx-auto">
                        <div class="product-card" data-product-id="{{ $product['id'] }}">
                            <a href="{{ route('test.product', $product['id'])}}" class="text-decoration-none">
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
        </div>
        </section>
</div>
@endsection


@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
@endpush
