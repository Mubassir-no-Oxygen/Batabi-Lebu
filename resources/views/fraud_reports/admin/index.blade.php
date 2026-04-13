@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>🛡️ Admin – Fraud Reports</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($reports as $report)
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between">
            <strong>Report #{{ $report->id }}</strong>
            @if($report->status === 'pending')
                <span class="badge bg-warning text-dark">Pending</span>
            @elseif($report->status === 'investigating')
                <span class="badge bg-info">Investigating</span>
            @elseif($report->status === 'resolved')
                <span class="badge bg-success">Resolved</span>
            @else
                <span class="badge bg-secondary">Dismissed</span>
            @endif
        </div>
        <div class="card-body">
            <p><strong>Reporter:</strong> {{ $report->reporter->name }}</p>
            <p><strong>Reported User:</strong> {{ $report->reportedUser->name }}</p>
            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $report->type)) }}</p>
            <p><strong>Description:</strong> {{ $report->description }}</p>

            @if($report->order_id)
                <p><strong>Order ID:</strong> #{{ $report->order_id }}</p>
            @endif

            @if($report->evidence_path)
                <p>
                    <strong>Evidence:</strong>
                    <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank">
                        View File
                    </a>
                </p>
            @endif

            <hr>

            <form method="POST" action="{{ route('admin.fraud.update', $report) }}">
                @csrf
                @method('PATCH')
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="investigating" {{ $report->status === 'investigating' ? 'selected' : '' }}>Investigating</option>
                            <option value="resolved"     {{ $report->status === 'resolved'      ? 'selected' : '' }}>Resolved</option>
                            <option value="dismissed"    {{ $report->status === 'dismissed'     ? 'selected' : '' }}>Dismissed</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="admin_note" class="form-control"
                               placeholder="Admin note..."
                               value="{{ $report->admin_note }}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @empty
        <p class="text-muted">No fraud reports found.</p>
    @endforelse
</div>
@endsection