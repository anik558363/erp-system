@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Central Depot Stock Controller & Audit')

@section('content')
    <!-- Top Header & Action Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h5 fw-bold text-dark mb-1">CENTRAL DEPOT STOCK CONTROLLER & AUDIT</h2>
            <p class="text-muted small mb-0">Strict separation: Available Stock = Physical - Sellable Stock. Reserved Stock.
                Damaged stock is strictly quarantined.</p>
        </div>
        <div class="d-flex gap-2">

            <button type="button" class="btn btn-dark btn-sm px-3 shadow-xs d-flex align-items-center gap-1"
                style="background-color: #1e293b;" data-bs-toggle="modal" data-bs-target="#stockAdjustmentModal">
                <i data-lucide="plus-circle" class="icon-xs"></i> Stock Adjustment / Damage Entry
            </button>
        </div>
    </div>

    <!-- Summary Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-primary">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 11px;">Physical Stock in
                    Depot</span>
                <h3 class="fw-bold text-dark mt-1 mb-0 font-mono">{{ number_format($totalPhysical ?? 0) }} <span
                        class="fs-6 text-muted fw-normal">units</span></h3>
                <small class="text-muted" style="font-size: 11px;">Total sellable on pallets</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-info">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 11px;">Reserved (Approved
                    Orders)</span>
                <h3 class="fw-bold text-dark mt-1 mb-0 font-mono">{{ number_format($totalReserved ?? 0) }} <span
                        class="fs-6 text-muted fw-normal">units</span></h3>
                <small class="text-muted" style="font-size: 11px;">Committed for dispatch</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-success">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 11px;">Available (Free to
                    Sell)</span>
                <h3 class="fw-bold text-success mt-1 mb-0 font-mono">
                    {{ number_format(($totalPhysical ?? 0) - ($totalReserved ?? 0)) }} <span
                        class="fs-6 text-muted fw-normal">units</span></h3>
                <small class="text-muted" style="font-size: 11px;">Physical - Reserved</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-danger">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 11px;">Damaged /
                    Quarantine</span>
                <h3 class="fw-bold text-danger mt-1 mb-0 font-mono">{{ number_format($totalDamaged ?? 0) }} <span
                        class="fs-6 text-muted fw-normal">units</span></h3>
                <small class="text-muted" style="font-size: 11px;">Quarantine from returns</small>
            </div>
        </div>
    </div>



    <!-- Search & Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('stock.index') }}" method="GET"
                class="row g-3 align-items-center justify-content-between">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i data-lucide="search"
                                class="icon-xs"></i></span>
                        <input type="text" name="search" class="form-control shadow-none border-start-0"
                            value="{{ request('search') }}" placeholder="Search product inventory...">
                        <button type="submit" class="btn btn-dark btn-sm px-3">Search</button>
                        @if (request('search'))
                            <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-auto text-muted small font-mono">
                    {{ $stocks->total() }} Items Listed
                </div>
            </form>
        </div>
    </div>

    <!-- Detailed Inventory Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase font-mono" style="font-size: 11px;">
                        <tr>
                            <th class="py-3 px-3">SKU / Code</th>
                            <th class="py-3">Product Name</th>
                            <th class="py-3">Unit</th>
                            <th class="py-3 text-center">Physical Stock</th>

                            <th class="py-3 text-center">Available Stock</th>
                            <th class="py-3 text-center">Damaged Stock</th>
                            <th class="py-3 text-center">Min Threshold</th>
                            <th class="py-3 px-3 text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            <tr>
                                <td class="px-3 font-mono fw-bold text-dark small">{{ $stock->product->sku ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light rounded p-1 border text-center"
                                            style="width: 32px; height: 32px;">
                                            <i data-lucide="box" class="icon-xs text-secondary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ $stock->product->name ?? 'N/A' }}</div>
                                            <small class="text-muted font-mono"
                                                style="font-size: 10px;">{{ $stock->product->category->name ?? 'General' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border font-mono small"
                                        style="font-size: 10px;">
                                        {{ $stock->product->unit ?? 'Pcs' }}
                                    </span>
                                </td>
                                <td class="text-center font-mono fw-bold text-dark">
                                    {{ number_format($stock->physical_qty ?? 0) }}</td>
                               
                                <td class="text-center font-mono text-success fw-bold">
                                    {{ number_format(($stock->physical_qty ?? 0) - ($stock->reserved_qty ?? 0)) }}</td>
                                <td class="text-center font-mono text-danger fw-semibold">
                                    {{ number_format($stock->damaged_qty ?? 0) }}</td>
                                <td class="text-center font-mono text-muted">{{ $stock->product->min_threshold ?? 10 }}
                                </td>
                                <td class="px-3 text-end">
                                    @if (($stock->physical_qty ?? 0) <= ($stock->product->min_threshold ?? 10))
                                        <span
                                            class="badge bg-warning-subtle text-warning border border-warning-subtle font-mono px-2 py-1"
                                            style="font-size: 10px;">LOW STOCK</span>
                                    @else
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle font-mono px-2 py-1"
                                            style="font-size: 10px;">IN STOCK</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5 small font-mono">No inventory stock
                                    records found in central depot.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($stocks->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $stocks->links() }}
            </div>
        @endif
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="stockAdjustmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <form action="{{ route('stock.adjust') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light px-4 py-3 border-bottom">
                        <h5 class="modal-title fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                            <i data-lucide="sliders" class="icon-sm text-emerald" style="color: #059669;"></i>
                            Manual Stock Adjustment & Damage Entry
                        </h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-start">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase"
                                style="font-size: 11px;">Select Product *</label>
                            <select name="product_id" class="form-select shadow-none py-2" required>
                                <option value="">-- Choose Product --</option>
                                @foreach ($stocks as $stk)
                                    <option value="{{ $stk->product_id }}">{{ $stk->product->name ?? 'N/A' }} (SKU:
                                        {{ $stk->product->sku ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary text-uppercase"
                                    style="font-size: 11px;">Adjustment Type *</label>
                                <select name="adjustment_type" class="form-select shadow-none py-2 fw-semibold" required>
                                    <option value="add">Add Stock (Inward)</option>
                                    <option value="subtract">Subtract Stock (Outward)</option>
                                    <option value="damage">Move to Damaged / Quarantine</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary text-uppercase"
                                    style="font-size: 11px;">Quantity *</label>
                                <input type="number" name="quantity" class="form-control shadow-none py-2 font-mono"
                                    min="1" placeholder="Enter qty" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold text-secondary text-uppercase"
                                style="font-size: 11px;">Reason / Remarks Note</label>
                            <textarea name="note" class="form-control shadow-none" rows="3" placeholder="Write adjustment reason..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-4 fw-semibold"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark btn-sm text-white px-4 fw-semibold shadow-xs"
                            style="background-color: #1e293b;">
                            <i data-lucide="check" class="icon-xs me-1"></i> Save Adjustment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
