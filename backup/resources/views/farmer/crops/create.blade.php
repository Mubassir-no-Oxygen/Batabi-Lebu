@extends('layouts.app')
@section('title', 'Add New Crop')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('farmer.crops.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <h2 class="section-title mb-0">Add New Crop Listing</h2>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('farmer.crops.store') }}" method="POST" enctype="multipart/form-data" id="create-crop-form">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Crop Name *</label>
                                <input type="text" name="crop_name" id="crop-name"
                                    class="form-control @error('crop_name') is-invalid @enderror"
                                    value="{{ old('crop_name') }}" placeholder="e.g. Red Tomato" required>
                                @error('crop_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category *</label>
                                <select name="category" id="crop-category"
                                    class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach(['vegetable','fruit','grain','spice','other'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                            {{ ucfirst($cat) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Quantity *</label>
                                <input type="number" step="0.1" name="quantity" id="crop-quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity') }}" placeholder="e.g. 500" required>
                                @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Unit *</label>
                                <select name="unit" id="crop-unit"
                                    class="form-select @error('unit') is-invalid @enderror" required>
                                    @foreach(['kg','ton','quintal','maund'] as $u)
                                        <option value="{{ $u }}" {{ old('unit') == $u ? 'selected' : '' }}>
                                            {{ strtoupper($u) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Price per Unit (৳) *</label>
                                <input type="number" step="0.01" name="price_per_unit" id="crop-price"
                                    class="form-control @error('price_per_unit') is-invalid @enderror"
                                    value="{{ old('price_per_unit') }}" placeholder="e.g. 40" required>
                                @error('price_per_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Harvest Date</label>
                                <input type="date" name="harvest_date" id="crop-harvest-date"
                                    class="form-control @error('harvest_date') is-invalid @enderror"
                                    value="{{ old('harvest_date') }}">
                                @error('harvest_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Available From</label>
                                <input type="date" name="available_from" id="crop-available-from"
                                    class="form-control @error('available_from') is-invalid @enderror"
                                    value="{{ old('available_from') }}">
                                @error('available_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Available Until</label>
                                <input type="date" name="available_until" id="crop-available-until"
                                    class="form-control @error('available_until') is-invalid @enderror"
                                    value="{{ old('available_until') }}">
                                @error('available_until')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Listing Status *</label>
                                <select name="status" id="crop-status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="available" {{ old('status','available') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="upcoming"  {{ old('status') == 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
                                    <option value="sold_out"  {{ old('status') == 'sold_out'  ? 'selected' : '' }}>Sold Out</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Crop Photo</label>
                                <input type="file" name="image" id="crop-image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/webp">
                                <div class="form-text">Optional. Max 2MB.</div>
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" id="crop-description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    rows="3" placeholder="Quality, grade, notes about the crop...">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success px-4" id="crop-save-btn">
                                <i class="bi bi-check-circle me-1"></i> Save Listing
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
