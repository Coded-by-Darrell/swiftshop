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
                        <div class="col-12 col-sm-6 col-md-5 mb-4 mb-md-0">
                            <div class="card" >
                                <img src="../images/guest/francc.png" class="card-img-top" alt="..." height="350px">
                                <div class="card-body">
                                  <h5 class="card-title">Francc Heartfilla</h5>
                                  <p class="card-text text-muted">Founder & CEO</p>
                                  <p class="card-text">With over 15 years in e-commerce, Darrell leads our vision to create the most user-friendly marketplace.</p>
                                </div>
                              </div>
                        </div>
                    
                        <div class="col-12 col-sm-6 col-md-5 mb-4 mb-md-0">
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



@endsection