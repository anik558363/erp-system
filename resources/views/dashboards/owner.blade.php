@extends('layouts.app')

@section('title', 'NOOR SUPER MART - Business Executive Dashboard')

@section('content')
    <!-- Top Banner Card -->
    <div class="card border-0 shadow-xs mb-4">
        <div class="card-body p-3 p-md-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="h5 fw-bold text-dark mb-1">BUSINESS EXECUTIVE DASHBOARD</h2>
                <p class="text-muted small mb-0">Real-time B2B overview of Noor Super Mart wholesale distribution depot.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-dark btn-sm px-3 py-2 fw-medium">Review Orders</button>
                <button class="btn btn-outline-secondary btn-sm px-3 py-2 fw-medium bg-white">Generate Reports</button>
            </div>
        </div>
    </div>

    <!-- Section Header: Today's Real-Time Operations -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="section-label">TODAY'S REAL-TIME OPERATIONS</span>
        <span class="section-label font-mono">DATE: 2026-11-02</span>
    </div>

    <!-- 7-Cards Responsive Grid -->
    <div class="ops-grid mb-4">
        <div class="card border-0 shadow-xs"><div class="card-body p-3"><div class="text-muted small mb-1" style="font-size: 11px;">Today Orders</div><div class="h4 fw-bold text-dark mb-1">1</div><div class="text-muted" style="font-size: 11px;">৳12</div></div></div>
        <div class="card border-0 shadow-xs"><div class="card-body p-3"><div class="text-muted small mb-1" style="font-size: 11px;">Today Sales</div><div class="h4 fw-bold text-emerald mb-1">৳12</div><div class="text-emerald" style="font-size: 11px;">B2B Invoices</div></div></div>
        <div class="card border-0 shadow-xs"><div class="card-body p-3"><div class="text-muted small mb-1" style="font-size: 11px;">Today Collection</div><div class="h4 fw-bold text-primary mb-1">৳0</div><div class="text-primary" style="font-size: 11px;">Route Cash & Pay</div></div></div>
        <div class="card border-0 shadow-xs"><div class="card-body p-3"><div class="text-muted small mb-1" style="font-size: 11px;">Today Return</div><div class="h4 fw-bold text-warning-emphasis mb-1">৳0</div><div class="text-warning-emphasis" style="font-size: 11px;">0 return items</div></div></div>
        <div class="card border-0 shadow-xs"><div class="card-body p-3"><div class="text-muted small mb-1" style="font-size: 11px;">Total Customer Due</div><div class="h4 fw-bold text-danger mb-1">৳0</div><div class="text-danger" style="font-size: 11px;">1 retail stores</div></div></div>
        <div class="card border-0 shadow-xs"><div class="card-body p-3"><div class="text-muted small mb-1" style="font-size: 11px;">Pending Approval</div><div class="h4 fw-bold text-warning mb-1">1</div><div class="text-warning-emphasis" style="font-size: 11px;">Office Attention</div></div></div>
        <div class="card border-0 shadow-xs"><div class="card-body p-3"><div class="text-muted small mb-1" style="font-size: 11px;">Pending Delivery</div><div class="h4 fw-bold text-info-emphasis mb-1">0</div><div class="text-info-emphasis" style="font-size: 11px;">On Fleet / Packing</div></div></div>
    </div>

    <!-- Middle Row: Business Performance & Depot Stock Status -->
    <div class="row g-3 mb-4">
        <!-- Left Column: Business Performance -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-xs h-100">
                <div class="card-body p-3 p-md-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                            <h3 class="h6 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <span class="text-emerald fw-bold">$</span> BUSINESS PERFORMANCE & GROSS PROFIT BREAKDOWN
                            </h3>
                            <span class="badge bg-light text-muted border font-mono fw-normal" style="font-size: 11px;">Formula: Net Sales - COGS</span>
                        </div>
                        <div class="perf-grid bg-light rounded p-3 mb-3 border">
                            <div><div class="text-muted small mb-1" style="font-size: 11px;">Net Sales</div><div class="h5 fw-bold text-dark mb-0">৳12</div></div>
                            <div><div class="text-muted small mb-1" style="font-size: 11px;">Purchases</div><div class="h5 fw-bold text-dark mb-0">৳0</div></div>
                            <div><div class="text-muted small mb-1" style="font-size: 11px;">Collections</div><div class="h5 fw-bold text-primary mb-0">৳0</div></div>
                            <div><div class="text-muted small mb-1" style="font-size: 11px;">Total Expenses</div><div class="h5 fw-bold text-purple mb-0">৳0</div></div>
                            <div><div class="text-muted small mb-1" style="font-size: 11px;">Gross Profit</div><div class="h5 fw-bold text-emerald mb-0">৳12 <small class="fw-normal" style="font-size: 11px;">(100.0%)</small></div></div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-2 border-top">
                        <small class="text-muted" style="font-size: 11px;">Realized profit automatically accounts for product wholesale margin and delivery returns.</small>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-purple text-decoration-none fw-semibold small" style="font-size: 12px;"><i data-lucide="file-text" class="icon-xs me-1"></i>খরচের হিসাব ↗</a>
                            <a href="#" class="text-emerald text-decoration-none fw-semibold small" style="font-size: 12px;">Detailed Reports ↗</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Depot Stock Status -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-xs h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i data-lucide="box" class="icon-sm text-purple"></i> DEPOT STOCK STATUS
                        </h3>
                        <a href="#" class="text-emerald text-decoration-none small fw-semibold" style="font-size: 12px;">View All</a>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6"><div class="p-2 border rounded bg-light-subtle"><div class="text-muted" style="font-size: 11px;">Available Stock</div><div class="h5 fw-bold text-dark mb-0 mt-1">6 <small class="text-muted fw-normal" style="font-size: 11px;">units</small></div></div></div>
                        <div class="col-6"><div class="p-2 border rounded bg-light-subtle"><div class="text-muted" style="font-size: 11px;">Reserved Stock</div><div class="h5 fw-bold text-purple mb-0 mt-1">0 <small class="text-muted fw-normal" style="font-size: 11px;">units</small></div></div></div>
                        <div class="col-6"><div class="p-2 border rounded bg-light-subtle"><div class="text-muted" style="font-size: 11px;">Damaged Stock</div><div class="h5 fw-bold text-danger mb-0 mt-1">0 <small class="text-muted fw-normal" style="font-size: 11px;">units</small></div></div></div>
                        <div class="col-6"><div class="p-2 border rounded bg-light-subtle"><div class="text-muted" style="font-size: 11px;">Low Stock Alerts</div><div class="h5 fw-bold text-warning-emphasis mb-0 mt-1">2 <small class="text-muted fw-normal" style="font-size: 11px;">items</small></div></div></div>
                    </div>
                    <div class="alert alert-warning border-warning border-opacity-50 py-2 px-3 mb-0 d-flex align-items-center gap-2" style="font-size: 12px;">
                        <i data-lucide="alert-circle" class="icon-sm text-warning-emphasis flex-shrink-0"></i>
                        <span class="text-warning-emphasis fw-medium">Low stock alert: fdsf, ted</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Table: Salesman Performance -->
    <div class="card border-0 shadow-xs">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <h3 class="h6 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i data-lucide="users" class="icon-sm text-primary"></i> SALESMAN PERFORMANCE & DUE RECOVERY SUMMARY
                </h3>
                <a href="#" class="text-emerald text-decoration-none small fw-semibold" style="font-size: 12px;">Salesman Profiles & Ledgers</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="table-light">
                        <tr>
                            <th>Salesman Name</th>
                            <th>Assigned Territory</th>
                            <th class="text-center">Retailers</th>
                            <th class="text-center">Orders</th>
                            <th class="text-end">Gross Sales</th>
                            <th class="text-end">Net Sales</th>
                            <th class="text-end">Collections</th>
                            <th class="text-end">Current Customer Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-semibold text-dark">Rafiqul Islam</td>
                            <td class="text-muted">Dhanmondi & Mohammadpur</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-end fw-medium">৳0</td>
                            <td class="text-end text-emerald fw-semibold">৳0</td>
                            <td class="text-end text-primary fw-semibold">৳0</td>
                            <td class="text-end text-danger fw-semibold">৳0</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-dark">Tariqul Alam</td>
                            <td class="text-muted">Mirpur & Uttara</td>
                            <td class="text-center">1</td>
                            <td class="text-center">1</td>
                            <td class="text-end fw-medium">৳12</td>
                            <td class="text-end text-emerald fw-semibold">৳12</td>
                            <td class="text-end text-primary fw-semibold">৳0</td>
                            <td class="text-end text-danger fw-semibold">৳0</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-dark">Md. Mizanur Rahman (মিজান)</td>
                            <td class="text-muted">Gulshan & Badda Route</td>
                            <td class="text-center">0</td>
                            <td class="text-center">0</td>
                            <td class="text-end fw-medium">৳0</td>
                            <td class="text-end text-emerald fw-semibold">৳0</td>
                            <td class="text-end text-primary fw-semibold">৳0</td>
                            <td class="text-end text-danger fw-semibold">৳0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
