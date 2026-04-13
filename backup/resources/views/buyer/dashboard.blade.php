@extends('layouts.app')
@section('title', 'Buyer Dashboard')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="section-title mb-1">Welcome, {{ auth()->user()->name }}! 🛒</h2>
            <p class="text-muted mb-0">Your buying dashboard</p>
        </div>
        <a href="{{ route('buyer.crops.index') }}" class="btn btn-success px-4" id="browse-crops-btn">
            <i class="bi bi-search me-1"></i> Browse Crops
        </a>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-sm-4">
            <div class="stat-card stat-green">
                <div class="stat-icon"><i class="bi bi-bag"></i></div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card stat-orange">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card stat-blue">
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-value">{{ $stats['accepted'] }}</div>
                <div class="stat-label">Accepted</div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header-green d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-clock-history me-2"></i>Recent Orders</span>
            <a href="{{ route('buyer.orders.index') }}" class="btn btn-sm btn-light">View All</a>
        </div>
        <div class="card-body p-0">
            @if($orders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bag-x" style="font-size: 3rem; opacity: .3;"></i>
                    <p class="mt-2">No orders placed yet. <a href="{{ route('buyer.crops.index') }}">Browse crops</a> to get started!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Crop</th>
                                <th>Quantity</th>
                                <th>Offered Price</th>
                                <th>Final Price</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->crop->crop_name }}</td>
                                <td>{{ $order->requested_quantity }} {{ $order->crop->unit }}</td>
                                <td>{{ $order->offered_price ? '৳'.number_format($order->offered_price,0) : '—' }}</td>
                                <td>{{ $order->final_price ? '৳'.number_format($order->final_price,0) : '—' }}</td>
                                <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
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
