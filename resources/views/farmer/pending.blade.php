@extends('layouts.app')
@section('title', 'Account Pending Approval')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card p-5">
                <div style="font-size: 5rem; margin-bottom: 1rem;">⏳</div>
                <h3 class="fw-bold mb-2" style="color: var(--green-dark);">Application Under Review</h3>
                <p class="text-muted mb-4">
                    Thank you for registering, <strong>{{ auth()->user()->name }}</strong>!
                    Your farmer account is pending admin approval. You will be able to access the dashboard once verified.
                </p>
                <div class="alert alert-info text-start">
                    <strong>Farm:</strong> {{ auth()->user()->farmer->farm_name }}<br>
                    <strong>District:</strong> {{ auth()->user()->farmer->district }}<br>
                    <strong>Status:</strong> <span class="badge badge-pending">Pending</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
