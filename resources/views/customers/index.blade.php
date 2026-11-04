@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Retail Customer Profiles & Ledgers')

@section('content')
    <!-- Top Customers Header Banner -->
    <div class="card border-0 shadow-xs mb-4">
        <div class="card-body p-3 p-md-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="h5 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i data-lucide="users" class="text-emerald"></i> RETAIL CUSTOMER PROFILES & LEDGERS
                </h2>
                <p class="text-muted small mb-0">Registered grocery retailers, shopkeeper photos, credit lines, and real-time
                    outstanding balances.</p>
            </div>
            <div>


                <button type="button"
                    class="btn btn-emerald text-white fw-semibold px-3 py-2 shadow-xs d-inline-flex align-items-center gap-2"
                    style="background-color: #059669 !important; font-size: 13px;" data-bs-toggle="modal"
                    data-bs-target="#registerCustomerModal">
                    <i data-lucide="plus-circle" class="icon-sm"></i> Register New Shop
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

    <!-- Customer Card List Section -->
    <div class="row g-3">
        @forelse($customers as $customer)
            <div class="col-12  col-xl-4">
                <div class="card border-0 shadow-xs h-100">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                @if ($customer->photo)
                                    <img src="{{ asset('storage/' . $customer->photo) }}" alt="Shop"
                                        class="rounded border" style="width: 55px; height: 55px; object-fit: cover;">
                                @else
                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted"
                                        style="width: 55px; height: 55px;">
                                        <i data-lucide="store" class="icon-sm"></i>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-muted font-mono" style="font-size: 10px;">{{ $customer->code }}</span>
                                    <h4 class="h6 fw-bold text-dark mb-0">{{ $customer->shop_name }}</h4>
                                    <small class="text-muted" style="font-size: 11px;">Prop:
                                        {{ $customer->proprietor_name }}</small>
                                </div>
                            </div>
                            <span
                                class="badge {{ $customer->status == 'ACTIVE' ? 'bg-emerald-subtle text-emerald border-emerald-subtle' : 'bg-danger-subtle text-danger border-danger-subtle' }} border px-2 py-1 font-mono"
                                style="font-size: 9px;">
                                {{ $customer->status }}
                            </span>
                        </div>

                        <div class="mb-3 text-muted small" style="font-size: 12px;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i data-lucide="phone" class="icon-xs text-secondary"></i>
                                <span>{{ $customer->phone }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i data-lucide="map-pin" class="icon-xs text-secondary"></i>
                                <span>{{ $customer->address }} ({{ $customer->area->name ?? 'N/A' }})</span>
                            </div>
                            <div>
                                <span class="text-secondary">Salesman:</span> <strong
                                    class="text-dark">{{ $customer->salesman->name ?? 'Unassigned' }}</strong>
                            </div>
                        </div>

                        <div class="bg-light rounded p-3 mb-3 border">
                            <div class="row g-2">
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 10px;">Current Outstanding</span>
                                    <span
                                        class="h6 fw-bold text-emerald mb-0">৳{{ number_format($customer->current_due, 2) }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block" style="font-size: 10px;">Credit Limit</span>
                                    <span
                                        class="h6 fw-bold text-dark font-mono mb-0">৳{{ number_format($customer->credit_limit, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                            <a href="#"
                                class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1 text-decoration-none"
                                style="font-size: 12px;">
                                <i data-lucide="book-open" class="icon-xs"></i> Ledger Book
                            </a>
                            <button class="btn btn-outline-secondary btn-sm px-2 d-flex align-items-center gap-1"
                                style="font-size: 12px;" data-bs-toggle="modal"
                                data-bs-target="#editCustomerModal{{ $customer->id }}">
                                <i data-lucide="edit-3" class="icon-xs"></i> Edit
                            </button>
                            <button
                                class="btn btn-emerald btn-sm px-3 text-white bg-emerald ms-auto d-flex align-items-center gap-1 shadow-none"
                                style="font-size: 12px; background-color: #059669 !important;" data-bs-toggle="modal"
                                data-bs-target="#fastOrderModal">
                                <i data-lucide="shopping-cart" class="icon-xs"></i> Take Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal: Edit Customer Details -->
            <div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-dark text-white py-3">
                            <div>
                                <h5 class="modal-title h6 fw-bold">EDIT CUSTOMER DETAILS</h5>
                                <small class="text-secondary" style="font-size: 11px;">Customer Code:
                                    {{ $customer->code }}</small>
                            </div>
                            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('customers.update', $customer->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4" style="max-height: calc(100vh - 180px); overflow-y: auto;">

                                <!-- Shop Photo Update Section -->
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Shopkeeper / Shop Photo (Optional)</label>
                                    <div
                                        class="border border-dashed rounded p-3 text-center bg-light d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $customer->photo ? asset('storage/' . $customer->photo) : 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=100&auto=format&fit=crop&q=60' }}"
                                                alt="Shop" class="rounded border"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="text-start">
                                                <div class="fw-medium small text-dark">Change Photo</div>
                                                <small class="text-muted" style="font-size: 10px;">Select a new image if
                                                    you want to replace</small>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="file" name="photo" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Shop Name *</label>
                                        <input type="text" name="shop_name" class="form-control form-control-sm py-2"
                                            value="{{ $customer->shop_name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Owner / Proprietor Name *</label>
                                        <input type="text" name="proprietor_name"
                                            class="form-control form-control-sm py-2"
                                            value="{{ $customer->proprietor_name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Mobile Phone *</label>
                                        <input type="text" name="phone"
                                            class="form-control form-control-sm font-mono py-2"
                                            value="{{ $customer->phone }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Area / Route *</label>
                                        <select name="area_id" class="form-select form-select-sm py-2" required>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    {{ $customer->area_id == $area->id ? 'selected' : '' }}>
                                                    {{ $area->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Assigned Salesman</label>
                                        <select name="salesman_id" class="form-select form-select-sm py-2">
                                            <option value="">Select Salesman</option>
                                            @foreach ($salesmen as $salesman)
                                                <option value="{{ $salesman->id }}"
                                                    {{ $customer->salesman_id == $salesman->id ? 'selected' : '' }}>
                                                    {{ $salesman->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Shop Address *</label>
                                        <input type="text" name="address" class="form-control form-control-sm py-2"
                                            value="{{ $customer->address }}" required>
                                    </div>

                                    <!-- GPS Location Update Box -->
                                    <div class="col-12">
                                        <div class="p-3 border rounded bg-light">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i data-lucide="map-pin" class="icon-sm text-primary"></i>
                                                <span class="fw-bold small text-dark">দোকানের GPS লোকেশন আপডেট (Smart
                                                    Delivery)</span>
                                            </div>
                                            <p class="text-muted mb-2" style="font-size: 11px;">দোকানে অবস্থানকালে
                                                মোবাইলের GPS চালু করে নিচের বাটনে চাপ দিন।</p>
                                            <button type="button"
                                                class="btn btn-dark btn-sm px-3 text-white fw-medium shadow-none"
                                                style="font-size: 11px;"
                                                onclick="captureLocationEdit('{{ $customer->id }}')">
                                                <i data-lucide="crosshair" class="icon-xs me-1"></i> জিপিএস লোকেশন
                                                রি-ক্যাপচার করুন
                                            </button>
                                            <span id="gpsStatusEdit{{ $customer->id }}"
                                                class="ms-2 small text-success fw-semibold"></span>
                                            <input type="hidden" name="latitude" id="latitudeEdit{{ $customer->id }}"
                                                value="{{ $customer->latitude ?? '' }}">
                                            <input type="hidden" name="longitude" id="longitudeEdit{{ $customer->id }}"
                                                value="{{ $customer->longitude ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Credit Limit (৳) *</label>
                                        <input type="number" step="0.01" name="credit_limit"
                                            class="form-control form-control-sm font-mono py-2"
                                            value="{{ $customer->credit_limit }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Current Due (৳)</label>
                                        <input type="number" step="0.01"
                                            class="form-control form-control-sm font-mono py-2 bg-light"
                                            value="{{ $customer->current_due }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light py-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-emerald btn-sm px-3 text-white bg-emerald shadow-xs"
                                    style="background-color: #059669 !important;">Update Customer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <i data-lucide="users" class="icon-lg text-muted mb-2 opacity-50"
                        style="width: 48px; height: 48px;"></i>
                    <p class="small mb-1">No customers registered yet.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    @if ($customers->hasPages())
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    @endif

    <!-- ==========================================
                            MODAL: REGISTER NEW B2B CUSTOMER
                            ========================================== -->
    <div class="modal fade" id="registerCustomerModal" tabindex="-1" aria-labelledby="registerCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <div>
                        <h5 class="modal-title h6 fw-bold" id="registerCustomerModalLabel">REGISTER NEW B2B CUSTOMER</h5>
                        <small class="text-secondary" style="font-size: 11px;">Customer code is generated automatically
                            (NSM-CUST-XXXX)</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4" style="max-height: calc(100vh - 180px); overflow-y: auto;">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Shopkeeper / Shop Photo (Optional)</label>
                            <div class="border border-dashed rounded p-3 text-center bg-light">
                                <i data-lucide="camera" class="text-muted mb-1" style="width: 24px; height: 24px;"></i>
                                <div class="fw-medium small text-dark">Upload Shop / Proprietor Photo[cite: 3]</div>
                                <small class="text-muted d-block mb-2" style="font-size: 11px;">Select file from your
                                    device[cite: 3]</small>
                                <input type="file" name="photo" class="form-control form-control-sm mx-auto"
                                    style="max-width: 280px;">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Shop Name *[cite: 3]</label>
                                <input type="text" name="shop_name" class="form-control form-control-sm"
                                    placeholder="e.g. Al-Madina Store" required[cite: 3]>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Owner / Proprietor Name *[cite: 3]</label>
                                <input type="text" name="proprietor_name" class="form-control form-control-sm"
                                    placeholder="e.g. Md. Nasiruddin" required[cite: 3]>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Mobile Phone *[cite: 3]</label>
                                <input type="text" name="phone" class="form-control form-control-sm font-mono"
                                    placeholder="017XX-XXXXXX" required[cite: 3]>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Area / Route *[cite: 3]</label>
                                <select name="area_id" class="form-select form-select-sm" required[cite: 3]>
                                    <option value="">Select Area</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Assigned Salesman[cite: 3]</label>
                                <select name="salesman_id" class="form-select form-select-sm">
                                    <option value="">Select Salesman</option>
                                    @foreach ($salesmen as $salesman)
                                        <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Shop Address *[cite: 3]</label>
                                <input type="text" name="address" class="form-control form-control-sm"
                                    placeholder="e.g. Road 4, Plot 12, West Dhanmondi" required[cite: 3]>
                            </div>
                            <div class="col-12">
                                <div class="p-3 border rounded bg-light">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i data-lucide="map-pin" class="icon-sm text-primary"></i>
                                        <span class="fw-bold small text-dark">দোকানের GPS লোকেশন (Smart Delivery)</span>
                                    </div>
                                    <p class="text-muted mb-2" style="font-size: 11px;">দোকানে অবস্থানকালে মোবাইলের GPS
                                        চালু করে নিচের বাটনে চাপ দিন।</p>
                                    <button type="button" id="getGpsBtn"
                                        class="btn btn-dark btn-sm px-3 text-white fw-medium" style="font-size: 11px;"
                                        onclick="captureLocation()">
                                        <i data-lucide="crosshair" class="icon-xs me-1"></i> বর্তমান GPS লোকেশন সংরক্ষণ
                                        করুন
                                    </button>
                                    <span id="gpsStatus" class="ms-2 small text-success fw-semibold"></span>
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Credit Limit (৳) *[cite: 3]</label>
                                <input type="number" step="0.01" name="credit_limit"
                                    class="form-control form-control-sm font-mono" value="50000" required[cite: 3]>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Opening Due (৳)[cite: 3]</label>
                                <input type="number" step="0.01" name="opening_due"
                                    class="form-control form-control-sm font-mono" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-emerald btn-sm px-3 text-white bg-emerald"
                            style="background-color: #059669 !important;">Register Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script to handle GPS Geolocation -->
    <script>
        function captureLocation() {
            const statusSpan = document.getElementById('gpsStatus');
            if (navigator.geolocation) {
                statusSpan.textContent = "Locating...";
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    statusSpan.textContent = "✓ Location Captured Successfully!";
                }, function(error) {
                    statusSpan.textContent = "Error: Unable to retrieve location.";
                    statusSpan.className = "ms-2 small text-danger fw-semibold";
                });
            } else {
                statusSpan.textContent = "Geolocation is not supported by this browser.";
                statusSpan.className = "ms-2 small text-danger fw-semibold";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        function captureLocationEdit(customerId) {
            const statusSpan = document.getElementById('gpsStatusEdit' + customerId);
            if (navigator.geolocation) {
                statusSpan.textContent = "Locating...";
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitudeEdit' + customerId).value = position.coords.latitude;
                    document.getElementById('longitudeEdit' + customerId).value = position.coords.longitude;
                    statusSpan.textContent = "✓ Location Updated Successfully!";
                }, function(error) {
                    statusSpan.textContent = "Error: Unable to retrieve location.";
                    statusSpan.className = "ms-2 small text-danger fw-semibold";
                });
            } else {
                statusSpan.textContent = "Geolocation is not supported.";
                statusSpan.className = "ms-2 small text-danger fw-semibold";
            }
        }
    </script>


@endsection
