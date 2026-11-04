<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOOR SUPER MART - Login to B2B ERP</title>
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-light-subtle d-flex align-items-center justify-content-center min-vh-100">

    <div class="container" style="max-width: 420px;">

        <!-- Brand Header / Logo Box -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-emerald text-white fw-bold rounded shadow-xs mb-2"
                style="width: 54px; height: 54px; font-size: 20px; background-color: #059669 !important;">
                NSM
            </div>
            <h1 class="h5 fw-bold text-dark mb-1">NOOR SUPER MART</h1>
            <p class="text-muted small font-mono mb-0" style="font-size: 11px;">B2B DISTRIBUTION SYSTEM &bull; CORE ERP
                v1.0</p>
        </div>

        <!-- Login Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-4">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 15px;">Sign in to your account</h5>
                <p class="text-muted small mb-4">Enter your credentials to access the ERP dashboard.</p>

                <!-- Session Status / Errors Validation -->
                @if ($errors->any())
                    <div class="alert alert-danger py-2 small mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Laravel Login Form -->
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Email or Phone Number *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted border-end-0">
                                <i data-lucide="user" class="icon-xs"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control border-start-0 ps-0 shadow-none py-2"
                                placeholder="owner@noorsupermart.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-semibold text-dark mb-0">Password *</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-decoration-none text-emerald small fw-medium"
                                    style="font-size: 11px;">Forgot password?</a>
                            @endif
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted border-end-0">
                                <i data-lucide="lock" class="icon-xs"></i>
                            </span>
                            <input type="password" name="password"
                                class="form-control border-start-0 ps-0 shadow-none py-2"
                                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input shadow-none" id="rememberMe">
                        <label class="form-check-label small text-muted" for="rememberMe">Remember this device</label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-emerald text-white fw-semibold py-2 bg-emerald shadow-xs"
                            style="background-color: #059669 !important; font-size: 13px;">
                            Sign In to ERP Dashboard
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center mt-4 text-muted font-mono" style="font-size: 10px;">
            &copy; 2026 Noor Super Mart. Secure B2B Enterprise ERP.
        </div>

    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Script -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
