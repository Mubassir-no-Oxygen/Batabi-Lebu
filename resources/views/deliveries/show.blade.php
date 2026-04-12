@extends('layouts.main')

@section('title', 'Delivery ' . $delivery->tracking_number)

@section('content')
<div class="container py-4" style="max-width: 760px;">

    @foreach(['success','info','warning','danger'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type }} alert-dismissible fade show">
                {{ session($type) }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    <div class="card shadow border-0">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #2d6a4f, #40916c); color: white;">
            <div class="fw-bold fs-5">🚚 Delivery Tracking</div>
            <code class="text-white opacity-75">{{ $delivery->tracking_number }}</code>
        </div>

        <div class="card-body p-4">

            {{-- Status Timeline --}}
            <div class="mb-4">
                @php
                    $steps = ['pending', 'picked_up', 'in_transit', 'delivered'];
                    $currentIndex = array_search($delivery->status, $steps);
                    if($currentIndex === false) $currentIndex = -1;
                @endphp
                <div class="d-flex justify-content-between align-items-center position-relative mb-2">
                    <div style="position:absolute; top:50%; left:0; right:0; height:3px; background:#dee2e6; z-index:0;"></div>
                    @foreach($steps as $i => $step)
                        <div class="text-center position-relative" style="z-index:1; flex:1;">
                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                style="width:36px; height:36px; background:{{ $i <= $currentIndex ? '#2d6a4f' : '#dee2e6' }}; color:{{ $i <= $currentIndex ? 'white' : '#6c757d' }};">
                                {{ $i + 1 }}
                            </div>
                            <div class="small mt-1 {{ $i <= $currentIndex ? 'text-success fw-semibold' : 'text-muted' }}">
                                {{ ucfirst(str_replace('_', ' ', $step)) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Details --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 h-100">
                        <div class="text-muted small mb-1">ORDER DETAILS</div>
                        <div class="fw-bold">{{ $delivery->order->crop->crop_name }}</div>
                        <div class="small">{{ number_format($delivery->order->requested_quantity, 1) }} {{ $delivery->order->crop->unit }}</div>
                        <div class="small text-muted mt-1">Buyer: {{ $delivery->order->buyer->user->name }}</div>
                        <div class="small text-muted">Farmer: {{ $delivery->order->crop->farmer->user->name }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 h-100">
                        <div class="text-muted small mb-1">DELIVERY PARTNER</div>
                        @if($delivery->deliveryPartner)
                            <div class="fw-bold">{{ $delivery->deliveryPartner->name }}</div>
                            <div class="small text-muted">{{ $delivery->deliveryPartner->phone ?? 'No phone listed' }}</div>
                        @else
                            <span class="badge bg-warning text-dark">Not yet assigned</span>
                        @endif
                        @if($delivery->expected_at)
                            <div class="small text-muted mt-2">Expected: {{ $delivery->expected_at->format('d M Y, h:i A') }}</div>
                        @endif
                        @if($delivery->delivered_at)
                            <div class="small text-success mt-1">Delivered: {{ $delivery->delivered_at->format('d M Y, h:i A') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <table class="table table-sm table-bordered mb-4">
                <tr><th>Pickup From</th><td>{{ $delivery->pickup_address }}</td></tr>
                <tr><th>Deliver To</th><td>{{ $delivery->delivery_address }}</td></tr>
                <tr>
                    <th>Current Status</th>
                    <td><span class="badge bg-{{ $delivery->statusBadgeColor() }}">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span></td>
                </tr>
            </table>

            {{-- Update Status (delivery partner or admin) --}}
            @if(in_array(auth()->user()->role, ['delivery_partner', 'admin']) && $delivery->status !== 'delivered')
                <div class="card border-success">
                    <div class="card-header bg-success text-white fw-semibold">Update Delivery Status</div>
                    <div class="card-body">
                        <form action="{{ route('deliveries.status', $delivery) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach(['pending','picked_up','in_transit','delivered','returned'] as $statusOption)
                                    @if($statusOption !== $delivery->status)
                                        <button type="submit" name="status" value="{{ $statusOption }}"
                                            class="btn btn-sm {{ $statusOption === 'delivered' ? 'btn-success' : ($statusOption === 'returned' ? 'btn-danger' : 'btn-outline-primary') }}"
                                            onclick="return confirm('Mark as {{ ucfirst(str_replace(\'_\',\' \', $statusOption)) }}?')">
                                            {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>

        <div class="card-footer text-muted small text-center py-3">
            Created {{ $delivery->created_at->format('d M Y, h:i A') }} · Batabi Lebu Platform
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('orders.show', $delivery->order) }}" class="btn btn-outline-secondary btn-sm">← Back to Order</a>
        <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary btn-sm">All Deliveries</a>
    </div>
</div>
@endsection
