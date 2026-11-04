@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Category Management')

@section('content')
    <!-- Header Section with Breadcrumb & Action -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 11px;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                            class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Product Categories</li>
                </ol>
            </nav>
            <h1 class="h4 fw-bold text-dark mb-0">Product Categories</h1>
            <p class="text-muted small mb-0">Manage wholesale product categories (e.g., Edible Oil, Rice, Spices) and
                organize inventories.</p>
        </div>
        <div>
            <button type="button"
                class="btn btn-emerald text-white fw-semibold px-3 py-2 shadow-xs d-inline-flex align-items-center gap-2"
                style="background-color: #059669 !important; font-size: 13px;" data-bs-toggle="modal"
                data-bs-target="#addCategoryModal">
                <i data-lucide="plus-circle" class="icon-sm"></i> Add New Category
            </button>
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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small shadow-xs border-0 mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content Card / Table Box -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-dark mb-0 fs-6">Category Directory</h5>
            <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 11px;">Total:
                {{ $categories->total() ?? count($categories) }} Categories</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light text-muted font-mono" style="font-size: 11px; letter-spacing: 0.5px;">
                        <tr>
                            <th class="py-3 px-4">#ID</th>
                            <th class="py-3">CATEGORY NAME</th>
                            <th class="py-3 text-center">TOTAL PRODUCTS</th>
                            <th class="py-3">CREATED / UPDATED AT</th> <!-- Notun Column -->
                            <th class="py-3 text-end px-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="px-4 fw-mono text-muted small">#{{ $category->id }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $category->name }}</div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill bg-emerald-subtle text-emerald border border-emerald-subtle px-2 py-1 fw-medium"
                                        style="font-size: 11px;">
                                        {{ $category->products_count ?? 0 }} items
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium">
                                        <i data-lucide="calendar" class="icon-xs text-muted me-1"></i>
                                        {{ $category->created_at ? $category->created_at->format('d M Y, h:i A') : 'N/A' }}
                                    </div>
                                    @if ($category->updated_at && $category->updated_at != $category->created_at)
                                        <div class="text-muted" style="font-size: 10px;">
                                            Updated: {{ $category->updated_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-light border text-primary p-1 px-2 shadow-none"
                                            data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}"
                                            title="Edit Category">
                                            <i data-lucide="edit-2" class="icon-xs"></i>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-light border text-danger p-1 px-2 shadow-none"
                                                title="Delete Category">
                                                <i data-lucide="trash-2" class="icon-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-bottom px-4 py-3">
                                            <h5 class="modal-title fw-bold fs-6 text-dark">Edit Product Category</h5>
                                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-dark">Category Name
                                                        *</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted border-end-0">
                                                            <i data-lucide="layers" class="icon-xs"></i>
                                                        </span>
                                                        <input type="text" name="name"
                                                            value="{{ $category->name }}"
                                                            class="form-control border-start-0 ps-0 shadow-none py-2"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top bg-light px-4 py-3">
                                                <button type="button" class="btn btn-secondary btn-sm px-3"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit"
                                                    class="btn btn-emerald btn-sm text-white px-3 fw-semibold shadow-xs"
                                                    style="background-color: #059669 !important;">Update Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="py-3">
                                        <i data-lucide="folder-open" class="icon-lg text-muted mb-2 opacity-50"></i>
                                        <p class="small mb-1">No product categories found.</p>
                                        <a href="#" class="text-emerald text-decoration-none small fw-semibold"
                                            data-bs-toggle="modal" data-bs-target="#addCategoryModal">Click here to add
                                            your first category</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($categories->hasPages())
            <div class="card-footer bg-white py-3 px-4 border-top">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold fs-6 text-dark">Add New Category</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Category Name *</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted border-end-0">
                                    <i data-lucide="layers" class="icon-xs"></i>
                                </span>
                                <input type="text" name="name"
                                    class="form-control border-start-0 ps-0 shadow-none py-2"
                                    placeholder="e.g. Edible Oil" required autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary btn-sm px-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-emerald btn-sm text-white px-3 fw-semibold shadow-xs"
                            style="background-color: #059669 !important;">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endsection
