@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="section-title mb-0">Your Notifications</h2>
        </div>
        <div class="col-auto">
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-check2-all me-1"></i>Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="list-group list-group-flush">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = $notification->unread();
                @endphp
                <div class="list-group-item list-group-item-action p-4 {{ $isUnread ? 'bg-success-subtle' : '' }}">
                    <div class="d-flex w-100 justify-content-between align-items-start">
                        <div class="d-flex gap-3">
                            <div class="fs-4">
                                <i class="bi {{ $data['icon'] ?? 'bi-bell' }}"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold {{ $isUnread ? 'text-dark' : 'text-secondary' }}">
                                    {{ $data['title'] ?? 'Notification' }}
                                    @if($isUnread)
                                        <span class="badge bg-danger ms-2" style="font-size: 0.6rem;">NEW</span>
                                    @endif
                                </h6>
                                <p class="mb-1 text-muted small">{{ $data['message'] ?? '' }}</p>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div>
                            @if(isset($data['url']))
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">View</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="list-group-item p-5 text-center text-muted">
                    <div class="fs-1 mb-3" style="opacity: 0.2;"><i class="bi bi-bell-slash"></i></div>
                    <h5>No notifications yet</h5>
                    <p class="small">When you get updates, they will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
