@extends('layouts.app')
@section('title', 'Rate Your Experience')

@section('content')
<div class="container py-5" style="max-width: 680px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('buyer.orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item active">Leave a Review</li>
        </ol>
    </nav>

    {{-- Farmer info card --}}
    <div class="card mb-4">
        <div class="card-header-green">
            <i class="bi bi-star-fill me-2"></i> Rate Your Experience
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#1a5c2e,#4caf73);
                            display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5">{{ $order->crop->farmer->user->name }}</div>
                    <div class="text-muted small">
                        <i class="bi bi-geo-alt me-1"></i>{{ $order->crop->farmer->district }}
                        &nbsp;·&nbsp;{{ $order->crop->farmer->farm_name }}
                    </div>
                </div>
            </div>

            {{-- Order summary --}}
            <div class="bg-green-pale rounded-xl p-3 small mb-0">
                <div class="row g-2">
                    <div class="col-6">
                        <span class="text-muted">Crop:</span>
                        <strong>{{ $order->crop->crop_name }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted">Quantity:</span>
                        <strong>{{ $order->requested_quantity }} {{ $order->crop->unit }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted">Order #:</span>
                        <strong>#{{ $order->id }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted">Status:</span>
                        <span class="badge badge-accepted">Accepted</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Review Form --}}
    <div class="card">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4" style="color:var(--green-dark);">Your Feedback</h5>

            <form method="POST" action="{{ route('buyer.reviews.store', $order) }}" id="review-form">
                @csrf

                {{-- Star Rating --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Overall Rating <span class="text-danger">*</span></label>
                    <div class="star-rating d-flex gap-2" id="star-rating-group">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    class="star-btn"
                                    data-value="{{ $i }}"
                                    id="star-{{ $i }}"
                                    title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"
                                    aria-label="Rate {{ $i }} out of 5">
                                <i class="bi bi-star"></i>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="">
                    @error('rating')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="small text-muted mt-2" id="rating-label">Click a star to rate</div>
                </div>

                {{-- Written Feedback --}}
                <div class="mb-4">
                    <label for="comment" class="form-label fw-semibold">Your Feedback (Optional)</label>
                    <textarea
                        name="comment"
                        id="comment"
                        class="form-control @error('comment') is-invalid @enderror"
                        rows="4"
                        maxlength="1000"
                        placeholder="Share your experience — was the crop fresh? Was the farmer responsive? Would you order again?">{{ old('comment') }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="text-muted small mt-1">Max 1000 characters</div>
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4" id="submit-review-btn">
                        <i class="bi bi-send me-2"></i>Submit Review
                    </button>
                    <a href="{{ route('buyer.orders.index') }}" class="btn btn-outline-secondary px-4">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ─── Star Rating Widget ─── */
    .star-btn {
        background: none;
        border: none;
        font-size: 2.2rem;
        color: #d0d0d0;
        cursor: pointer;
        transition: color .15s, transform .15s;
        padding: 0;
        line-height: 1;
    }
    .star-btn:hover,
    .star-btn.active {
        color: #f5a623;
        transform: scale(1.15);
    }
    .star-btn .bi-star-fill { color: #f5a623; }
</style>
@endpush

@push('scripts')
<script>
    const stars   = document.querySelectorAll('.star-btn');
    const input   = document.getElementById('rating-input');
    const label   = document.getElementById('rating-label');
    const labels  = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

    function setStars(value) {
        stars.forEach((btn, idx) => {
            const icon = btn.querySelector('i');
            if (idx < value) {
                icon.className = 'bi bi-star-fill';
                btn.classList.add('active');
            } else {
                icon.className = 'bi bi-star';
                btn.classList.remove('active');
            }
        });
        input.value  = value;
        label.textContent = value ? `${value}/5 — ${labels[value]}` : 'Click a star to rate';
    }

    stars.forEach(btn => {
        btn.addEventListener('click',       () => setStars(parseInt(btn.dataset.value)));
        btn.addEventListener('mouseenter',  () => setStars(parseInt(btn.dataset.value)));
    });

    document.getElementById('star-rating-group').addEventListener('mouseleave', () => {
        setStars(parseInt(input.value) || 0);
    });

    // Prevent submit without rating
    document.getElementById('review-form').addEventListener('submit', function (e) {
        if (!input.value) {
            e.preventDefault();
            label.textContent = '⚠ Please select a star rating before submitting.';
            label.style.color = '#dc3545';
        }
    });
</script>
@endpush
