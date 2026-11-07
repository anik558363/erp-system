@extends('layouts.app')

@section('title', 'NOOR SUPER MART - POS Returns')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">POS Return History</h4>
            <small class="text-muted">Manage product returns, damage tracking, and stock adjustments.</small>
        </div>
        <a href="{{ route('pos.returns.create') }}" class="btn btn-success btn-sm shadow-xs fw-bold">
            + Process New Return
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small py-2 mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-xs">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 13px;">
                    <thead class="table-light font-mono" style="font-size: 10px;">
                        <tr>
                            <th class="ps-3">Invoice No</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Refund Amount</th>
                            <th>Method</th>
                            <th>Cashier</th>
                            <th class="pe-3">Date / Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $ret)
                            <tr>
                                <td class="ps-3 font-mono fw-bold text-primary">{{ $ret->sale_no }}</td>
                                <td>{{ $ret->product_name }}</td>
                                <td>
                                    <span class="badge {{ $ret->return_type == 'GOOD' ? 'bg-success' : 'bg-danger' }}" style="font-size: 10px;">
                                        {{ $ret->return_type }}
                                    </span>
                                </td>
                                <td class="font-mono fw-bold">{{ $ret->quantity }}</td>
                                <td class="font-mono text-danger fw-bold">৳{{ number_format($ret->refund_amount, 2) }}</td>
                                <td class="font-mono">{{ $ret->refund_method }}</td>
                                <td>{{ $ret->cashier_name }}</td>
                                <td class="pe-3 font-mono text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($ret->created_at)->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No return records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
