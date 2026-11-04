<aside class="sidebar bg-slate-900 text-white d-flex flex-column justify-content-between" id="sidebar">
    <div>
        <!-- Brand Box -->
        <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center gap-2">
            <div
                class="brand-badge bg-emerald text-white fw-bold rounded d-flex align-items-center justify-content-center">
                NSM
            </div>
            <div>
                <div class="fw-bold small lh-1">NOOR SUPER MART</div>
                <small class="text-secondary" style="font-size: 10px;">B2B Core ERP v1.0</small>
            </div>
        </div>

        <!-- Active Role Box -->
        <div class="p-3">
            <div class="role-card p-2 rounded bg-slate-800 border border-secondary border-opacity-25">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-secondary" style="font-size: 10px; font-weight: 600;">ACTIVE ROLE</span>
                    <span class="badge bg-emerald text-white" style="font-size: 9px;">OWNER</span>
                </div>
                <div class="fw-semibold small text-white">Haji Noor Mohammad</div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="px-2">
            <div class="text-secondary px-2 mb-1" style="font-size: 10px; font-weight: 600; letter-spacing: 0.5px;">MAIN
                NAVIGATION</div>
            <ul class="nav flex-column sidebar-nav gap-1">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link active d-flex align-items-center justify-content-between">
                        <span><i data-lucide="layout-dashboard" class="icon-sm me-2"></i>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link d-flex align-items-center justify-content-between">
                        <span><i data-lucide="package" class="icon-sm me-2"></i>Products</span>
                        <span class="badge rounded-pill bg-warning text-dark">2</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="shopping-bag"
                            class="icon-sm me-2"></i>Purchase</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="boxes" class="icon-sm me-2"></i>Stock</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="users" class="icon-sm me-2"></i>Customers</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="user-check" class="icon-sm me-2"></i>Salesmen</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link d-flex align-items-center justify-content-between">
                        <span><i data-lucide="clipboard-list" class="icon-sm me-2"></i>Orders</span>
                        <span class="badge rounded-pill bg-warning text-dark">1</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="truck" class="icon-sm me-2"></i>Delivery</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="rotate-ccw" class="icon-sm me-2"></i>Returns</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="credit-card"
                            class="icon-sm me-2"></i>Payments</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="book-open" class="icon-sm me-2"></i>Ledger</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('expenses.index') }}" class="nav-link"><i data-lucide="receipt"
                            class="icon-sm me-2"></i>Expenses (খরচ)</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link"><i data-lucide="bar-chart-2"
                            class="icon-sm me-2"></i>Reports</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="user-cog" class="icon-sm me-2"></i>Users</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i data-lucide="settings" class="icon-sm me-2"></i>Settings</a>
                </li>

                <!-- Logout Option -->
                <div class="px-3 mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="nav-link text-danger border-0 bg-transparent d-flex align-items-center gap-2 w-100 p-0"
                            style="font-size: 13px;">
                            <i data-lucide="log-out" class="icon-sm"></i> Logout
                        </button>
                    </form>
                </div>
            </ul>
        </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-3 border-top border-secondary border-opacity-25 mt-3">
        <div class="d-flex align-items-center gap-2 text-emerald small fw-medium mb-1" style="font-size: 11px;">
            <i data-lucide="refresh-cw" class="icon-xs"></i> Automatic Ledger Sync
        </div>
        <p class="text-secondary mb-0" style="font-size: 10px; line-height: 1.3;">
            Single-entry system. No manual ledger calculation required.
        </p>
    </div>
</aside>
