@extends('layouts.app')

@section('title', 'List a New Crop - Batabi Lebu')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('farmer.crops.index') }}" style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-weight: 500;">
        <i class='bx bx-left-arrow-alt'></i> Back to Inventory
    </a>
    <h1 style="font-size: 2rem; margin-top: 1rem;">List a New Crop</h1>
</div>

<div class="glass-card" style="max-width: 800px; margin: 0 auto;">
    @if ($errors->any())
        <div class="alert alert-danger" style="background: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
            <ul style="margin-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('farmer.crops.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Crop Name</label>
                <input type="text" name="crop_name" class="form-control" value="{{ old('crop_name') }}" required placeholder="e.g. BRRI Dhan-28 Rice">
            </div>

            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-control" required>
                    <option value="" disabled selected>Select Category</option>
                    <option value="vegetable" {{ old('category') == 'vegetable' ? 'selected' : '' }}>Vegetable</option>
                    <option value="fruit" {{ old('category') == 'fruit' ? 'selected' : '' }}>Fruit</option>
                    <option value="grain" {{ old('category') == 'grain' ? 'selected' : '' }}>Grain</option>
                    <option value="spice" {{ old('category') == 'spice' ? 'selected' : '' }}>Spice</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Available Quantity</label>
                <input type="number" step="0.1" name="quantity" class="form-control" value="{{ old('quantity') }}" required placeholder="e.g. 500">
            </div>

            <div class="form-group">
                <label class="form-label">Measurement Unit</label>
                <select name="unit" class="form-control" required>
                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kg</option>
                    <option value="ton" {{ old('unit') == 'ton' ? 'selected' : '' }}>Ton</option>
                    <option value="quintal" {{ old('unit') == 'quintal' ? 'selected' : '' }}>Quintal</option>
                    <option value="maund" {{ old('unit') == 'maund' ? 'selected' : '' }}>Maund (মন)</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label">Standard Price per Unit (BDT)</label>
                <input type="number" step="0.01" name="price_per_unit" class="form-control" value="{{ old('price_per_unit') }}" required placeholder="৳">
                <small style="color: var(--text-muted);">Buyers can negotiate this price when placing bulk orders.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Expected Harvest Date</label>
                <input type="date" name="harvest_date" class="form-control" value="{{ old('harvest_date') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available for Sale</option>
                    <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming / Pre-order</option>
                    <option value="sold_out" {{ old('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                </select>
            </div>
            
            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label">Description & Quality Notes</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe the quality, farming method (e.g. organic), or any other details...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div style="text-align: right; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">
                Publish Listing
            </button>
        </div>
    </form>
</div>
@endsection
