@extends('layouts.app')

@section('title', 'NOOR SUPER MART - POS Shift Management')

@section('content')
    <div class="container-fluid px-4 py-3" style="max-width: 800px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark m-0">POS Cash Counter Shift</h4>
                <small class="text-muted">Manage your daily counter open and close shift operations.</small>
            </div>
            <a href="{{ route('pos.index') }}" class="btn btn-dark btn-sm shadow-xs">
                Back to POS Terminal
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show small py-2 mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show small py-2 mb-3" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (!$activeShift)
            <!-- Open Shift Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="m-0 fw-bold font-mono">Open New POS Shift (শিফট শুরু করুন)</h6>
                </div>
                <form action="{{ route('pos.shift.open') }}" method="POST" class="card-body p-4 font-mono small">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Register / Counter *</label>
                        <select name="register_id" class="form-select form-select-sm" required>
                            <option value="">-- Choose Register --</option>
                            @foreach ($registers as $reg)
                                <option value="{{ $reg->id }}">{{ $reg->name }} ({{ $reg->location ?? 'Main' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Opening Cash (ড্রয়ারের শুরুর খুচরা টাকা) *</label>
                        <input type="number" step="0.01" name="opening_cash"
                            class="form-control form-control-sm font-mono text-success fw-bold" value="500" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opening Note (Optional)</label>
                        <textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Any initial notes..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold py-2">Open Shift & Start Selling</button>
                </form>
            </div>
        @else
            <!-- Close Shift Panel -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold font-mono">Active Shift Details (চলমান শিফট)</h6>
                    <span class="badge bg-light text-success font-mono">STATUS: OPEN</span>
                </div>
                <div class="card-body p-4 font-mono small">
                    <div class="row mb-3 bg-light p-3 rounded border">
                        <div class="col-6">
                            <p class="mb-1"><strong>Cashier:</strong> {{ auth()->user()->name }}</p>
                            <p class="mb-1"><strong>Register:</strong> {{ $activeShift->register->name ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>Opened At:</strong>
                                {{ \Carbon\Carbon::parse($activeShift->opened_at)->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-1"><strong>Opening Cash:</strong>
                                ৳{{ number_format($activeShift->opening_cash, 2) }}</p>
                        </div>
                    </div>

                    <form action="{{ route('pos.shift.close', $activeShift->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-danger">Counted Cash in Drawer (ড্রয়ার গুne মোট ক্যাশ কত
                                পেলেন?) *</label>
                            <input type="number" step="0.01" name="counted_cash"
                                class="form-control font-mono fw-bold text-success fs-5 py-2" placeholder="0.00" required>
                            <small class="text-muted">সারা দিনের ক্যাশ বিক্রির টাকা এবং শুরুর ওপেনিং ক্যাশ মিলিয়ে ড্রয়ারে
                                থাকা মোট ফিজিক্যাল টাকা এখানে লিখুন।</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Closing Note</label>
                            <textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Closing remarks..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2"
                            onclick="return confirm('Are you sure you want to close this shift?');">
                            Close Shift & Reconcile
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
