@extends('layouts.main')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="container py-4">

    @foreach(['success','info','warning','danger'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type }} alert-dismissible fade show">
                {{ session($type) }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    <div class="row g-4">

        {{-- Order Summary --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white fw-bold">🌾 Order #{{ $order->id }}</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr><th>Crop</th><td>{{ $order->crop->crop_name }}</td></tr>
                        <tr><th>Category</th><td>{{ ucfirst($order->crop->category) }}</td></tr>
                        <tr><th>Farmer</th><td>{{ $order->crop->farmer->user->name }} ({{ $order->crop->farmer->district }})</td></tr>
                        <tr><th>Buyer</th><td>{{ $order->buyer->user->name }}</td></tr>
                        <tr><th>Quantity</th><td>{{ number_format($order->requested_quantity, 1) }} {{ $order->crop->unit }}</td></tr>
                        <tr><th>Listed Price</th><td>৳{{ number_format($order->crop->price_per_unit, 2) }}/{{ $order->crop->unit }}</td></tr>
                        @if($order->offered_price)
                        <tr><th>Offered Price</th><td>৳{{ number_format($order->offered_price, 2) }}</td></tr>
                        @endif
                        @if($order->final_price)
                        <tr><th>Final Price</th><td class="text-success fw-bold">৳{{ number_format($order->final_price, 2) }}</td></tr>
                        <tr><th>Total</th><td class="text-success fw-bold">৳{{ number_format($order->totalAmount(), 2) }}</td></tr>
                        @endif
                        @if($order->bulk_discount_percent)
                        <tr><th>Bulk Discount</th><td>{{ $order->bulk_discount_percent }}%</td></tr>
                        @endif
                        <tr>
                            <th>Status</th>
                            <td><span class="badge bg-{{ $order->statusBadgeColor() }}">{{ ucfirst($order->status) }}</span></td>
                        </tr>
                    </table>
                    @if($order->note)
                        <p class="text-muted small"><strong>Note:</strong> {{ $order->note }}</p>
                    @endif
                </div>
                <div class="card-footer bg-white border-0 d-flex flex-wrap gap-2 pb-3">
                    @php $user = auth()->user(); @endphp

                    @if($user->role === 'farmer' && $order->status === 'pending')
                        <form action="{{ route('orders.accept', $order) }}" method="POST">
                            @csrf
                            <button class="btn btn-success btn-sm" onclick="return confirm('Accept this order?')">✔ Accept Order</button>
                        </form>
                    @endif

                    @if(!in_array($order->status, ['accepted','completed','cancelled']))
                        <form action="{{ route('orders.cancel', $order) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Cancel this order?')">✖ Cancel</button>
                        </form>
                    @endif

                    @if($order->agreement)
                        <a href="{{ route('agreements.show', $order->agreement) }}" class="btn btn-outline-success btn-sm">📄 View Agreement</a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Negotiation Panel --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold bg-light">💬 Price Negotiation</div>
                <div class="card-body" style="max-height:360px; overflow-y:auto;">
                    @if($order->negotiations->isEmpty())
                        <p class="text-muted text-center py-3">No negotiations yet.</p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($order->negotiations->reverse() as $neg)
                                @php $isMe = $neg->sender_id === auth()->id(); @endphp
                                <div class="d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="p-3 rounded-3 shadow-sm" style="max-width:80%; background:{{ $isMe ? '#d1e7dd' : '#f8f9fa' }}">
                                        <div class="fw-semibold small text-muted mb-1">
                                            {{ $neg->sender->name }} · {{ $neg->created_at->diffForHumans() }}
                                        </div>
                                        <div class="fs-5 fw-bold text-success">৳{{ number_format($neg->proposed_price, 2) }}/{{ $order->crop->unit }}</div>
                                        @if($neg->message)<p class="mb-0 mt-1 small">{{ $neg->message }}</p>@endif
                                        <span class="badge mt-1 bg-{{ match($neg->status) { 'accepted'=>'success','rejected'=>'danger','countered'=>'secondary',default=>'warning' } }}">
                                            {{ ucfirst($neg->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @php
                    $latest = $order->latestNegotiation;
                    $canRespond = $latest && $latest->status === 'pending'
                        && $latest->receiver_id === auth()->id()
                        && !in_array($order->status, ['accepted','completed','cancelled']);
                @endphp

                @if($canRespond)
                    <div class="card-footer bg-light d-flex gap-2 flex-wrap">
                        <form action="{{ route('negotiations.accept', $order) }}" method="POST">
                            @csrf
                            <button class="btn btn-success btn-sm">✔ Accept ৳{{ number_format($latest->proposed_price, 2) }}</button>
                        </form>
                        <form action="{{ route('negotiations.reject', $order) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">✖ Reject</button>
                        </form>
                    </div>
                @endif

                @if(!in_array($order->status, ['accepted','completed','cancelled','rejected']))
                    <div class="card-footer bg-white border-top">
                        <form action="{{ route('negotiations.store', $order) }}" method="POST">
                            @csrf
                            <label class="form-label small fw-semibold">Make a Counter-Offer</label>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="proposed_price" class="form-control"
                                    placeholder="Price per {{ $order->crop->unit }}" min="0" step="0.01" required>
                                <span class="input-group-text">/{{ $order->crop->unit }}</span>
                            </div>
                            <input type="text" name="message" class="form-control form-control-sm mb-2" placeholder="Optional message...">
                            <button type="submit" class="btn btn-sm btn-success w-100">Send Counter-Offer</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
