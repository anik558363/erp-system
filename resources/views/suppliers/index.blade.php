@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Supplier Management')

@section('content')
    <!-- Header Section -->
    <div class="card border-0 shadow-xs mb-4">
        <div class="card-body p-3 p-md-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="h5 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i data-lucide="truck" class="text-emerald"></i> SUPPLIER MANAGEMENT (সাপ্লায়ার্স)
                </h2>
                <p class="text-muted small mb-0">Manage manufacturer companies, distributor suppliers, and payable balances.
                </p>
            </div>
            <div>

                {{-- add new supplier button --}}
                <button type="button"
                    class="btn btn-emerald text-white fw-semibold px-3 py-2 shadow-xs d-inline-flex align-items-center gap-2"
                    style="background-color: #059669 !important; font-size: 13px;" data-bs-toggle="modal"
                    data-bs-target="#addSupplierModal">
                    <i data-lucide="plus-circle" class="icon-sm"></i> Add New Supplier
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

    <!-- Table Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-dark mb-0 fs-6">Supplier Directory</h5>
            <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 11px;">Total:
                {{ $suppliers->total() ?? count($suppliers) }} Suppliers</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light text-muted font-mono" style="font-size: 11px; letter-spacing: 0.5px;">
                        <tr>
                            <th class="py-3 px-4">CODE</th>
                            <th class="py-3">COMPANY & REPRESENTATIVE</th>
                            <th class="py-3">PHONE & ADDRESS</th>
                            <th class="py-3">CURRENT DUE / PAYABLE</th>
                            <th class="py-3 text-center">STATUS</th>
                            <th class="py-3 text-end px-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td class="px-4 fw-mono text-muted small">{{ $supplier->code }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $supplier->company_name }}</div>
                                    <div class="text-muted small">Rep: {{ $supplier->name }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $supplier->phone }}</div>
                                    <div class="text-muted small"><i data-lucide="map-pin"
                                            class="icon-xs me-1"></i>{{ $supplier->address }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-danger small">৳{{ number_format($supplier->current_due, 2) }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill {{ $supplier->status == 'ACTIVE' ? 'bg-success-subtle text-success border-success-subtle' : 'bg-danger-subtle text-danger border-danger-subtle' }} border px-2 py-1 fw-medium"
                                        style="font-size: 11px;">
                                        {{ $supplier->status }}
                                    </span>
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-inline-flex align-items-center gap-1">

                                        <a href="{{ route('suppliers.ledger', $supplier->id) }}"
                                            class="btn btn-outline-dark btn-sm py-0 px-2" style="font-size: 11px;">
                                            Ledger Book
                                        </a>

                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-light border text-primary p-1 px-2 shadow-none"
                                            data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->id }}"
                                            title="Edit Supplier">
                                            <i data-lucide="edit-2" class="icon-xs"></i>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-light border text-danger p-1 px-2 shadow-none"
                                                title="Delete Supplier">
                                                <i data-lucide="trash-2" class="icon-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Supplier Modal -->
                            <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-bottom px-4 py-3 bg-dark text-white">
                                            <h5 class="modal-title fw-bold fs-6">Edit Supplier:
                                                {{ $supplier->company_name }}</h5>
                                            <button type="button" class="btn-close btn-close-white shadow-none"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-dark">Company Name
                                                            *</label>
                                                        <input type="text" name="company_name"
                                                            class="form-control form-control-sm py-2"
                                                            value="{{ $supplier->company_name }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label small fw-semibold text-dark">Representative
                                                            Name *</label>
                                                        <input type="text" name="name"
                                                            class="form-control form-control-sm py-2"
                                                            value="{{ $supplier->name }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-dark">Phone Number
                                                            *</label>
                                                        <input type="text" name="phone"
                                                            class="form-control form-control-sm py-2 font-mono"
                                                            value="{{ $supplier->phone }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-dark">Status
                                                            *</label>
                                                        <select name="status" class="form-select form-select-sm py-2"
                                                            required>
                                                            <option value="ACTIVE"
                                                                {{ $supplier->status == 'ACTIVE' ? 'selected' : '' }}>
                                                                Active</option>
                                                            <option value="INACTIVE"
                                                                {{ $supplier->status == 'INACTIVE' ? 'selected' : '' }}>
                                                                Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold text-dark">Supplier
                                                            Address *</label>
                                                        <textarea name="address" class="form-control form-control-sm" rows="2" required>{{ $supplier->address }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top bg-light px-4 py-3">
                                                <button type="button" class="btn btn-secondary btn-sm px-3"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit"
                                                    class="btn btn-emerald btn-sm text-white px-3 fw-semibold shadow-xs"
                                                    style="background-color: #059669 !important;">Update Supplier</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <div class="py-3">
                                        <i data-lucide="truck" class="icon-lg text-muted mb-2 opacity-50"
                                            style="width: 48px; height: 48px;"></i>
                                        <p class="small mb-1">No suppliers found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($suppliers->hasPages())
            <div class="card-footer bg-white py-3 px-4 border-top">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3 bg-dark text-white">
                    <h5 class="modal-title fw-bold fs-6">Add New Supplier</h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Company Name *</label>
                                <input type="text" name="company_name" class="form-control form-control-sm py-2"
                                    placeholder="e.g. City Group / Square" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Representative Name *</label>
                                <input type="text" name="name" class="form-control form-control-sm py-2"
                                    placeholder="e.g. Mr. Rahim" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Phone Number *</label>
                                <input type="text" name="phone" class="form-control form-control-sm py-2 font-mono"
                                    placeholder="01711XXXXXX" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Opening Due / Advance (৳)</label>
                                <input type="number" step="0.01" name="opening_due"
                                    class="form-control form-control-sm py-2 font-mono" value="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">Supplier Address *</label>
                                <textarea name="address" class="form-control form-control-sm" rows="2"
                                    placeholder="Full office/warehouse address..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary btn-sm px-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-emerald btn-sm text-white px-3 fw-semibold shadow-xs"
                            style="background-color: #059669 !important;">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endsection
