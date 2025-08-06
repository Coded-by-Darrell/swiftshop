@extends('layouts.account')

@section('title', 'Account Settings - SwiftShop')

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
                    <a href="{{ route('test.account.address-book') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Address Book
                    </a>
                    <a href="{{ route('test.account.settings') }}" class="list-group-item list-group-item-action active">
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
                    <h5>Account Settings</h5>
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
                    
                    <!-- Settings Navigation Tabs -->
                    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab">
                                <i class="fas fa-lock me-2"></i>Change Password
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="login-activity-tab" data-bs-toggle="tab" data-bs-target="#login-activity" type="button" role="tab">
                                <i class="fas fa-clock me-2"></i>Login Activity
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="danger-zone-tab" data-bs-toggle="tab" data-bs-target="#danger-zone" type="button" role="tab">
                                <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content mt-4" id="settingsTabContent">
                        <!-- Change Password Tab -->
                        <div class="tab-pane fade show active" id="change-password" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-3">Change Password</h6>
                                    <p class="text-muted mb-4">Ensure your account is using a long, random password to stay secure.</p>
                                    
                                    <form method="POST" action="{{ route('test.account.change-password') }}">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                   id="current_password" name="current_password" required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                                   id="new_password" name="new_password" required>
                                            @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                                   id="new_password_confirmation" name="new_password_confirmation" required>
                                            @error('new_password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-save">
                                            <i class="fas fa-save me-2"></i>Update Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Login Activity Tab -->
                        <div class="tab-pane fade" id="login-activity" role="tabpanel">
                            <h6 class="mb-3">Login Activity</h6>
                            <p class="text-muted mb-4">Review your recent login activity and sessions.</p>
                            
                            <div class="login-activity-list">
                                @forelse($loginActivities as $activity)
                                    <div class="activity-item border-bottom py-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="fas fa-{{ $activity['device_icon'] }} text-muted me-2"></i>
                                                    <strong>{{ $activity['device'] }}</strong>
                                                    @if($activity['is_current'])
                                                        <span class="badge bg-success ms-2">Current Session</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $activity['location'] }}
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="fas fa-globe me-1"></i>{{ $activity['ip_address'] }}
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="text-muted small">{{ $activity['login_time'] }}</div>
                                                @if(!$activity['is_current'])
                                                    <button class="btn btn-sm btn-outline-danger mt-1" onclick="terminateSession('{{ $activity['session_id'] }}')">
                                                        <i class="fas fa-times me-1"></i>Terminate
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No login activity recorded yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Danger Zone Tab -->
                        <div class="tab-pane fade" id="danger-zone" role="tabpanel">
                            <h6 class="mb-3 text-danger">Danger Zone</h6>
                            <p class="text-muted mb-4">Permanently delete your account and all associated data. This action cannot be undone.</p>
                            
                            <div class="border border-danger rounded p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="text-danger mb-2">Delete Account</h6>
                                        <p class="text-muted mb-0 small">
                                            Once you delete your account, all of your data will be permanently deleted. 
                                            Before deleting your account, please download any data or information that you wish to retain.
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                            <i class="fas fa-trash me-2"></i>Delete Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone and will permanently delete:</p>
                <ul class="text-muted">
                    <li>Your profile information</li>
                    <li>Order history</li>
                    <li>Saved addresses</li>
                    <li>All account data</li>
                </ul>
                <p class="text-danger fw-bold">This action is permanent and cannot be reversed.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="alert('Account deletion functionality will be implemented later.')">
                    <i class="fas fa-trash me-2"></i>Delete My Account
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function terminateSession(sessionId) {
    if (confirm('Are you sure you want to terminate this session?')) {
        fetch('{{ route("test.account.terminate-session") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                session_id: sessionId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert(data.message);
                // Remove the session row from the page
                location.reload();
            } else {
                alert(data.message || 'Error terminating session');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while terminating the session');
        });
    }
}
</script>
@endpush
@endsection