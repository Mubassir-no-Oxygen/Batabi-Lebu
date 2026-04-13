@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>📋 My Fraud Reports</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('fraud.create') }}" class="btn btn-danger mb-3">
        + New Report
    </a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Reported User</th>
                <th>Type</th>
                <th>Status</th>
                <th>Submitted</th>
            </tr>
        </thead>
        <tbody>
        @forelse($reports as $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->reportedUser->name }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $report->type)) }}</td>
                <td>
                    @if($report->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($report->status === 'investigating')
                        <span class="badge bg-info">Investigating</span>
                    @elseif($report->status === 'resolved')
                        <span class="badge bg-success">Resolved</span>
                    @else
                        <span class="badge bg-secondary">Dismissed</span>
                    @endif
                </td>
                <td>{{ $report->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No reports submitted yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection