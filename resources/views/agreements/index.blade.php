@extends('layouts.main')

@section('title', 'My Agreements')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-success mb-4">📄 My Agreements</h2>

    @if($agreements->isEmpty())
        <div class="text-center py-5 text-muted">
            <h5>No agreements yet.</h5>
            <p>Agreements are generated automatically when an order is confirmed.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Crop</th>
                        <th>{{ auth()->user()->role === 'buyer' ? 'Farmer' : 'Buyer' }}</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Farmer Sig.</th>
                        <th>Buyer Sig.</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agreements as $agr)
                    <tr>
                        <td>{{ $agr->id }}</td>
                        <td>{{ $agr->order->crop->crop_name }}</td>
                        <td>{{ auth()->user()->role === 'buyer' ? $agr->farmer->user->name : $agr->buyer->user->name }}</td>
                        <td>{{ number_format($agr->agreed_quantity, 1) }} {{ $agr->quantity_unit }}</td>
                        <td>৳{{ number_format($agr->total_amount, 2) }}</td>
                        <td>
                            @if($agr->farmer_signed_at)
                                <span class="badge bg-success">Signed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($agr->buyer_signed_at)
                                <span class="badge bg-success">Signed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ match($agr->status) { 'signed'=>'success','draft','pending_farmer','pending_buyer'=>'warning',default=>'secondary' } }}">
                                {{ ucfirst(str_replace('_', ' ', $agr->status)) }}
                            </span>
                        </td>
                        <td><a href="{{ route('agreements.show', $agr) }}" class="btn btn-sm btn-outline-success">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $agreements->links() }}
    @endif
</div>
@endsection
