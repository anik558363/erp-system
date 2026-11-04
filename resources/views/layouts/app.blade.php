<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NOOR SUPER MART - B2B Distribution System')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- Top Header Navbar -->
    <header class="top-header bg-white border-bottom sticky-top px-3 py-2 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle" type="button">
                <i data-lucide="menu" class="icon-sm"></i>
            </button>
            <div>
                <h1 class="h6 fw-bold mb-0 text-dark text-truncate d-none d-md-block" style="max-width: 150px;">NOOR SUPER MART</h1>
                <small class="text-muted d-none d-sm-block" style="font-size: 10px; letter-spacing: 0.5px;">B2B DISTRIBUTION SYSTEM</small>
            </div>
            <div class="vr mx-2 d-none d-md-block"></div>
            <div class="d-none d-md-flex align-items-center gap-1 text-muted small">
                <span>Active Module:</span>
                <strong class="text-dark">Dashboard</strong>
            </div>
        </div>

        <!-- Center Status Pill -->
        <div class="d-none d-xl-flex align-items-center gap-3">
            <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle rounded-pill px-3 py-1 fw-medium d-flex align-items-center gap-2">
                <span class="status-dot"></span> Haji Noor Mohammad
            </span>
            <span class="text-muted small font-mono">Central HQ / All Zones</span>
        </div>

        <!-- Right Role Switcher -->
        <div class="d-flex align-items-center gap-2">
            <div class="bg-light border rounded px-2 py-1 d-flex align-items-center gap-2">
                <span class="text-muted small d-none d-sm-inline"><i data-lucide="repeat" class="icon-xs me-1"></i>Role Switch:</span>
                <select class="form-select form-select-sm border-0 bg-transparent fw-semibold text-dark py-0 ps-1 pe-4 shadow-none" style="width: auto; font-size: 12px;">
                    <option>Haji Noor Mohammad (Owner / Admin)</option>
                    <option>Depot Manager</option>
                    <option>Sales Executive</option>
                </select>
            </div>
        </div>
    </header>

    <div class="app-wrapper d-flex">
        <!-- Sidebar Navigation Inclusion -->
        @include('layouts.sidebar')

        <!-- Sidebar Backdrop for Mobile -->
        <div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>

        <!-- Main Content Area -->
        <main class="main-content flex-grow-1 p-3 p-md-4 bg-light-subtle">
            <div class="container-fluid px-0" style="max-width: 1400px;">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript File Link -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
    </script>
</body>
</html>
