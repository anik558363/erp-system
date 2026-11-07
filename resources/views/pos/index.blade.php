@extends('layouts.app')

@section('title', 'NOOR SUPER MART - POS Terminal')

@section('content')
    <div class="container-fluid px-3 py-2" style="max-width: 100%;">
        <!-- Top Banner -->
        <div class="card border-0 shadow-xs mb-3">
            <div class="card-body p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i data-lucide="shopping-cart" class="text-emerald"></i> POINT OF SALE (কাউন্টার বিক্রয় ও সরাসরি
                        বিলিং)
                    </h5>
                    <p class="text-muted small mb-0">POS থেকে কাউন্টার থেকে তাৎক্ষণিক পাইকারি ও খুচরা ক্যাশ মোডে বিক্রি করা
                        সম্ভব।</p>
                </div>
                <!-- Top Summary Cards -->
                <div class="d-flex flex-wrap gap-2 font-mono" style="font-size: 11px;">
                    <div class="border rounded px-3 py-1 bg-light">
                        <span class="text-muted d-block">TODAY POS SALES</span>
                        <strong class="text-dark">৳0.00</strong>
                    </div>
                    <div class="border rounded px-3 py-1 bg-light">
                        <span class="text-muted d-block">CASH (নগদ)</span>
                        <strong class="text-success">৳0.00</strong>
                    </div>
                    <div class="border rounded px-3 py-1 bg-light">
                        <span class="text-muted d-block">ডিজিটাল পেমেন্ট</span>
                        <strong class="text-primary">৳0.00</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show small py-2 mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show small py-2 mb-3" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('pos.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <!-- Left Side: Product Search & Grid -->
                <div class="col-xl-7 col-lg-7">
                    <div class="card border-0 shadow-xs mb-2">
                        <div class="card-body p-2">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control font-mono"
                                    placeholder="বারকোড স্ক্যান করুন বা SKU লিখে Enter চাপুন..." id="barcodeScanInput">
                                <button class="btn btn-dark px-3" type="button"><i data-lucide="scan" class="icon-xs"></i>
                                    স্ক্যান যোগ</button>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-xs">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center gap-2">
                            <input type="text" class="form-control form-control-sm"
                                placeholder="নাম, ব্র্যান্ড বা SKU দিয়ে পণ্য খুঁজুন..." id="productSearch">
                            <select class="form-select form-select-sm w-auto">
                                <option value="">সকল ক্যাটাগরি</option>
                            </select>
                        </div>
                        <div class="card-body p-3" style="min-height: 480px; max-height: 550px; overflow-y: auto;">
                            <div class="row g-3" id="productGrid">
                                @foreach ($products as $product)
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 product-col">
                                        <div class="card border rounded-3 p-3 bg-white h-100 product-card shadow-xs position-relative"
                                            style="cursor: pointer; transition: all 0.2s;" data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->wholesale_price ?? 0 }}"
                                            data-stock="{{ $product->stock->current_stock ?? 0 }}">

                                            <!-- Top Image & Stock Badge -->
                                            <div class="position-relative mb-2 rounded-2 overflow-hidden bg-light"
                                                style="height: 110px;">
                                                @if (!empty($product->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        alt="{{ $product->name }}"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100">
                                                        <i data-lucide="package" class="text-secondary"
                                                            style="width: 36px; height: 36px;"></i>
                                                    </div>
                                                @endif

                                                <!-- Stock / Quantity Badge on Image Top-Right -->
                                                @php
                                                    $currentStock = $product->stock->current_stock ?? 0;
                                                @endphp
                                                <span
                                                    class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-success font-mono shadow-xs"
                                                    style="font-size: 10px;">
                                                    {{ $currentStock }}
                                                </span>
                                            </div>

                                            <!-- SKU / Brand Text -->
                                            <div class="text-muted font-mono mb-1"
                                                style="font-size: 10px; letter-spacing: 0.5px;">
                                                {{ strtoupper($product->sku ?? 'SKU-N/A') }}
                                            </div>

                                            <!-- Product Name -->
                                            <h6 class="fw-bold text-dark mb-2"
                                                style="font-size: 13px; line-height: 1.25; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                                                title="{{ $product->name }}">
                                                {{ $product->name }}
                                            </h6>

                                            <hr class="my-2 text-muted opacity-25">

                                            <!-- Bottom Price & Unit Type -->
                                            <div class="d-flex align-items-end justify-content-between mt-auto">
                                                <div>
                                                    <div class="fw-bold text-dark font-mono fs-6 lh-1">
                                                        ৳{{ number_format($product->wholesale_price ?? 0, 0) }}
                                                    </div>
                                                    <small class="text-muted font-mono" style="font-size: 10px;">
                                                        /{{ $product->unit_type ?? 'Pcs' }}
                                                    </small>
                                                </div>
                                                <div class="text-success fw-bold font-mono" style="font-size: 11px;">
                                                    {{ $currentStock }} {{ $product->unit_type ?? 'Bag' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Cart & Checkout Summary -->
                <div class="col-xl-5 col-lg-5">
                    <div class="card border-0 shadow-xs">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-dark m-0 small font-mono">বিক্রয় কার্ট / Cart (<span
                                    id="cartCount">0</span> আইটেম)</h6>
                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 10px;"
                                id="clearCartBtn">খালি করুন</button>
                        </div>
                        <div class="card-body p-3 font-mono" style="font-size: 12px;">

                            <!-- Customer Selection with Search -->
                            <div class="mb-2 position-relative">
                                <label class="form-label fw-bold mb-1" style="font-size: 11px;">কাস্টমার নির্বাচন
                                    (CUSTOMER)</label>

                                <input type="hidden" name="customer_id" id="selectedCustomerId" value="">

                                <div class="input-group input-group-sm">
                                    <input type="text" id="customerSearchInput" class="form-control font-mono"
                                        placeholder="নাম বা ফোন নম্বর দিয়ে খুঁজুন..." autocomplete="off">
                                    <button class="btn btn-outline-secondary px-2" type="button" id="clearCustomerBtn"
                                        title="Clear">
                                        <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </div>

                                <div id="customerDropdownList"
                                    class="position-absolute w-100 bg-white border rounded shadow-sm mt-1 d-none"
                                    style="max-height: 180px; overflow-y: auto; z-index: 1050; font-size: 11px;">
                                    <div class="p-2 text-muted customer-option border-bottom" style="cursor: pointer;"
                                        data-id="" data-name="Walk-in Counter Customer">
                                        <strong>Walk-in Counter Customer</strong> (কাউন্টার খুচরা কাস্টমার)
                                    </div>
                                    @foreach ($customers as $customer)
                                        <div class="p-2 border-bottom customer-option" style="cursor: pointer;"
                                            data-id="{{ $customer->id }}"
                                            data-name="{{ $customer->shop_name }} ({{ $customer->phone }})">
                                            <div class="fw-bold text-dark">{{ $customer->shop_name ?? 'N/A' }}</div>
                                            <div class="text-muted font-mono" style="font-size: 10px;">Phone:
                                                {{ $customer->phone }} | Area: {{ $customer->area->name ?? 'N/A' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Cart Items Table Container -->
                            <div class="table-responsive mb-3 border rounded"
                                style="max-height: 160px; overflow-y: auto;">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 11px;"
                                    id="cartTable">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th class="ps-2">Item</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th class="text-end pe-2">Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartItemsBody">
                                        <!-- Dynamic Cart Rows -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Calculations -->
                            <div class="border-top pt-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Subtotal (মোট মূল্য):</span>
                                    <span class="fw-bold" id="subtotalDisplay">৳0.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Discount (ছাড়):</span>
                                    <input type="number" step="0.01" name="discount" id="discountInput"
                                        class="form-control form-control-sm w-25 font-mono text-end py-0" value="0">
                                </div>
                                <div class="d-flex justify-content-between fw-bold text-dark mb-3"
                                    style="font-size: 13px;">
                                    <span>Grand Total (সর্বমোট বিল):</span>
                                    <span class="text-success font-mono fs-6" id="grandTotalDisplay">৳0.00</span>
                                    <input type="hidden" name="total_amount" id="hiddenGrandTotal" value="0">
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-2">
                                <label class="form-label fw-bold mb-1" style="font-size: 11px;">পেমেন্ট মাধ্যম (PAYMENT
                                    METHOD)</label>
                                <div class="d-flex gap-1">
                                    <input type="radio" class="btn-check" name="payment_method" id="pmCash"
                                        value="CASH" checked>
                                    <label class="btn btn-outline-success btn-sm flex-fill py-1" style="font-size: 11px;"
                                        for="pmCash">নগদ</label>

                                    <input type="radio" class="btn-check" name="payment_method" id="pmBkash"
                                        value="BKASH">
                                    <label class="btn btn-outline-primary btn-sm flex-fill py-1" style="font-size: 11px;"
                                        for="pmBkash">bKash</label>

                                    <input type="radio" class="btn-check" name="payment_method" id="pmNagad"
                                        value="NAGAD">
                                    <label class="btn btn-outline-warning btn-sm flex-fill py-1" style="font-size: 11px;"
                                        for="pmNagad">Nagad</label>

                                    <input type="radio" class="btn-check" name="payment_method" id="pmBank"
                                        value="BANK">
                                    <label class="btn btn-outline-secondary btn-sm flex-fill py-1"
                                        style="font-size: 11px;" for="pmBank">Bank</label>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label mb-1" style="font-size: 10px;">Transaction ID / রেফারেন্স
                                    নং</label>
                                <input type="text" name="reference_no" class="form-control form-control-sm"
                                    placeholder="Optional transaction ID">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold mb-1" style="font-size: 11px;">টাকা গ্রহণ (Amount
                                        Paid) *</label>
                                    <input type="number" step="0.01" name="paid_amount" id="paidAmountInput"
                                        class="form-control form-control-sm font-mono fw-bold text-success" value="0"
                                        required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold mb-1 text-danger" style="font-size: 11px;">ফেরত টাকা
                                        / বকেয়া:</label>
                                    <div class="form-control form-control-sm font-mono fw-bold bg-light"
                                        id="changeReturnDisplay">৳0.00</div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm"
                                id="completeSaleBtn" style="font-size: 13px;">
                                <i data-lucide="check-circle" class="icon-sm me-1"></i> বিক্রয় সম্পন্ন করুন (POS)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- POS Cart & Interaction JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            let cart = [];

            const productCards = document.querySelectorAll('.product-card');
            const cartItemsBody = document.getElementById('cartItemsBody');
            const cartCount = document.getElementById('cartCount');
            const subtotalDisplay = document.getElementById('subtotalDisplay');
            const discountInput = document.getElementById('discountInput');
            const grandTotalDisplay = document.getElementById('grandTotalDisplay');
            const hiddenGrandTotal = document.getElementById('hiddenGrandTotal');
            const paidAmountInput = document.getElementById('paidAmountInput');
            const changeReturnDisplay = document.getElementById('changeReturnDisplay');
            const clearCartBtn = document.getElementById('clearCartBtn');

            // Customer Search Elements
            const customerSearchInput = document.getElementById('customerSearchInput');
            const customerDropdownList = document.getElementById('customerDropdownList');
            const selectedCustomerId = document.getElementById('selectedCustomerId');
            const clearCustomerBtn = document.getElementById('clearCustomerBtn');

            // Customer Search Logic
            customerSearchInput.addEventListener('focus', function() {
                customerDropdownList.classList.remove('d-none');
            });

            customerSearchInput.addEventListener('input', function() {
                let term = this.value.toLowerCase();
                let options = customerDropdownList.querySelectorAll('.customer-option');
                customerDropdownList.classList.remove('d-none');

                options.forEach(opt => {
                    let text = opt.innerText.toLowerCase();
                    if (text.includes(term)) {
                        opt.style.display = 'block';
                    } else {
                        opt.style.display = 'none';
                    }
                });
            });

            document.querySelectorAll('.customer-option').forEach(opt => {
                opt.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');
                    let name = this.getAttribute('data-name');
                    selectedCustomerId.value = id;
                    customerSearchInput.value = name;
                    customerDropdownList.classList.add('d-none');
                });
            });

            clearCustomerBtn.addEventListener('click', function() {
                selectedCustomerId.value = '';
                customerSearchInput.value = '';
                customerDropdownList.classList.add('d-none');
            });

            document.addEventListener('click', function(e) {
                if (!customerSearchInput.contains(e.target) && !customerDropdownList.contains(e.target)) {
                    customerDropdownList.classList.add('d-none');
                }
            });

            productCards.forEach(card => {
                card.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');
                    let name = this.getAttribute('data-name');
                    let price = parseFloat(this.getAttribute('data-price'));
                    let stock = parseInt(this.getAttribute('data-stock'));

                    let existingItem = cart.find(item => item.id === id);
                    if (existingItem) {
                        if (existingItem.qty < stock) {
                            existingItem.qty++;
                        } else {
                            alert('Stock limit exceeded!');
                        }
                    } else {
                        if (stock > 0) {
                            cart.push({
                                id,
                                name,
                                price,
                                qty: 1,
                                stock
                            });
                        } else {
                            alert('Product out of stock!');
                        }
                    }
                    renderCart();
                });
            });

            function renderCart() {
                cartItemsBody.innerHTML = '';
                let subtotal = 0;
                let totalCount = 0;

                cart.forEach((item, index) => {
                    let itemTotal = item.price * item.qty;
                    subtotal += itemTotal;
                    totalCount += item.qty;

                    let row = `
                    <tr>
                        <td class="ps-2">
                            <div class="fw-bold text-dark text-truncate" style="max-width: 110px;" title="${item.name}">${item.name}</div>
                            <input type="hidden" name="products[]" value="${item.id}">
                            <input type="hidden" name="prices[]" value="${item.price}">
                        </td>
                        <td>
                            <input type="number" name="quantities[]" value="${item.qty}" min="1" max="${item.stock}" class="form-control form-control-sm font-mono py-0 px-1 qty-input" data-index="${index}" style="width: 45px;">
                        </td>
                        <td class="font-mono">৳${item.price.toFixed(0)}</td>
                        <td class="text-end font-mono fw-bold pe-2">৳${itemTotal.toFixed(0)}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-link text-danger p-0 remove-item" data-index="${index}"><i data-lucide="trash-2" class="icon-xs" style="width:14px; height:14px;"></i></button>
                        </td>
                    </tr>
                `;
                    cartItemsBody.insertAdjacentHTML('beforeend', row);
                });

                cartCount.innerText = totalCount;
                subtotalDisplay.innerText = '৳' + subtotal.toFixed(2);

                let discount = parseFloat(discountInput.value) || 0;
                let grandTotal = Math.max(0, subtotal - discount);

                grandTotalDisplay.innerText = '৳' + grandTotal.toFixed(2);
                hiddenGrandTotal.value = grandTotal.toFixed(2);

                if (paidAmountInput.value == 0 || paidAmountInput.value == '') {
                    paidAmountInput.value = grandTotal.toFixed(2);
                }

                calculateChange();

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                attachCartEvents();
            }

            function attachCartEvents() {
                document.querySelectorAll('.qty-input').forEach(input => {
                    input.addEventListener('change', function() {
                        let index = this.getAttribute('data-index');
                        let val = parseInt(this.value) || 1;
                        if (val > cart[index].stock) {
                            alert('Stock limit exceeded!');
                            val = cart[index].stock;
                            this.value = val;
                        }
                        cart[index].qty = val;
                        renderCart();
                    });
                });

                document.querySelectorAll('.remove-item').forEach(btn => {
                    btn.addEventListener('click', function() {
                        let index = this.getAttribute('data-index');
                        cart.splice(index, 1);
                        renderCart();
                    });
                });
            }

            discountInput.addEventListener('input', renderCart);
            paidAmountInput.addEventListener('input', calculateChange);

            function calculateChange() {
                let grandTotal = parseFloat(hiddenGrandTotal.value) || 0;
                let paid = parseFloat(paidAmountInput.value) || 0;
                let change = paid - grandTotal;

                if (change >= 0) {
                    changeReturnDisplay.innerText = '৳' + change.toFixed(2);
                    changeReturnDisplay.classList.remove('text-danger');
                    changeReturnDisplay.classList.add('text-success');
                } else {
                    let due = Math.abs(change);
                    changeReturnDisplay.innerText = '-৳' + due.toFixed(2) + ' (Short)';
                    changeReturnDisplay.classList.remove('text-success');
                    changeReturnDisplay.classList.add('text-danger');
                }
            }

            clearCartBtn.addEventListener('click', function() {
                cart = [];
                renderCart();
            });

            // Product search filter
            document.getElementById('productSearch').addEventListener('input', function(e) {
                let term = e.target.value.toLowerCase();
                document.querySelectorAll('.product-col').forEach(col => {
                    let card = col.querySelector('.product-card');
                    let name = card.getAttribute('data-name').toLowerCase();
                    if (name.includes(term)) {
                        col.style.display = 'block';
                    } else {
                        col.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
