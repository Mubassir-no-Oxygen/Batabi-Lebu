@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header-green">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Sign In</h4>
                    <p class="mb-0 small opacity-75">Welcome back to Batabi Lebu</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="login-email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="login-password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="••••••••" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4 d-flex align-items-center justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label small" for="remember">Remember me</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold" id="login-submit-btn">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center text-muted small mb-0">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="fw-semibold text-green">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
