@extends('layouts.auth')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <!-- Product Image -->
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ asset('images/no-image.jpg') }}" alt="Product Image" class="img-fluid" style="height: 300px; object-fit: cover;">
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <!-- Product Details -->
            <h1>{{ $product->name }}</h1>
            <p class="text-muted">by {{ $product->vendor->business_name }}</p>
            <h3 class="text-primary">₱{{ number_format($product->price, 2) }}</h3>
            <p class="mt-3">{{ $product->description }}</p>
            
            <div class="mt-4">
                <p><strong>Stock:</strong> {{ $product->stock_quantity }} available</p>
                <p><strong>Category:</strong> {{ $product->category->name }}</p>
            </div>

            <!-- Buttons -->
            <div class="mt-4">
                <button class="btn btn-primary btn-lg me-2">Add to Cart</button>
                <button class="btn btn-success btn-lg">Buy Now</button>
            </div>

            <!-- Back Link -->
            <div class="mt-3">
                <a href="{{ route('browse') }}" class="btn btn-secondary">← Back to Products</a>
            </div>
        </div>
    </div>

    <!-- Seller Info -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Seller Information</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $product->vendor->business_name }}</h6>
                    <p>{{ $product->vendor->business_address }}</p>
                    <p>Email: {{ $product->vendor->business_email }}</p>
                    <p>Phone: {{ $product->vendor->business_phone }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection