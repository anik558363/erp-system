@extends('layouts.app')

@section('title', 'NOOR SUPER MART - B2B Wholesale Order Pipeline')

@section('content')
    <!-- Top Header & Action Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h5 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i data-lucide="file-text" class="text-success" style="width: 20px; height: 20px;"></i>
                B2B WHOLESALE ORDER PIPELINE
            </h2>
            <p class="text-muted small mb-0">Single entry pipeline: Submitted → Approved → Packed → Dispatched → Delivered →
                Reconciled.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button"
                class="btn btn-success btn-sm px-3 shadow-xs d-flex align-items-center gap-1 fw-bold text-white"
                style="background-color: #059669; border-color: #059669;" data-bs-toggle="modal"
                data-bs-target="#fastOrderModal" onclick="resetOrderModal()">
                <i data-lucide="plus-circle" class="icon-xs"></i> Create New Order
            </button>
        </div>
    </div>

    <!-- Success & Error Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-xs mb-4" role="alert" style="font-size: 13px;">
            <i data-lucide="check-circle" class="icon-xs me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-xs mb-4" role="alert" style="font-size: 13px;">
            <i data-lucide="alert-circle" class="icon-xs me-1"></i> {{ $errors->first() }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Pipeline Status Tabs & Search Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('orders.index') }}" method="GET">
                <div class="row mb-0">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i data-lucide="search"
                                    class="icon-xs"></i></span>
                            <input type="text" name="search" class="form-control shadow-none border-start-0"
                                value="{{ request('search') }}" placeholder="Search order #, customer, mobile...">
                            <button type="submit" class="btn btn-dark btn-sm px-3">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase font-mono" style="font-size: 11px;">
                        <tr>
                            <th class="py-3 px-3">Order #</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Customer & Mobile</th>
                            <th class="py-3">Area</th>
                            <th class="py-3">Salesman</th>
                            <th class="py-3">Items Summary</th>
                            <th class="py-3 text-end">Order Amount</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 px-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-3 font-mono fw-bold text-dark small">{{ $order->order_no }}</td>
                                <td class="font-mono text-muted small">{{ $order->order_date }}</td>
                                <td>
                                    <div class="fw-bold text-dark small">
                                        {{ $order->customer->proprietor_name ?? ($order->customer->name ?? 'N/A') }}</div>
                                    <small class="text-muted font-mono"
                                        style="font-size: 10px;">{{ $order->customer->phone ?? '' }}</small>
                                </td>
                                <td><span class="badge bg-light text-secondary border font-mono"
                                        style="font-size: 9px;">{{ $order->area->name ?? 'General' }}</span></td>
                                <td><span class="text-secondary small">{{ $order->salesman->name ?? 'N/A' }}</span></td>
                                <td class="px-3">
                                    @if ($order->items && $order->items->count() > 0)
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-dark border font-mono"
                                                style="font-size: 10px;">{{ $order->items->count() }} Items</span>

                                        </div>
                                    @else
                                        <span class="text-muted small">No items</span>
                                    @endif
                                </td>
                                <td class="text-end font-mono fw-bold text-dark">
                                    ৳{{ number_format($order->total_amount, 2) }}</td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match ($order->status) {
                                            'APPROVED' => 'bg-success-subtle text-success border-success',
                                            'PENDING_APPROVAL' => 'bg-warning-subtle text-warning border-warning',
                                            'PACKED' => 'bg-info-subtle text-info border-info',
                                            'DISPATCHED' => 'bg-primary-subtle text-primary border-primary',
                                            'DELIVERED' => 'bg-teal-subtle text-success border-teal',
                                            'REJECTED' => 'bg-danger-subtle text-danger border-danger',
                                            default => 'bg-secondary-subtle text-secondary border-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} font-mono px-2 py-1 border"
                                        style="font-size: 10px;">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-3 text-end">
                                    <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1"
                                        onclick="openOrderDetailsModal({{ json_encode($order) }})">
                                        View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5 small font-mono">কোনো অর্ডার পাওয়া
                                    যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Links Section -->
        @if (isset($orders) && method_exists($orders, 'hasPages') && $orders->hasPages())
            <div class="card-footer bg-white py-3 px-3 d-flex justify-content-between align-items-center">
                <div class="text-muted small font-mono">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of
                    {{ $orders->total() ?? 0 }} entries
                </div>
                <div>
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- ORDER DETAILS & APPROVAL MODAL -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-2 px-3">
                    <h6 class="modal-title fw-bold fs-6">ORDER DETAILS & APPROVAL</h6>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 small">
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Order No:</span>
                            <strong class="font-mono text-dark" id="modalOrderNo">-</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Order Date:</span>
                            <span class="font-mono" id="modalOrderDate">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Status:</span>
                            <span id="modalOrderStatus" class="badge bg-warning font-mono">-</span>
                        </div>
                    </div>

                    <div class="mb-3 pb-2 border-bottom">
                        <span class="text-muted d-block mb-1 fw-bold">CUSTOMER & AREA INFO:</span>
                        <div class="fw-bold text-dark" id="modalCustName">-</div>
                        <div class="text-muted">Shop: <span id="modalCustShop" class="text-dark fw-semibold">-</span>
                        </div>
                        <div class="text-muted">Phone: <span id="modalCustPhone" class="font-mono text-dark">-</span>
                        </div>
                        <div class="text-muted">Area: <span id="modalCustArea"
                                class="badge bg-light text-secondary border font-mono">-</span></div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted d-block mb-1 fw-bold">ORDERED ITEMS:</span>
                        <div class="border rounded bg-light p-2 font-mono" id="modalItemsList"
                            style="max-height: 160px; overflow-y: auto; font-size: 11px;">
                            <!-- Dynamic Items -->
                        </div>
                    </div>

                    <div
                        class="d-flex justify-content-between align-items-center bg-white p-2 border rounded font-mono mb-3">
                        <span class="fw-bold text-dark">Total Amount:</span>
                        <strong class="text-success fs-6" id="modalTotalAmount">৳0.00</strong>
                    </div>

                    <!-- APPROVAL & STATUS UPDATE FORM -->
                    <form id="orderApprovalForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Update Order Status / Workflow:</label>
                            <select name="status" id="modalStatusSelect"
                                class="form-select form-select-sm shadow-none font-mono">
                                <option value="PENDING_APPROVAL">PENDING_APPROVAL</option>
                                <option value="APPROVED">APPROVED (Deduct Stock)</option>
                                <option value="PACKED">PACKED</option>
                                <option value="DISPATCHED">DISPATCHED</option>
                                <option value="DELIVERED">DELIVERED</option>
                                <option value="REJECTED">REJECTED</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary btn-sm px-3"
                                data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-outline-dark btn-sm px-3 font-mono"
                                onclick="printOrderInvoice()">
                                <i data-lucide="printer" class="icon-xs me-1"></i> Print Invoice
                            </button>
                            <button type="submit" class="btn btn-success btn-sm px-4 fw-bold"
                                style="background-color: #059669;">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 3-STEP MULTI-STEP ORDER MODAL FLOW -->
    <div class="modal fade" id="fastOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('orders.store') }}" method="POST" id="orderSubmitForm">
                    @csrf
                    <div class="modal-header bg-dark text-white py-2 px-3 align-items-center">
                        <div>
                            <h6 class="modal-title fw-bold mb-0 fs-6">SALESMAN FAST ORDER DISPATCH</h6>
                            <small class="text-secondary" style="font-size: 10px;">Step-by-Step B2B Order Flow</small>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex gap-1 text-white small" style="font-size: 11px;">
                                <span id="indicator1" class="badge bg-success px-2 py-1">1. Customer</span>
                                <span id="indicator2" class="badge bg-secondary px-2 py-1">2. Products</span>
                                <span id="indicator3" class="badge bg-secondary px-2 py-1">3. Confirm</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                    </div>

                    <div class="modal-body p-4" style="min-height: 320px;">
                        <!-- STEP 1: SELECT CUSTOMER -->
                        <div id="modalStep1" class="order-step-content">
                            <h6 class="fw-bold text-dark mb-3">STEP 1: SELECT B2B CUSTOMER</h6>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Choose Customer Shop *</label>
                                <select id="customerSelect" name="customer_id" class="form-select shadow-none" required>
                                    <option value="">-- Select Customer --</option>
                                    @foreach ($customers ?? [] as $customer)
                                        <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                            data-shop="{{ $customer->shop_name ?? $customer->name }}"
                                            data-phone="{{ $customer->phone ?? 'N/A' }}"
                                            data-area="{{ $customer->area->name ?? 'General' }}">
                                            {{ $customer->name }} (Area: {{ $customer->area->name ?? 'General' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="salesman_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="order_date" value="{{ date('Y-m-d') }}">
                        </div>

                        <!-- STEP 2: SEARCH & SELECT MULTIPLE PRODUCTS -->
                        <div id="modalStep2" class="order-step-content d-none">
                            <div
                                class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-3 border">
                                <div>
                                    <span class="fw-bold text-dark small" id="selectedCustomerDisplay">Customer
                                        Name</span>
                                    <div class="text-muted" style="font-size: 11px;">Search product by name or SKU and add
                                        to order.</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2"
                                    onclick="goToStep(1)">Change Customer</button>
                            </div>

                            <div class="row g-2 mb-3 align-items-end">
                                <div class="col-md-8 position-relative">
                                    <label class="form-label small fw-bold">Search Product (Name / SKU)</label>
                                    <input type="text" id="productSearchInput"
                                        class="form-control form-control-sm shadow-none"
                                        placeholder="Type product name or SKU to search..." autocomplete="off">
                                    <div id="productSuggestionsList" class="dropdown-menu w-100 shadow border bg-white"
                                        style="max-height: 200px; overflow-y: auto; display: none; position: absolute; z-index: 1050;">
                                    </div>
                                    <input type="hidden" id="selectedProductId">
                                    <input type="hidden" id="selectedProductName">
                                    <input type="hidden" id="selectedProductSku">
                                    <input type="hidden" id="selectedProductPrice">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-dark btn-sm w-100 fw-semibold"
                                        onclick="addProductBySearch()">+ Add Product</button>
                                </div>
                            </div>

                            <div class="table-responsive border rounded bg-white"
                                style="max-height: 200px; overflow-y: auto;">
                                <table class="table table-sm align-middle mb-0" style="font-size: 11px;">
                                    <thead class="table-light text-uppercase">
                                        <tr>
                                            <th>Product Name</th>
                                            <th style="width: 80px;" class="text-center">Qty</th>
                                            <th style="width: 90px;" class="text-end">Rate (৳)</th>
                                            <th style="width: 90px;" class="text-end">Total</th>
                                            <th style="width: 40px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selectedProductsTableBody">
                                        <tr id="emptyProductRow">
                                            <td colspan="5" class="text-center text-muted py-3">No products added yet.
                                                Please search and add above.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div
                                class="mt-3 p-2 bg-light rounded border d-flex justify-content-between align-items-center font-mono small">
                                <span class="text-muted fw-bold">Grand Total Amount:</span>
                                <span class="fw-bold text-success fs-6" id="calcGrandTotalDisplay">৳0.00</span>
                            </div>
                        </div>

                        <!-- STEP 3: CONFIRM & REVIEW -->
                        <div id="modalStep3" class="order-step-content d-none">
                            <h6 class="fw-bold text-dark mb-3">STEP 3: REVIEW & CONFIRM ORDER</h6>
                            <div class="p-3 bg-light rounded border mb-3 small">
                                <div class="mb-2 pb-2 border-bottom">
                                    <span class="text-muted d-block mb-1" style="font-size: 10px;">CUSTOMER
                                        DETAILS:</span>
                                    <div class="fw-bold text-dark" id="reviewCustomerName">-</div>
                                    <div class="text-muted" style="font-size: 11px;">Shop/Store: <span
                                            id="reviewCustomerShop" class="fw-semibold text-dark">-</span></div>
                                    <div class="text-muted" style="font-size: 11px;">Phone: <span
                                            id="reviewCustomerPhone" class="font-mono text-dark">-</span></div>
                                    <div class="text-muted" style="font-size: 11px;">Area: <span id="reviewCustomerArea"
                                            class="badge bg-light text-secondary border font-mono">-</span></div>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted d-block mb-1">Ordered Items Summary:</span>
                                    <div id="reviewItemsList" class="border rounded bg-white p-2 font-mono"
                                        style="max-height: 120px; overflow-y: auto; font-size: 10px;">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                    <span class="fw-bold text-dark">Total Payable Amount:</span>
                                    <strong id="reviewTotal" class="text-success font-mono fs-6">-</strong>
                                </div>
                            </div>

                            <!-- Partial Payment Input Field (এখানে পেস্ট করবেন) -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Paid Amount (BDT) *</label>
                                <input type="number" name="paid_amount" id="modalPaidAmount"
                                    class="form-control form-control-sm shadow-none font-mono" step="0.01"
                                    min="0" value="0.00" oninput="calculateDueAmount()" required>
                                <small class="text-muted font-mono" id="calcDueDisplay" style="font-size: 11px;">Due
                                    Amount: ৳0.00</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Remarks / Notes</label>
                                <textarea name="note" class="form-control shadow-none" rows="2"
                                    placeholder="Optional delivery instructions..."></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light py-2 justify-content-between" id="modalFooterBtns">
                        <div>
                            <button type="button" id="modalBackBtn" class="btn btn-outline-secondary btn-sm px-3 d-none"
                                onclick="prevStep()">← Back</button>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" id="modalNextBtn" class="btn btn-success btn-sm px-4 text-white"
                                style="background-color: #059669;" onclick="nextStep()">Next: Products →</button>
                            <button type="submit" id="modalSubmitBtn"
                                class="btn btn-success btn-sm px-4 d-none text-white fw-bold"
                                style="background-color: #059669;">✓ Confirm & Submit Order</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Section -->
    <script>
        let currentModalStep = 1;
        let addedProducts = [];

        function resetOrderModal() {
            currentModalStep = 1;
            addedProducts = [];
            document.getElementById('orderSubmitForm').reset();
            let suggestionsList = document.getElementById('productSuggestionsList');
            if (suggestionsList) suggestionsList.style.display = 'none';
            renderProductsTable();
            updateModalView();
        }

        function goToStep(step) {
            currentModalStep = step;
            updateModalView();
        }

        const productsData = [
            @foreach ($products ?? [] as $product)
                {
                    id: "{{ $product->id }}",
                    name: "{{ $product->name }}",
                    sku: "{{ $product->sku }}",
                    price: "{{ $product->wholesale_price ?? 0 }}",
                    image: "{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                },
            @endforeach
        ];

        document.getElementById('productSearchInput').addEventListener('input', function() {
            let keyword = this.value.toLowerCase().trim();
            let suggestionsList = document.getElementById('productSuggestionsList');
            suggestionsList.innerHTML = '';

            if (keyword.length === 0) {
                suggestionsList.style.display = 'none';
                return;
            }

            let filtered = productsData.filter(p =>
                p.name.toLowerCase().includes(keyword) || p.sku.toLowerCase().includes(keyword)
            );

            if (filtered.length > 0) {
                suggestionsList.style.display = 'block';
                filtered.forEach(p => {
                    let item = document.createElement('a');
                    item.href = '#';
                    item.className = 'dropdown-item small py-2 px-3 text-dark border-bottom';
                    item.innerHTML =
                        `<strong>${p.name}</strong> <span class="text-muted font-mono" style="font-size: 10px;">(SKU: ${p.sku}) - ৳${p.price}</span>`;

                    item.onclick = function(e) {
                        e.preventDefault();
                        document.getElementById('productSearchInput').value =
                            `${p.name} (SKU: ${p.sku})`;
                        document.getElementById('selectedProductId').value = p.id;
                        document.getElementById('selectedProductName').value = p.name;
                        document.getElementById('selectedProductSku').value = p.sku;
                        document.getElementById('selectedProductPrice').value = p.price;
                        suggestionsList.style.display = 'none';
                    };
                    suggestionsList.appendChild(item);
                });
            } else {
                suggestionsList.style.display = 'block';
                suggestionsList.innerHTML =
                    `<span class="dropdown-item small text-muted py-2 px-3">কোনো পণ্য পাওয়া যায়নি</span>`;
            }
        });

        document.addEventListener('click', function(e) {
            let searchInput = document.getElementById('productSearchInput');
            let suggestionsList = document.getElementById('productSuggestionsList');
            if (searchInput && suggestionsList && !searchInput.contains(e.target) && !suggestionsList.contains(e
                    .target)) {
                suggestionsList.style.display = 'none';
            }
        });

        function addProductBySearch() {
            let prodId = document.getElementById('selectedProductId').value;
            let prodName = document.getElementById('selectedProductName').value;
            let prodSku = document.getElementById('selectedProductSku').value;
            let price = parseFloat(document.getElementById('selectedProductPrice').value) || 0;
            let searchInput = document.getElementById('productSearchInput');

            if (!prodId || !prodName) {
                alert('Please select a valid product from the search suggestions.');
                return;
            }

            let existing = addedProducts.find(p => p.id === prodId);
            if (existing) {
                existing.qty += 1;
            } else {
                addedProducts.push({
                    id: prodId,
                    name: prodName,
                    sku: prodSku,
                    qty: 1,
                    rate: price
                });
            }

            searchInput.value = "";
            document.getElementById('selectedProductId').value = "";
            document.getElementById('selectedProductName').value = "";
            document.getElementById('selectedProductSku').value = "";
            document.getElementById('selectedProductPrice').value = "";

            renderProductsTable();
        }

        function updateProductQty(id, qty) {
            let item = addedProducts.find(p => p.id === id);
            if (item) {
                item.qty = parseInt(qty) || 1;
                renderProductsTable();
            }
        }

        function updateProductRate(id, rate) {
            let item = addedProducts.find(p => p.id === id);
            if (item) {
                item.rate = parseFloat(rate) || 0;
                renderProductsTable();
            }
        }

        function removeProductRow(id) {
            addedProducts = addedProducts.filter(p => p.id !== id);
            renderProductsTable();
        }

        function renderProductsTable() {
            let tbody = document.getElementById('selectedProductsTableBody');
            tbody.innerHTML = '';

            if (addedProducts.length === 0) {
                tbody.innerHTML =
                    `<tr id="emptyProductRow"><td colspan="5" class="text-center text-muted py-3">No products added yet. Please search and add above.</td></tr>`;
                document.getElementById('calcGrandTotalDisplay').innerText = '৳0.00';
                return;
            }

            let grandTotal = 0;
            addedProducts.forEach(item => {
                let total = item.qty * item.rate;
                grandTotal += total;

                tbody.innerHTML += `
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">${item.name}</div>
                            <small class="text-muted font-mono" style="font-size: 9px;">SKU: ${item.sku}</small>
                            <input type="hidden" name="products[]" value="${item.id}">
                        </td>
                        <td class="text-center">
                            <input type="number" name="quantities[]" class="form-control form-control-sm text-center p-1 shadow-none" value="${item.qty}" min="1" oninput="updateProductQty('${item.id}', this.value)" required>
                        </td>
                        <td class="text-end">
                            <input type="number" name="prices[]" class="form-control form-control-sm text-end p-1 shadow-none font-mono" value="${item.rate}" step="0.01" oninput="updateProductRate('${item.id}', this.value)" required>
                        </td>
                        <td class="text-end font-mono fw-bold">৳${total.toFixed(2)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm p-0 px-1" onclick="removeProductRow('${item.id}')">&times;</button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('calcGrandTotalDisplay').innerText = '৳' + grandTotal.toFixed(2);
        }

        function nextStep() {
            if (currentModalStep === 1) {
                let custSelect = document.getElementById('customerSelect');
                if (!custSelect.value) {
                    alert('Please select a customer first.');
                    return;
                }
                let selectedOpt = custSelect.options[custSelect.selectedIndex];
                document.getElementById('selectedCustomerDisplay').innerText = selectedOpt.getAttribute('data-name') +
                    ' (' + selectedOpt.getAttribute('data-area') + ')';
            } else if (currentModalStep === 2) {
                if (addedProducts.length === 0) {
                    alert('Please add at least one product to the order.');
                    return;
                }

                let custSelect = document.getElementById('customerSelect');
                let custOpt = custSelect.options[custSelect.selectedIndex];

                document.getElementById('reviewCustomerName').innerText = custOpt.getAttribute('data-name');
                document.getElementById('reviewCustomerShop').innerText = custOpt.getAttribute('data-shop');
                document.getElementById('reviewCustomerPhone').innerText = custOpt.getAttribute('data-phone');
                document.getElementById('reviewCustomerArea').innerText = custOpt.getAttribute('data-area');

                let reviewList = document.getElementById('reviewItemsList');
                reviewList.innerHTML = '';
                let grandTotal = 0;

                addedProducts.forEach((item, index) => {
                    let total = item.qty * item.rate;
                    grandTotal += total;
                    reviewList.innerHTML += `
                        <div class="d-flex justify-content-between mb-1">
                            <span>${index + 1}. ${item.name} (${item.qty} × ৳${item.rate})</span>
                            <strong class="font-mono">৳${total.toFixed(2)}</strong>
                        </div>
                    `;
                });

                document.getElementById('reviewTotal').innerText = '৳' + grandTotal.toFixed(2);
            }

            currentModalStep++;
            if (currentModalStep > 3) currentModalStep = 3;
            updateModalView();
        }

        function calculateDueAmount() {
            let totalText = document.getElementById('reviewTotal').innerText.replace('৳', '').trim();
            let total = parseFloat(totalText) || 0;
            let paidInput = document.getElementById('modalPaidAmount');
            let paid = parseFloat(paidInput.value) || 0;

            // যদি Paid Amount, Total Amount-er চেয়ে বেশি হয়ে যায়, তবে আটকে দিবে
            if (paid > total) {
                alert('Paid amount cannot be greater than the total payable amount!');
                paidInput.value = total.toFixed(2); // অটোমেটিক টোটাল অ্যামাউন্ট বসিয়ে দিবে
                paid = total;
            }

            let due = total - paid;
            document.getElementById('calcDueDisplay').innerText = `Due Amount: ৳${due.toFixed(2)}`;
        }

        function prevStep() {
            currentModalStep--;
            if (currentModalStep < 1) currentModalStep = 1;
            updateModalView();
        }

        function updateModalView() {
            document.getElementById('modalStep1').classList.add('d-none');
            document.getElementById('modalStep2').classList.add('d-none');
            document.getElementById('modalStep3').classList.add('d-none');

            document.getElementById(`modalStep${currentModalStep}`).classList.remove('d-none');

            document.getElementById('indicator1').className = currentModalStep === 1 ? 'badge bg-success px-2 py-1' :
                'badge bg-secondary px-2 py-1';
            document.getElementById('indicator2').className = currentModalStep === 2 ? 'badge bg-success px-2 py-1' :
                'badge bg-secondary px-2 py-1';
            document.getElementById('indicator3').className = currentModalStep === 3 ? 'badge bg-success px-2 py-1' :
                'badge bg-secondary px-2 py-1';

            let backBtn = document.getElementById('modalBackBtn');
            let nextBtn = document.getElementById('modalNextBtn');
            let submitBtn = document.getElementById('modalSubmitBtn');

            if (currentModalStep === 1) {
                backBtn.classList.add('d-none');
                nextBtn.classList.remove('d-none');
                nextBtn.innerText = 'Next: Products →';
                submitBtn.classList.add('d-none');
            } else if (currentModalStep === 2) {
                backBtn.classList.remove('d-none');
                nextBtn.classList.remove('d-none');
                nextBtn.innerText = 'Next: Review & Confirm →';
                submitBtn.classList.add('d-none');
            } else if (currentModalStep === 3) {
                backBtn.classList.remove('d-none');
                nextBtn.classList.add('d-none');
                submitBtn.classList.remove('d-none');
            }
        }

function openOrderDetailsModal(order) {
            document.getElementById('modalOrderNo').innerText = order.order_no;
            document.getElementById('modalOrderDate').innerText = order.order_date;
            document.getElementById('modalOrderStatus').innerText = order.status;

            // কাস্টমার নাম ঠিকভাবে তোলার লজিক (undefined রোধ করতে)
            let customerName = 'N/A';
            if (order.customer) {
                customerName = order.customer.proprietor_name || order.customer.name || 'N/A';
            }

            document.getElementById('modalCustName').innerText = customerName;
            document.getElementById('modalCustShop').innerText = order.customer ? (order.customer.shop_name || customerName) : 'N/A';
            document.getElementById('modalCustPhone').innerText = order.customer ? (order.customer.phone || 'N/A') : 'N/A';
            document.getElementById('modalCustArea').innerText = order.area ? order.area.name : 'General';

            // Financial Calculations (Total, Paid & Due)
            let totalAmt = parseFloat(order.total_amount) || 0;
            let paidAmt = parseFloat(order.paid_amount) || 0;
            let dueAmt = totalAmt - paidAmt;

            document.getElementById('modalTotalAmount').innerText = '৳' + totalAmt.toFixed(2);

            // মডালের ভেতর যদি Paid ও Due দেখানোর আলাদা element রাখতে চান, নিচের ফিল্ডগুলো জেনারেট করবে
            let totalSectionHtml = `
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Total Amount:</span>
                    <strong class="text-dark font-mono">৳${totalAmt.toFixed(2)}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Paid Amount:</span>
                    <strong class="text-success font-mono">৳${paidAmt.toFixed(2)}</strong>
                </div>
                <div class="d-flex justify-content-between border-top pt-1 mt-1">
                    <span class="fw-bold text-dark">Due Amount:</span>
                    <strong class="text-danger font-mono fs-6">৳${dueAmt.toFixed(2)}</strong>
                </div>
            `;
            // মডালের টোটাল অ্যামাউন্ট বক্সের আইডি আপডেট করা (বা নিচে ইনজেক্ট করা)
            document.getElementById('modalTotalAmount').innerHTML = totalSectionHtml;

            document.getElementById('modalStatusSelect').value = order.status;
            document.getElementById('orderApprovalForm').action = `/orders/${order.id}/status`;

            let itemsList = document.getElementById('modalItemsList');
            itemsList.innerHTML = '';

            if (order.items && order.items.length > 0) {
                order.items.forEach((item, index) => {
                    let productName = item.product ? item.product.name : 'Product #' + item.product_id;
                    let productImage = (item.product && item.product.image) ? `/storage/${item.product.image}` : 'https://via.placeholder.com/40';
                    let lineTotal = parseFloat(item.line_total || (item.quantity * item.unit_rate)).toFixed(2);

                    itemsList.innerHTML += `
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <div class="d-flex align-items-center gap-2">
                                <img src="${productImage}" alt="Product" class="rounded border" style="width: 35px; height: 35px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark">${index + 1}. ${productName}</div>
                                    <small class="text-muted font-mono">Qty: ${item.quantity} × ৳${item.unit_rate}</small>
                                </div>
                            </div>
                            <strong class="font-mono text-dark">৳${lineTotal}</strong>
                        </div>
                    `;
                });
            } else {
                itemsList.innerHTML = '<div class="text-center text-muted">No items found</div>';
            }

            let myModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            myModal.show();
        }

        function printOrderInvoice() {
            let orderNo = document.getElementById('modalOrderNo').innerText;
            let orderDate = document.getElementById('modalOrderDate').innerText;
            let orderStatus = document.getElementById('modalOrderStatus').innerText;
            let custName = document.getElementById('modalCustName').innerText;
            let custShop = document.getElementById('modalCustShop').innerText;
            let custPhone = document.getElementById('modalCustPhone').innerText;
            let custArea = document.getElementById('modalCustArea').innerText;

            // মডাল থেকে টotal, paid এবং due অ্যামাউন্ট বের করে আনা
            let itemsHtmlContent = document.getElementById('modalItemsList').innerHTML;

            // হিসাব করার জন্য টোটাল ডেটা রি-ত্রিপ্লেট করা
            let totalText = document.querySelector('#modalTotalAmount strong.text-dark').innerText;
            let paidText = document.querySelector('#modalTotalAmount strong.text-success').innerText;
            let dueText = document.querySelector('#modalTotalAmount strong.text-danger').innerText;

            let printWindow = window.open('', '_blank', 'width=900,height=700');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Invoice - ${orderNo}</title>
                    <style>
                        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; padding: 20px; margin: 0; background: #fff; }
                        .invoice-container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
                        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #059669; padding-bottom: 10px; }
                        .header h2 { margin: 0; color: #059669; font-size: 22px; }
                        .header p { margin: 5px 0 0; color: #555; font-size: 11px; text-transform: uppercase; }

                        .info-section { display: flex; justify-content: space-between; margin-bottom: 20px; background: #f8f9fa; padding: 12px; border-radius: 6px; }
                        .info-box { width: 48%; }
                        .info-box h4 { margin: 0 0 6px 0; font-size: 12px; color: #059669; text-transform: uppercase; border-bottom: 1px solid #ddd; padding-bottom: 3px; }
                        .info-box p { margin: 2px 0; font-size: 12px; color: #333; }

                        .items-box { margin-bottom: 20px; border: 1px solid #ddd; padding: 10px; border-radius: 6px; background: #fff; }

                        .d-flex { display: flex; display: -webkit-flex; }
                        .align-items-center { align-items: center; }
                        .justify-content-between { justify-content: space-between; }
                        .gap-2 { gap: 10px; }
                        .mb-2 { margin-bottom: 8px; }
                        .border-bottom { border-bottom: 1px solid #eee; }
                        .pb-2 { padding-bottom: 8px; }
                        .fw-bold { font-weight: bold; }
                        .text-dark { color: #212529; }
                        .text-muted { color: #6c757d; font-size: 11px; }
                        .rounded { border-radius: 4px; }
                        .border { border: 1px solid #dee2e6; }

                        .total-section { float: right; width: 280px; margin-top: 10px; background: #f8f9fa; padding: 12px; border-radius: 6px; border: 1px solid #ddd; }
                        .total-row { display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 13px; }
                        .grand-total { border-top: 1px solid #ddd; padding-top: 6px; margin-top: 4px; font-weight: bold; font-size: 14px; }

                        .clearfix::after { content: ""; clear: both; display: table; }

                        .footer { text-align: center; margin-top: 40px; font-size: 11px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }

                        @media print {
                            body { padding: 0; background: #fff; }
                            .invoice-container { border: none; padding: 0; }
                        }
                    </style>
                </head>
                <body>
                    <div class="invoice-container">
                        <div class="header">
                            <h2>NOOR SUPER MART</h2>
                            <p>B2B Wholesale Distribution System | Official Invoice & Challan</p>
                        </div>

                        <div class="info-section">
                            <div class="info-box">
                                <h4>Customer Details</h4>
                                <p><strong>Name:</strong> ${custName}</p>
                                <p><strong>Shop:</strong> ${custShop}</p>
                                <p><strong>Phone:</strong> ${custPhone}</p>
                                <p><strong>Area:</strong> ${custArea}</p>
                            </div>
                            <div class="info-box" style="text-align: right;">
                                <h4>Order Information</h4>
                                <p><strong>Order No:</strong> ${orderNo}</p>
                                <p><strong>Date:</strong> ${orderDate}</p>
                                <p><strong>Status:</strong> <span style="color: #059669; font-weight: bold;">${orderStatus}</span></p>
                            </div>
                        </div>

                        <div style="font-weight: bold; margin-bottom: 6px; font-size: 11px; text-transform: uppercase; color: #555;">Ordered Products Summary:</div>
                        <div class="items-box">
                            ${itemsHtmlContent}
                        </div>

                        <div class="clearfix">
                            <div class="total-section">
                                <div class="total-row">
                                    <span>Total Amount:</span>
                                    <strong>${totalText}</strong>
                                </div>
                                <div class="total-row">
                                    <span>Paid Amount:</span>
                                    <strong style="color: #059669;">${paidText}</strong>
                                </div>
                                <div class="total-row grand-total">
                                    <span>Due Amount:</span>
                                    <strong style="color: #dc2626;">${dueText}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="footer">
                            <p>Thank you for your business! This is a system-generated B2B wholesale invoice.</p>
                        </div>
                    </div>

                    <script>
                        window.onload = function() {
                            window.print();
                            window.close();
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

    </script>
@endsection
