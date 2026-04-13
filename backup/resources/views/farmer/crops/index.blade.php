@extends('layouts.app')
@section('title', 'My Crop Listings')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">My Crop Listings</h2>
        <a href="{{ route('farmer.crops.create') }}" class="btn btn-success" id="create-crop-btn">
            <i class="bi bi-plus-circle me-1"></i> Add New Crop
        </a>
    </div>

    @if($crops->isEmpty())
        <div class="card text-center py-5">
            <div style="font-size: 4rem; opacity: .3;">🌾</div>
            <h5 class="mt-3 text-muted">No crops listed yet</h5>
            <a href="{{ route('farmer.crops.create') }}" class="btn btn-success mt-2">Add Your First Crop</a>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Crop Name</th>
                            <th>Category</th>
                            <th>Qty Available</th>
                            <th>Price/Unit</th>
                            <th>Harvest Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($crops as $crop)
                        <tr>
                            <td class="fw-semibold">
                                @if($crop->image)
                                    <img src="{{ Storage::url($crop->image) }}" width="40" height="40"
                                         class="rounded me-2" style="object-fit:cover;" alt="{{ $crop->crop_name }}">
                                @else
                                    <span class="me-2">🌿</span>
                                @endif
                                {{ $crop->crop_name }}
                            </td>
                            <td>{{ ucfirst($crop->category) }}</td>
                            <td>{{ $crop->quantity }} {{ $crop->unit }}</td>
                            <td class="fw-semibold text-success">৳{{ number_format($crop->price_per_unit, 0) }}</td>
                            <td class="small text-muted">
                                {{ $crop->harvest_date ? $crop->harvest_date->format('d M Y') : '—' }}
                            </td>
                            <td>
                                @php
                                    $statusClass = match($crop->status) {
                                        'available' => 'badge-approved',
                                        'sold_out'  => 'badge-rejected',
                                        'upcoming'  => 'badge-pending',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $crop->status)) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('farmer.crops.edit', $crop) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('farmer.crops.destroy', $crop) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this crop listing?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $crops->links() }}</div>
    @endif
</div>
@endsection
