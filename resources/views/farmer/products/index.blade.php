@extends('layouts.app')

@section('title', 'Buy Farming Supplies - Batabi Lebu')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Farming Supplies Market 🌾</h1>
        <p style="color: var(--text-muted); font-size: 1.05rem;">Purchase high-quality seeds, fertilizers, and tools from verified suppliers.</p>
    </div>
</div>

<!-- Simple Filter -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1.5rem;">
    <form action="{{ route('farmer.products.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0; flex-grow: 1;">
            <label class="form-label" style="font-size: 0.9rem;">Search Subplies</label>
            <div style="position: relative;">
                <i class='bx bx-search' style="position: absolute; top: 12px; left: 12px; color: var(--text-muted);"></i>
                <input type="text" name="search" class="form-control" style="padding-left: 35px;" placeholder="Search for seeds, urea..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 0; width: 250px;">
            <label class="form-label" style="font-size: 0.9rem;">Category</label>
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                <option value="seeds" {{ request('category') == 'seeds' ? 'selected' : '' }}>Seeds & Seedlings</option>
                <option value="fertilizer" {{ request('category') == 'fertilizer' ? 'selected' : '' }}>Fertilizers & Pesticides</option>
                <option value="tools" {{ request('category') == 'tools' ? 'selected' : '' }}>Farming Machinery & Tools</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="height: 42px;">Filter</button>
    </form>
</div>

<!-- Product Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
    @forelse($products as $product)
        <div class="glass-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
            
            <div style="height: 180px; background: linear-gradient(135deg, rgba(16,185,129,0.1) 0%, rgba(245,158,11,0.1) 100%); position: relative; display: flex; align-items: center; justify-content: center;">
                @if($product->category == 'seeds')
                    <i class='bx bx-spa' style="font-size: 5rem; color: var(--primary-light);"></i>
                @elseif($product->category == 'fertilizer')
                    <i class='bx bxs-flask' style="font-size: 5rem; color: #3b82f6;"></i>
                @else
                    <i class='bx bxs-wrench' style="font-size: 5rem; color: var(--text-muted);"></i>
                @endif
                <div style="position: absolute; top: 10px; right: 10px;">
                    <span class="badge badge-success" style="background: var(--glass-bg); backdrop-filter: blur(4px);">{{ ucfirst($product->category) }}</span>
                </div>
            </div>

            <div style="padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column;">
                <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 5px;">{{ $product->product_name }}</h3>
                <div style="display: flex; align-items: center; gap: 5px; color: var(--text-muted); font-size: 0.85rem; margin-bottom: 10px;">
                    <i class='bx bxs-badge-check' style="color: var(--primary);"></i> By: {{ $product->supplier->name ?? 'Verified Supplier' }}
                </div>
                
                <p style="font-size: 0.9rem; color: var(--text-muted); flex-grow: 1; margin-bottom: 15px;">
                    {{ $product->description }}
                </p>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                    <div>
                        <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-main);">৳{{ $product->price }}</span>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">/{{ $product->unit }}</span>
                    </div>
                </div>

                <button class="btn btn-primary" style="width: 100%; border-radius: 8px;">
                    <i class='bx bx-cart-add'></i> Add to Needs
                </button>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem;">
            <i class='bx bx-shopping-bag' style="font-size: 4rem; color: var(--border); margin-bottom: 1rem;"></i>
            <h3>No supplies found</h3>
            <p style="color: var(--text-muted);">We couldn't find any products matching your search.</p>
            <a href="{{ route('farmer.products.index') }}" class="btn btn-outline" style="margin-top: 1rem;">Clear Search</a>
        </div>
    @endforelse
</div>

<div style="margin-top: 2rem;">
    {{ $products->links() }}
</div>
@endsection
