@extends('layouts.app')

@section('content')
<div class="container mt-4" style="max-width: 600px">
    <h4>🚨 Report Fraud or Suspicious Activity</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('fraud.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Who are you reporting?</label>
            <select name="reported_user_id" class="form-select" required>
                <option value="">-- Select User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @error('reported_user_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Type of Fraud</label>
            <select name="type" class="form-select" required>
                <option value="">-- Select Type --</option>
                <option value="fake_listing">Fake Listing</option>
                <option value="payment_fraud">Payment Fraud</option>
                <option value="non_delivery">Non Delivery</option>
                <option value="quality_fraud">Quality Fraud</option>
                <option value="impersonation">Impersonation</option>
                <option value="scam">Scam</option>
                <option value="other">Other</option>
            </select>
            @error('type')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"
                      placeholder="Describe what happened in detail..." required></textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Related Order ID <span class="text-muted">(optional)</span></label>
            <input type="number" name="order_id" class="form-control"
                   placeholder="Leave blank if not order related">
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Evidence <span class="text-muted">(optional, jpg/png/pdf)</span></label>
            <input type="file" name="evidence" class="form-control"
                   accept=".jpg,.jpeg,.png,.pdf">
            @error('evidence')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-danger w-100">🚨 Submit Report</button>
    </form>
</div>
@endsection