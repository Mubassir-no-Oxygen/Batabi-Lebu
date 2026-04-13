@extends('layouts.app')
@section('title', 'Farmer Management')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Farmer Management</h2>
    </div>

    <!-- Status Tabs -->
    <div class="mb-4">
        <div class="nav nav-pills gap-2">
            <a class="nav-link {{ $status === 'pending' ? 'active bg-warning text-dark' : 'btn-outline-secondary' }}"
               href="{{ route('admin.farmers.index', ['status' => 'pending']) }}">
                ⏳ Pending
            </a>
            <a class="nav-link {{ $status === 'approved' ? 'active bg-success' : 'btn-outline-secondary' }}"
               href="{{ route('admin.farmers.index', ['status' => 'approved']) }}">
                ✅ Approved
            </a>
            <a class="nav-link {{ $status === 'rejected' ? 'active bg-danger' : 'btn-outline-secondary' }}"
               href="{{ route('admin.farmers.index', ['status' => 'rejected']) }}">
                ❌ Rejected
            </a>
            <a class="nav-link {{ $status === 'all' ? 'active' : 'btn-outline-secondary' }}"
               href="{{ route('admin.farmers.index', ['status' => 'all']) }}">
                📋 All
            </a>
        </div>
    </div>

    @if($farmers->isEmpty())
        <div class="card text-center py-5">
            <div style="font-size: 4rem; opacity: .3;">👨‍🌾</div>
            <h5 class="mt-3 text-muted">No farmers found</h5>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Farmer</th>
                            <th>Email</th>
                            <th>Farm</th>
                            <th>District</th>
                            <th>Land Size</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($farmers as $farmer)
                        <tr>
                            <td class="fw-semibold">{{ $farmer->user->name }}</td>
                            <td class="small text-muted">{{ $farmer->user->email }}</td>
                            <td>{{ $farmer->farm_name }}</td>
                            <td>{{ $farmer->district }}</td>
                            <td>{{ $farmer->land_size ?? '—' }} {{ $farmer->land_unit }}</td>
                            <td>
                                <span class="badge badge-{{ $farmer->verification_status }}">
                                    {{ ucfirst($farmer->verification_status) }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $farmer->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.farmers.show', $farmer) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($farmer->verification_status === 'pending')
                                    <form action="{{ route('admin.farmers.approve', $farmer) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Approve {{ $farmer->farm_name }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            id="approve-{{ $farmer->id }}">
                                            ✓ Approve
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $farmers->links() }}</div>
    @endif
</div>
@endsection
