@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Expense Categories')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Header Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark m-0">Expense Categories</h4>
                <small class="text-muted">Manage operational expense types (e.g., Rent, Utility, Salary, Transport).</small>
            </div>
            <button type="button" class="btn btn-success btn-sm shadow-xs fw-bold" data-bs-toggle="modal"
                data-bs-target="#addCategoryModal">
                + Add New Category
            </button>
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

        <!-- Categories Table -->
        <div class="card border-0 shadow-xs">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 13px;">
                        <thead class="table-light font-mono" style="font-size: 10px;">
                            <tr>
                                <th class="ps-3">#ID</th>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th>Total Expenses</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr>
                                    <td class="ps-3 font-mono text-muted">#{{ $cat->id }}</td>
                                    <td class="fw-bold text-dark">{{ $cat->name }}</td>
                                    <td class="text-muted">{{ $cat->description ?? 'N/A' }}</td>
                                    <td><span class="badge bg-secondary font-mono">{{ $cat->expenses_count }} Records</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2"
                                            style="font-size: 11px;" data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal{{ $cat->id }}">
                                            Edit
                                        </button>

                                        <form action="{{ route('expense.categories.destroy', $cat->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2"
                                                style="font-size: 11px;"
                                                onclick="return confirm('Are you sure to delete this category?');">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editCategoryModal{{ $cat->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-dark text-white py-3">
                                                <h6 class="modal-title fw-bold font-mono">Edit Expense Category</h6>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('expense.categories.update', $cat->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body font-mono small text-start">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Category Name *</label>
                                                        <input type="text" name="name"
                                                            class="form-control form-control-sm"
                                                            value="{{ $cat->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea name="description" class="form-control form-control-sm" rows="2">{{ $cat->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light py-2">
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm px-4 fw-bold">Update Category</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No expense categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($categories->hasPages())
                <div class="card-footer bg-white py-2">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white py-3">
                    <h6 class="modal-title fw-bold font-mono">Add New Expense Category</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('expense.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body font-mono small text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category Name *</label>
                            <input type="text" name="name" class="form-control form-control-sm"
                                placeholder="e.g. Office Rent, Electricity Bill" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Optional details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm px-4 fw-bold">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
