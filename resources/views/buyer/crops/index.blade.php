@extends('layouts.app')

@section('title', 'Marketplace - Batabi Lebu')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Direct Farmer Market 🌾</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem;">Source fresh agricultural products directly from verified farmers across Bangladesh.</p>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1.5rem;">
    <form action="{{ route('buyer.crops.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
        
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" style="font-size: 0.9rem;">Product Search</label>
            <div style="position: relative;">
                <i class='bx bx-search' style="position: absolute; top: 12px; left: 12px; color: var(--text-muted);"></i>
                <input type="text" name="search" class="form-control" style="padding-left: 35px;" placeholder="Search crops..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" style="font-size: 0.9rem;">Category</label>
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                <option value="vegetable" {{ request('category') == 'vegetable' ? 'selected' : '' }}>Vegetable</option>
                <option value="fruit" {{ request('category') == 'fruit' ? 'selected' : '' }}>Fruit</option>
                <option value="grain" {{ request('category') == 'grain' ? 'selected' : '' }}>Grain</option>
                <option value="spice" {{ request('category') == 'spice' ? 'selected' : '' }}>Spice</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" style="font-size: 0.9rem;">Farmer District</label>
            <input type="text" name="district" class="form-control" placeholder="e.g. Rajshahi" value="{{ request('district') }}">
        </div>

        <button type="submit" class="btn btn-primary" style="height: 42px;">
            Filter Market
        </button>
    </form>
</div>

<!-- Market Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    @forelse($crops as $crop)
        <a href="{{ route('buyer.crops.show', $crop) }}" style="text-decoration: none; color: inherit;">
            <div class="glass-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%;">
                
                <!-- Mock Image Area -->
                <div style="height: 200px; background-color: #e2e8f0; position: relative;">
                    @if($crop->images && count($crop->images) > 0)
                        <!-- If they set up storage, show image here -->
                        <div style="width: 100%; height: 100%; background: url('/storage/{{ $crop->images[0] }}') center/cover;"></div>
                    @else
                        <!-- Placeholder Pattern -->
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(16,185,129,0.2) 0%, rgba(245,158,11,0.2) 100%); display: flex; align-items: center; justify-content: center;">
                            <i class='bx bx-image-alt' style="font-size: 4rem; color: rgba(0,0,0,0.1);"></i>
                        </div>
                    @endif
                    
                    <div style="position: absolute; top: 10px; right: 10px;">
                        <span class="badge badge-success" style="background: var(--glass-bg); backdrop-filter: blur(4px); box-shadow: var(--shadow-sm);">{{ ucfirst($crop->category) }}</span>
                    </div>
                </div>

                <!-- Product Details -->
                <div style="padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <h3 style="font-size: 1.25rem; font-weight: 700;">{{ $crop->crop_name }}</h3>
                        <span style="font-weight: 800; color: var(--primary); font-size: 1.2rem;">৳{{ $crop->price_per_unit }}<span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">/{{ $crop->unit }}</span></span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 5px; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px;">
                        <i class='bx bx-user'></i> {{ $crop->farmer->farm_name }} 
                        <i class='bx bx-map' style="margin-left: 10px;"></i> {{ $crop->farmer->user->address ?? 'District Location' }}
                    </div>

                    <p style="font-size: 0.9rem; color: var(--text-muted); flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $crop->description ?? 'Premium quality freshly harvested ' . $crop->crop_name }}
                    </p>

                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600; font-size: 0.9rem;">
                            <i class='bx bx-cube-alt'></i> {{ $crop->quantity }} {{ $crop->unit }} Stock
                        </span>
                        <span class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.85rem;">View Deal</span>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem;">
            <i class='bx bx-search-alt' style="font-size: 4rem; color: var(--border); margin-bottom: 1rem;"></i>
            <h3>No crops found</h3>
            <p style="color: var(--text-muted);">We couldn't find any listings matching your filters.</p>
            <a href="{{ route('buyer.crops.index') }}" class="btn btn-outline" style="margin-top: 1rem;">Clear Filters</a>
        </div>
    @endforelse
</div>

<div style="margin-top: 2rem;">
    {{ $crops->links() }}
</div>
@endsection
