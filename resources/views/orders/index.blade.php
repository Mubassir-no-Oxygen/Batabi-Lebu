@extends('layouts.main')

@section('title', 'My Orders')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success mb-0">🌾 My Orders</h2>
        @if(auth()->user()->role === 'buyer')
            <a href="{{ route('orders.create') }}" class="btn btn-success">+ Place New Order</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-5 text-muted">
            <h5>No orders yet.</h5>
            @if(auth()->user()->role === 'buyer')
                <a href="{{ route('orders.create') }}" class="btn btn-outline-success mt-2">Place your first order</a>
            @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Crop</th>
                        <th>{{ auth()->user()->role === 'buyer' ? 'Farmer' : 'Buyer' }}</th>
                        <th>Qty</th>
                        <th>Offered Price</th>
                        <th>Final Price</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td><strong>{{ $order->crop->crop_name }}</strong></td>
                        <td>
                            @if(auth()->user()->role === 'buyer')
                                {{ $order->crop->farmer->user->name }}
                            @else
                                {{ $order->buyer->user->name }}
                            @endif
                        </td>
                        <td>{{ number_format($order->requested_quantity, 1) }} {{ $order->crop->unit }}</td>
                        <td>{{ $order->offered_price ? '৳'.number_format($order->offered_price, 2) : '—' }}</td>
                        <td>{{ $order->final_price ? '৳'.number_format($order->final_price, 2) : '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $order->statusBadgeColor() }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-success">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    @endif
</div>
@endsection
