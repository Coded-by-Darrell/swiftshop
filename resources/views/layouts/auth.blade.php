<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'SwiftShop - Swift. Simple. Everything.')</title>
    
    <!-- Add Bootstrap 5 CDN, CSS, and SwiftShop styling here -->
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>
   
        <!-- Navigation -->
        <nav class="navbar navbar-light bg-white shadow-sm py-3">
            <div class="container">
                <!-- Always visible, responsive layout -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <!-- Logo -->
                    <a class="navbar-brand" href="{{ url('/browse') }}">
                        <img src="{{ asset('images/logo.png')}}" alt="SwiftShop" width="60px" height="60px">
                    </a>
                    
                    <!-- Search Bar (HIDDEN ON MOBILE) -->
                    <div class="mx-3 flex-grow-1 d-none d-sm-block" style="max-width: 500px;">
                        <div class="input-group browse-search-bar">
                            <input type="text" class="form-control" placeholder="Search for products, brands, and more...">
                            <button class="btn btn-primary-custom" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Right Icons -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Cart -->
                        <a href="#" class="nav-link position-relative cart-icon">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">3</span>
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
        
        <!-- Mobile Search Bar (Only visible on mobile) -->
        <div class="mobile-search-section d-sm-none">
            <div class="container">
                <div class="mobile-search-bar">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search products...">
                        <button class="btn btn-primary-custom" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Main content area -->
        <main>
            @yield('content')
        </main>
        
        <!-- Footer will go here -->
        {{-- Footer --}}
<footer class="footer-section bg-dark text-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5 class="text-white mb-3">
                    <strong>SwiftShop</strong> 
                </h5>
                <p class="text-white mb-4">
                    Stay updated with our latest deals, Swift. Simple. Everything.
                </p>
                
                <div class="newsletter-form">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email address">
                        <button class="btn btn-primary-custom" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-white mb-3">Shop</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#categories" class="text-white">Electronics</a></li>
                    <li><a href="#categories" class="text-white">Fashion</a></li>
                    <li><a href="#categories" class="text-white">Home & Garden</a></li>
                    <li><a href="#categories" class="text-white">Gaming</a></li>
                    <li><a href="#categories" class="text-white">New Arrivals</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-white mb-3">Company</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#about" class="text-white">About Us</a></li>
                    <li><a href="#security" class="text-white">Security</a></li>
                    <li><a href="#how-it-works" class="text-white">How It Works</a></li>
                    <li><a href="#delivery" class="text-white">Delivery</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-white mb-3">Support</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#contact" class="text-white">Contact Us</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-white mb-3">Legal</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#" class="text-white">Privacy Policy</a></li>
                    <li><a href="#" class="text-white">Terms of Service</a></li>
                    <li><a href="#" class="text-white">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
        
        <hr class="my-4 border-secondary">
        
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="text-white mb-0">
                    © 2025 SwiftShop. All rights reserved.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="social-links">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
        <!-- Add Bootstrap JS CDN here -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>

