<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirm password - SwiftShop</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom Auth Styles -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <!-- Back to Dashboard -->
    <div class="back-home">
        <a href="{{ route('test.browse') }}">
            <i class="fas fa-arrow-left"></i>
            Back to dashboard
        </a>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="SwiftShop" />
                </div>
                <h1 class="auth-title">Confirm password</h1>
                <p class="auth-subtitle">This is a secure area of the application. Please confirm your password before continuing.</p>
            </div>

            <!-- Security Icon -->
            <div class="text-center mb-4">
                <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--success-green); opacity: 0.8;"></i>
            </div>

            <!-- Confirm Password Form -->
            <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
                @csrf

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        id="password" 
                        class="form-control @error('password') error @enderror" 
                        type="password"
                        name="password"
                        placeholder="Enter your current password"
                        required 
                        autocomplete="current-password" 
                        autofocus
                    />
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Button -->
                <button type="submit" class="btn-primary">
                    Confirm Password
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer">
                <p style="font-size: 0.85rem; color: #6c757d;">
                    For your security, please confirm your password to access this protected area.
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>