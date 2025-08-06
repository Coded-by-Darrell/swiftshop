@extends('layouts.account')

@section('title', 'My Account - Profile Information')

@section('content')

<div class="container account-container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card account-sidebar">
                <div class="card-header">
                    <h5>My Account</h5>
                    <p>Manage your account settings and preferences</p>
                </div>
                
                <!-- Light gray break line -->
                <div class="sidebar-divider"></div>
                
                <div class="list-group list-group-flush">
                    <a href="{{ route('test.account.profile') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i>
                        Profile Information
                    </a>
                    <a href="{{ route('test.account.order-history') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-history me-2"></i>
                        Order History
                    </a>
                    <a href="{{ route('test.account.address-book') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Address Book
                    </a>
                    
                    <a href="{{ route('test.account.settings') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-cog me-2"></i>
                        Account Settings
                    </a>
                    @if(Auth::user()->isVendor())
                        <div class="sidebar-divider"></div>
                        <a href="{{ route('test.seller.overview') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Seller Overview
                        </a>
                        <a href="{{ route('test.seller.products') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-box me-2"></i>
                            My Products
                        </a>
                        <a href="{{ route('test.seller.orders') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Seller Orders
                        </a>
                        @endif
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card account-main-card">
                <div class="card-header">
                    <h5>Personal Details</h5>
                    <a href="{{ route('test.account.edit-profile') }}" class="btn btn-edit-profile">
                        <i class="fas fa-edit me-1"></i>Edit Profile
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- Profile Picture and Basic Info -->
                    <div class="profile-basic-info">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="profile-picture-container mb-3">
                                    <img src="{{ $user->profile_picture && Storage::disk('public')->exists($user->profile_picture) 
                                                ? Storage::url($user->profile_picture) 
                                                : asset('images/default-profile-pic.jpg') }}" 
                                         alt="Profile Picture" 
                                         class="rounded-circle profile-picture">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h4>{{ $user->name }}</h4>
                                <p class="member-since">Member since {{ $user->created_at->format('F Y') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personal Information Grid -->
                    <div class="profile-info-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <div class="field-label">Full Name</div>
                                    <p class="field-value">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <div class="field-label">Email Address</div>
                                    <p class="field-value">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <div class="field-label">Phone Number</div>
                                    <p class="field-value not-provided">Not provided</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <div class="field-label">Date of Birth</div>
                                    <p class="field-value not-provided">Not provided</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <div class="field-label">Gender</div>
                                    <p class="field-value not-provided">Not specified</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Status Section -->
                    <div class="account-status-section">
                        <h6 class="status-section-title">Account Status</h6>
                        
                        <div class="status-info-grid">
                            <div class="status-details">
                                <div class="status-item">
                                    <span class="status-label">Member since:</span>
                                    <span class="status-value">{{ $user->created_at->format('F j, Y') }}</span>
                                </div>
                                <div class="status-item">
                                    <span class="status-label">Account type:</span>
                                    <span class="status-value">{{ $user->role ?? 'Regular Customer' }}</span>
                                </div>
                            </div>
                            
                            {{-- @if($user->role === 'customer' || !$user->role)
                            <div class="apply-seller-section">
                                <a href='#' class="btn btn-apply-seller">
                                    <i class="fas fa-store"></i>
                                    Apply as Seller
                                </a>
                            </div>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection