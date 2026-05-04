@extends('layouts.app')
@section('title', $crop->crop_name)

@section('content')
<div class="container py-4">
    <div class="mb-3">
        <a href="{{ route('buyer.crops.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Browse
        </a>
    </div>

    <div class="row g-4">
        <!-- ─── Crop Details ─── -->
        <div class="col-lg-7">
            <div class="card">
                @if($crop->image)
                    <img src="{{ Storage::url($crop->image) }}" class="card-img-top"
                         alt="{{ $crop->crop_name }}" style="height:340px;object-fit:cover;border-radius:14px 14px 0 0;">
                @else
                    <div class="crop-placeholder" style="height:200px;border-radius:14px 14px 0 0;font-size:6rem;">🌾</div>
                @endif
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $crop->crop_name }}</h2>
                            <span class="badge bg-success-subtle text-success px-3">{{ ucfirst($crop->category) }}</span>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-bold" style="color: var(--green-dark);">
                                ৳{{ number_format($crop->price_per_unit, 0) }}
                            </div>
                            <div class="text-muted small">per {{ $crop->unit }}</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-xl bg-green-pale">
                                <div class="small text-muted">Available Quantity</div>
                                <div class="fw-bold fs-5">{{ $crop->quantity }} {{ $crop->unit }}</div>
                            </div>
                        </div>
                        @if($crop->harvest_date)
                        <div class="col-sm-6">
                            <div class="p-3 rounded-xl bg-green-pale">
                                <div class="small text-muted">Harvest Date</div>
                                <div class="fw-bold">{{ $crop->harvest_date->format('d M Y') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($crop->description)
                        <h6 class="fw-semibold mb-1">Description</h6>
                        <p class="text-muted">{{ $crop->description }}</p>
                    @endif

                    <!-- Farmer Info -->
                    <div class="card mt-3" style="background: var(--green-pale); border: none;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="font-size: 2.5rem;">👨‍🌾</div>
                                <div>
                                    <div class="fw-bold">{{ $crop->farmer->user->name }}</div>
                                    <div class="small text-muted">{{ $crop->farmer->farm_name }}</div>
                                    <div class="small"><i class="bi bi-geo-alt-fill text-success"></i> {{ $crop->farmer->district }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── Order Form ─── -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header-green">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-bag-plus me-2"></i>Place Order Request</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('buyer.orders.store', $crop) }}" method="POST" id="order-form">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quantity Required *</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="requested_quantity" id="order-quantity"
                                    class="form-control @error('requested_quantity') is-invalid @enderror"
                                    placeholder="e.g. 100" min="0.1" max="{{ $crop->quantity }}" required>
                                <span class="input-group-text">{{ $crop->unit }}</span>
                                @error('requested_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-text">Max available: {{ $crop->quantity }} {{ $crop->unit }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Your Price Offer (৳/{{ $crop->unit }})
                                <span class="text-muted small fw-normal">— Optional</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" name="offered_price" id="order-offered-price"
                                    class="form-control @error('offered_price') is-invalid @enderror"
                                    placeholder="{{ $crop->price_per_unit }}">
                                @error('offered_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-text">
                                Farmer's asking price: ৳{{ number_format($crop->price_per_unit, 2) }}/{{ $crop->unit }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Note to Farmer</label>
                            <textarea name="note" id="order-note"
                                class="form-control @error('note') is-invalid @enderror"
                                rows="3" placeholder="Delivery requirements, preferred date, etc.">{{ old('note') }}</textarea>
                            @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Price estimate preview -->
                        <div class="alert alert-info mb-3" id="price-preview">
                            <div class="small text-muted mb-1">Estimated Total at Asking Price</div>
                            <div class="fw-bold fs-5" id="price-estimate">৳—</div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold" id="submit-order-btn">
                            <i class="bi bi-send me-2"></i>Submit Order Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Live price estimate
const qtyInput   = document.getElementById('order-quantity');
const priceInput = document.getElementById('order-offered-price');
const estimate   = document.getElementById('price-estimate');
const askingPrice = {{ $crop->price_per_unit }};

function updateEstimate() {
    const qty   = parseFloat(qtyInput.value) || 0;
    const price = parseFloat(priceInput.value) || askingPrice;
    const total = qty * price;
    estimate.textContent = total > 0 ? '৳' + total.toLocaleString('en-BD', {maximumFractionDigits: 0}) : '৳—';
}

qtyInput.addEventListener('input', updateEstimate);
priceInput.addEventListener('input', updateEstimate);
</script>
@endpush
