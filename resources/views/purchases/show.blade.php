@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Purchase Invoice Details')

@section('content')
    <div class="card border-0 shadow-xs mb-4">
        <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="h5 fw-bold text-dark mb-1">PURCHASE INVOICE DETAILS</h2>
                <p class="text-muted small mb-0">Invoice chalan no: <span class="font-mono fw-bold text-dark">{{ $purchase->chalan_no }}</span></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i data-lucide="arrow-left" class="icon-xs me-1"></i> Back to List
                </a>
                <button onclick="window.print()" class="btn btn-emerald btn-sm text-white px-3 fw-semibold shadow-xs" style="background-color: #059669 !important;">
                    <i data-lucide="printer" class="icon-xs me-1"></i> Print Invoice
                </button>
            </div>
        </div>
    </div>

    <!-- Printable Invoice Card -->
    <div class="card border-0 shadow-sm rounded-3 p-4 p-md-5 bg-white" id="printableInvoice">
        <!-- Professional Header Section Side-by-Side -->
        <div class="row mb-4 pb-3 border-bottom align-items-center">
            <div class="col-sm-6">
                <h3 class="fw-bold text-dark fs-4 mb-1">NOOR SUPER MART</h3>
                <p class="text-muted small mb-1">B2B Distribution System & Central HQ</p>
                <p class="text-muted small mb-0"><i data-lucide="map-pin" class="icon-xs me-1"></i> Mirpur-02, Dhaka, Bangladesh</p>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <h4 class="fw-bold fs-6 text-uppercase text-dark mb-1">Chalan #: <span class="font-mono text-emerald" style="color: #059669;">{{ $purchase->chalan_no }}</span></h4>
                <p class="text-muted small mb-1">Receive Date: {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</p>
                <div>
                    <span class="badge {{ $purchase->status == 'RECEIVED' ? 'bg-success-subtle text-success border-success-subtle' : 'bg-warning-subtle text-warning-emphasis border' }} border px-2 py-1" style="font-size: 11px;">
                        STATUS: {{ $purchase->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Supplier & Payment Summary Side-by-Side (Clean Compact Box) -->
        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <h6 class="fw-bold text-dark small text-uppercase mb-2 border-bottom pb-1">Supplier Information</h6>
                    <div class="fw-bold text-dark small">{{ $purchase->supplier->company_name ?? 'N/A' }}</div>
                    <div class="text-muted small">Representative: {{ $purchase->supplier->name ?? 'N/A' }}</div>
                    <div class="text-muted small">Phone: {{ $purchase->supplier->phone ?? 'N/A' }}</div>
                    <div class="text-muted small">Address: {{ $purchase->supplier->address ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded border h-100">
                    <h6 class="fw-bold text-dark small text-uppercase mb-2 border-bottom pb-1">Payment Summary</h6>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Total Amount:</span>
                        <span class="fw-bold text-dark font-mono">৳{{ number_format($purchase->total_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Paid Amount:</span>
                        <span class="fw-bold text-primary font-mono">৳{{ number_format($purchase->paid_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small border-top pt-1 mt-1">
                        <span class="fw-semibold text-danger">Due Balance:</span>
                        <span class="fw-bold text-danger font-mono">৳{{ number_format($purchase->due_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light text-uppercase font-mono" style="font-size: 11px;">
                    <tr>
                        <th class="py-2 px-3" style="width: 50px;">SL</th>
                        <th class="py-2">Product Name & SKU</th>
                        <th class="py-2 text-center" style="width: 120px;">Quantity</th>
                        <th class="py-2 text-end" style="width: 150px;">Purchase Price (৳)</th>
                        <th class="py-2 text-end px-3" style="width: 130px;">Line Total (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $index => $item)
                        <tr>
                            <td class="px-3 text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark small">{{ $item->product->name ?? 'N/A' }}</div>
                                <small class="text-muted font-mono" style="font-size: 10px;">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                            </td>
                            <td class="text-center fw-medium small">
                                {{ $item->quantity }} {{ $item->product->unit_type ?? '' }}
                            </td>
                            <td class="text-end font-mono small">৳{{ number_format($item->purchase_price, 2) }}</td>
                            <td class="text-end font-mono fw-bold px-3 small">৳{{ number_format($item->sub_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($purchase->note)
            <div class="mb-4 small text-muted bg-light p-3 rounded border">
                <strong>Note:</strong> {{ $purchase->note }}
            </div>
        @endif

        <!-- Footer Signatures -->
        <div class="row mt-5 pt-4 border-top text-center text-muted small">
            <div class="col-4">
                <div class="border-top border-dark pt-2 mx-3">Prepared By</div>
            </div>
            <div class="col-4">
                <div class="border-top border-dark pt-2 mx-3">Supplier Signature</div>
            </div>
            <div class="col-4">
                <div class="border-top border-dark pt-2 mx-3">Authorized Signature</div>
            </div>
        </div>
    </div>

   @push('styles')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }
                #printableInvoice, #printableInvoice * {
                    visibility: visible;
                }
                #printableInvoice {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    margin: 0;
                    padding: 15px !important;
                    box-shadow: none !important;
                    border: none !important;
                    background: #fff !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
                .btn, header, aside, .card:not(#printableInvoice) {
                    display: none !important;
                }
                .row {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                }
                .col-sm-6, .col-md-6 {
                    flex: 0 0 50% !important;
                    max-width: 50% !important;
                }
                .text-sm-end, .text-md-end {
                    text-align: right !important;
                }
                .bg-light {
                    background-color: #f8f9fa !important;
                    -webkit-print-color-adjust: exact !important;
                }
            }
        </style>
    @endpush
@endsection
