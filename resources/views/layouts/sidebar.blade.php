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
                    <span class="badge bg-emerald text-white" style="font-size: 9px;">
                        @if (auth()->check())
                            {{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}
                        @endif
                    </span>
                </div>
                <div class="fw-semibold small text-white">{{ auth()->user()->name ?? 'Guest User' }}</div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="px-2">
            <div class="text-secondary px-2 mb-1" style="font-size: 10px; font-weight: 600; letter-spacing: 0.5px;">MAIN
                NAVIGATION</div>
            <ul class="nav flex-column sidebar-nav gap-1">

                <!-- ১. সবার জন্য কমন ড্যাশবোর্ড -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span><i data-lucide="layout-dashboard" class="icon-sm me-2"></i>Dashboard</span>
                    </a>
                </li>

                <!-- ২. OWNER এবং OFFICE রোলের জন্য মেনু -->
                @role('Owner|Office')
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}"
                            class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i data-lucide="package" class="icon-sm me-2"></i>Products
                        </a>
                    </li>


                    <!-- POS Management Dropdown Menu -->
                    <li class="nav-item">
                        <a href="#posSubmenu" data-bs-toggle="collapse"
                            class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                            <div class="d-flex align-items-center">
                                <i data-lucide="shopping-cart" class="icon-sm me-2"></i>
                                <span>POS Management</span>
                            </div>
                            <i data-lucide="chevron-down" class="icon-xs"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('pos.*') ? 'show' : '' }}" id="posSubmenu">
                            <ul class="nav flex-column ms-3 ps-2 border-start border-secondary border-opacity-25 mt-1 gap-1"
                                style="font-size: 12px;">
                                <!-- ১. Registers CRUD -->
                                <li class="nav-item">
                                    <a href="{{ route('pos.registers.index') }}"
                                        class="nav-link py-1 {{ request()->routeIs('pos.registers.*') ? 'fw-bold text-emerald' : 'text-secondary' }}">
                                        Registers CRUD
                                    </a>
                                </li>
                                <!-- ২. Shift & Register -->
                                <li class="nav-item">
                                    <a href="{{ route('pos.shift.index') }}"
                                        class="nav-link py-1 {{ request()->routeIs('pos.shift.*') ? 'fw-bold text-emerald' : 'text-secondary' }}">
                                        Shift & Register
                                    </a>
                                </li>
                                <!-- ৩. POS Terminal -->
                                <li class="nav-item">
                                    <a href="{{ route('pos.index') }}"
                                        class="nav-link py-1 {{ request()->routeIs('pos.index') || request()->routeIs('pos.store') ? 'fw-bold text-emerald' : 'text-secondary' }}">
                                        POS Terminal
                                    </a>
                                </li>
                                <!-- ৪. Sales History & Invoices -->
                                <li class="nav-item">
                                    <a href="{{ route('pos.sales.index') }}"
                                        class="nav-link py-1 {{ request()->routeIs('pos.sales.*') ? 'fw-bold text-emerald' : 'text-secondary' }}">
                                        Sales History & Invoices
                                    </a>
                                </li>
                                <!-- ৫. নতুন যোগ করা POS Returns -->
                                <li class="nav-item">
                                    <a href="{{ route('pos.returns.index') }}"
                                        class="nav-link py-1 {{ request()->routeIs('pos.returns.*') ? 'fw-bold text-emerald' : 'text-secondary' }}">
                                        POS Returns
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('categories.index') }}"
                            class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i data-lucide="layers" class="icon-sm me-2"></i>Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('areas.index') }}"
                            class="nav-link {{ request()->routeIs('areas.*') ? 'active' : '' }}">
                            <i data-lucide="map-pin" class="icon-sm me-2"></i>Areas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('brands.index') }}"
                            class="nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                            <i data-lucide="tag" class="icon-sm me-2"></i>Brands
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('customers.index') }}"
                            class="nav-link {{ request()->routeIs('customers.index') || request()->routeIs('customers.edit') ? 'active' : '' }}">
                            <i data-lucide="users" class="icon-sm me-2"></i>Customers
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('purchases.index') }}"
                            class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                            <i data-lucide="shopping-bag" class="icon-sm me-2"></i>Purchase
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('suppliers.index') }}"
                            class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                            <i data-lucide="truck" class="icon-sm me-2"></i>Suppliers
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('supplier.payments.index') }}"
                            class="nav-link {{ request()->routeIs('supplier.payments.*') ? 'active' : '' }}">
                            <i data-lucide="credit-card" class="icon-sm me-2"></i> Supplier Payments
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('stock.index') }}"
                            class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                            <i data-lucide="box" class="icon-sm me-2"></i> Stock Inventory
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i data-lucide="user-check"
                                class="icon-sm me-2"></i>Salesmen</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders.index') }}"
                            class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                            <span><i data-lucide="clipboard-list" class="icon-sm me-2"></i>Orders</span>
                            <span class="badge rounded-pill bg-warning text-dark">1</span>
                        </a>
                    </li>

                    <!-- Delivery Menu Route Added -->
                    <li class="nav-item">
                        <a href="{{ route('delivery.index') }}"
                            class="nav-link {{ request()->routeIs('delivery.*') ? 'active' : '' }}">
                            <i data-lucide="truck" class="icon-sm me-2"></i>Delivery
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i data-lucide="rotate-ccw"
                                class="icon-sm me-2"></i>Returns</a>
                    </li>

                    <!-- Payments menu -->
                    <li class="nav-item">
                        <a href="{{ route('customers.payments.index') }}"
                            class="nav-link {{ request()->routeIs('customers.payments.*') ? 'active' : '' }}">
                            <i data-lucide="credit-card" class="icon-sm me-2"></i>Payments & Collections
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i data-lucide="book-open"
                                class="icon-sm me-2"></i>Ledger</a>
                    </li>

                    <!-- Expense Management Menu -->
                    <li class="nav-item">
                        <a href="#expenseSubmenu" data-bs-toggle="collapse"
                            class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('expenses.*') || request()->routeIs('expense.categories.*') ? 'active' : '' }}">
                            <div class="d-flex align-items-center">
                                <i data-lucide="wallet" class="icon-sm me-2"></i>
                                <span>Expense Management</span>
                            </div>
                            <i data-lucide="chevron-down" class="icon-xs"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('expenses.*') || request()->routeIs('expense.categories.*') ? 'show' : '' }}"
                            id="expenseSubmenu">
                            <ul class="nav flex-column ms-3 ps-2 border-start border-secondary border-opacity-25 mt-1 gap-1"
                                style="font-size: 12px;">
                                <!-- ১. Expense List -->
                                <li class="nav-item">
                                    <a href="{{ route('expenses.index') }}"
                                        class="nav-link py-1 {{ request()->routeIs('expenses.*') ? 'fw-bold text-emerald' : 'text-secondary' }}">
                                        All Expenses
                                    </a>
                                </li>
                                <!-- ২. Expense Categories -->
                                <li class="nav-item">
                                    <a href="{{ route('expense.categories.index') }}"
                                        class="nav-link py-1 {{ request()->routeIs('expense.categories.*') ? 'fw-bold text-emerald' : 'text-secondary' }}">
                                        Categories
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}"
                            class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"><i
                                data-lucide="bar-chart-2" class="icon-sm me-2"></i>Reports</a>
                    </li>
                @endrole

                <!-- ৩. WAREHOUSE রোলের জন্য মেনু -->
                @role('Warehouse')
                    <li class="nav-item">
                        <a href="{{ route('warehouse.stock') }}" class="nav-link"><i data-lucide="boxes"
                                class="icon-sm me-2"></i>Stock Management</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i data-lucide="clipboard-list"
                                class="icon-sm me-2"></i>Packing Orders</a>
                    </li>
                @endrole

                <!-- ৪. DELIVERY রোলের জন্য মেনু -->
                @role('Delivery')
                    <li class="nav-item">
                        <a href="{{ route('delivery.index') }}"
                            class="nav-link {{ request()->routeIs('delivery.*') ? 'active' : '' }}"><i
                                data-lucide="truck" class="icon-sm me-2"></i>Assigned Deliveries</a>
                    </li>
                @endrole

                <!-- ৫. SALESMAN রোলের জন্য মেনু -->
                @role('Salesman')
                    <li class="nav-item">
                        <a href="{{ route('orders.index') }}"
                            class="nav-link d-flex align-items-center justify-content-between">
                            <span><i data-lucide="clipboard-list" class="icon-sm me-2"></i>Orders</span>
                            <span class="badge rounded-pill bg-warning text-dark">1</span>
                        </a>
                    </li>
                @endrole

                <!-- ৬. শুধু OWNER (অথবা সুপার অ্যাডমিন)-এর জন্য সেটিংস ও ইউজার ম্যানেজমেন্ট -->
                @role('Owner')
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i data-lucide="user-cog" class="icon-sm me-2"></i>Users
                            Management</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i data-lucide="settings" class="icon-sm me-2"></i>System
                            Settings</a>
                    </li>
                @endrole

                <!-- Logout Option (সবার জন্য দৃশ্যমান থাকবে) -->
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
