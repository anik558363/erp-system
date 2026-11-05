@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Purchase & Supplier Invoices')

@section('content')
    <!-- Top Purchase Header Banner -->
    <div class="card border-0 shadow-xs mb-4">
        <div class="card-body p-3 p-md-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="h5 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i data-lucide="shopping-bag" class="text-emerald"></i> PURCHASE & SUPPLIER INVOICES
                </h2>
                <p class="text-muted small mb-0">Receive supplier shipments. Inward stock automatically increments warehouse
                    inventory.</p>
            </div>
            <div>
                <!-- Trigger Button for New Purchase Modal -->
                <button
                    class="btn btn-emerald btn-sm px-3 py-2 fw-medium d-flex align-items-center gap-1 shadow-sm text-white bg-emerald"
                    style="background-color: #059669 !important;" data-bs-toggle="modal" data-bs-target="#newPurchaseModal">
                    <i data-lucide="plus" class="icon-sm"></i> New Purchase Receive
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show small shadow-xs border-0 mb-4" role="alert"
            style="background-color: #ecfdf5; color: #065f46;">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="check-circle" class="icon-sm"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small shadow-xs border-0 mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search & Status Filter Section -->
    <div class="card border-0 shadow-xs mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div class="w-100" style="max-width: 320px;">
                    <form action="{{ route('purchases.index') }}" method="GET">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i data-lucide="search" class="icon-sm"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control border-start-0 ps-0 shadow-none"
                                placeholder="Search by supplier or invoice #...">
                        </div>
                    </form>
                </div>

                <div class="d-flex flex-wrap gap-1 align-items-center purchase-status-filter">
                    <a href="{{ route('purchases.index') }}"
                        class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-light border text-secondary' }} px-3 py-1 fw-medium text-decoration-none">ALL</a>
                    <a href="{{ route('purchases.index', ['status' => 'RECEIVED']) }}"
                        class="btn btn-sm {{ request('status') == 'RECEIVED' ? 'btn-dark' : 'btn-light border text-secondary' }} px-3 py-1 fw-medium text-decoration-none">RECEIVED</a>
                    <a href="{{ route('purchases.index', ['status' => 'DRAFT']) }}"
                        class="btn btn-sm {{ request('status') == 'DRAFT' ? 'btn-dark' : 'btn-light border text-secondary' }} px-3 py-1 fw-medium text-decoration-none">DRAFT</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase Invoices Table Section -->
    <div class="card border-0 shadow-xs">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 custom-table">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Supplier Name</th>
                            <th>Items Summary</th>
                            <th class="text-end">Total (৳)</th>
                            <th class="text-end">Paid (৳)</th>
                            <th class="text-end">Supplier Due (৳)</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td class="fw-semibold font-mono text-dark">{{ $purchase->chalan_no }}</td>
                                <td class="text-muted">
                                    {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                                <td class="fw-medium text-dark">{{ $purchase->supplier->company_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        @foreach ($purchase->items as $item)
                                            {{ $item->product->name ?? '' }} ({{ $item->quantity }}
                                            {{ $item->product->unit_type ?? '' }}){{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </span>
                                </td>
                                <td class="text-end fw-bold">৳{{ number_format($purchase->total_amount, 2) }}</td>
                                <td class="text-end text-primary">৳{{ number_format($purchase->paid_amount, 2) }}</td>
                                <td class="text-end text-danger">৳{{ number_format($purchase->due_amount, 2) }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge {{ ($purchase->status ?? 'RECEIVED') == 'RECEIVED' ? 'bg-emerald-subtle text-emerald border-emerald-subtle' : 'bg-warning-subtle text-warning-emphasis border' }}">
                                        {{ $purchase->status ?? 'RECEIVED' }}
                                    </span>
                                </td>
                                <!-- একশন কলামের ভেতরে এডিট বাটনটি এভাবে দিন -->
                                <td class="text-end">
                                    <a href="{{ route('purchases.show', $purchase->id) }}"
                                        class="btn btn-sm btn-light border text-secondary p-1" title="View Details">
                                        <i data-lucide="eye" class="icon-xs"></i>
                                    </a>

                                    @if ($purchase->status == 'DRAFT')
                                        <button type="button" class="btn btn-sm btn-light border text-primary p-1 ms-1"
                                            title="Edit Draft" data-bs-toggle="modal"
                                            data-bs-target="#editPurchaseModal{{ $purchase->id }}">
                                            <i data-lucide="edit" class="icon-xs"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i data-lucide="shopping-bag" class="icon-lg text-muted mb-2 opacity-50"
                                        style="width: 48px; height: 48px;"></i>
                                    <p class="small mb-1">No purchase invoices found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($purchases->hasPages())
        <div class="mt-4">
            {{ $purchases->links() }}
        </div>
    @endif

    <!-- ==========================================
                    MODAL: NEW SUPPLIER PURCHASE ENTRY
                    ========================================== -->
    <div class="modal fade" id="newPurchaseModal" tabindex="-1" aria-labelledby="newPurchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title h6 fw-bold" id="newPurchaseModalLabel">NEW SUPPLIER PURCHASE ENTRY</h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('purchases.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4" style="max-height: calc(100vh - 180px); overflow-y: auto;">
                        <p class="text-muted small mb-3">Search catalog products, specify quantity, unit rate, and record
                            received shipment or draft invoice.</p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Supplier *</label>
                                <select name="supplier_id" class="form-select form-select-sm py-2" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->company_name }}
                                            ({{ $supplier->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Invoice Number *</label>
                                <input type="text" name="chalan_no"
                                    class="form-control form-control-sm font-mono py-2"
                                    value="PUR-{{ date('Y') }}-{{ rand(1000, 9999) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Receive Date *</label>
                                <input type="date" name="purchase_date" class="form-control form-control-sm py-2"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <!-- Product Search & Selection Section -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Search & Add Product *</label>
                            <div class="position-relative mb-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white text-muted"><i data-lucide="search"
                                            class="icon-xs"></i></span>
                                    <input type="text" id="productSearchInput"
                                        class="form-control form-control-sm shadow-none"
                                        placeholder="Type product name or SKU to search from catalog..."
                                        autocomplete="off">
                                </div>
                                <!-- Search Suggestions Dropdown Box -->
                                <div id="productSuggestions"
                                    class="list-group position-absolute w-100 shadow bg-white border mt-1"
                                    style="z-index: 1055; max-height: 220px; overflow-y: auto; display: none;">
                                    @foreach ($products as $product)
                                        <button type="button"
                                            class="list-group-item list-group-item-action product-suggestion-item py-2 px-3 small d-flex justify-content-between align-items-center border-bottom"
                                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                            data-sku="{{ $product->sku }}" data-price="{{ $product->purchase_price }}"
                                            data-unit="{{ $product->unit_type }}"
                                            data-stock="{{ $product->stock->physical_qty ?? 0 }}"
                                            data-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=100&auto=format&fit=crop&q=60' }}">
                                            <div>
                                                <span class="fw-bold text-dark">{{ $product->name }}</span>
                                                <span class="text-muted font-mono ms-2" style="font-size: 10px;">[SKU:
                                                    {{ $product->sku }}]</span>
                                            </div>
                                            <span class="badge bg-light text-secondary border">Stock:
                                                {{ $product->stock->physical_qty ?? 0 }} {{ $product->unit_type }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="section-label fw-bold small">SELECTED PURCHASE ITEMS</span>
                            </div>

                            <div class="table-responsive border rounded bg-light p-2">
                                <table class="table table-sm table-borderless align-middle mb-0 bg-white rounded"
                                    id="purchaseItemsTable">
                                    <thead class="table-light text-uppercase" style="font-size: 10px;">
                                        <tr>
                                            <th>Product (Name & SKU)</th>
                                            <th style="width: 150px;">Quantity</th>
                                            <th style="width: 150px;">Purchase Price (৳)</th>
                                            <th class="text-end" style="width: 120px;">Line Total (৳)</th>
                                            <th class="text-center" style="width: 50px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purchaseTableBody">
                                        <tr id="noProductRow">
                                            <td colspan="5" class="text-center text-muted py-4 small">No products added
                                                yet. Use the search box above to add products.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Inward Inventory Stock Mode -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Inward Inventory Stock Mode *</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-white position-relative">
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="modeReceived" value="RECEIVED" checked>
                                            <label class="form-check-label fw-bold text-success small" for="modeReceived">
                                                RECEIVED (Immediate Stock In)
                                            </label>
                                        </div>
                                        <p class="text-muted mb-0 ps-4" style="font-size: 11px;">Warehouse stock
                                            increments immediately upon saving.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-white">
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="radio" name="status" id="modeDraft"
                                                value="DRAFT">
                                            <label class="form-check-label fw-bold text-secondary small" for="modeDraft">
                                                DRAFT (Pending Shipment)
                                            </label>
                                        </div>
                                        <p class="text-muted mb-0 ps-4" style="font-size: 11px;">Warehouse stock remains
                                            unchanged until shipment is confirmed.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Totals & Payment Summary -->
                        <div class="row g-3 bg-light p-3 rounded border align-items-center">
                            <div class="col-md-4">
                                <span class="text-muted small d-block">Total Purchase Amount:</span>
                                <span class="h5 fw-bold text-dark" id="displayTotalAmount">৳0.00</span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold mb-1">Payment Paid Now (৳):</label>
                                <input type="number" step="0.01" name="paid_amount" id="paidAmountInput"
                                    class="form-control form-control-sm py-2" value="0" oninput="calculateTotals()"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted small d-block">Supplier Due (Remaining):</span>
                                <span class="h5 fw-bold text-danger" id="displayDueAmount">৳0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-emerald btn-sm px-3 text-white bg-emerald shadow-xs"
                            style="background-color: #059669 !important;">Receive Purchase & Stock In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($purchases as $purchase)
    @if($purchase->status == 'DRAFT')
        <div class="modal fade" id="editPurchaseModal{{ $purchase->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-light px-4 py-3 border-bottom">
                            <h5 class="modal-title fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                                <i data-lucide="edit-3" class="icon-sm text-emerald" style="color: #059669;"></i>
                                Edit Draft Purchase <span class="font-mono text-muted small">(#{{ $purchase->chalan_no }})</span>
                            </h5>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-start">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 11px;">Supplier Name *</label>
                                <select name="supplier_id" class="form-select shadow-none py-2" required>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->company_name }} ({{ $supplier->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 11px;">Purchase Date *</label>
                                    <input type="date" name="purchase_date" class="form-control shadow-none py-2 font-mono" value="{{ $purchase->purchase_date }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 11px;">Status *</label>
                                    <select name="status" class="form-select shadow-none py-2 fw-semibold text-dark" required>
                                        <option value="DRAFT" {{ $purchase->status == 'DRAFT' ? 'selected' : '' }}>DRAFT</option>
                                        <option value="RECEIVED" {{ $purchase->status == 'RECEIVED' ? 'selected' : '' }}>RECEIVED (Update Stock)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-text text-muted mb-3" style="font-size: 11px;">
                                <i data-lucide="info" class="icon-xs me-1 text-primary"></i> Status changing to <strong>RECEIVED</strong> go automatically add di items to warehouse inventory stock.
                            </div>

                            <div class="mb-0">
                                <label class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 11px;">Note / Remarks</label>
                                <textarea name="note" class="form-control shadow-none" rows="3" placeholder="Add any optional notes here...">{{ $purchase->note }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light px-4 py-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm px-4 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-emerald btn-sm text-white px-4 fw-semibold shadow-xs" style="background-color: #059669 !important;">
                                <i data-lucide="check" class="icon-xs me-1"></i> Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach



@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const searchInput = document.getElementById('productSearchInput');
            const suggestionsBox = document.getElementById('productSuggestions');
            const tableBody = document.getElementById('purchaseTableBody');
            const noProductRow = document.getElementById('noProductRow');

            if (searchInput && suggestionsBox) {
                // Live Search Input Event
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    const items = suggestionsBox.querySelectorAll('.product-suggestion-item');

                    if (query.length > 0) {
                        suggestionsBox.style.display = 'block';
                        let hasMatch = false;
                        items.forEach(item => {
                            const text = item.textContent.toLowerCase();
                            if (text.includes(query)) {
                                item.style.display = 'flex';
                                hasMatch = true;
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    } else {
                        suggestionsBox.style.display = 'none';
                    }
                });

                // Event Delegation for clicking suggestion items
                suggestionsBox.addEventListener('click', function(e) {
                    const button = e.target.closest('.product-suggestion-item');
                    if (!button) return;

                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const sku = button.getAttribute('data-sku');
                    const price = parseFloat(button.getAttribute('data-price')) || 0;
                    const unit = button.getAttribute('data-unit');
                    const stock = button.getAttribute('data-stock');
                    const image = button.getAttribute('data-image');

                    // Check if product already exists in table
                    if (document.getElementById('row-product-' + id)) {
                        alert('Product already added to list!');
                        searchInput.value = '';
                        suggestionsBox.style.display = 'none';
                        return;
                    }

                    // Remove 'no products' row if present
                    if (noProductRow && noProductRow.parentNode) {
                        noProductRow.remove();
                    }

                    // Create new table row
                    const newRow = document.createElement('tr');
                    newRow.className = 'border-bottom product-row';
                    newRow.id = 'row-product-' + id;
                    newRow.innerHTML = `
                        <td>
                            <input type="hidden" name="products[]" value="${id}">
                            <div class="d-flex align-items-center gap-2 py-1">
                                <img src="${image}" alt="Product" class="rounded border" style="width: 35px; height: 35px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark small">${name}</div>
                                    <small class="text-muted font-mono" style="font-size: 10px;">SKU: ${sku}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" name="quantities[]" class="form-control text-center qty-input" value="1" min="1" oninput="calculateTotals()">
                                <span class="input-group-text bg-light" style="font-size: 11px;">${unit}</span>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" name="prices[]" class="form-control text-end price-input" value="${price.toFixed(2)}" oninput="calculateTotals()">
                            </div>
                        </td>
                        <td class="text-end fw-bold text-dark line-total">৳${price.toFixed(2)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm text-danger p-0 shadow-none remove-row-btn"><i data-lucide="x" class="icon-sm"></i></button>
                        </td>
                    `;

                    tableBody.appendChild(newRow);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                    searchInput.value = '';
                    suggestionsBox.style.display = 'none';

                    calculateTotals();
                });

                // Hide suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                        suggestionsBox.style.display = 'none';
                    }
                });
            }

            // Remove row event delegation
            if (tableBody) {
                tableBody.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-row-btn')) {
                        e.target.closest('tr').remove();
                        calculateTotals();

                        if (tableBody.children.length === 0) {
                            const emptyRow = document.createElement('tr');
                            emptyRow.id = 'noProductRow';
                            emptyRow.innerHTML =
                                `<td colspan="5" class="text-center text-muted py-4 small">No products added yet. Use the search box above to add products.</td>`;
                            tableBody.appendChild(emptyRow);
                            calculateTotals();
                        }
                    }
                });
            }
        });

        function calculateTotals() {
            let grandTotal = 0;
            const rows = document.querySelectorAll('.product-row');

            rows.forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const lineTotal = qty * price;

                row.querySelector('.line-total').textContent = '৳' + lineTotal.toFixed(2);
                grandTotal += lineTotal;
            });

            const totalAmountEl = document.getElementById('displayTotalAmount');
            if (totalAmountEl) {
                totalAmountEl.textContent = '৳' + grandTotal.toFixed(2);
            }

            const paidInput = document.getElementById('paidAmountInput');
            const paidAmount = paidInput ? (parseFloat(paidInput.value) || 0) : 0;
            const dueAmount = grandTotal - paidAmount;

            const dueAmountEl = document.getElementById('displayDueAmount');
            if (dueAmountEl) {
                dueAmountEl.textContent = '৳' + (dueAmount > 0 ? dueAmount.toFixed(2) : '0.00');
            }
        }
    </script>
@endpush
