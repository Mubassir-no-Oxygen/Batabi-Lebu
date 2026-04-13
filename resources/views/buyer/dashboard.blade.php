@extends('layouts.app')

@section('title', 'Buyer Dashboard - Batabi Lebu')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Welcome, {{ Auth::user()->buyer->company_name ?? Auth::user()->name }} 🛒</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem;">Your bulk purchasing overview.</p>
    </div>
    <a href="{{ route('buyer.crops.index') }}" class="btn btn-primary">
        <i class='bx bx-search'></i> Browse Market
    </a>
</div>

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;"><i class='bx bx-shopping-bag'></i></div>
        <div class="metric-info">
            <h4>Total Orders Placed</h4>
            <h2>{{ $stats['total'] }}</h2>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--accent);"><i class='bx bx-time-five'></i></div>
        <div class="metric-info">
            <h4>Awaiting Farmer Approval</h4>
            <h2>{{ $stats['pending'] }}</h2>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: rgba(16, 185, 129, 0.1);"><i class='bx bx-check-shield'></i></div>
        <div class="metric-info">
            <h4>Total Deals Locked</h4>
            <h2>{{ $stats['accepted'] }}</h2>
        </div>
    </div>
</div>

<div class="glass-card">
    <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-history' style="color: var(--primary);"></i> Recent Bulk Orders
    </h2>

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order Ref</th>
                        <th>Product</th>
                        <th>Farmer Details</th>
                        <th>Qty Ordered</th>
                        <th>Agreement Status</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="font-weight: 600; color: var(--text-muted);">#B-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div style="font-weight: 600; color: var(--primary);">{{ $order->crop->crop_name }}</div>
                            </td>
                            <td>{{ $order->crop->farmer->farm_name }}</td>
                            <td style="font-weight: 600;">{{ $order->requested_quantity }} {{ $order->crop->unit }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge badge-warning">Negotiating / Pending</span>
                                @elseif($order->status == 'accepted')
                                    <span class="badge badge-success">Deal Locked</span>
                                @elseif($order->status == 'rejected')
                                    <span class="badge badge-danger">Denied</span>
                                @else
                                    <span class="badge" style="background: var(--surface-hover);">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->final_price)
                                    <span style="font-weight: bold; color: var(--text-main);">৳{{ $order->final_price }}/{{ $order->crop->unit }}</span>
                                @else
                                    <span style="color: var(--text-muted);">TBD</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <i class='bx bx-folder-open' style="font-size: 4rem; color: var(--border); margin-bottom: 1rem;"></i>
            <h3>No orders yet</h3>
            <p>Start browsing the marketplace to find the best agricultural products.</p>
        </div>
    @endif
</div>
@endsection
