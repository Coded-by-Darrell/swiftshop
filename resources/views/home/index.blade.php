@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="hero-section d-flex align-items-center mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-7">
                    <div class="hero-content text-white">
                        <h1 class="hero-title mb-4">
                            SWIFT. SIMPLE.<br>
                            EVERYTHING.
                        </h1>
                        <p class="hero-subtitle mb-5">
                            Shop from hundreds of vendors in one place.
                        </p>
                        <div class="hero-buttons">
                            <a href="#" class="btn btn-dark btn-lg me-3 mb-3 mb-sm-3 mb-md-0">Start Shopping</a>
                            <a href="#categories" class="btn btn-outline-light btn-lg">Browse Categories</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- About --}}
    <section class="about-section d-flex align-items-center">
        <div class="container">
            <div class="row">
                {{-- title & subtitle --}}
                <div class="about-content text-center">
                    <h1 class="section-header">About SwiftShop</h1>
                    <p class="section-subtitle">SwiftShop is a premier online marketplace connecting quality vendors with discerning shoppers. Our platform offers a seamless shopping experience with secure transactions and swift delivery.</p>
                </div>

                {{-- cards --}}
                <div class="about-cards mb-5"> 
                    <div class="row justify-content-center">
                        <div class="col-10 col-sm-6 col-md-5 mb-4 mb-md-0">
                            <div class="card" >
                                <img src="../images/guest/francc.png" class="card-img-top" alt="..." height="350px">
                                <div class="card-body">
                                  <h5 class="card-title">Francc Heartfilla</h5>
                                  <p class="card-text text-muted">Founder & CEO</p>
                                  <p class="card-text">With over 15 years in e-commerce, Darrell leads our vision to create the most user-friendly marketplace.</p>
                                </div>
                              </div>
                        </div>
                    
                        <div class="col-10 col-sm-6 col-md-5 mb-4 mb-md-0">
                            <div class="card">
                                <img src="../images/guest/alex.png" class="card-img-top" alt="..." height="350px">
                                <div class="card-body">
                                  <h5 class="card-title">Alex Chen</h5>
                                  <p class="card-text text-muted">Co-Founder</p>
                                  <p class="card-text">My role is to ensure SwiftShop not only works flawlessly but delivers an experience that customers and vendors truly love. </p>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Shop by Category --}}
    <section class="category-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-header">Shop By Category</h2>
                </div>
            </div>
            
            <div class="row g-4">
                {{-- Electronics --}}
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="category-card text-center">
                        <div class="category-icon mb-3">
                            <i class="fas fa-laptop fa-2x text-primary"></i>
                        </div>
                        <h6 class="category-name">Electronics</h6>
                    </div>
                </div>
                
                {{-- Fashion --}}
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="category-card text-center">
                        <div class="category-icon mb-3">
                            <i class="fas fa-tshirt fa-2x text-primary"></i>
                        </div>
                        <h6 class="category-name">Fashion</h6>
                    </div>
                </div>
                
                {{-- Home & Garden --}}
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="category-card text-center">
                        <div class="category-icon mb-3">
                            <i class="fas fa-home fa-2x text-primary"></i>
                        </div>
                        <h6 class="category-name">Home & Garden</h6>
                    </div>
                </div>
                
                {{-- Gaming --}}
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="category-card text-center">
                        <div class="category-icon mb-3">
                            <i class="fas fa-gamepad fa-2x text-primary"></i>
                        </div>
                        <h6 class="category-name">Gaming</h6>
                    </div>
                </div>
                
                {{-- Photography --}}
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="category-card text-center">
                        <div class="category-icon mb-3">
                            <i class="fas fa-camera fa-2x text-primary"></i>
                        </div>
                        <h6 class="category-name">Photography</h6>
                    </div>
                </div>
                
                {{-- Audio --}}
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="category-card text-center">
                        <div class="category-icon mb-3">
                            <i class="fas fa-headphones fa-2x text-primary"></i>
                        </div>
                        <h6 class="category-name">Audio</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Buyers and Sellers Security --}}
    <section class="security-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-header">Buyers and Sellers Security</h2>
                </div>
            </div>
            
            <div class="row g-4">
                {{-- Verified Vendors --}}
                <div class="col-12 col-md-4">
                    <div class="security-card text-center">
                        <div class="security-icon mb-4">
                            <i class="fas fa-user-check fa-3x text-primary"></i>
                        </div>
                        <h5 class="security-title mb-3">Verified Vendors</h5>
                        <p class="security-description">
                            All vendors undergo a strict verification process to ensure quality and reliability.
                        </p>
                    </div>
                </div>

                {{-- Secure Payments --}}
                <div class="col-12 col-md-4">
                    <div class="security-card text-center">
                        <div class="security-icon mb-4">
                            <i class="fas fa-credit-card fa-3x text-primary"></i>
                        </div>
                        <h5 class="security-title mb-3">Secure Payments</h5>
                        <p class="security-description">
                            Your payment information is encrypted and securely processed using industry-leading technology.
                        </p>
                    </div>
                </div>

                {{-- Purchase Protection --}}
                <div class="col-12 col-md-4">
                    <div class="security-card text-center">
                        <div class="security-icon mb-4">
                            <i class="fa-solid fa-user-shield fa-3x text-primary"></i>
                        </div>
                        <h5 class="security-title mb-3">Purchase Protection</h5>
                        <p class="security-description">
                            Every purchase is backed by our buyer protection program for peace of mind.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="how-it-works-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-header">How It Works</h2>
                </div>
            </div>
            
            <div class="row">
                {{-- Step 1: Browse --}}
                <div class="col-12 col-md-4 text-center mb-4 mb-md-0">
                    <div class="step-container">
                        <div class="step-circle">
                            <i class="fas fa-search fa-2x"></i>
                        </div>
                        <h5 class="step-title mt-3">Browse</h5>
                        <p class="step-description">
                            Explore thousands of products from verified vendors across multiple categories.
                        </p>
                    </div>
                </div>
                
                {{-- Step 2: Buy --}}
                <div class="col-12 col-md-4 text-center mb-4 mb-md-0">
                    <div class="step-container">
                        <div class="step-circle">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                        <h5 class="step-title mt-3">Buy</h5>
                        <p class="step-description">
                            Add products to cart and checkout securely with multiple payment options.
                        </p>
                    </div>
                </div>
                
                {{-- Step 3: Delivered --}}
                <div class="col-12 col-md-4 text-center">
                    <div class="step-container">
                        <div class="step-circle">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                        <h5 class="step-title mt-3">Delivered</h5>
                        <p class="step-description">
                            Track your order and receive reliable delivery straight to your doorstep.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>      

{{-- Delivery Section --}}
<section class="delivery-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-header">Delivery. Swift and reliable.</h2>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="delivery-table-container">
                    <table class="table table-borderless delivery-table text-center">
                        <thead>
                            <tr>
                                <th>Delivery Option</th>
                                <th>Price</th>
                                <th>Estimated Delivery</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Standard</strong></td>
                                <td><strong>₱45.00</strong></td>
                                <td>3-5 business days</td>
                            </tr>
                            <tr>
                                <td><strong>Express</strong></td>
                                <td><strong>₱85.00</strong></td>
                                <td>1-2 business days</td>
                            </tr>
                            <tr>
                                <td><strong>Same Day</strong></td>
                                <td><strong>₱150.00</strong></td>
                                <td>Same day within Metro Manila</td>
                            </tr>
                            <tr>
                                <td><strong>International</strong></td>
                                <td><strong>₱350.00</strong></td>
                                <td>7-14 business days</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ Section --}}
<section class="faq-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-header">Questions. Meet answers.</h2>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                How do I create an account?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Simply click the "Sign Up" button and fill in your details. You'll receive a confirmation email to activate your account.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We currently accept Cash on Delivery (COD) for all orders within the Philippines.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                How long does shipping take?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Standard delivery takes 3-5 business days, Express delivery takes 1-2 business days, and Same Day delivery is available within Metro Manila.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                What is your return policy?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We offer a 7-day return policy for unopened items in original condition. Contact our support team to initiate a return.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                How do I become a vendor?
                            </button>
                        </h2>
                        <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Click "Apply as Seller" in your account dashboard, submit required documents, and wait for admin approval. The process typically takes 3-5 business days.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contact Section --}}
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-header">Help is here.</h2>
                <p class="section-subtitle">How can we assist you today?</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="contact-form-container">
                    <form class="contact-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fullName" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="fullName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number (optional)</label>
                                <input type="tel" class="form-control" id="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="orderNumber" class="form-label">Order Number (optional)</label>
                                <input type="text" class="form-control" id="orderNumber" placeholder="Leave blank if not order-related">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="issueType" class="form-label">Subject/Issue Type *</label>
                            <select class="form-select" id="issueType" required>
                                <option value="">Select an issue type</option>
                                <option value="order">Order Issue</option>
                                <option value="payment">Payment Problem</option>
                                <option value="delivery">Delivery Inquiry</option>
                                <option value="account">Account Support</option>
                                <option value="vendor">Vendor Application</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" rows="5" placeholder="Please describe your issue in detail..." required></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="attachment" class="form-label">Attach screenshots or documents</label>
                            <input type="file" class="form-control" id="attachment" multiple>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary-custom px-5">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-md-6 col-lg-12 mb-4">
                        <div class="contact-card text-center">
                            <div class="contact-icon mb-3">
                                <i class="fas fa-envelope fa-2x text-primary"></i>
                            </div>
                            <h5>Email Us</h5>
                            <p class="text-muted">Get help via email and we'll respond within 24 hours</p>
                            <a href="mailto:support@swiftshop.com" class="btn btn-outline-primary">support@swiftshop.com</a>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-12 mb-4">
                        <div class="contact-card text-center">
                            <div class="contact-icon mb-3">
                                <i class="fas fa-phone fa-2x text-primary"></i>
                            </div>
                            <h5>Call Us</h5>
                            <p class="text-muted">Speak directly with our customer service team</p>
                            <a href="tel:+639123456789" class="btn btn-outline-primary">+63 912 345 6789</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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


@endsection