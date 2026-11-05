@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Title & Action -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Customer Due Collection & Payments</h4>
            <small class="text-muted">Track and manage all incoming customer payments and ledger adjustments.</small>
        </div>
        <button class="btn btn-success btn-sm shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
            <i data-lucide="plus-circle" class="icon-sm"></i> Collect Payment
        </button>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form action="{{ route('customers.payments.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <select name="customer_id" class="form-select form-select-sm shadow-none">
                        <option value="">-- Filter by Customer --</option>
                        @foreach($customers as $cust)
                            <option value="{{ $cust->id }}" {{ request('customer_id') == $cust->id ? 'selected' : '' }}>
                                {{ $cust->proprietor_name }} ({{ $cust->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm px-3">Filter</button>
                    <a href="{{ route('customers.payments.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light text-uppercase font-mono" style="font-size: 11px;">
                        <tr>
                            <th class="ps-3">Payment No</th>
                            <th>Date</th>
                            <th>Customer Details</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Collected By</th>
                            <th class="pe-3">Note / Reference</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono" style="font-size: 12px;">
                        @forelse($payments as $payment)
                        <tr>
                            <td class="ps-3 fw-bold text-success">{{ $payment->payment_no }}</td>
                            <td>{{ $payment->payment_date }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $payment->customer->proprietor_name ?? 'N/A' }}</div>
                                <small class="text-muted" style="font-size: 11px;">{{ $payment->customer->shop_name ?? $payment->customer->phone ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $payment->payment_method }}</span>
                            </td>
                            <td class="fw-bold text-success fs-6">৳{{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <div class="text-dark">{{ $payment->collectedBy->proprietor_name ?? 'N/A' }}</div>
                            </td>
                            <td class="pe-3">
                                <div>{{ $payment->reference_no ? 'Ref: ' . $payment->reference_no : '' }}</div>
                                <span class="text-muted text-truncate d-inline-block" style="max-width: 180px; font-size: 11px;">{{ $payment->note ?? '-' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i data-lucide="file-text" class="icon-lg text-secondary mb-2 d-block mx-auto" style="width: 32px; height: 32px;"></i>
                                No payment collection records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payments->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Collect Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('customers.payments.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header bg-light py-3">
                <h5 class="modal-title fw-bold text-dark fs-6">
                    <i data-lucide="credit-card" class="icon-sm me-1 text-success"></i> Collect Due Payment
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 small">
                <!-- Searchable Customer Input -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Search & Select Customer *</label>
                    <input type="text" class="form-control form-control-sm shadow-none font-mono" placeholder="Type customer name or phone..." list="customerList" id="customerSearchInput" autocomplete="off" required>

                    <datalist id="customerList">
                        @foreach($customers as $customer)
                        <option value="{{ $customer->proprietor_name }} - {{ $customer->phone }} (Shop: {{ $customer->shop_name ?? 'N/A' }}) [ID:{{ $customer->id }}]">
                        @endforeach
                    </datalist>

                    <!-- Hidden input to store actual customer_id -->
                    <input type="hidden" name="customer_id" id="selectedCustomerId" required>
                    <small class="text-muted" style="font-size: 10px;">Type name or phone number and select from the list.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Amount (BDT) *</label>
                        <input type="number" name="amount" class="form-control form-control-sm shadow-none font-mono" step="0.01" min="1" placeholder="0.00" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Method *</label>
                        <select name="payment_method" class="form-select form-select-sm shadow-none" required>
                            <option value="CASH">CASH</option>
                            <option value="BKASH">BKASH</option>
                            <option value="BANK">BANK</option>
                            <option value="CHEQUE">CHEQUE</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Date *</label>
                        <input type="date" name="payment_date" class="form-control form-control-sm shadow-none" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Reference No / TrxID</label>
                        <input type="text" name="reference_no" class="form-control form-control-sm shadow-none" placeholder="Optional transaction ID">
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold">Note / Remarks</label>
                    <textarea name="note" class="form-control shadow-none" rows="2" placeholder="Optional remarks..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success btn-sm px-4">Save Payment</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to capture Customer ID from Search Input -->
<script>
    document.getElementById('customerSearchInput').addEventListener('input', function(e) {
        let val = e.target.value;
        let match = val.match(/\[ID:(\d+)\]/);
        let customerIdField = document.getElementById('selectedCustomerId');

        if (match) {
            customerIdField.value = match[1];
        } else {
            customerIdField.value = '';
        }
    });
</script>

@endsection
