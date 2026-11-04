@extends('layouts.app')

@section('title', 'Warehouse Dashboard')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Warehouse & Stock Management</h2>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card p-3 bg-warning text-dark shadow-sm">
                    <h5>Pending Packing</h5>
                    <h3>12 Orders</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-info text-white shadow-sm">
                    <h5>Low Stock Alerts</h5>
                    <h3>2 Items</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
