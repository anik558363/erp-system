@extends('layouts.app')

@section('title', 'NOOR SUPER MART - POS Registers')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Header Title & Action -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark m-0">POS Register Management</h4>
                <small class="text-muted">Manage your cash counters and store registers.</small>
            </div>
            <button class="btn btn-dark btn-sm shadow-xs d-flex align-items-center gap-1" data-bs-toggle="modal"
                data-bs-target="#addRegisterModal">
                <i data-lucide="plus-circle" class="icon-sm"></i> Add New Register
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show small py-2 mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Registers Table Card -->
        <div class="card border-0 shadow-xs">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 13px;">
                        <thead class="table-light text-uppercase font-mono" style="font-size: 10px;">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Register Name</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registers as $reg)
                                <tr>
                                    <td class="ps-3 fw-bold font-mono">#{{ $reg->id }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $reg->name }}</div>
                                    </td>
                                    <td>{{ $reg->location ?? 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $reg->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} font-mono px-2 py-1"
                                            style="font-size: 10px;">
                                            {{ $reg->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                        </span>
                                    </td>
                                    <td class="font-mono text-muted" style="font-size: 11px;">
                                        {{ $reg->created_at->format('d M, Y') }}</td>
                                    <td class="pe-3 text-end">
                                        <button type="button" class="btn btn-outline-dark btn-sm py-0 px-2"
                                            style="font-size: 11px;" data-bs-toggle="modal"
                                            data-bs-target="#editRegisterModal{{ $reg->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('pos.registers.destroy', $reg->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this register?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2"
                                                style="font-size: 11px;">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editRegisterModal{{ $reg->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('pos.registers.update', $reg->id) }}" method="POST"
                                            class="modal-content border-0 shadow-lg">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-dark text-white py-3">
                                                <h5 class="modal-title h6 fw-bold">Edit Register: {{ $reg->name }}</h5>
                                                <button type="button" class="btn-close btn-close-white shadow-none"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 small text-start">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Register Name *</label>
                                                    <input type="text" name="name"
                                                        class="form-control form-control-sm" value="{{ $reg->name }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Location / Branch</label>
                                                    <input type="text" name="location"
                                                        class="form-control form-control-sm" value="{{ $reg->location }}">
                                                </div>
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" name="is_active"
                                                        value="1" id="isActiveEdit{{ $reg->id }}"
                                                        {{ $reg->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold"
                                                        for="isActiveEdit{{ $reg->id }}">Is Active</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light py-2">
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-dark btn-sm px-4">Update
                                                    Register</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No POS registers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Register Modal -->
    <div class="modal fade" id="addRegisterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('pos.registers.store') }}" method="POST" class="modal-content border-0 shadow-lg">
                @csrf
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title h6 fw-bold">Add New POS Register</h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 small text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Register Name *</label>
                        <input type="text" name="name" class="form-control form-control-sm"
                            placeholder="e.g. Counter 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Location / Branch</label>
                        <input type="text" name="location" class="form-control form-control-sm"
                            placeholder="e.g. Main Showroom">
                    </div>
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                            id="isActiveAdd" checked>
                        <label class="form-check-label fw-bold" for="isActiveAdd">Is Active</label>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm px-4">Save Register</button>
                </div>
            </form>
        </div>
    </div>
@endsection
