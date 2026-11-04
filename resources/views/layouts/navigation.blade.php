<!-- Top Header Navbar -->
<header class="top-header bg-white border-bottom sticky-top px-3 py-2 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2 gap-md-3">
        <!-- Mobile Sidebar Toggle Button -->
        <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle" type="button">
            <i data-lucide="menu" class="icon-sm"></i>
        </button>
        <div>
            <h1 class="h6 fw-bold mb-0 text-dark text-truncate d-none d-md-block" style="max-width: 150px;">NOOR SUPER
                MART</h1>
            <small class="text-muted d-none d-sm-block" style="font-size: 10px; letter-spacing: 0.5px;">B2B DISTRIBUTION
                SYSTEM</small>
        </div>
        <div class="vr mx-2 d-none d-md-block"></div>
        <div class="d-none d-md-flex align-items-center gap-1 text-muted small">
            <span>Active Module:</span>
            <strong class="text-dark">Dashboard</strong>
        </div>
    </div>

    <!-- Center Status Pill (Dynamic User Name & Role) -->
    <div class="d-none d-xl-flex align-items-center gap-3">
        <span
            class="badge bg-emerald-subtle text-emerald border border-emerald-subtle rounded-pill px-3 py-1 fw-medium d-flex align-items-center gap-2">
            <span class="status-dot"></span>
            {{ auth()->user()->name ?? 'Guest User' }}
            <span class="text-muted">({{ auth()->user()->getRoleNames()->first() ?? 'User' }})</span>
        </span>
        <span class="text-muted small font-mono">Central HQ / All Zones</span>
    </div>

    <!-- Right Role Badge / Profile Quick View -->
    <div class="d-flex align-items-center gap-2">
        <div class="bg-light border rounded px-3 py-1 d-flex align-items-center gap-2">
            <span class="text-muted small">
                <i data-lucide="user-check" class="icon-xs me-1 text-success"></i>Role:
            </span>
            <span class="fw-semibold text-dark" style="font-size: 12px;">
                {{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}
            </span>
        </div>
    </div>
</header>
