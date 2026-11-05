@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Supplier Payments')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h5 fw-bold text-dark mb-1">SUPPLIER PAYMENTS & LEDGER</h2>
            <p class="text-muted small mb-0">Manage supplier dues clearance and payment history.</p>
        </div>
        <button type="button" class="btn btn-emerald btn-sm text-white px-3 fw-semibold shadow-xs"
            style="background-color: #059669 !important;" data-bs-toggle="modal" data-bs-target="#newPaymentModal">
            <i data-lucide="plus" class="icon-xs me-1"></i> Make New Payment
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Payments Table Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase font-mono" style="font-size: 11px;">
                        <tr>
                            <th class="py-3 px-3">Date</th>
                            <th class="py-3">Supplier Company</th>
                            <th class="py-3">Chalan / Purchase Ref</th>
                            <th class="py-3">Method</th>
                            <th class="py-3 text-end">Amount (৳)</th>
                            <th class="py-3 px-3 text-center">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="px-3 small font-mono">
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold text-dark small">{{ $payment->supplier->company_name ?? 'N/A' }}
                                    </div>
                                    <small class="text-muted"
                                        style="font-size: 11px;">{{ $payment->supplier->name ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="font-mono badge bg-light text-dark border">
                                        {{ $payment->purchase->chalan_no ?? 'General Payment' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border font-mono small"
                                        style="font-size: 10px;">
                                        {{ $payment->payment_method }}
                                    </span>
                                </td>
                                <td class="text-end font-mono fw-bold text-success">
                                    ৳{{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-3 text-center text-muted small">
                                    {{ $payment->note ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4 small">No payment records found yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($payments->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <!-- New Payment Modal -->
    <div class="modal fade" id="newPaymentModal" tabindex="-1" aria-labelledby="newPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('supplier.payments.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-dark fs-6" id="newPaymentModalLabel">Record Supplier Payment
                        </h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Select Supplier *</label>
                            <select name="supplier_id" class="form-select form-select-sm" required>
                                <option value="">Choose Supplier...</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->company_name }}
                                        ({{ $supplier->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Payment Amount (৳) *</label>
                            <input type="number" step="0.01" name="amount" class="form-control form-control-sm"
                                placeholder="0.00" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Payment Date *</label>
                                <input type="date" name="payment_date" class="form-control form-control-sm"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Payment Method *</label>
                                <select name="payment_method" class="form-select form-select-sm" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank">Bank Transfer</option>
                                    <option value="Bkash">bKash / Nagad</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Reference / Chalan No (Optional)</label>
                            <input type="text" name="reference_no" class="form-control form-control-sm"
                                placeholder="e.g. PUR-2026-XXXX">
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-semibold">Note / Remarks (Optional)</label>
                            <textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Write payment note..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-emerald btn-sm text-white px-3 fw-semibold"
                            style="background-color: #059669 !important;">Save Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
