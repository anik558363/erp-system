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

    <!-- Top Header Navbar Inclusion -->
    @include('layouts.navigation')

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
