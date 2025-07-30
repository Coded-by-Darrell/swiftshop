@extends('layouts.auth')

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
@endpush

@section('content')
<!-- Include Product Detail Specific Styles -->
<link rel="stylesheet" href="{{ asset('css/show.css') }}">

<div class="container my-4">
    <!-- Back to Products Button -->
    <div class="back-to-products mb-3">
        <a href="{{ route('browse') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Back to Products
        </a>
    </div>

    <div class="row">
        <!-- Product Image Section -->
        <div class="col-md-6">
            <div class="product-image-container">
                <div class="main-image mb-3">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}" class="img-fluid rounded main-product-image">
                </div>
                
                <!-- Thumbnail Images -->
                <div class="thumbnail-images d-flex gap-2">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Thumbnail 1" class="thumbnail-img active">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Thumbnail 2" class="thumbnail-img">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Thumbnail 3" class="thumbnail-img">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Thumbnail 4" class="thumbnail-img">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Thumbnail 5" class="thumbnail-img">
                </div>
            </div>
        </div>

        <!-- Product Details Section -->
        <div class="col-md-6">
            <div class="product-details">
                <!-- Product Title with Wishlist -->
                <div class="d-flex align-items-start mb-3">
                    <h1 class="product-title mt-3 flex-grow-1">{{ $product->name }}</h1>
                    <button class="btn btn-outline-secondary wishlist-btn flex-shrink-0">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                
                <!-- Rating and Reviews -->
                <div class="rating-section mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rating-number">{{ number_format($product->averageRating(), 1) }}</span>
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
                        <a href="#reviews-section" class="review-count text-muted text-decoration-underline">({{ $product->reviewsCount() }} reviews)</a>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="price-section mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="current-price text-primary fw-bold fs-2">₱{{ number_format($product->getDisplayPrice(), 2) }}</span>
                        @if($product->hasActiveDiscount())
                            <span class="original-price text-muted text-decoration-line-through">₱{{ number_format($product->getOriginalPrice(), 2) }}</span>
                            <span class="discount-badge bg-danger text-white px-2 py-1 rounded">{{ $product->getDiscountPercentage() }}% OFF</span>
                        @endif
                    </div>
                </div>

                <!-- Color Selection -->
                <div class="color-section mb-3">
                    <label class="form-label fw-bold">Color: <span class="selected-color">Black</span></label>
                    <div class="color-options d-flex gap-2 mt-2">
                        <button class="btn color-option active" data-color="Black">Black</button>
                        <button class="btn color-option" data-color="Silver">Silver</button>
                        <button class="btn color-option" data-color="Blue">Blue</button>
                        <button class="btn color-option" data-color="White">White</button>
                    </div>
                </div>

                <!-- Size Selection -->
                <div class="size-section mb-3">
                    <label class="form-label fw-bold">Size: <span class="selected-size">One Size</span></label>
                    <div class="size-options mt-2">
                        <button class="btn size-option active" data-size="One Size">One Size</button>
                    </div>
                </div>

                <!-- Quantity Section -->
                <div class="quantity-section mb-3">
                    <label class="form-label fw-bold">Quantity</label>
                    <div class="quantity-container mt-2">
                        <div class="quantity-selector">
                            <button class="quantity-btn decrease" type="button" id="decrease-qty">-</button>
                            <input type="number" class="quantity-input text-center" value="1" min="1" max="50" readonly>
                            <button class="quantity-btn increase" type="button" id="increase-qty">+</button>
                        </div>
                    </div>
                </div>

                <!-- Stock Section -->
                <div class="stock-section mb-4">
                    <label class="form-label fw-bold">Stock</label>
                    <div class="stock-info mt-2">
                        <span class="stock-badge">47 units available</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons mb-4">
                    <div class="d-flex gap-3">
                        <button class="btn btn-outline-primary flex-fill py-2 fw-semibold add-to-cart-btn" data-product-id="{{ $product->id }}">Add to Cart</button>
                        <button class="btn btn-primary flex-fill py-2 fw-semibold btn-buy-now" data-product-id="{{ $product->id }}">Buy Now</button>                </div>
            </div>
        </div>
    </div>


    <!-- Product Description Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="product-description-card">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#description">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#seller-info">Seller</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#shipping">Shipping</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#return-policy">Return Policy</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade" id="seller-info">
                                <div class="seller-details d-flex align-items-start">
                                    <!-- Seller Profile Picture -->
                                    <div class="seller-avatar me-3">
                                        <img src="{{ asset('images/placeholder-avatar.jpg') }}" alt="Vendor Avatar" class="rounded-circle">
                                    </div>
                                    
                                    <!-- Seller Information -->
                                    <div class="seller-info">
                                        <div class="d-flex align-items-center mb-1">
                                            <a href="#" class="seller-name mb-0 me-2 text-decoration-none">{{ $product->vendor->business_name }}</a>
                                            <span class="verified-badge">
                                                <i class="fas fa-check-circle text-primary"></i>
                                            </span>
                                        </div>
                                        
                                        <div class="seller-followers mb-1">
                                            <span class="text-muted">15,420 followers</span>
                                        </div>
                                        
                                        <div class="seller-rating">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="rating-text">4.9</span>
                                                <div class="stars">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="description">
                                <h6>Description</h6>
                                <p>{{ $product->description ?: 'Experience industry-leading noise cancellation with the Sony WH-1000XM4 headphones. These premium wireless headphones deliver exceptional sound quality with 30-hour battery life, quick charge functionality, and smart listening features that adapt to your environment.' }}</p>
                                
                                    <h6 class="mt-4">Specifications</h6>
                                    @if($product->specifications && $product->specifications->count() > 0)
                                        <div class="row">
                                            @php
                                                $specs = $product->specifications;
                                                $halfCount = ceil($specs->count() / 2);
                                                $leftSpecs = $specs->take($halfCount);
                                                $rightSpecs = $specs->skip($halfCount);
                                            @endphp
                                            
                                            <div class="col-md-6">
                                                @foreach($leftSpecs as $spec)
                                                <div class="spec-item mb-2">
                                                    <strong>{{ $spec->spec_name }}:</strong> <span>{{ $spec->spec_value }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                            
                                            @if($rightSpecs->count() > 0)
                                            <div class="col-md-6">
                                                @foreach($rightSpecs as $spec)
                                                <div class="spec-item mb-2">
                                                    <strong>{{ $spec->spec_name }}:</strong> <span>{{ $spec->spec_value }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted">No specifications available for this product.</p>
                                    @endif
                            </div>
                            <div class="tab-pane fade" id="shipping">
                                <h6>Shipping Information</h6>
                                <p>Fast and reliable shipping options available.</p>
                            </div>
                            <div class="tab-pane fade" id="return-policy">
                                <h6>Return Policy</h6>
                                <p>30-day return policy for unused items.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                        <!-- Reviews Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="reviews-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Customer Reviews</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="reviews-section" id="reviews-section">
                                                <!-- Review Summary -->
                        <div class="review-summary mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="avg-rating display-4 fw-bold text-primary">{{ number_format($product->averageRating(), 1) }}</div>
                                        <div class="stars mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($product->averageRating()))
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i - 0.5 <= $product->averageRating())
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="text-muted">Based on {{ $product->reviewsCount() }} reviews</div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <!-- Keep existing rating breakdown for now -->
                                </div>
                            </div>
                        </div>

                        <!-- Real Individual Reviews -->
                        <div class="reviews-list">
                            @foreach($product->approvedReviews()->with('user')->latest()->take(5)->get() as $review)
                            <div class="review-item mb-4 pb-3 border-bottom">
                                <div class="d-flex mb-2">
                                    <img src="{{ asset('images/placeholder-avatar.jpg') }}" alt="User" class="rounded-circle me-3" width="40" height="40">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $review->user->name }}</h6>
                                                <div class="stars mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                @if($review->verified_purchase)
                                                    <small class="text-success">✓ Verified Purchase</small>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        <h6 class="mt-2 mb-1">{{ $review->title }}</h6>
                                        <p class="mb-0">{{ $review->content }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($product->reviewsCount() > 5)
                        <div class="text-center">
                            <button class="btn btn-outline-primary" id="loadMoreReviews" onclick="loadMoreReviews()">Load More Reviews</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Include Product Detail Specific JavaScript -->
<script src="{{ asset('js/show.js') }}"></script>
@endsection
