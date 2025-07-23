<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Secure Checkout - SwiftShop')</title>
    
    <!-- Bootstrap 5 CDN and SwiftShop styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
</head>
<body>
    <!-- Secure Checkout Navigation -->
    <nav class="navbar navbar-light bg-white shadow-sm py-3 checkout-navbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('test.browse') }}">
                    <img src="{{ asset('images/logo.png')}}" alt="SwiftShop" width="50px" height="50px">
                </a>
                
                <!-- Secure Checkout Badge (Center) -->
                <div class="secure-checkout-badge d-flex align-items-center">
                    <i class="fas fa-lock text-success me-2"></i>
                    <span class="secure-text">Secure Checkout</span>
                </div>
                
                <!-- Right Icons (Same as auth layout) -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Cart -->
                    <a href="{{ route('test.cart.index') }}" class="nav-link position-relative cart-icon">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge" style="display: none;">0</span>
                    </a>
                    
                    <!-- User Dropdown -->
                    <div class="dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-1"></i>
                            <span class="d-none d-sm-inline">{{ $userAccount['fullName'] }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Manage Account</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-history me-2"></i>Order History</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-heart me-2"></i>Wishlist</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i>Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content area -->
    <main>
        @yield('content')
    </main>
    
    <!-- Minimal Footer for Checkout -->
    <footer class="checkout-footer bg-light py-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0 text-muted">© 2025 SwiftShop. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="{{ asset('js/checkout.js') }}"></script>
</body>
</html>