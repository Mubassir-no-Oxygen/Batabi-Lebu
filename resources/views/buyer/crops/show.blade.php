@extends('layouts.app')

@section('title', $crop->crop_name . ' - Batabi Lebu')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('buyer.crops.index') }}" style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-weight: 500;">
        <i class='bx bx-left-arrow-alt'></i> Back to Market
    </a>
</div>

<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; align-items: start;">
    
    <!-- Left: Product Information -->
    <div>
        <div class="glass-card" style="margin-bottom: 2rem;">
            <!-- Placeholder Image -->
            <div style="height: 350px; background: linear-gradient(135deg, rgba(16,185,129,0.1) 0%, rgba(245,158,11,0.1) 100%); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
                 <i class='bx bx-image-alt' style="font-size: 6rem; color: rgba(0,0,0,0.05);"></i>
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 1rem;">
                <span class="badge badge-success">{{ ucfirst($crop->category) }}</span>
                @if($crop->status == 'available')
                    <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">Ready for Dispatch</span>
                @endif
            </div>

            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem; line-height: 1.2;">{{ $crop->crop_name }}</h1>
            
            <div style="display: flex; gap: 20px; color: var(--text-muted); margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
                <span><i class='bx bx-calendar'></i> Harvested: {{ $crop->harvest_date ? \Carbon\Carbon::parse($crop->harvest_date)->format('M d, Y') : 'N/A' }}</span>
                <span><i class='bx bx-store-alt'></i> Farm: {{ $crop->farmer->farm_name }}</span>
            </div>

            <h3 style="margin-bottom: 1rem;">Product Description</h3>
            <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.8;">
                {{ $crop->description ?? 'No detailed description provided by the farmer for this lot.' }}
            </p>
        </div>
        
        <!-- Farmer Profile Snippet -->
        <div class="glass-card" style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--primary-light); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold;">
                {{ substr($crop->farmer->user->name, 0, 1) }}
            </div>
            <div>
                <h3 style="margin-bottom: 0.2rem;">{{ $crop->farmer->user->name }}</h3>
                <p style="color: var(--primary); font-weight: 500; font-size: 0.9rem;">✓ Verified Batabi Lebu Farmer</p>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.3rem;"><i class='bx bx-map'></i> {{ $crop->farmer->user->address ?? 'Bangladesh' }}</p>
            </div>
        </div>
    </div>

    <!-- Right: Order & Negotiation Panel -->
    <div style="position: sticky; top: 100px;">
        <div class="glass-card" style="border-top: 5px solid var(--primary);">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                <div>
                    <span style="display: block; color: var(--text-muted); font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">Standard Price</span>
                    <span style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); line-height: 1;">৳{{ $crop->price_per_unit }}</span>
                    <span style="color: var(--text-muted); font-size: 1rem;">/{{ $crop->unit }}</span>
                </div>
                <div style="text-align: right;">
                    <span style="display: block; color: var(--text-muted); font-size: 0.9rem;">Available Stock</span>
                    <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ $crop->quantity }} {{ $crop->unit }}</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" style="background: #fef2f2; color: #b91c1c; border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1rem;">
                    <ul style="margin-left: 1rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('buyer.orders.store', $crop) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" style="display: flex; justify-content: space-between;">
                        <span>Required Quantity</span>
                        <span style="color: var(--text-muted); font-weight: 400; font-size: 0.85rem;">Max: {{ $crop->quantity }}</span>
                    </label>
                    <div style="position: relative;">
                        <input type="number" step="0.1" name="requested_quantity" class="form-control" value="{{ old('requested_quantity') }}" required max="{{ $crop->quantity }}">
                        <span style="position: absolute; right: 15px; top: 12px; color: var(--text-muted); font-weight: 600;">{{ $crop->unit }}</span>
                    </div>
                </div>

                <div class="form-group" style="padding: 1.5rem; background: var(--surface-hover); border-radius: var(--radius-md); border: 1px dashed var(--border);">
                    <label class="form-label">
                        <i class='bx bx-message-square-edit' style="color: var(--accent);"></i> Negotiate Price (Optional BDT)
                    </label>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">Offer a different price per {{ $crop->unit }} if you are buying in large bulk quantities.</p>
                    <input type="number" step="0.01" name="offered_price" class="form-control" value="{{ old('offered_price') }}" placeholder="e.g. ৳{{ $crop->price_per_unit - ($crop->price_per_unit * 0.05) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Note for Farmer</label>
                    <textarea name="delivery_address" class="form-control" rows="2" required placeholder="Where do you need this delivered? Any specific loading instructions?">{{ old('delivery_address') }}</textarea>
                </div> <!-- Using delivery_address as the note payload per your schema requirements -->

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);">
                    Send Purchase Request
                </button>
            </form>
            
            <p style="text-align: center; font-size: 0.8rem; color: var(--text-muted); margin-top: 1rem;">
                <i class='bx bx-shield-quarter'></i> Your payment is held securely until delivery is confirmed.
            </p>
        </div>
    </div>
</div>
@endsection
