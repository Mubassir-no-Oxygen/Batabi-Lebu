@extends('layouts.app')
@section('title', 'Farmer Dashboard')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="section-title mb-1">Welcome, {{ auth()->user()->name }}! 👨‍🌾</h2>
            <p class="text-muted mb-0">{{ auth()->user()->farmer->farm_name }} · {{ auth()->user()->farmer->district }}</p>
        </div>
        <a href="{{ route('farmer.crops.create') }}" class="btn btn-success px-4" id="add-crop-btn">
            <i class="bi bi-plus-circle me-1"></i> Add Crop
        </a>
    </div>

    <!-- ─── Stats Row ─── -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-green">
                <div class="stat-icon"><i class="bi bi-flower1"></i></div>
                <div class="stat-value">{{ $stats['total_crops'] }}</div>
                <div class="stat-label">Total Crops Listed</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-blue">
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-value">{{ $stats['active_crops'] }}</div>
                <div class="stat-label">Active Listings</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-orange">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-value">{{ $stats['pending_orders'] }}</div>
                <div class="stat-label">Pending Orders</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-purple">
                <div class="stat-icon"><i class="bi bi-bag-check"></i></div>
                <div class="stat-value">{{ $stats['accepted_orders'] }}</div>
                <div class="stat-label">Accepted Orders</div>
            </div>
        </div>
    </div>

    <!-- ─── Recent Orders ─── -->
    <div class="card">
        <div class="card-header-green d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-bag me-2"></i>Recent Order Requests</span>
            <a href="{{ route('farmer.orders.index') }}" class="btn btn-sm btn-light">View All</a>
        </div>
        <div class="card-body p-0">
            @if($recentOrders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: .3;"></i>
                    <p class="mt-2">No orders yet. Add crops to start receiving orders!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Crop</th>
                                <th>Buyer</th>
                                <th>Qty</th>
                                <th>Offered Price</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->crop->crop_name }}</td>
                                <td>{{ $order->buyer->user->name }}</td>
                                <td>{{ $order->requested_quantity }} {{ $order->crop->unit }}</td>
                                <td>
                                    @if($order->offered_price)
                                        ৳{{ number_format($order->offered_price, 0) }}
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td class="small text-muted">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
