@extends('layouts.app')
@section('title', 'Edit Crop')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('farmer.crops.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <h2 class="section-title mb-0">Edit: {{ $crop->crop_name }}</h2>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('farmer.crops.update', $crop) }}" method="POST" enctype="multipart/form-data" id="edit-crop-form">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Crop Name *</label>
                                <input type="text" name="crop_name" id="edit-crop-name"
                                    class="form-control @error('crop_name') is-invalid @enderror"
                                    value="{{ old('crop_name', $crop->crop_name) }}" required>
                                @error('crop_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category *</label>
                                <select name="category" id="edit-crop-category"
                                    class="form-select @error('category') is-invalid @enderror" required>
                                    @foreach(['vegetable','fruit','grain','spice','other'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $crop->category) == $cat ? 'selected' : '' }}>
                                            {{ ucfirst($cat) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Quantity *</label>
                                <input type="number" step="0.1" name="quantity" id="edit-crop-quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity', $crop->quantity) }}" required>
                                @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Unit *</label>
                                <select name="unit" id="edit-crop-unit"
                                    class="form-select @error('unit') is-invalid @enderror" required>
                                    @foreach(['kg','ton','quintal','maund'] as $u)
                                        <option value="{{ $u }}" {{ old('unit', $crop->unit) == $u ? 'selected' : '' }}>
                                            {{ strtoupper($u) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Price per Unit (৳) *</label>
                                <input type="number" step="0.01" name="price_per_unit" id="edit-crop-price"
                                    class="form-control @error('price_per_unit') is-invalid @enderror"
                                    value="{{ old('price_per_unit', $crop->price_per_unit) }}" required>
                                @error('price_per_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Harvest Date</label>
                                <input type="date" name="harvest_date" id="edit-crop-harvest-date"
                                    class="form-control"
                                    value="{{ old('harvest_date', $crop->harvest_date?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Available From</label>
                                <input type="date" name="available_from" id="edit-crop-available-from"
                                    class="form-control"
                                    value="{{ old('available_from', $crop->available_from?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Available Until</label>
                                <input type="date" name="available_until" id="edit-crop-available-until"
                                    class="form-control"
                                    value="{{ old('available_until', $crop->available_until?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Listing Status *</label>
                                <select name="status" id="edit-crop-status" class="form-select" required>
                                    <option value="available" {{ old('status', $crop->status) == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="upcoming"  {{ old('status', $crop->status) == 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
                                    <option value="sold_out"  {{ old('status', $crop->status) == 'sold_out'  ? 'selected' : '' }}>Sold Out</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">New Crop Photo</label>
                                @if($crop->image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($crop->image) }}" height="60"
                                             class="rounded border" alt="Current photo">
                                        <span class="text-muted small ms-2">Current photo</span>
                                    </div>
                                @endif
                                <input type="file" name="image" id="edit-crop-image"
                                    class="form-control" accept="image/jpeg,image/png,image/webp">
                                <div class="form-text">Leave empty to keep current photo.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" id="edit-crop-description"
                                    class="form-control" rows="3">{{ old('description', $crop->description) }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success px-4" id="edit-crop-save-btn">
                                <i class="bi bi-check-circle me-1"></i> Update Listing
                            </button>
                            <a href="{{ route('farmer.crops.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
