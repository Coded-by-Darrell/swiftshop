<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify your email - SwiftShop</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom Auth Styles -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="SwiftShop" />
                </div>
                <h1 class="auth-title">Verify your email</h1>
                <p class="auth-subtitle">Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.</p>
            </div>

            <!-- Success Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <!-- Email Icon -->
            <div class="text-center mb-4">
                <i class="fas fa-envelope-open" style="font-size: 4rem; color: var(--primary-blue); opacity: 0.7;"></i>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-column gap-3">
                <!-- Resend Verification Email -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-primary">
                        Resend Verification Email
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary">
                        Log Out
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="auth-footer">
                <p style="font-size: 0.85rem; color: #6c757d;">
                    If you didn't receive the email, check your spam folder or try resending the verification link.
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>