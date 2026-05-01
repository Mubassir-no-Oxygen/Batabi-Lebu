@extends('layouts.app')
@section('title', $farmer->user->name . ' — Reviews')

@section('content')
<div class="container py-4" style="max-width: 860px;">

    {{-- Farmer Header --}}
    <div class="card mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div style="width:72px;height:72px;border-radius:50%;
                            background:linear-gradient(135deg,#1a5c2e,#4caf73);
                            display:flex;align-items:center;justify-content:center;
                            color:#fff;font-size:2rem;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1" style="color:var(--green-dark);">
                        {{ $farmer->user->name }}
                    </h3>
                    <div class="text-muted small">
                        <i class="bi bi-geo-alt me-1"></i>{{ $farmer->district }}
                        &nbsp;·&nbsp;{{ $farmer->farm_name }}
                    </div>
                    @if($avgRating)
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <span style="font-size:1.2rem;color:#f5a623;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                                @endfor
                            </span>
                            <strong style="color:var(--green-dark);">{{ number_format($avgRating, 1) }}</strong>
                            <span class="text-muted small">({{ $reviews->total() }} {{ Str::plural('review', $reviews->total()) }})</span>
                        </div>
                    @else
                        <div class="mt-2 text-muted small">No reviews yet</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <h4 class="section-title mb-4">Buyer Reviews</h4>

    @if($reviews->isEmpty())
        <div class="card text-center py-5">
            <div style="font-size:3.5rem;opacity:.25;"><i class="bi bi-chat-square-text"></i></div>
            <h5 class="mt-3 text-muted">No reviews yet for this farmer.</h5>
        </div>
    @else
        <div class="row g-4">
            @foreach($reviews as $review)
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:44px;height:44px;border-radius:50%;
                                            background:linear-gradient(135deg,#1a7fd4,#0a4080);
                                            display:flex;align-items:center;justify-content:center;
                                            color:#fff;font-size:1.2rem;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $review->reviewer->name }}</div>
                                    <div class="small text-muted">{{ $review->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            <div style="font-size:1.3rem;color:#f5a623;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                                <span class="fs-6 fw-bold ms-1" style="color:var(--green-dark);">{{ $review->rating }}/5</span>
                            </div>
                        </div>
                        @if($review->comment)
                            <div class="mt-3 p-3 bg-green-pale rounded-xl" style="border-left:4px solid var(--green-mid);">
                                <p class="mb-0">"{{ $review->comment }}"</p>
                            </div>
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
