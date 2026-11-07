@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Process Return')

@section('content')
<div class="container-fluid px-4 py-3" style="max-width: 950px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Process Product Return</h4>
            <small class="text-muted">Search original sale invoice to return items as Good or Damaged stock.</small>
        </div>
        <a href="{{ route('pos.returns.index') }}" class="btn btn-dark btn-sm shadow-xs">
            Back to List
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small py-2 mb-3" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Invoice Search Box -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('pos.returns.create') }}" method="GET" class="input-group input-group-sm">
                <input type="text" name="sale_no" class="form-control font-mono" placeholder="Enter Original Invoice No (e.g. POS-261107-XXXX)..." value="{{ request('sale_no') }}" required>
                <button class="btn btn-dark px-4" type="submit">Search Invoice</button>
            </form>
        </div>
    </div>

    @if(isset($sale))
        <form action="{{ route('pos.returns.store') }}" method="POST">
            @csrf
            <input type="hidden" name="pos_sale_id" value="{{ $sale->id }}">

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold m-0 font-mono">Invoice: <span class="text-primary">{{ $sale->sale_no }}</span></h6>
                    <small class="text-muted">Customer: {{ $sale->customer->shop_name ?? 'Walk-in Customer' }}</small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm align-middle mb-0 font-mono" style="font-size: 12px;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 40px;">Select</th>
                                <th>Product Name</th>
                                <th class="text-center">Sold Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-center" style="width: 130px;">Return Qty</th>
                                <th style="width: 140px;">Condition</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $index => $item)
                                <tr>
                                    <td class="ps-3">
                                        <input type="hidden" name="return_items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                        <input type="hidden" name="return_items[{{ $index }}][sale_item_id]" value="{{ $item->id }}">
                                        <input type="checkbox" name="return_items[{{ $index }}][selected]" value="1" class="form-check-input">
                                    </td>
                                    <td>{{ $item->product->name ?? 'Product' }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">৳{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-center">
                                        <input type="number" name="return_items[{{ $index }}][qty]" value="1" min="1" max="{{ $item->quantity }}" class="form-control form-control-sm text-center font-mono">
                                    </td>
                                    <td>
                                        <select name="return_items[{{ $index }}][return_type]" class="form-select form-select-sm font-mono" style="font-size: 11px;">
                                            <option value="GOOD">Good (রিস্টক হবে)</option>
                                            <option value="DAMAGED">Damaged (নষ্ট)</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-3">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Refund Method</label>
                        <select name="refund_method" class="form-select form-select-sm">
                            <option value="CASH">Cash (নগদ ফেরত)</option>
                            <option value="BKASH">bKash</option>
                            <option value="NAGAD">Nagad</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Return Reason</label>
                        <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason for return...">
                    </div>
                </div>
                <button type="submit" class="btn btn-danger fw-bold py-2 w-100" onclick="return confirm('Confirm processing this return?');">
                    Process Return & Adjust Stock
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
