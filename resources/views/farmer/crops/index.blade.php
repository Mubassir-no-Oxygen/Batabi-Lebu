@extends('layouts.app')

@section('title', 'My Crops - Batabi Lebu')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">My Crop Listings</h1>
        <p style="color: var(--text-muted);">Manage your market inventory and availability.</p>
    </div>
    <a href="{{ route('farmer.crops.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> New Listing
    </a>
</div>

<div class="glass-card">
    @if($crops->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Crop & Category</th>
                        <th>Inventory</th>
                        <th>Price/Unit</th>
                        <th>Harvest Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($crops as $crop)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: var(--primary);">{{ $crop->crop_name }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: capitalize;">{{ $crop->category }}</div>
                            </td>
                            <td style="font-weight: 600;">{{ $crop->quantity }} {{ $crop->unit }}</td>
                            <td style="font-weight: 600; color: var(--text-main);">৳{{ $crop->price_per_unit }}</td>
                            <td>{{ $crop->harvest_date ? \Carbon\Carbon::parse($crop->harvest_date)->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($crop->status === 'available')
                                    <span class="badge badge-success">Available</span>
                                @elseif($crop->status === 'sold_out')
                                    <span class="badge badge-danger">Sold Out</span>
                                @else
                                    <span class="badge badge-warning">Upcoming</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 10px;">
                                    <a href="{{ route('farmer.crops.edit', $crop) }}" style="color: var(--text-muted); font-size: 1.2rem; transition: var(--transition);" title="Edit"><i class='bx bx-edit-alt'></i></a>
                                    
                                    <form action="{{ route('farmer.crops.destroy', $crop) }}" method="POST" onsubmit="return confirm('Delete this listing?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 1.2rem; cursor: pointer; transition: var(--transition);" title="Delete"><i class='bx bx-trash'></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1.5rem;">
            {{ $crops->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 4rem 1rem;">
            <i class='bx bx-basket' style="font-size: 4rem; color: var(--bg-color); margin-bottom: 1rem;"></i>
            <h3>No crops listed yet</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Add your first crop to start receiving bulk orders from buyers.</p>
            <a href="{{ route('farmer.crops.create') }}" class="btn btn-outline">List a Crop</a>
        </div>
    @endif
</div>
@endsection
