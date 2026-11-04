@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Area Management')

@section('content')
    <!-- Header Section with Breadcrumb & Action -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 11px;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Delivery Areas</li>
                </ol>
            </nav>
            <h1 class="h4 fw-bold text-dark mb-0">Delivery Areas / Territories</h1>
            <p class="text-muted small mb-0">Manage distribution areas, delivery routes, and sales territories (e.g., Mirpur, Uttara).</p>
        </div>
        <div>
            <button type="button" class="btn btn-emerald text-white fw-semibold px-3 py-2 shadow-xs d-inline-flex align-items-center gap-2" style="background-color: #059669 !important; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#addAreaModal">
                <i data-lucide="plus-circle" class="icon-sm"></i> Add New Area
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small shadow-xs border-0 mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46;">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="check-circle" class="icon-sm"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small shadow-xs border-0 mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content Card / Table Box -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-dark mb-0 fs-6">Area Directory</h5>
            <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 11px;">Total: {{ $areas->total() ?? count($areas) }} Areas</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light text-muted font-mono" style="font-size: 11px; letter-spacing: 0.5px;">
                        <tr>
                            <th class="py-3 px-4">#ID</th>
                            <th class="py-3">AREA / TERRITORY NAME</th>
                            <th class="py-3 text-center">STATUS</th>
                            <th class="py-3">CREATED / UPDATED AT</th>
                            <th class="py-3 text-end px-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($areas as $area)
                            <tr>
                                <td class="px-4 fw-mono text-muted small">#{{ $area->id }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $area->name }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-medium" style="font-size: 11px;">
                                        Active
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-dark fw-medium">
                                        <i data-lucide="calendar" class="icon-xs text-muted me-1"></i>
                                        {{ $area->created_at ? $area->created_at->format('d M Y, h:i A') : 'N/A' }}
                                    </div>
                                    @if($area->updated_at && $area->updated_at != $area->created_at)
                                        <div class="text-muted" style="font-size: 10px;">
                                            Updated: {{ $area->updated_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-light border text-primary p-1 px-2 shadow-none" data-bs-toggle="modal" data-bs-target="#editAreaModal{{ $area->id }}" title="Edit Area">
                                            <i data-lucide="edit-2" class="icon-xs"></i>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('areas.destroy', $area->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this area?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger p-1 px-2 shadow-none" title="Delete Area">
                                                <i data-lucide="trash-2" class="icon-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editAreaModal{{ $area->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-bottom px-4 py-3">
                                            <h5 class="modal-title fw-bold fs-6 text-dark">Edit Delivery Area</h5>
                                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('areas.update', $area->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-dark">Area Name *</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted border-end-0">
                                                            <i data-lucide="map-pin" class="icon-xs"></i>
                                                        </span>
                                                        <input type="text" name="name" value="{{ $area->name }}" class="form-control border-start-0 ps-0 shadow-none py-2" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top bg-light px-4 py-3">
                                                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-emerald btn-sm text-white px-3 fw-semibold shadow-xs" style="background-color: #059669 !important;">Update Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="py-3">
                                        <i data-lucide="map" class="icon-lg text-muted mb-2 opacity-50"></i>
                                        <p class="small mb-1">No delivery areas found.</p>
                                        <a href="#" class="text-emerald text-decoration-none small fw-semibold" data-bs-toggle="modal" data-bs-target="#addAreaModal">Click here to add your first area</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($areas->hasPages())
            <div class="card-footer bg-white py-3 px-4 border-top">
                {{ $areas->links() }}
            </div>
        @endif
    </div>

    <!-- Add Area Modal -->
    <div class="modal fade" id="addAreaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold fs-6 text-dark">Add New Delivery Area</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('areas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Area Name *</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted border-end-0">
                                    <i data-lucide="map-pin" class="icon-xs"></i>
                                </span>
                                <input type="text" name="name" class="form-control border-start-0 ps-0 shadow-none py-2" placeholder="e.g. Mirpur-02 / Mohammadpur" required autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-emerald btn-sm text-white px-3 fw-semibold shadow-xs" style="background-color: #059669 !important;">Save Area</button>
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
