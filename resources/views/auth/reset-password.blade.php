<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset your password - SwiftShop</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom Auth Styles -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <!-- Back to Login -->
    <div class="back-home">
        <a href="{{ route('login') }}">
            <i class="fas fa-arrow-left"></i>
            Back to sign in
        </a>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="SwiftShop" />
                </div>
                <h1 class="auth-title">Set new password</h1>
                <p class="auth-subtitle">Enter your new password below</p>
            </div>

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.store') }}" class="auth-form">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        id="email" 
                        class="form-control @error('email') error @enderror" 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $request->email) }}" 
                        placeholder="Your email address"
                        required 
                        autofocus 
                        autocomplete="username" 
                    />
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input 
                        id="password" 
                        class="form-control @error('password') error @enderror" 
                        type="password" 
                        name="password" 
                        placeholder="Enter your new password"
                        required 
                        autocomplete="new-password" 
                    />
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input 
                        id="password_confirmation" 
                        class="form-control @error('password_confirmation') error @enderror"
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm your new password"
                        required 
                        autocomplete="new-password" 
                    />
                    @error('password_confirmation')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Reset Password Button -->
                <button type="submit" class="btn-primary">
                    Reset Password
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer">
                <p>
                    Remember your password? 
                    <a href="{{ route('login') }}" class="auth-link">Sign in</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>