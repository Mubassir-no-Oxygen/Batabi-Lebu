@extends('layouts.app')
@section('title', 'Incoming Orders')

@section('content')
<div class="container py-4">
    <h2 class="section-title mb-4">Incoming Order Requests</h2>

    @if($orders->isEmpty())
        <div class="card text-center py-5">
            <div style="font-size: 4rem; opacity: .3;">📦</div>
            <h5 class="mt-3 text-muted">No orders yet</h5>
            <p class="text-muted small">List crops to start receiving orders from buyers.</p>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Crop</th>
                            <th>Qty Requested</th>
                            <th>Offered Price</th>
                            <th>Listing Price</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $order->buyer->user->name }}</div>
                                <div class="small text-muted">{{ $order->buyer->user->phone }}</div>
                            </td>
                            <td class="fw-semibold">{{ $order->crop->crop_name }}</td>
                            <td>{{ $order->requested_quantity }} {{ $order->crop->unit }}</td>
                            <td>
                                @if($order->offered_price)
                                    <span class="fw-semibold text-primary">৳{{ number_format($order->offered_price, 0) }}</span>
                                @else
                                    <span class="text-muted small">Not offered</span>
                                @endif
                            </td>
                            <td class="text-success">৳{{ number_format($order->crop->price_per_unit, 0) }}/{{ $order->crop->unit }}</td>
                            <td class="small text-muted">{{ $order->note ?? '—' }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="small text-muted">{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                @if($order->status === 'pending')
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('farmer.orders.accept', $order) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success"
                                                id="accept-order-{{ $order->id }}"
                                                title="Accept Order"
                                                onclick="return confirm('Accept this order?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('farmer.orders.reject', $order) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                id="reject-order-{{ $order->id }}"
                                                title="Reject Order"
                                                onclick="return confirm('Reject this order?')">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
