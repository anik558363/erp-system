@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Customer Ledger Statement')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header & Back Button (Hidden during print) -->
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <div>
            <a href="{{ route('customers.index') }}" class="text-decoration-none text-muted small mb-1 d-inline-flex align-items-center gap-1">
                <i data-lucide="arrow-left" class="icon-xs"></i> Back to Customers
            </a>
            <h4 class="fw-bold text-dark m-0">Customer Ledger & Financial Statement</h4>
            <small class="text-muted">Complete purchase, payment history and live balance tracking.</small>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-dark btn-sm shadow-sm d-flex align-items-center gap-1">
                <i data-lucide="printer" class="icon-sm"></i> Print Statement
            </button>
        </div>
    </div>

    <!-- Printable Official Ledger Box -->
    <div class="card border-0 shadow-sm p-4 bg-white" id="printableArea">
        <!-- Official Memo Header -->
        <div class="text-center border-bottom pb-3 mb-4">
            <h3 class="fw-bold text-dark mb-1">NOOR SUPER MART</h3>
            <p class="text-muted small mb-1">B2B Core ERP & Wholesale Distribution Management</p>
            <h5 class="fw-bold text-success text-uppercase mt-2" style="letter-spacing: 1px;">Customer Ledger Statement</h5>
            <small class="text-muted font-mono">Statement Date: {{ date('d M, Y') }}</small>
        </div>

        <!-- Customer Profile Summary Info -->
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light h-100">
                    <span class="text-muted small d-block mb-1">Customer Information:</span>
                    <h5 class="fw-bold text-dark mb-1">{{ $customer->shop_name }}</h5>
                    <p class="mb-1 small"><strong>Proprietor:</strong> {{ $customer->proprietor_name }}</p>
                    <p class="mb-1 small"><strong>Phone:</strong> {{ $customer->phone }}</p>
                    <p class="mb-0 small"><strong>Address:</strong> {{ $customer->address }} (Area: {{ $customer->area->name ?? 'N/A' }})</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light h-100 font-mono">
                    <span class="text-muted small d-block mb-2">Financial Summary:</span>
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Opening Due:</span>
                        <span class="fw-bold">৳{{ number_format($openingDue, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Total Purchases:</span>
                        <span class="fw-bold text-primary">৳{{ number_format($totalOrders, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Total Paid / Collected:</span>
                        <span class="fw-bold text-success">৳{{ number_format($totalPaid, 2) }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold text-danger">
                        <span>Current Outstanding Due:</span>
                        <span>৳{{ number_format($currentDue, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Columns: Orders & Payments History -->
        <div class="row g-4">
            <!-- Orders History Table -->
            <div class="col-xl-6">
                <div class="border rounded overflow-hidden">
                    <div class="bg-light px-3 py-2 border-bottom">
                        <h6 class="fw-bold text-dark m-0 small text-uppercase font-mono">
                            <i data-lucide="shopping-bag" class="icon-xs me-1 text-primary"></i> Order & Purchase History
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0 text-nowrap" style="font-size: 11px;">
                            <thead class="table-light text-uppercase font-mono">
                                <tr>
                                    <th class="ps-2">Order No</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th class="pe-2">Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="ps-2 fw-bold text-primary">{{ $order->order_no ?? 'ORD-' . $order->id }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="fw-bold">৳{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-success">৳{{ number_format($order->paid_amount, 2) }}</td>
                                    <td class="pe-2 text-danger fw-bold">৳{{ number_format($order->total_amount - $order->paid_amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">No orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payments History Table -->
            <div class="col-xl-6">
                <div class="border rounded overflow-hidden">
                    <div class="bg-light px-3 py-2 border-bottom">
                        <h6 class="fw-bold text-dark m-0 small text-uppercase font-mono">
                            <i data-lucide="credit-card" class="icon-xs me-1 text-success"></i> Payment Collection History
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0 text-nowrap" style="font-size: 11px;">
                            <thead class="table-light text-uppercase font-mono">
                                <tr>
                                    <th class="ps-2">Payment No</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th class="pe-2">Collector</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td class="ps-2 fw-bold text-success">{{ $payment->payment_no }}</td>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $payment->payment_method }}</span></td>
                                    <td class="fw-bold text-success">৳{{ number_format($payment->amount, 2) }}</td>
                                    <td class="pe-2 text-muted">{{ $payment->collectedBy->name ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">No collections found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Footer Signature Area -->
        <div class="row mt-5 pt-4 border-top d-print-flex">
            <div class="col-4 text-center">
                <div class="border-top border-dark pt-1 mx-4" style="font-size: 11px; font-weight: 600;">Customer Signature</div>
            </div>
            <div class="col-4"></div>
            <div class="col-4 text-center">
                <div class="border-top border-dark pt-1 mx-4" style="font-size: 11px; font-weight: 600;">Authorized Signature</div>
            </div>
        </div>
    </div>
</div>

<!-- CSS for Clean Printing -->
<style>
@media print {
    body {
        background-color: #fff !important;
        color: #000 !important;
    }
    .d-print-none {
        display: none !important;
    }
    .sidebar, .navbar, footer {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    .container-fluid {
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
}
</style>
@endsection
