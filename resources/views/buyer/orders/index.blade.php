@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="container py-4">
    <h2 class="section-title mb-4">My Order History</h2>

    @if($orders->isEmpty())
        <div class="card text-center py-5">
            <div style="font-size: 4rem; opacity: .3;">
                <i class="bi bi-bag"></i>
            </div>
            <h5 class="mt-3 text-muted">No orders placed yet</h5>
            <a href="{{ route('buyer.crops.index') }}" class="btn btn-success mt-2">Browse Crops</a>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Crop</th>
                            <th>Farmer</th>
                            <th>Qty Requested</th>
                            <th>Offered Price</th>
                            <th>Final Price</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="text-muted small">{{ $order->id }}</td>
                            <td class="fw-semibold">{{ $order->crop->crop_name }}</td>
                            <td>
                                <div>{{ $order->crop->farmer->user->name }}</div>
                                <div class="small text-muted">{{ $order->crop->farmer->district }}</div>
                            </td>
                            <td>{{ $order->requested_quantity }} {{ $order->crop->unit }}</td>
                            <td>{{ $order->offered_price ? '৳'.number_format($order->offered_price, 0) : '—' }}</td>
                            <td class="fw-semibold {{ $order->final_price ? 'text-success' : '' }}">
                                {{ $order->final_price ? '৳'.number_format($order->final_price, 0) : '—' }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="small text-muted">{{ $order->created_at->format('d M Y') }}</td>

                            {{-- Feature 5: Review column --}}
                            <td>
                                @if($order->status === 'accepted')
                                    @if($order->review)
                                        {{-- Already reviewed --}}
                                        <div style="color:#f5a623;font-size:.95rem;" title="You reviewed this order">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $order->review->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    @else
                                        {{-- Not yet reviewed --}}
                                        <a href="{{ route('buyer.reviews.create', $order) }}"
                                           class="btn btn-sm btn-outline-warning"
                                           id="review-order-{{ $order->id }}-btn">
                                            <i class="bi bi-star me-1"></i>Rate
                                        </a>
                                    @endif
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
