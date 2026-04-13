@extends('layouts.app')

@section('title', 'Farmer Dashboard - Batabi Lebu')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Welcome back, {{ Auth::user()->name }} 👋</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem;">Here is what's happening at {{ Auth::user()->farmer->farm_name }} today.</p>
    </div>
    <a href="{{ route('farmer.crops.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Add New Crop Listing
    </a>
</div>

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon"><i class='bx bx-layer'></i></div>
        <div class="metric-info">
            <h4>Total Crops Listed</h4>
            <h2>{{ $stats['total_crops'] }}</h2>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--accent);"><i class='bx bx-broadcast'></i></div>
        <div class="metric-info">
            <h4>Active Market Listings</h4>
            <h2>{{ $stats['active_crops'] }}</h2>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;"><i class='bx bx-time-five'></i></div>
        <div class="metric-info">
            <h4>Pending Orders</h4>
            <h2>{{ $stats['pending_orders'] }}</h2>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(16, 185, 129, 0.1);"><i class='bx bx-check-circle'></i></div>
        <div class="metric-info">
            <h4>Accepted Deals</h4>
            <h2>{{ $stats['accepted_orders'] }}</h2>
        </div>
    </div>
</div>

<div class="glass-card" style="margin-bottom: 3rem;">
    <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-bell' style="color: var(--primary);"></i> Recent Order Requests
    </h2>

    @if($recentOrders->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Crop</th>
                        <th>Buyer</th>
                        <th>Requested Qty</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td style="font-weight: 600; color: var(--text-muted);">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div style="font-weight: 600;">{{ $order->crop->crop_name }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">Price: ৳{{ $order->crop->price_per_unit }}/{{ $order->crop->unit }}</div>
                            </td>
                            <td>{{ $order->buyer->company_name ?? $order->buyer->user->name }}</td>
                            <td style="font-weight: 600;">
                                {{ $order->requested_quantity }} {{ $order->crop->unit }}
                                @if($order->offered_price)
                                    <div style="font-size: 0.85rem; color: var(--accent);">Offer: ৳{{ $order->offered_price }}</div>
                                @endif
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($order->status == 'accepted')
                                    <span class="badge badge-success">Accepted</span>
                                @elseif($order->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge" style="background: var(--surface-hover);">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('farmer.orders.index') }}" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.9rem;">View Review</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="{{ route('farmer.orders.index') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">View All Orders &rarr;</a>
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <i class='bx bx-package' style="font-size: 4rem; color: var(--border); margin-bottom: 1rem;"></i>
            <h3>No recent orders</h3>
            <p>You haven't received any new order requests yet.</p>
        </div>
    @endif
</div>
@endsection
