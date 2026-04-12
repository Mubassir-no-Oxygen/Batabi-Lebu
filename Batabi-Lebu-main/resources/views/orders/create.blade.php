@extends('layouts.main')

@section('title', 'Place Order')

@section('content')
<div class="container py-4" style="max-width: 680px;">
    <h2 class="fw-bold text-success mb-4">🛒 Place a New Order</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Crop</label>
                    <select name="crop_id" class="form-select" required>
                        <option value="">-- Choose a crop --</option>
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ old('crop_id') == $crop->id ? 'selected' : '' }}>
                                {{ $crop->crop_name }} — {{ $crop->farmer->user->name }} ({{ $crop->farmer->district }})
                                · ৳{{ number_format($crop->price_per_unit, 2) }}/{{ $crop->unit }}
                                · {{ number_format($crop->quantity, 1) }} {{ $crop->unit }} available
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Quantity</label>
                        <input type="number" name="requested_quantity" class="form-control"
                            value="{{ old('requested_quantity') }}" min="1" step="0.5" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Your Offered Price/unit (৳) <span class="text-muted fw-normal small">optional</span></label>
                        <input type="number" name="offered_price" class="form-control"
                            value="{{ old('offered_price') }}" min="0" step="0.01"
                            placeholder="Leave blank to use listed price">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Notes <span class="text-muted fw-normal">(optional)</span></label>
                    <textarea name="note" class="form-control" rows="3"
                        placeholder="Delivery requirements, quality preferences...">{{ old('note') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-fill">Send Order Request</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
