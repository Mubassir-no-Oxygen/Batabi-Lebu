@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card">
                <div class="card-header-green">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2"></i>Create Account</h4>
                    <p class="mb-0 small opacity-75">Join the Batabi Lebu community</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}" id="register-form">
                        @csrf

                        <!-- Role selection -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">I want to register as:</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role" id="role-farmer" value="farmer"
                                        {{ old('role', 'buyer') === 'farmer' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 py-3" for="role-farmer" id="label-farmer">
                                        <div style="font-size: 2rem;">👨‍🌾</div>
                                        <div class="fw-semibold">Farmer</div>
                                        <div class="small text-muted">I sell crops</div>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role" id="role-buyer" value="buyer"
                                        {{ old('role', 'buyer') === 'buyer' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 py-3" for="role-buyer" id="label-buyer">
                                        <div style="font-size: 2rem;">🛒</div>
                                        <div class="fw-semibold">Buyer</div>
                                        <div class="small text-muted">I buy crops</div>
                                    </label>
                                </div>
                            </div>
                            @error('role')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <!-- Common Fields -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="name" id="reg-name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Your full name" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" name="phone" id="reg-phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" placeholder="01700000000" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" id="reg-email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="you@example.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" id="reg-password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Min. 8 characters" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="reg-password-confirm"
                                    class="form-control" placeholder="Repeat password" required>
                            </div>
                        </div>

                        <!-- ─── Farmer-specific fields ─── -->
                        <div id="farmer-fields" class="{{ old('role', 'buyer') === 'farmer' ? '' : 'd-none' }}">
                            <hr>
                            <h6 class="fw-bold mb-3" style="color: var(--green-dark);">
                                <i class="bi bi-tree me-1"></i> Farm Details
                            </h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Farm Name</label>
                                <input type="text" name="farm_name" id="reg-farm-name"
                                    class="form-control @error('farm_name') is-invalid @enderror"
                                    value="{{ old('farm_name') }}" placeholder="e.g. Green Valley Farm">
                                @error('farm_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">District</label>
                                    <input type="text" name="district" id="reg-district"
                                        class="form-control @error('district') is-invalid @enderror"
                                        value="{{ old('district') }}" placeholder="e.g. Sylhet">
                                    @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Land Size (Bigha)</label>
                                    <input type="number" step="0.1" name="land_size" id="reg-land-size"
                                        class="form-control @error('land_size') is-invalid @enderror"
                                        value="{{ old('land_size') }}" placeholder="e.g. 5.5">
                                    @error('land_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- ─── Buyer-specific fields ─── -->
                        <div id="buyer-fields" class="{{ old('role', 'buyer') === 'buyer' ? '' : 'd-none' }}">
                            <hr>
                            <h6 class="fw-bold mb-3" style="color: var(--green-dark);">
                                <i class="bi bi-geo-alt me-1"></i> Delivery Information
                            </h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Address</label>
                                <input type="text" name="address" id="reg-address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address') }}" placeholder="Your delivery address">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">District</label>
                                <input type="text" name="buyer_district" id="reg-buyer-district"
                                    class="form-control @error('buyer_district') is-invalid @enderror"
                                    value="{{ old('buyer_district') }}" placeholder="e.g. Dhaka">
                                @error('buyer_district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold mt-3" id="register-submit-btn">
                            <i class="bi bi-person-check me-2"></i>Create My Account
                        </button>
                    </form>

                    <hr class="my-3">
                    <p class="text-center text-muted small mb-0">
                        Already have an account?
                        <a href="{{ route('login') }}" class="fw-semibold text-green">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle farmer/buyer specific fields based on role selection
document.querySelectorAll('input[name="role"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const isFarmer = this.value === 'farmer';
        document.getElementById('farmer-fields').classList.toggle('d-none', !isFarmer);
        document.getElementById('buyer-fields').classList.toggle('d-none', isFarmer);
    });
});
</script>
@endpush
