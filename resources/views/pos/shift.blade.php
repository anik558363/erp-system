@extends('layouts.app')

@section('title', 'NOOR SUPER MART - POS Shift Opening')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="fw-bold m-0 fs-6"><i data-lucide="lock" class="icon-sm me-1"></i> POS Cash Register & Shift
                            Opening</h5>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success small">{{ session('success') }}</div>
                        @endif

                        @if (isset($activeShift))
                            <div class="alert alert-info small">
                                <h6 class="fw-bold">Active Shift Running!</h6>
                                <p class="mb-1"><strong>Register:</strong> {{ $activeShift->register->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Opened At:</strong> {{ $activeShift->opened_at }}</p>
                                <p class="mb-0"><strong>Opening Cash:</strong>
                                    ৳{{ number_format($activeShift->opening_cash, 2) }}</p>
                            </div>
                            <a href="{{ route('pos.index') }}" class="btn btn-success w-100 fw-bold">Go to POS Terminal</a>
                        @else
                            <form action="{{ route('pos.shift.open') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Select Cash Register *</label>
                                    <select name="register_id" class="form-select form-select-sm" required>
                                        <option value="">-- Choose Register --</option>
                                        @foreach ($registers as $reg)
                                            <option value="{{ $reg->id }}">{{ $reg->name }}
                                                ({{ $reg->location ?? 'Main' }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Opening Cash (ড্রয়ারে শুরুর ক্যাশ টাকা)
                                        *</label>
                                    <input type="number" step="0.01" name="opening_cash"
                                        class="form-control form-control-sm font-mono fw-bold text-success" value="0.00"
                                        required>
                                    <small class="text-muted" style="font-size: 10px;">কাউন্টার খোলার সময় ক্যাশ বাক্সে খুচরা
                                        বা ক্যাশ কত টাকা আছে তা লিখুন।</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Shift Note / Remarks</label>
                                    <textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Optional note..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-dark w-100 py-2 fw-bold shadow-xs">Open Shift & Start
                                    POS</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
