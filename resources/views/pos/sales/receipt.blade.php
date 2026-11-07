@extends('layouts.app')

@section('title', 'Invoice - ' . $sale->sale_no)

@section('content')
    <div class="container py-4 d-flex justify-content-center">
        <!-- Printable Receipt Card with specific ID -->
        <div class="card border shadow-sm p-3 bg-white font-mono" id="printableInvoice"
            style="width: 100%; max-width: 420px; font-size: 12px;">

            <!-- Shop Header -->
            <div class="text-center mb-2 border-bottom pb-2">
                <h5 class="fw-bold m-0 text-dark" style="font-size: 16px;">NOOR SUPER MART</h5>
                <small class="text-muted d-block" style="font-size: 10px;">B2B Wholesale & Distribution ERP</small>
                <p class="mb-0 text-secondary" style="font-size: 10px;">Mirpur-02, Dhaka, Bangladesh | Phone: 01700000000</p>
            </div>

            <!-- Sale Meta Info -->
            <div class="mb-2 border-bottom pb-2" style="font-size: 11px;">
                <div class="d-flex justify-content-between">
                    <span><strong>Invoice:</strong> {{ $sale->sale_no }}</span>
                    <span><strong>Date:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span><strong>Customer:</strong> {{ $sale->customer->shop_name ?? 'Walk-in Customer' }}</span>
                    <span><strong>Cashier:</strong> {{ $sale->cashier->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between text-muted" style="font-size: 10px;">
                    <span>Phone: {{ $sale->customer->phone ?? 'N/A' }}</span>
                    <span>Counter: {{ $sale->register->name ?? 'Main' }}</span>
                </div>
            </div>

            <!-- Items Table -->
            <table class="table table-sm table-borderless mb-2" style="font-size: 11px;">
                <thead class="border-bottom border-top">
                    <tr>
                        <th class="ps-0">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end pe-0">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->items as $item)
                        <tr class="border-bottom border-light">
                            <td class="ps-0 text-truncate" style="max-width: 130px;"
                                title="{{ $item->product->name ?? 'Product' }}">
                                {{ $item->product->name ?? 'Product' }}
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end pe-0 fw-bold">৳{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Calculations Summary -->
            <div class="border-top pt-2 mb-3" style="font-size: 11px;">
                <div class="d-flex justify-content-between mb-1">
                    <span>Subtotal:</span>
                    <span>৳{{ number_format($sale->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Discount:</span>
                    <span>৳{{ number_format($sale->discount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold text-dark fs-6 border-top border-bottom py-1 my-1">
                    <span>GRAND TOTAL:</span>
                    <span class="text-success">৳{{ number_format($sale->total_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between text-muted">
                    <span>Payment Method:</span>
                    <span class="fw-bold text-dark">{{ $sale->payments->first()->method ?? 'CASH' }}</span>
                </div>
                <div class="d-flex justify-content-between text-muted">
                    <span>Paid Amount:</span>
                    <span>৳{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between text-muted">
                    <span>Change Return:</span>
                    <span>৳{{ number_format($sale->change_amount ?? $sale->paid_amount - $sale->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Footer Thank you note -->
            <div class="text-center border-top pt-2 mb-3 text-muted" style="font-size: 10px;">
                <p class="mb-1">*** THANK YOU FOR YOUR BUSINESS ***</p>
                <p class="mb-0">Software by Noor Super Mart ERP System</p>
            </div>

            <!-- Action Buttons (Hidden on Print) -->
            <div class="d-flex gap-2 justify-content-center d-print-none">
                <button onclick="window.print()" class="btn btn-dark btn-sm px-3 fw-bold" style="font-size: 11px;">
                    <i data-lucide="printer" class="icon-xs me-1" style="width: 14px; height: 14px;"></i> Print Receipt
                </button>
                <a href="{{ route('pos.sales.index') }}" class="btn btn-outline-secondary btn-sm px-3"
                    style="font-size: 11px;">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Direct Print CSS inside view -->
    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }

            #printableInvoice,
            #printableInvoice * {
                visibility: visible !important;
            }

            #printableInvoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 5px !important;
                border: none !important;
                box-shadow: none !important;
            }

            .d-print-none {
                display: none !important;
            }
        }
    </style>
@endsection
