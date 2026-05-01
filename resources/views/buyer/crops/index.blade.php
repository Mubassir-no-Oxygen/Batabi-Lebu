@extends('layouts.app')
@section('title', 'Browse Crops')

@section('content')
<div class="container py-4">
    <h2 class="section-title mb-4">Browse Available Crops</h2>

    <!-- ─── Filter Bar ─── -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('buyer.crops.index') }}" id="crop-filter-form">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Search Crop</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" id="filter-search"
                                class="form-control" placeholder="e.g. Tomato"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Category</label>
                        <select name="category" id="filter-category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach(['vegetable','fruit','grain','spice','other'] as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">District</label>
                        <select name="district" id="filter-district" class="form-select">
                            <option value="">All Districts</option>
                            @foreach($districts as $d)
                                <option value="{{ $d }}" {{ request('district') == $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Min Price (৳)</label>
                        <input type="number" name="min_price" id="filter-min-price"
                            class="form-control" placeholder="e.g. 10" value="{{ request('min_price') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Max Price (৳)</label>
                        <input type="number" name="max_price" id="filter-max-price"
                            class="form-control" placeholder="e.g. 200" value="{{ request('max_price') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100" id="filter-submit-btn">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['search','category','district','min_price','max_price']))
                    <div class="mt-2">
                        <a href="{{ route('buyer.crops.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- ─── Results ─── -->
    @if($crops->isEmpty())
        <div class="card text-center py-5">
            <div style="font-size: 4rem; opacity: .3;">🔍</div>
            <h5 class="mt-3 text-muted">No crops found</h5>
            <p class="text-muted small">Try different filters or check back later.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($crops as $crop)
            <div class="col-md-6 col-lg-4">
                <div class="card crop-card h-100">
                    @if($crop->image)
                        <img src="{{ Storage::url($crop->image) }}" class="card-img-top" alt="{{ $crop->crop_name }}" style="height:200px;object-fit:cover;">
                    @else
                        <div class="crop-placeholder">
                            {{ ['🌽','🍅','🥬','🧅','🌾','🍆','🫑','🥔'][array_rand(['🌽','🍅','🥬','🧅','🌾','🍆','🫑','🥔'])] }}
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">{{ $crop->crop_name }}</h5>
                            <span class="badge bg-success-subtle text-success">{{ ucfirst($crop->category) }}</span>
                        </div>
                        <p class="small text-muted mb-1">
                            <i class="bi bi-geo-alt-fill text-success"></i> {{ $crop->farmer->district }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-person-fill"></i> {{ $crop->farmer->user->name }}
                        </p>
                        @if($crop->description)
                            <p class="small text-muted mb-2">{{ Str::limit($crop->description, 80) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="fs-5 fw-bold" style="color: var(--green-dark);">৳{{ number_format($crop->price_per_unit, 0) }}</span>
                                <span class="small text-muted">/{{ $crop->unit }}</span>
                            </div>
                            <span class="small text-muted">{{ $crop->quantity }} {{ $crop->unit }} avail.</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
                        <a href="{{ route('buyer.crops.show', $crop) }}" class="btn btn-success w-100" id="view-crop-{{ $crop->id }}">
                            <i class="bi bi-eye me-1"></i> View & Order
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $crops->links() }}</div>
    @endif
</div>
@endsection
