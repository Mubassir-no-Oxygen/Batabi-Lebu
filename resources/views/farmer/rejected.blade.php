@extends('layouts.app')
@section('title', 'Application Rejected')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card p-5">
                <div style="font-size: 5rem; margin-bottom: 1rem;">❌</div>
                <h3 class="fw-bold mb-2 text-danger">Application Rejected</h3>
                <p class="text-muted mb-3">
                    Unfortunately, your farmer application has been rejected.
                </p>
                @if(auth()->user()->farmer->rejection_reason)
                <div class="alert alert-danger text-start">
                    <strong>Reason:</strong> {{ auth()->user()->farmer->rejection_reason }}
                </div>
                @endif
                <p class="small text-muted">
                    Please contact support if you believe this was a mistake.
                    <a href="mailto:support@batabi-lebu.com">support@batabi-lebu.com</a>
                </p>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
