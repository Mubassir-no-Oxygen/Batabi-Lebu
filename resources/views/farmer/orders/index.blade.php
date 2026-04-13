@extends('layouts.app')

@section('title', 'Incoming Orders - Farmer')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Incoming Order Requests</h1>
    <p style="color: var(--text-muted);">Manage and respond to bulk purchase offers from verified buyers.</p>
</div>

<div class="glass-card">
    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Crop Details</th>
                        <th>Buyer Request</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="font-weight: 600; color: var(--text-muted);">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}<br><small>{{ $order->created_at->format('M d, Y') }}</small></td>
                            <td>
                                <div style="font-weight: 600;">{{ $order->crop->crop_name }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">Base Price: ৳{{ $order->crop->price_per_unit }}</div>
                            </td>
                            <td>
                                <div><i class='bx bx-user'></i> {{ $order->buyer->company_name ?? $order->buyer->user->name }}</div>
                                <div style="font-weight: bold; color: var(--primary);">Req: {{ $order->requested_quantity }} {{ $order->crop->unit }}</div>
                                @if($order->offered_price)
                                    <div style="font-size: 0.85rem; color: var(--accent); font-weight: 600;">Offer: ৳{{ $order->offered_price }}/{{ $order->crop->unit }}</div>
                                @endif
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge badge-warning">Action Required</span>
                                @elseif($order->status == 'accepted')
                                    <span class="badge badge-success">Deal Locked</span>
                                @elseif($order->status == 'rejected')
                                    <span class="badge badge-danger">Rejected Option</span>
                                @endif
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                    <div style="display: flex; gap: 10px;">
                                        <form action="{{ route('farmer.orders.accept', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;"><i class='bx bx-check'></i> Accept</button>
                                        </form>
                                        <form action="{{ route('farmer.orders.reject', $order) }}" method="POST" onsubmit="return confirm('Reject this offer?');">
                                            @csrf
                                            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; border-color: #ef4444; color: #ef4444;"><i class='bx bx-x'></i> Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.9rem;">No actions required</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1.5rem;">
            {{ $orders->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <h3>No order requests found.</h3>
        </div>
    @endif
</div>
@endsection
