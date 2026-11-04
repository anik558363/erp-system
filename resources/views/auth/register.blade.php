<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOOR SUPER MART - Register Staff Account</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body class="bg-light-subtle d-flex align-items-center justify-content-center min-vh-100 py-4">

    <div class="container" style="max-width: 460px;">

        <!-- Brand Header / Logo Box -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-emerald text-white fw-bold rounded shadow-xs mb-2" style="width: 54px; height: 54px; font-size: 20px; background-color: #059669 !important;">
                NSM
            </div>
            <h1 class="h5 fw-bold text-dark mb-1">NOOR SUPER MART</h1>
            <p class="text-muted small font-mono mb-0" style="font-size: 11px;">B2B DISTRIBUTION SYSTEM &bull; CORE ERP v1.0</p>
        </div>

        <!-- Register Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-4">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 15px;">Create Staff Account</h5>
                <p class="text-muted small mb-4">Register a new user profile with role-based access control.</p>

                <!-- Validation Errors Alert -->
                @if ($errors->any())
                    <div class="alert alert-danger py-2 small mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Laravel Register Form -->
                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-sm py-2" placeholder="e.g. Md. Rahim" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-sm py-2 font-mono" placeholder="rahim@noorsupermart.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Phone Number *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control form-control-sm py-2 font-mono" placeholder="01931654590" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Assign Role (RBAC) *</label>
                        <select name="role" class="form-select form-select-sm py-2" required>
                            <option value="" selected disabled>Select staff role...</option>
                            <option value="Owner">Owner / Admin</option>
                            <option value="Office">Office / Accounts</option>
                            <option value="Salesman">Salesman</option>
                            <option value="Warehouse">Warehouse Staff</option>
                            <option value="Delivery">Delivery Man</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Password *</label>
                        <input type="password" name="password" class="form-control form-control-sm py-2" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-sm py-2" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
                    </div>

                    <div class="d-grid mb-3 pt-2">
                        <button type="submit" class="btn btn-emerald text-white fw-semibold py-2 bg-emerald shadow-xs" style="background-color: #059669 !important; font-size: 13px;">
                            Register New Account
                        </button>
                    </div>
                </form>

                <div class="text-center pt-3 border-top small text-muted">
                    Already have an account? <a href="{{ route('login') }}" class="text-decoration-none text-emerald fw-semibold">Sign In</a>
                </div>
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
    <script src="{{ asset('script.js') }}"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
