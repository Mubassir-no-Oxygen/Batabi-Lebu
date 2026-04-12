@extends('layouts.main')

@section('title', 'Agreement')

@section('content')
<div class="container py-4" style="max-width: 760px;">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card shadow border-0">
        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #2d6a4f, #40916c); color: white;">
            <div class="fw-bold fs-4">🌿 Batabi Lebu</div>
            <div class="fs-6 mt-1 opacity-75">Digital Trade Agreement</div>
            <div class="badge bg-light text-dark mt-2">Agreement #{{ $agreement->id }}</div>
        </div>

        <div class="card-body p-4">

            <div class="alert {{ $agreement->status === 'signed' ? 'alert-success' : 'alert-warning' }} text-center mb-4">
                <strong>Status: {{ ucfirst(str_replace('_', ' ', $agreement->status)) }}</strong>
                @if($agreement->status === 'signed') — Fully signed and active ✔ @endif
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border">
                        <div class="text-muted small mb-1">FARMER (Seller)</div>
                        <div class="fw-bold fs-5">{{ $agreement->farmer->user->name }}</div>
                        <div class="small text-muted">{{ $agreement->farmer->farm_name }} · {{ $agreement->farmer->district }}</div>
                        <div class="mt-2">
                            @if($agreement->farmer_signed_at)
                                <span class="badge bg-success">✔ Signed {{ $agreement->farmer_signed_at->format('d M Y, h:i A') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending Signature</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border">
                        <div class="text-muted small mb-1">BUYER (Purchaser)</div>
                        <div class="fw-bold fs-5">{{ $agreement->buyer->user->name }}</div>
                        <div class="small text-muted">{{ $agreement->buyer->company_name ?? '' }} · {{ $agreement->buyer->district }}</div>
                        <div class="mt-2">
                            @if($agreement->buyer_signed_at)
                                <span class="badge bg-success">✔ Signed {{ $agreement->buyer_signed_at->format('d M Y, h:i A') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending Signature</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold text-success mb-3">📦 Trade Details</h6>
            <table class="table table-bordered table-sm mb-4">
                <tr><th>Crop</th><td>{{ $agreement->order->crop->crop_name }}</td></tr>
                <tr><th>Quantity</th><td>{{ number_format($agreement->agreed_quantity, 1) }} {{ $agreement->quantity_unit }}</td></tr>
                <tr><th>Price per Unit</th><td>৳{{ number_format($agreement->agreed_price_per_unit, 2) }}/{{ $agreement->quantity_unit }}</td></tr>
                @if($agreement->bulk_discount_percent > 0)
                <tr><th>Bulk Discount</th><td>{{ $agreement->bulk_discount_percent }}%</td></tr>
                @endif
                <tr><th class="text-success">Total Amount</th><td class="fw-bold text-success fs-5">৳{{ number_format($agreement->total_amount, 2) }}</td></tr>
            </table>

            @if($agreement->terms)
            <h6 class="fw-bold text-success mb-2">📋 Terms & Conditions</h6>
            <div class="bg-light rounded p-3 mb-4" style="white-space:pre-line; font-size:0.9rem;">{{ $agreement->terms }}</div>
            @endif

            @php
                $user   = auth()->user();
                $farmer = \App\Models\Farmer::where('user_id', $user->id)->first();
                $buyer  = \App\Models\Buyer::where('user_id', $user->id)->first();
                $canSign = ($farmer && $agreement->farmer_id === $farmer->id && !$agreement->farmer_signed_at)
                        || ($buyer  && $agreement->buyer_id  === $buyer->id  && !$agreement->buyer_signed_at);
            @endphp

            @if($canSign && $agreement->status !== 'cancelled')
                <div class="text-center mt-3">
                    <form action="{{ route('agreements.sign', $agreement) }}" method="POST"
                        onsubmit="return confirm('By clicking OK you confirm your digital signature.')">
                        @csrf
                        <button class="btn btn-success btn-lg px-5">✍ Sign Agreement</button>
                    </form>
                    <p class="text-muted small mt-2">Your signature is binding on this platform.</p>
                </div>
            @elseif($agreement->isFullySigned())
                <div class="text-center text-success fw-bold fs-5 mt-3">✅ Fully signed by both parties</div>
            @endif
        </div>

        <div class="card-footer text-center text-muted small py-3">
            Generated {{ $agreement->created_at->format('d M Y, h:i A') }} · Batabi Lebu Platform
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('orders.show', $agreement->order) }}" class="btn btn-outline-secondary btn-sm">← Back to Order</a>
        <a href="{{ route('agreements.index') }}" class="btn btn-outline-secondary btn-sm">All Agreements</a>
    </div>
</div>
@endsection
