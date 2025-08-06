@extends('layouts.account')

@section('title', 'Address Book - SwiftShop')

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
                    <a href="{{ route('test.account.profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i>
                        Profile Information
                    </a>
                    <a href="{{ route('test.account.order-history') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-history me-2"></i>
                        Order History
                    </a>
                    <a href="{{ route('test.account.address-book') }}" class="list-group-item list-group-item-action active">
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Address Book</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus me-2"></i>Add New Address
                    </button>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- Addresses List -->
                    <div id="addressesList">
                        @if($addresses->count() > 0)
                            @foreach($addresses as $address)
                                <div class="address-item mb-3" data-address-id="{{ $address->id }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-1">
                                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="address-details">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <h6 class="mb-0">{{ $address->label }}</h6>
                                                            @if($address->is_default)
                                                                <span class="badge bg-primary ms-2">Default</span>
                                                            @endif
                                                        </div>
                                                        <p class="mb-1">{{ $address->full_name }}</p>
                                                        <p class="mb-1 text-muted">{{ $address->address_line_1 }}</p>
                                                        @if($address->address_line_2)
                                                            <p class="mb-1 text-muted">{{ $address->address_line_2 }}</p>
                                                        @endif
                                                        <p class="mb-1 text-muted">{{ $address->city }}, {{ $address->postal_code }}</p>
                                                        <p class="mb-0 text-muted">{{ $address->country }}</p>
                                                        <p class="mb-0 text-muted"><i class="fas fa-phone me-1"></i>{{ $address->phone }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <div class="address-actions">
                                                        <button type="button" class="btn btn-sm btn-outline-primary me-2" 
                                                                onclick="editAddress({{ $address->id }})">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        @if(!$address->is_default)
                                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="deleteAddress({{ $address->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No addresses saved</h5>
                                <p class="text-muted">Add your first address to make checkout faster</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <i class="fas fa-plus me-2"></i>Add Your First Address
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAddressForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address Label</label>
                            <select class="form-select" name="label" required>
                                <option value="Home">Home</option>
                                <option value="Office">Office</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Street Address</label>
                            <input type="text" class="form-control" name="address_line_1" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address Line 2 (Optional)</label>
                            <input type="text" class="form-control" name="address_line_2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Postal Code</label>
                            <input type="text" class="form-control" name="postal_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_default" id="setAsDefault">
                                <label class="form-check-label" for="setAsDefault">Set as default address</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAddressForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editAddressId" name="address_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address Label</label>
                            <select class="form-select" name="label" id="editLabel" required>
                                <option value="Home">Home</option>
                                <option value="Office">Office</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" id="editFullName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" id="editPhone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" id="editCity" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Street Address</label>
                            <input type="text" class="form-control" name="address_line_1" id="editAddressLine1" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address Line 2 (Optional)</label>
                            <input type="text" class="form-control" name="address_line_2" id="editAddressLine2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Postal Code</label>
                            <input type="text" class="form-control" name="postal_code" id="editPostalCode" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_default" id="editSetAsDefault">
                                <label class="form-check-label" for="editSetAsDefault">Set as default address</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Address</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/address-book.js') }}"></script>
@endpush