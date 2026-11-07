@extends('layouts.app')

@section('title', 'Supplier Ledger - ' . ($supplier->company_name ?? 'Supplier Statement'))

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Top Header & Print Button (Hidden on Print) -->
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <div>
            <a href="{{ route('suppliers.index') }}" class="text-decoration-none text-muted small fw-bold font-mono">
                &larr; Back to Suppliers Directory
            </a>
            <h4 class="fw-bold text-dark m-0 mt-1">Supplier Ledger Statement</h4>
            <small class="text-muted">Detailed audit trail of purchases, payments, and payable liabilities.</small>
        </div>
        <button onclick="window.print()" class="btn btn-dark btn-sm shadow-xs font-mono px-3 py-2">
            <i data-lucide="printer" class="icon-xs me-1"></i> Print Statement / PDF
        </button>
    </div>

    <!-- Printable Statement Card -->
    <div class="card border-0 shadow-sm p-4 p-md-5 bg-white font-mono rounded-4 mx-auto" style="max-width: 1000px;">

        <!-- Corporate Letterhead Header -->
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
            <div>
                <h3 class="fw-bold text-dark m-0 tracking-tight">NOOR SUPER MART</h3>
                <p class="text-muted small m-0">B2B Core ERP & Wholesale Distribution Management System</p>
                <span class="badge bg-dark text-white mt-2" style="font-size: 10px;">OFFICIAL STATEMENT</span>
            </div>
            <div class="text-end">
                <h6 class="fw-bold text-uppercase text-secondary m-0">Payable Ledger Statement</h6>
                <p class="text-muted small mb-0 font-mono">Statement Date: {{ date('d M, Y') }}</p>
                <p class="text-muted font-mono" style="font-size: 11px;">Ref ID: NSM-SUP-LEDGER-{{ $supplier->id }}</p>
            </div>
        </div>

        <!-- Meta Information Grid (Supplier & Financial Summary Cards) -->
        <div class="row g-4 mb-4">
            <!-- Supplier Details Box -->
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100 border border-opacity-10">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Supplier Profile</span>
                    <h5 class="fw-bold text-dark mt-1 mb-1">{{ $supplier->company_name }}</h5>
                    <p class="mb-1 text-secondary small"><strong>Representative:</strong> {{ $supplier->name }}</p>
                    <p class="mb-1 text-secondary small"><strong>Phone Contact:</strong> {{ $supplier->phone }}</p>
                    <p class="mb-0 text-secondary small"><strong>Office Address:</strong> {{ $supplier->address }}</p>
                </div>
            </div>

            <!-- Financial Summary Box -->
            <div class="col-md-6">
                <div class="p-3 bg-dark text-white rounded-3 h-100 shadow-xs">
                    <span class="text-uppercase text-white-50 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Financial Summary</span>
                    <div class="d-flex justify-content-between small mt-2 mb-1">
                        <span class="text-white-50">Opening Due:</span>
                        <span class="font-mono fw-bold">৳{{ number_format($supplier->opening_due, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-white-50">Total Purchases:</span>
                        <span class="font-mono text-info fw-bold">৳{{ number_format($totalPurchases, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-white-50">Total Paid / Settled:</span>
                        <span class="font-mono text-success fw-bold">৳{{ number_format($totalPaid, 2) }}</span>
                    </div>
                    <div class="border-top border-secondary pt-2 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-warning small">Current Payable Due:</span>
                        <span class="font-mono fw-bold text-warning fs-5">৳{{ number_format($currentDue, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Section: Purchase History & Payment History -->
        <div class="row g-4 mb-5">
            <!-- Purchase / Chalan History -->
            <div class="col-12">
                <h6 class="fw-bold text-dark font-mono text-uppercase mb-2" style="font-size: 12px; border-left: 3px solid #198754; padding-left: 8px;">
                    Purchase / Chalan History
                </h6>
                <div class="table-responsive border rounded-3 overflow-hidden">
                    <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 12px;">
                        <thead class="table-light font-mono" style="font-size: 10px;">
                            <tr>
                                <th class="ps-3">Chalan No</th>
                                <th>Purchase Date</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-end pe-3">Remaining Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $pur)
                                <tr>
                                    <td class="ps-3 font-mono text-primary fw-bold">{{ $pur->chalan_no }}</td>
                                    <td class="font-mono text-muted">{{ \Carbon\Carbon::parse($pur->purchase_date)->format('d M Y') }}</td>
                                    <td class="text-end font-mono fw-bold text-dark">৳{{ number_format($pur->total_amount, 2) }}</td>
                                    <td class="text-end pe-3 font-mono text-danger fw-bold">৳{{ number_format($pur->due_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">No purchase records found for this supplier.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Settlement History -->
            <div class="col-12 mt-4">
                <h6 class="fw-bold text-dark font-mono text-uppercase mb-2" style="font-size: 12px; border-left: 3px solid #0d6efd; padding-left: 8px;">
                    Payment Settlement History
                </h6>
                <div class="table-responsive border rounded-3 overflow-hidden">
                    <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 12px;">
                        <thead class="table-light font-mono" style="font-size: 10px;">
                            <tr>
                                <th class="ps-3">Payment Date</th>
                                <th>Method</th>
                                <th>Reference No</th>
                                <th class="text-end pe-3">Settled Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $pay)
                                <tr>
                                    <td class="ps-3 font-mono text-muted">{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') }}</td>
                                    <td><span class="badge bg-secondary font-mono" style="font-size: 10px;">{{ $pay->payment_method }}</span></td>
                                    <td class="font-mono text-primary">{{ $pay->reference_no ?? 'N/A' }}</td>
                                    <td class="text-end pe-3 font-mono text-success fw-bold">৳{{ number_format($pay->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">No payment settlement records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Corporate Signature Footer -->
        <div class="row mt-5 pt-4 border-top text-muted small font-mono">
            <div class="col-6 text-center">
                <div class="border-top border-dark w-75 mx-auto pt-1">Supplier's Signature & Seal</div>
            </div>
            <div class="col-6 text-center">
                <div class="border-top border-dark w-75 mx-auto pt-1">Authorized Accountant Signature</div>
            </div>
        </div>

    </div>
</div>

<!-- Print CSS Optimization -->
<style>
@media print {
    body {
        background-color: #fff !important;
        -webkit-print-color-adjust: exact;
    }
    .d-print-none {
        display: none !important;
    }
    .card {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
    }
}
</style>
@endsection
