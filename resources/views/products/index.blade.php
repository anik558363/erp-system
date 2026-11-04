@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Products & Inventory Catalog')

@section('content')
    <!-- Top Catalog Header Banner -->
    <div class="card border-0 shadow-xs mb-4">
        <div class="card-body p-3 p-md-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="h5 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i data-lucide="package" class="text-emerald"></i> PRODUCT & INVENTORY CATALOG
                </h2>
                <p class="text-muted small mb-0">Standard wholesale FMCG products, pricing tiers, and warehouse stock levels.
                </p>
            </div>
            <div>
                <!-- Button to trigger Add New Product Modal -->
                <button type="button"
                    class="btn btn-emerald text-white fw-semibold px-3 py-2 shadow-xs d-inline-flex align-items-center gap-2"
                    style="background-color: #059669 !important; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i data-lucide="plus-circle" class="icon-sm"></i> Add New Product
                </button>
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

    <!-- Search & Category Filter Section -->
    <div class="card border-0 shadow-xs mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div class="w-100" style="max-width: 320px;">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i data-lucide="search" class="icon-sm"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control border-start-0 ps-0 shadow-none"
                                placeholder="Search by name, brand, SKU...">
                        </div>
                    </form>
                </div>

                <div class="w-100 overflow-hidden">
                    <div class="category-scroll-container d-flex gap-2">
                        <a href="{{ route('products.index') }}"
                            class="btn btn-sm {{ !request('category_id') ? 'btn-dark' : 'btn-light border text-secondary' }} px-3 py-1 fw-medium text-decoration-none">ALL</a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('products.index', ['category_id' => $cat->id]) }}"
                                class="btn btn-sm {{ request('category_id') == $cat->id ? 'btn-dark' : 'btn-light border text-secondary' }} px-3 py-1 fw-medium text-decoration-none">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid Section -->
    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="card border-0 shadow-xs h-100">
                    <div class="position-relative border-bottom text-center bg-light"
                        style="height: 160px; overflow: hidden;">
                        <span class="badge bg-dark position-absolute top-0 start-0 m-2 font-mono"
                            style="font-size: 10px; z-index: 2;">{{ $product->sku }}</span>

                        <!-- Edit Icon to trigger Edit Modal -->
                        <button
                            class="badge bg-light text-dark border position-absolute top-0 end-0 m-2 p-1 border-0 bg-white shadow-xs rounded"
                            style="z-index: 2; cursor: pointer;" data-bs-toggle="modal"
                            data-bs-target="#editProductModal{{ $product->id }}">
                            <i data-lucide="more-vertical" class="icon-xs"></i>
                        </button>

                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=500&auto=format&fit=crop&q=60' }}"
                            alt="{{ $product->name }}" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="text-muted"
                                style="font-size: 11px;">{{ $product->category->name ?? 'N/A' }}</span>
                            <span class="text-muted font-mono"
                                style="font-size: 11px;">{{ $product->brand->name ?? 'N/A' }}</span>
                        </div>
                        <h4 class="h6 fw-bold text-dark mb-2 text-truncate" title="{{ $product->name }}">
                            {{ $product->name }}</h4>

                        <div class="bg-light rounded p-2 mb-2 border-0">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted" style="font-size: 10px;">Wholesale / Trade</span>
                                <span class="fw-bold text-dark"
                                    style="font-size: 12px;">৳{{ number_format($product->wholesale_price, 2) }} <span
                                        class="text-muted fw-normal" style="font-size: 10px;">/
                                        {{ $product->unit_type }}</span></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted" style="font-size: 10px;">Retail MRP</span>
                                <span class="text-muted"
                                    style="font-size: 11px;">৳{{ number_format($product->mrp, 2) }}</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top text-muted"
                            style="font-size: 11px;">
                            <span>Purchase Cost: ৳{{ number_format($product->purchase_price, 2) }}</span>
                            <span
                                class="badge {{ $product->status == 'ACTIVE' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border">{{ $product->status }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-2 px-3 border-top d-flex align-items-center justify-content-between text-dark fw-medium"
                        style="font-size: 11px;">
                        <span class="d-flex align-items-center gap-1">
                            <i data-lucide="box" class="icon-xs text-secondary"></i> Available:
                            {{ $product->stock->current_stock ?? 0 }} {{ $product->unit_type }}
                        </span>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0 shadow-none"
                                style="font-size: 11px;"><i data-lucide="trash-2" class="icon-xs"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL: EDIT PRODUCT DETAILS -->
            <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-dark text-white py-3">
                            <div>
                                <h5 class="modal-title h6 fw-bold">EDIT WHOLESALE PRODUCT</h5>
                                <small class="text-white small">Update product pricing, stock levels and details.</small>
                            </div>
                            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4" style="max-height: calc(100vh - 180px); overflow-y: auto;">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Product Name *</label>
                                        <input type="text" name="name" class="form-control form-control-sm"
                                            value="{{ $product->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Category *</label>
                                        <select name="category_id" class="form-select form-select-sm" required>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Brand *</label>
                                        <select name="brand_id" class="form-select form-select-sm" required>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">SKU / Product Code *</label>
                                        <input type="text" name="sku" class="form-control form-control-sm"
                                            value="{{ $product->sku }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Barcode (POS Scanner)</label>
                                        <input type="text" name="barcode" class="form-control form-control-sm"
                                            value="{{ $product->barcode }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Unit Type *</label>
                                        <select name="unit_type" class="form-select form-select-sm" required>
                                            <option value="Carton"
                                                {{ $product->unit_type == 'Carton' ? 'selected' : '' }}>Carton</option>
                                            <option value="Bag" {{ $product->unit_type == 'Bag' ? 'selected' : '' }}>
                                                Bag</option>
                                            <option value="Piece" {{ $product->unit_type == 'Piece' ? 'selected' : '' }}>
                                                Piece</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Status *</label>
                                        <select name="status" class="form-select form-select-sm" required>
                                            <option value="ACTIVE" {{ $product->status == 'ACTIVE' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="INACTIVE"
                                                {{ $product->status == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Purchase Price (৳) *</label>
                                        <input type="number" step="0.01" name="purchase_price"
                                            class="form-control form-control-sm" value="{{ $product->purchase_price }}"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Wholesale Price (৳) *</label>
                                        <input type="number" step="0.01" name="wholesale_price"
                                            class="form-control form-control-sm" value="{{ $product->wholesale_price }}"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">MRP (৳) *</label>
                                        <input type="number" step="0.01" name="mrp"
                                            class="form-control form-control-sm" value="{{ $product->mrp }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Minimum Stock Alert *</label>
                                        <input type="number" name="min_stock" class="form-control form-control-sm"
                                            value="{{ $product->min_stock }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Update Product Image</label>
                                        <div class="border border-dashed rounded p-3 text-center bg-light">
                                            <!-- Current/Preview Image -->
                                            <div class="mb-2">
                                                <img id="previewImage{{ $product->id }}"
                                                    src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=500&auto=format&fit=crop&q=60' }}"
                                                    alt="Product Preview" class="rounded border"
                                                    style="width: 70px; height: 70px; object-fit: cover;">
                                            </div>
                                            <div class="fw-medium small text-dark">Choose a new image to replace</div>
                                            <small class="text-muted d-block mb-2" style="font-size: 11px;">PNG, JPG, WEBP
                                                up to 2MB</small>

                                            <!-- File Input with onchange preview function -->
                                            <input type="file" name="image"
                                                class="form-control form-control-sm mx-auto" style="max-width: 280px;"
                                                onchange="previewFile(event, '{{ $product->id }}')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light py-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-emerald btn-sm px-3 text-white bg-emerald"
                                    style="background-color: #059669 !important;">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <i data-lucide="package" class="icon-lg text-muted mb-2 opacity-50"
                        style="width: 48px; height: 48px;"></i>
                    <p class="small mb-1">No products found in the catalog.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif

    <!-- ==========================================
                    MODAL 1: ADD NEW WHOLESALE PRODUCT
                    ========================================== -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <div>
                        <h5 class="modal-title h6 fw-bold" id="addProductModalLabel">ADD NEW WHOLESALE PRODUCT</h5>
                        <small class="text-white small">Fill in all required product fields. Opening stock initializes
                            inventory immediately.</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4" style="max-height: calc(100vh - 180px); overflow-y: auto;">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Product Name *</label>
                                <input type="text" name="name" class="form-control form-control-sm"
                                    placeholder="e.g. Radhuni Pure Mustard Oil (500ml)" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Category *</label>
                                <select name="category_id" class="form-select form-select-sm" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Brand *</label>
                                <select name="brand_id" class="form-select form-select-sm" required>
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">SKU / Product Code *</label>
                                <input type="text" name="sku" class="form-control form-control-sm"
                                    placeholder="e.g. OIL-RAD-500ML" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Barcode (POS Scanner)</label>
                                <input type="text" name="barcode" class="form-control form-control-sm"
                                    placeholder="Scan or type barcode">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Unit Type *</label>
                                <select name="unit_type" class="form-select form-select-sm" required>
                                    <option value="Carton">Carton</option>
                                    <option value="Bag">Bag</option>
                                    <option value="Piece" selected>Piece</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Status *</label>
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="ACTIVE" selected>Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Purchase Price (৳) *</label>
                                <input type="number" step="0.01" name="purchase_price"
                                    class="form-control form-control-sm" placeholder="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Wholesale Price (৳) *</label>
                                <input type="number" step="0.01" name="wholesale_price"
                                    class="form-control form-control-sm" placeholder="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">MRP (৳) *</label>
                                <input type="number" step="0.01" name="mrp" class="form-control form-control-sm"
                                    placeholder="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Opening Stock *</label>
                                <input type="number" name="opening_stock" class="form-control form-control-sm"
                                    placeholder="0" value="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Minimum Stock Alert *</label>
                                <input type="number" name="min_stock" class="form-control form-control-sm"
                                    placeholder="10" value="10" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Upload Product Image</label>
                                <input type="file" name="image" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-emerald btn-sm px-3 text-white bg-emerald"
                            style="background-color: #059669 !important;">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    <script>
        function previewFile(event, productId) {
            const input = event.target;
            const preview = document.getElementById('previewImage' + productId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
