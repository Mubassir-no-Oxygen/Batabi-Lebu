@extends('layouts.main')

@section('title', 'Assign Delivery')

@section('content')
<div class="container py-4" style="max-width: 680px;">
    <h2 class="fw-bold text-success mb-4">🚚 Assign Delivery Partner</h2>

    <div class="alert alert-info mb-4">
        <strong>Order #{{ $order->id }}</strong> —
        {{ $order->crop->crop_name }} ·
        {{ number_format($order->requested_quantity, 1) }} {{ $order->crop->unit }} ·
        Buyer: {{ $order->buyer->user->name }}
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('deliveries.store', $order) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Delivery Partner <span class="text-muted fw-normal">(optional — assign later)</span></label>
                    <select name="delivery_partner_id" class="form-select">
                        <option value="">-- Assign Later --</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" {{ old('delivery_partner_id') == $partner->id ? 'selected' : '' }}>
                                {{ $partner->name }} ({{ $partner->phone ?? 'No phone' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pickup Address</label>
                    <textarea name="pickup_address" class="form-control" rows="2" required
                        placeholder="Farmer's farm address...">{{ old('pickup_address', $order->crop->farmer->district) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Delivery Address</label>
                    <textarea name="delivery_address" class="form-control" rows="2" required
                        placeholder="Buyer's delivery address...">{{ old('delivery_address') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Expected Delivery Date <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="datetime-local" name="expected_at" class="form-control"
                        value="{{ old('expected_at') }}">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-fill">Assign Delivery</button>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
