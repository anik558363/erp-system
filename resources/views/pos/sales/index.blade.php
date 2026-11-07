@extends('layouts.app')

@section('title', 'NOOR SUPER MART - POS Sales History')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Header Title & Action -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark m-0">POS Sales History & Invoices</h4>
                <small class="text-muted">View all completed counter sales and print receipts.</small>
            </div>
            <a href="{{ route('pos.index') }}" class="btn btn-dark btn-sm shadow-xs d-flex align-items-center gap-1">
                <i data-lucide="shopping-cart" class="icon-sm"></i> Back to POS Terminal
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show small py-2 mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Sales Table Card -->
        <div class="card border-0 shadow-xs">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 13px;">
                        <thead class="table-light text-uppercase font-mono" style="font-size: 10px;">
                            <tr>
                                <th class="ps-3">Sale No</th>
                                <th>Date / Time</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="ps-3 fw-bold font-mono text-emerald">{{ $sale->sale_no }}</td>
                                    <td class="font-mono text-muted" style="font-size: 11px;">
                                        {{ $sale->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            {{ $sale->customer->shop_name ?? 'Walk-in Customer' }}</div>
                                        <small class="text-muted font-mono"
                                            style="font-size: 10px;">{{ $sale->customer->phone ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $sale->cashier->name ?? 'N/A' }}</td>
                                    <td class="font-mono fw-bold text-success">৳{{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle font-mono px-2 py-1"
                                            style="font-size: 10px;">
                                            {{ $sale->status }}
                                        </span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <a href="{{ route('pos.sales.receipt', $sale->id) }}"
                                            class="btn btn-outline-dark btn-sm py-0 px-2" style="font-size: 11px;"
                                            target="_blank">
                                            Receipt / Invoice
                                        </a>
                                        <form action="{{ route('pos.sales.destroy', $sale->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to void this sale and restore stock?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2"
                                                style="font-size: 11px;">Void</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No POS sales found yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($sales->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
