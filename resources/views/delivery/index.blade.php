@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Delivery Fleet & Field Collection')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Top Header Banner -->
        <div class="card border-0 shadow-xs mb-4">
            <div
                class="card-body p-3 p-md-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h2 class="h5 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i data-lucide="truck" class="text-emerald"></i> DELIVERY FLEET & FIELD COLLECTION
                    </h2>
                    <p class="text-muted small mb-0">দোকানের অ্যাড্রেস, জিপিএস নেভিগেশন (Google Maps), পণ্য ডেলিভারি এবং
                        ক্যাশ কালেকশন ব্যবস্থাপনা।</p>
                </div>

            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show small shadow-xs border-0 mb-4" role="alert"
                style="background-color: #ecfdf5; color: #065f46;">
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="check-circle" class="icon-sm"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Assigned Orders Table -->
        <div class="card border-0 shadow-xs">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold text-dark m-0">Delivery Orders & Fleet Tracking</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 12px;">
                        <thead class="table-light text-uppercase font-mono" style="font-size: 10px;">
                            <tr>
                                <th class="ps-3">Order No</th>
                                <th>Shop Name & Customer</th>
                                <th>Area / Route</th>
                                <th>Grand Total</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th> <!-- নতুন ডিউ কলাম হেডার -->
                                <th>Status</th>
                                <th>Delivery Man</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $order)
                                @php
                                    // অর্ডারের বকেয়া হিসাব: মোট দাম - পরিশোধিত টাকা
                                    $dueAmount = max(0, $order->total_amount - $order->paid_amount);
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-bold text-primary font-mono">
                                        {{ $order->order_no ?? 'ORD-' . $order->id }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $order->customer->shop_name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $order->customer->phone ?? '' }}</small>
                                    </td>
                                    <td>{{ $order->area->name ?? 'N/A' }}</td>
                                    <td class="fw-bold">৳{{ number_format($order->total_amount, 2) }}</td>

                                    <!-- পেইড অ্যামাউন্ট -->
                                    <td class="fw-bold text-success font-mono">
                                        ৳{{ number_format($order->paid_amount, 2) }}
                                    </td>

                                    <!-- ডিউ বা বকেয়া অ্যামাউন্ট (নতুন কলাম) -->
                                    <td class="fw-bold text-danger font-mono">
                                        ৳{{ number_format($dueAmount, 2) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border font-mono px-2 py-1"
                                            style="font-size: 9px;">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if (auth()->user()->hasRole('Owner|Office'))
                                            <form action="{{ route('delivery.assign') }}" method="POST"
                                                class="d-flex align-items-center gap-1">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <select name="delivery_man_id"
                                                    class="form-select form-select-sm font-mono py-1"
                                                    style="font-size: 11px; width: 140px;" onchange="this.form.submit()">
                                                    <option value="">Select Rider</option>
                                                    @foreach ($deliveryMen as $man)
                                                        <option value="{{ $man->id }}"
                                                            {{ $order->delivery_man_id == $man->id ? 'selected' : '' }}>
                                                            {{ $man->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary-subtle text-dark border font-mono px-2 py-1"
                                                style="font-size: 10px;">
                                                {{ $order->deliveryMan->name ?? 'Assigned to You' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            @if ($order->status != 'DELIVERED')
                                                <form action="{{ route('delivery.status.update', $order->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="DELIVERED">
                                                    <button type="submit" class="btn btn-success btn-sm py-0 px-2"
                                                        style="font-size: 11px;">
                                                        Mark Delivered
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-success fw-bold small"><i data-lucide="check"
                                                        class="icon-xs"></i> Completed</span>
                                            @endif

                                            <!-- পেমেন্ট কালেকশন বাটন -->
                                            <button type="button" class="btn btn-outline-dark btn-sm py-0 px-2"
                                                style="font-size: 11px;" data-bs-toggle="modal"
                                                data-bs-target="#collectModal{{ $order->id }}">
                                                <i data-lucide="dollar-sign" class="icon-xs"></i> Collect
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">No delivery orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($orders as $order)
        <!-- Modal: Order Specific Payment Collection -->
        <div class="modal fade" id="collectModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('delivery.payments.store') }}" method="POST"
                    class="modal-content border-0 shadow-lg">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    <div class="modal-header bg-dark text-white py-3">
                        <h5 class="modal-title h6 fw-bold">Collect Payment for Order: {{ $order->order_no }}</h5>
                        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 small text-start">
                        <div class="mb-2">
                            <span class="text-muted">Customer:</span>
                            <strong>{{ $order->customer->shop_name ?? 'N/A' }}</strong>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted">Order Grand Total:</span> <strong
                                class="text-primary font-mono">৳{{ number_format($order->total_amount, 2) }}</strong> |
                            <span class="text-muted">Already Paid:</span> <strong
                                class="text-success font-mono">৳{{ number_format($order->paid_amount, 2) }}</strong>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Collection Amount (BDT) *</label>
                            <input type="number" step="0.01" name="amount"
                                class="form-control form-control-sm font-mono"
                                value="{{ max(0, $order->total_amount - $order->paid_amount) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Method *</label>
                            <select name="payment_method" class="form-select form-select-sm" required>
                                <option value="CASH">CASH</option>
                                <option value="BKASH">BKASH</option>
                                <option value="BANK">BANK</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control form-control-sm"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Note / Remarks</label>
                            <textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Collection note..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm px-4">Save Collection</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach







@endsection
