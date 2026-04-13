@extends('layouts.app')

@section('content')
<div class="container mt-4" style="max-width: 600px">
    <h4>💳 Make Payment – Order #{{ $order->id }}</h4>

    <div class="card mb-3 p-3 bg-light">
        <p><strong>Crop:</strong> {{ $order->crop->crop_name }}</p>
        <p><strong>Quantity:</strong> {{ $order->requested_quantity }} {{ $order->crop->unit }}</p>
        <p><strong>Price per unit:</strong> ৳{{ $order->final_price }}</p>
        <p><strong>Total Amount:</strong>
            ৳{{ number_format($order->final_price * $order->requested_quantity, 2) }}
        </p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('payment.store', $order) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" required>
                <option value="">-- Select --</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
                <option value="bank">Bank Transfer</option>
                <option value="cash">Cash</option>
            </select>
            @error('payment_method')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Transaction ID <span class="text-muted">(optional)</span></label>
            <input type="text" name="transaction_id" class="form-control"
                   placeholder="e.g. TXN1234567">
        </div>

        <button class="btn btn-success w-100">
            🔒 Pay & Hold in Escrow
        </button>
    </form>
</div>
@endsection