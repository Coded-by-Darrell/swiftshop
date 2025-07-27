<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create your account - SwiftShop</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom Auth Styles -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <!-- Back to Home -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="pt-3 ps-3">
                    <a href="{{ url('/') }}" class="d-inline-flex align-items-center text-decoration-none" style="color: #495057; font-size: 0.9rem; font-weight: 500;">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to SwiftShop
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="auth-container" style="margin-top: 2rem;">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="SwiftShop" />
                </div>
                <h1 class="auth-title">Create your account</h1>
                <p class="auth-subtitle">Join SwiftShop and start shopping from hundreds of vendors</p>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input 
                        id="name" 
                        class="form-control @error('name') error @enderror" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        placeholder="Enter your full name"
                        required 
                        autofocus 
                        autocomplete="name" 
                    />
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        id="email" 
                        class="form-control @error('email') error @enderror" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Enter your email address"
                        required 
                        autocomplete="username" 
                    />
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        id="password" 
                        class="form-control @error('password') error @enderror"
                        type="password"
                        name="password"
                        placeholder="Create a strong password"
                        required 
                        autocomplete="new-password" 
                    />
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        id="password_confirmation" 
                        class="form-control @error('password_confirmation') error @enderror"
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        required 
                        autocomplete="new-password" 
                    />
                    @error('password_confirmation')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Terms Agreement -->
                <div class="checkbox-group">
                    <input 
                        id="agree_terms" 
                        type="checkbox" 
                        class="checkbox-input" 
                        name="agree_terms"
                        required
                    >
                    <label for="agree_terms" class="checkbox-label">
                        I agree to the <a href="#" class="auth-link">Terms of Service</a> and <a href="#" class="auth-link">Privacy Policy</a>
                    </label>
                </div>

                <!-- Subscribe Newsletter -->
                <div class="checkbox-group">
                    <input 
                        id="newsletter" 
                        type="checkbox" 
                        class="checkbox-input" 
                        name="newsletter"
                        checked
                    >
                    <label for="newsletter" class="checkbox-label">
                        Subscribe to our newsletter for deals and updates
                    </label>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn-primary">
                    Create Account
                </button>
            </form>

            <!-- Social Login -->
            <div class="social-login">
                <div class="divider">
                    <span>Or sign up with</span>
                </div>
                
                <div class="social-buttons">
                    <a href="#" class="btn-google">
                        <i class="fab fa-google"></i>
                        Google
                    </a>
                    <a href="#" class="btn-facebook">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="auth-footer">
                <p>
                    Already have an account? 
                    <a href="{{ route('login') }}" class="auth-link">Sign in</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



