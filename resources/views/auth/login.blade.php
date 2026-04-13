@extends('layouts.app')

@section('title', 'Login to Batabi Lebu')

@section('content')
<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="glass-card" style="width: 100%; max-width: 450px; padding: 3rem;">
        
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <i class='bx bx-leaf' style="font-size: 3.5rem; color: var(--primary);"></i>
            <h1 style="font-size: 2rem; margin-top: 10px;">Welcome Back</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Enter your credentials to access your portal</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <ul style="margin-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div style="position: relative;">
                    <i class='bx bx-envelope' style="position: absolute; top: 13px; left: 15px; color: var(--text-muted); font-size: 1.2rem;"></i>
                    <input type="email" name="email" class="form-control" style="padding-left: 45px;" required value="{{ old('email') }}" placeholder="hello@example.com">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" style="display: flex; justify-content: space-between;">
                    Password
                    <a href="#" style="font-size: 0.8rem; color: var(--primary); text-decoration: none;">Forgot?</a>
                </label>
                <div style="position: relative;">
                    <i class='bx bx-lock-alt' style="position: absolute; top: 13px; left: 15px; color: var(--text-muted); font-size: 1.2rem;"></i>
                    <input type="password" name="password" class="form-control" style="padding-left: 45px;" required placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-top: 1rem;">
                Sign In
            </button>
        </form>

        <p style="text-align: center; margin-top: 2rem; font-size: 0.95rem; color: var(--text-muted);">
            Don't have an account? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Create one here</a>
        </p>

    </div>
</div>
@endsection
