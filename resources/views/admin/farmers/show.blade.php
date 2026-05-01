@extends('layouts.app')
@section('title', 'Farmer Details')

@section('content')
<div class="container py-4">
    <div class="mb-3">
        <a href="{{ route('admin.farmers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Farmers
        </a>
    </div>

    <div class="row g-4">
        <!-- ─── Farmer Info ─── -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header-green">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i>Farmer Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div style="font-size: 4rem;">👨‍🌾</div>
                        <h4 class="fw-bold mt-2">{{ $farmer->user->name }}</h4>
                        <span class="badge badge-{{ $farmer->verification_status }} px-3 py-2 fs-6">
                            {{ ucfirst($farmer->verification_status) }}
                        </span>
                    </div>

                    <table class="table table-borderless table-sm">
                        <tr><th class="text-muted" width="40%">Email</th><td>{{ $farmer->user->email }}</td></tr>
                        <tr><th class="text-muted">Phone</th><td>{{ $farmer->user->phone }}</td></tr>
                        <tr><th class="text-muted">Farm Name</th><td class="fw-semibold">{{ $farmer->farm_name }}</td></tr>
                        <tr><th class="text-muted">District</th><td>{{ $farmer->district }}</td></tr>
                        <tr><th class="text-muted">Sub-District</th><td>{{ $farmer->sub_district ?? '—' }}</td></tr>
                        <tr><th class="text-muted">Land Size</th><td>{{ $farmer->land_size ?? '—' }} {{ $farmer->land_unit }}</td></tr>
                        <tr><th class="text-muted">Applied On</th><td>{{ $farmer->created_at->format('d M Y, h:i A') }}</td></tr>
                        @if($farmer->verified_at)
                        <tr><th class="text-muted">Verified On</th><td>{{ $farmer->verified_at->format('d M Y') }}</td></tr>
                        @endif
                    </table>

                    @if($farmer->rejection_reason)
                        <div class="alert alert-danger small mb-3">
                            <strong>Rejection Reason:</strong><br>{{ $farmer->rejection_reason }}
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="card-footer py-3 px-4">
                    @if($farmer->verification_status === 'pending')
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.farmers.approve', $farmer) }}" method="POST" class="flex-fill">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success w-100" id="approve-farmer-detail-{{ $farmer->id }}"
                                    onclick="return confirm('Approve this farmer?')">
                                    <i class="bi bi-check-circle me-1"></i> Approve
                                </button>
                            </form>
                        </div>
                        <!-- Reject with reason -->
                        <button class="btn btn-outline-danger w-100 mt-2" data-bs-toggle="collapse" data-bs-target="#reject-form">
                            <i class="bi bi-x-circle me-1"></i> Reject
                        </button>
                        <div class="collapse mt-2" id="reject-form">
                            <form action="{{ route('admin.farmers.reject', $farmer) }}" method="POST">
                                @csrf @method('PATCH')
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Reason for Rejection *</label>
                                    <textarea name="rejection_reason" id="rejection-reason"
                                        class="form-control form-control-sm @error('rejection_reason') is-invalid @enderror"
                                        rows="3" placeholder="Explain why the application is rejected..." required></textarea>
                                    @error('rejection_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn btn-danger btn-sm w-100"
                                    onclick="return confirm('Reject this farmer?')">Confirm Rejection</button>
                            </form>
                        </div>
                    @elseif($farmer->verification_status === 'approved')
                        <div class="alert alert-success mb-0 text-center">
                            <i class="bi bi-check-circle-fill me-2"></i>This farmer is approved and active.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ─── Crop Listings ─── -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header-green">
                    <span class="fw-bold"><i class="bi bi-flower1 me-2"></i>Crop Listings ({{ $farmer->crops->count() }})</span>
                </div>
                <div class="card-body p-0">
                    @if($farmer->crops->isEmpty())
                        <div class="text-center py-4 text-muted">No crops listed yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Crop</th>
                                        <th>Category</th>
                                        <th>Qty</th>
                                        <th>Price/Unit</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($farmer->crops as $crop)
                                    <tr>
                                        <td class="fw-semibold">{{ $crop->crop_name }}</td>
                                        <td>{{ ucfirst($crop->category) }}</td>
                                        <td>{{ $crop->quantity }} {{ $crop->unit }}</td>
                                        <td class="text-success">৳{{ number_format($crop->price_per_unit, 0) }}</td>
                                        <td>
                                            @php $sc = match($crop->status) { 'available' => 'badge-approved', 'sold_out' => 'badge-rejected', default => 'badge-pending' }; @endphp
                                            <span class="badge {{ $sc }}">{{ ucfirst(str_replace('_',' ',$crop->status)) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
