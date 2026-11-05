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
                <i data-lucide="plus-circle" class="icon-xs"></i> + Create New Order
            </button>
        </div>
    </div>

    <!-- Success & Error Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-xs mb-4" role="alert" style="font-size: 13px;">
            <i data-lucide="check-circle" class="icon-xs me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-xs mb-4" role="alert" style="font-size: 13px;">
            <i data-lucide="alert-circle" class="icon-xs me-1"></i> {{ $errors->first() }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Pipeline Status Tabs & Search Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('orders.index') }}" method="GET">
                <div class="row mb-3">
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
                                    <div class="fw-bold text-dark small">{{ $order->customer->proprietor_name ?? 'N/A' }}</div>
                                    <small class="text-muted font-mono"
                                        style="font-size: 10px;">{{ $order->customer->phone ?? '' }}</small>
                                </td>
                                <td><span class="badge bg-light text-secondary border font-mono"
                                        style="font-size: 9px;">{{ $order->area->name ?? 'General' }}</span></td>
                                <td><span class="text-secondary small">{{ $order->salesman->name ?? 'N/A' }}</span></td>
                                <td class="text-end font-mono fw-bold text-dark">
                                    ৳{{ number_format($order->total_amount, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-warning-subtle text-warning border font-mono px-2 py-1"
                                        style="font-size: 10px;">{{ $order->status }}</span>
                                </td>
                                <td class="px-3 text-end">
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="btn btn-outline-secondary btn-sm px-2 py-1">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5 small font-mono">কোনো অর্ডার পাওয়া
                                    যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Links Section -->
        @if(isset($orders) && method_exists($orders, 'hasPages') && $orders->hasPages())
            <div class="card-footer bg-white py-3 px-3 d-flex justify-content-between align-items-center">
                <div class="text-muted small font-mono">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() ?? 0 }} entries
                </div>
                <div>
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- ==========================================
            3-STEP MULTI-STEP ORDER MODAL FLOW
            ========================================== -->
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

                                    <!-- Custom Suggestions Dropdown Box -->
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

                            <!-- Dynamic Selected Products Table -->
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
                                        <!-- Dynamic items review -->
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                    <span class="fw-bold text-dark">Total Payable Amount:</span>
                                    <strong id="reviewTotal" class="text-success font-mono fs-6">-</strong>
                                </div>
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

    <!-- JavaScript for Dynamic Multiple Products & Search Flow -->
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

        // Product Catalog Array from PHP
        const productsData = [
            @foreach ($products ?? [] as $product)
                {
                    id: "{{ $product->id }}",
                    name: "{{ $product->name }}",
                    sku: "{{ $product->sku }}",
                    price: "{{ $product->wholesale_price ?? 0 }}"
                },
            @endforeach
        ];

        // Real-time search filter for products
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
                    `<span class="dropdown-item small text-muted py-2 px-3">কোনো পণ্য পাওয়া যায়নি</span>`;
            }
        });

        // Hide suggestions when clicking outside
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

            // Check if already added
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

            // Clear search input fields
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

                // Populate Review screen (Step 3) with Customer Details
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
    </script>
@endsection
