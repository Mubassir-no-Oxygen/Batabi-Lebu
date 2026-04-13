@extends('layouts.app')

@section('title', 'My Orders - Buyer')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">My Purchase History</h1>
    <p style="color: var(--text-muted);">Track the status of your bulk purchase negotiations and agreements.</p>
</div>

<div class="glass-card">
    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Crop Name</th>
                        <th>Farmer</th>
                        <th>Qty Requested</th>
                        <th>My Offer</th>
                        <th>Status</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="color: var(--text-muted);">#B-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}<br><small>{{ $order->created_at->format('M d, Y') }}</small></td>
                            <td style="font-weight: 600; color: var(--primary);">
                                <a href="{{ route('buyer.crops.show', $order->crop) }}" style="color: inherit; text-decoration: none;">
                                    {{ $order->crop->crop_name }} <i class='bx bx-link-external' style="font-size: 0.8rem;"></i>
                                </a>
                            </td>
                            <td>{{ $order->crop->farmer->farm_name }}</td>
                            <td style="font-weight: 600;">{{ $order->requested_quantity }} {{ $order->crop->unit }}</td>
                            <td>
                                @if($order->offered_price)
                                    ৳{{ $order->offered_price }} / {{ $order->crop->unit }}
                                @else
                                    <span style="color: var(--text-muted);">No negotiation</span>
                                @endif
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($order->status == 'accepted')
                                    <span class="badge badge-success">Accepted</span>
                                @elseif($order->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td style="font-weight: bold;">
                                {{ $order->final_price ? '৳'.$order->final_price.' / '.$order->crop->unit : 'TBD' }}
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
            <h3>You haven't placed any orders yet.</h3>
            <a href="{{ route('buyer.crops.index') }}" class="btn btn-outline" style="margin-top: 10px;">Go to Market</a>
        </div>
    @endif
</div>
@endsection
