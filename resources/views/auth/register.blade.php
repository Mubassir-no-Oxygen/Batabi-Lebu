@extends('layouts.app')
@section('title', 'Register')

@section('content')
{{-- Auto-refresh CSRF token every 10 minutes to prevent token expiry errors --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card">
                <div class="card-header-green">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2"></i>Create Account</h4>
                    <p class="mb-0 small opacity-75">Join the Batabi Lebu community</p>
                </div>
                <div class="card-body p-4">

                    {{-- Show ALL validation errors at top --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="register-form" autocomplete="on">
                        @csrf

                        {{-- ─── Step 1: Role ─── --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">I want to register as: <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role" id="role-farmer" value="farmer"
                                        {{ old('role') === 'farmer' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 py-3" for="role-farmer">
                                        <div style="font-size: 1.8rem;"><i class="bi bi-person-badge"></i></div>
                                        <div class="fw-semibold">Farmer</div>
                                        <div class="small text-muted">I sell crops</div>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role" id="role-buyer" value="buyer"
                                        {{ (old('role', 'buyer') === 'buyer') ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 py-3" for="role-buyer">
                                        <div style="font-size: 1.8rem;"><i class="bi bi-bag"></i></div>
                                        <div class="fw-semibold">Buyer</div>
                                        <div class="small text-muted">I buy crops</div>
                                    </label>
                                </div>
                            </div>
                            @error('role')<div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
                        </div>

                        <hr class="my-4">

                        {{-- ─── Step 2: Common Fields ─── --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="reg-name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="reg-name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="Your full name"
                                    autocomplete="name"
                                    required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="reg-phone">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" id="reg-phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}"
                                    placeholder="01700000000"
                                    autocomplete="tel"
                                    required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="reg-email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="reg-email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                autocomplete="email"
                                required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="reg-password">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="reg-password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Min. 8 characters"
                                    autocomplete="new-password"
                                    required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="reg-password-confirm">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="reg-password-confirm"
                                    class="form-control"
                                    placeholder="Repeat password"
                                    autocomplete="new-password"
                                    required>
                            </div>
                        </div>

                        {{-- ─── Farmer-specific fields ─── --}}
                        <div id="farmer-fields" style="{{ old('role') === 'farmer' ? '' : 'display:none;' }}">
                            <hr>
                            <h6 class="fw-bold mb-3" style="color: var(--green-dark);">
                                <i class="bi bi-tree me-1"></i> Farm Details
                            </h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="reg-farm-name">Farm Name</label>
                                <input type="text" name="farm_name" id="reg-farm-name"
                                    class="form-control @error('farm_name') is-invalid @enderror"
                                    value="{{ old('farm_name') }}"
                                    placeholder="e.g. Green Valley Farm"
                                    autocomplete="organization">
                                @error('farm_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="reg-district">District</label>
                                    <input type="text" name="district" id="reg-district"
                                        class="form-control @error('district') is-invalid @enderror"
                                        value="{{ old('district') }}"
                                        placeholder="e.g. Sylhet"
                                        autocomplete="address-level2">
                                    @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="reg-land-size">Land Size (Bigha)</label>
                                    <input type="number" step="0.1" name="land_size" id="reg-land-size"
                                        class="form-control @error('land_size') is-invalid @enderror"
                                        value="{{ old('land_size') }}"
                                        placeholder="e.g. 5.5">
                                    @error('land_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ─── Buyer-specific fields ─── --}}
                        <div id="buyer-fields" style="{{ old('role', 'buyer') !== 'farmer' ? '' : 'display:none;' }}">
                            <hr>
                            <h6 class="fw-bold mb-3" style="color: var(--green-dark);">
                                <i class="bi bi-geo-alt me-1"></i> Delivery Information
                            </h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="reg-address">Address</label>
                                <input type="text" name="address" id="reg-address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address') }}"
                                    placeholder="Your delivery address"
                                    autocomplete="street-address">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="reg-buyer-district">District</label>
                                <input type="text" name="buyer_district" id="reg-buyer-district"
                                    class="form-control @error('buyer_district') is-invalid @enderror"
                                    value="{{ old('buyer_district') }}"
                                    placeholder="e.g. Dhaka"
                                    autocomplete="address-level2">
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
// Toggle farmer/buyer fields based on role selection
function toggleRoleFields(role) {
    document.getElementById('farmer-fields').style.display = (role === 'farmer') ? '' : 'none';
    document.getElementById('buyer-fields').style.display  = (role === 'buyer')  ? '' : 'none';
}

document.querySelectorAll('input[name="role"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        toggleRoleFields(this.value);
    });
});

// Refresh CSRF token every 10 minutes so long form sessions don't expire
setInterval(function() {
    fetch('/sanctum/csrf-cookie').catch(function() {
        // Silently refresh CSRF by reloading the token from a meta fetch
        fetch(window.location.href, { method: 'HEAD' });
    });
}, 600000); // 10 minutes
</script>
@endpush
