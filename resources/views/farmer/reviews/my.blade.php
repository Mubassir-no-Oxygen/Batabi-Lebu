@extends('layouts.app')
@section('title', 'My Reviews')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="section-title mb-1">My Reviews</h2>
            <p class="text-muted mb-0">Feedback received from buyers</p>
        </div>
        <a href="{{ route('farmer.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Dashboard
        </a>
    </div>

    {{-- Average Rating Card --}}
    <div class="card mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-4">
                <div class="col-md-4 text-center border-end">
                    <div style="font-size: 4rem; font-weight: 700; color: var(--green-dark); line-height: 1;">
                        {{ $avgRating ? number_format($avgRating, 1) : '—' }}
                    </div>
                    <div class="mt-1" style="font-size: 1.8rem; color: #f5a623; letter-spacing: 3px;">
                        @if($avgRating)
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($avgRating))
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        @else
                            <i class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i>
                            <i class="bi bi-star"></i><i class="bi bi-star"></i>
                        @endif
                    </div>
                    <div class="text-muted small mt-1">Average Rating</div>
                </div>
                <div class="col-md-8">
                    <div class="fw-semibold mb-3" style="color: var(--green-dark);">
                        {{ $reviews->total() }} {{ Str::plural('Review', $reviews->total()) }} Total
                    </div>
                    {{-- Star breakdown bars --}}
                    @php
                        $counts = \App\Models\Review::where('reviewee_id', auth()->id())
                            ->selectRaw('rating, COUNT(*) as cnt')
                            ->groupBy('rating')
                            ->pluck('cnt', 'rating');
                        $total = $reviews->total() ?: 1;
                    @endphp
                    @for ($star = 5; $star >= 1; $star--)
                        @php $cnt = $counts[$star] ?? 0; @endphp
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="small fw-semibold" style="min-width:20px;">{{ $star }}</span>
                            <i class="bi bi-star-fill small" style="color:#f5a623;"></i>
                            <div class="progress flex-grow-1" style="height:10px;border-radius:5px;">
                                <div class="progress-bar"
                                     style="width:{{ ($cnt/$total)*100 }}%;background:var(--green-mid);border-radius:5px;">
                                </div>
                            </div>
                            <span class="small text-muted" style="min-width:24px;">{{ $cnt }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- Review List --}}
    @if($reviews->isEmpty())
        <div class="card text-center py-5">
            <div style="font-size: 3.5rem; opacity:.25;">
                <i class="bi bi-chat-square-text"></i>
            </div>
            <h5 class="mt-3 text-muted">No reviews yet</h5>
            <p class="text-muted small">Reviews will appear here after buyers rate your products.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($reviews as $review)
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            {{-- Buyer info --}}
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:44px;height:44px;border-radius:50%;
                                            background:linear-gradient(135deg,#1a7fd4,#0a4080);
                                            display:flex;align-items:center;justify-content:center;
                                            color:#fff;font-size:1.2rem;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $review->reviewer->name }}</div>
                                    <div class="small text-muted">
                                        Order #{{ $review->order_id }}
                                        &nbsp;·&nbsp;{{ $review->order->crop->crop_name }}
                                        &nbsp;·&nbsp;{{ $review->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                            {{-- Stars --}}
                            <div style="font-size:1.3rem;color:#f5a623;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                                <span class="fs-6 fw-bold ms-1" style="color:var(--green-dark);">
                                    {{ $review->rating }}/5
                                </span>
                            </div>
                        </div>

                        {{-- Comment --}}
                        @if($review->comment)
                            <div class="mt-3 p-3 bg-green-pale rounded-xl" style="border-left:4px solid var(--green-mid);">
                                <p class="mb-0" style="color:var(--text-dark);">
                                    "{{ $review->comment }}"
                                </p>
                            </div>
                        @else
                            <div class="mt-3 text-muted small fst-italic">No written comment left.</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
