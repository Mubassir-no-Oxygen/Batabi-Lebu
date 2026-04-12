@extends('layouts.main')

@section('title', 'Deliveries')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-success mb-4">🚚 Deliveries</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @if($deliveries->isEmpty())
        <div class="text-center py-5 text-muted">
            <h5>No deliveries yet.</h5>
            <p>Deliveries appear here once an order is confirmed and assigned.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Tracking #</th>
                        <th>Crop</th>
                        <th>Buyer</th>
                        <th>Delivery Partner</th>
                        <th>Expected</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                    <tr>
                        <td><code>{{ $delivery->tracking_number }}</code></td>
                        <td>{{ $delivery->order->crop->crop_name }}</td>
                        <td>{{ $delivery->order->buyer->user->name }}</td>
                        <td>
                            {{ $delivery->deliveryPartner?->name ?? '—' }}
                            @if(!$delivery->deliveryPartner)
                                <span class="badge bg-warning text-dark">Unassigned</span>
                            @endif
                        </td>
                        <td>{{ $delivery->expected_at?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $delivery->statusBadgeColor() }}">
                                {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-success">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $deliveries->links() }}
    @endif
</div>
@endsection
