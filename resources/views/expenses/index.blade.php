@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Expense Management')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Expense Management</h4>
            <small class="text-muted">Track daily operational costs, rent, bills, and office expenditures.</small>
        </div>
        <button type="button" class="btn btn-success btn-sm shadow-xs fw-bold" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
            + Record New Expense
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small py-2 mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small py-2 mb-3" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Summary Cards & Filters -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-xs bg-dark text-white p-3">
                <small class="text-uppercase font-mono text-muted">Total Filtered Expenses</small>
                <h3 class="fw-bold font-mono text-warning m-0">৳{{ number_format($totalExpense, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-xs p-2">
                <form action="{{ route('expenses.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <select name="category_id" class="form-select form-select-sm font-mono">
                            <option value="">-- All Categories --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="start_date" class="form-control form-control-sm font-mono" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" class="form-control form-control-sm font-mono" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-dark btn-sm w-100">Filter</button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card border-0 shadow-xs">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 13px;">
                    <thead class="table-light font-mono" style="font-size: 10px;">
                        <tr>
                            <th class="ps-3">Date</th>
                            <th>Category</th>
                            <th>Reference No</th>
                            <th>Amount</th>
                            <th>Recorded By</th>
                            <th>Note</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $exp)
                            <tr>
                                <td class="ps-3 font-mono text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M Y') }}</td>
                                <td><span class="badge bg-secondary font-mono" style="font-size: 10px;">{{ $exp->category->name ?? 'N/A' }}</span></td>
                                <td class="font-mono text-primary">{{ $exp->reference_no ?? 'N/A' }}</td>
                                <td class="font-mono fw-bold text-danger">৳{{ number_format($exp->amount, 2) }}</td>
                                <td>{{ $exp->recorder->name ?? 'N/A' }}</td>
                                <td class="text-muted" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $exp->note }}">{{ $exp->note ?? 'N/A' }}</td>
                                <td class="text-end pe-3">
                                    <form action="{{ route('expenses.destroy', $exp->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 11px;" onclick="return confirm('Are you sure to delete this expense record?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No expense records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($expenses->hasPages())
            <div class="card-footer bg-white py-2">
                {{ $expenses->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Record Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white py-3">
                <h6 class="modal-title fw-bold font-mono">Record New Expense</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="modal-body font-mono small">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Expense Category *</label>
                        <select name="expense_category_id" class="form-select form-select-sm" required>
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Amount (৳) *</label>
                        <input type="number" step="0.01" name="amount" class="form-control form-control-sm font-mono text-danger fw-bold" placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Expense Date *</label>
                        <input type="date" name="expense_date" class="form-control form-control-sm font-mono" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference No / Voucher No</label>
                        <input type="text" name="reference_no" class="form-control form-control-sm font-mono" placeholder="Optional voucher/memo no...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note / Description</label>
                        <textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Details about this expense..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 fw-bold">Save Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
