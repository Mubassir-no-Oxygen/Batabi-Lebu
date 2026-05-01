@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="section-title mb-1">Admin Dashboard</h2>
            <p class="text-muted mb-0">Platform overview & management</p>
        </div>
        <a href="{{ route('admin.farmers.index') }}" class="btn btn-warning fw-bold" id="admin-farmers-btn">
            <i class="bi bi-people me-1"></i> Manage Farmers
        </a>
    </div>

    <!-- ─── Stats Row ─── -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-green">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-orange">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-value">{{ $stats['pending_farmers'] }}</div>
                <div class="stat-label">Pending Farmers</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-blue">
                <div class="stat-icon"><i class="bi bi-flower1"></i></div>
                <div class="stat-value">{{ $stats['total_crops'] }}</div>
                <div class="stat-label">Total Crops</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card stat-purple">
                <div class="stat-icon"><i class="bi bi-bag"></i></div>
                <div class="stat-value">{{ $stats['total_orders'] }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
    </div>

    <!-- ─── Pending Farmers ─── -->
    <div class="card">
        <div class="card-header-green d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-person-check me-2"></i>Farmers Awaiting Approval</span>
            <a href="{{ route('admin.farmers.index') }}" class="btn btn-sm btn-light">View All</a>
        </div>
        <div class="card-body p-0">
            @if($recentFarmers->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0">All farmers have been processed. No pending requests.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Farmer</th>
                                <th>Farm Name</th>
                                <th>District</th>
                                <th>Phone</th>
                                <th>Applied</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentFarmers as $farmer)
                            <tr>
                                <td class="fw-semibold">{{ $farmer->user->name }}</td>
                                <td>{{ $farmer->farm_name }}</td>
                                <td>{{ $farmer->district }}</td>
                                <td>{{ $farmer->user->phone }}</td>
                                <td class="small text-muted">{{ $farmer->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.farmers.show', $farmer) }}" class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.farmers.approve', $farmer) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this farmer?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            id="approve-farmer-{{ $farmer->id }}">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
