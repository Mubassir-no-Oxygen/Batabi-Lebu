@extends('layouts.app')

@section('title', 'Weather Alerts & Advisories - Batabi Lebu')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Central Weather & Crop Advisories 🌦️</h1>
    <p style="color: var(--text-muted); font-size: 1.05rem;">Stay updated with official meteorological alerts and high-yield farming practices.</p>
</div>

<div style="display: flex; gap: 2rem; flex-wrap: wrap;">
    
    <div style="flex: 1; min-width: 300px;">
        @forelse($advisories as $advisory)
            <div class="glass-card" style="margin-bottom: 1.5rem; border-left: 5px solid {{ $advisory->severity == 'critical' ? '#ef4444' : ($advisory->severity == 'warning' ? '#f59e0b' : 'var(--primary)') }};">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        @if($advisory->type == 'weather_alert')
                            <i class='bx bx-cloud-lightning' style="font-size: 1.5rem; color: #3b82f6;"></i>
                        @elseif($advisory->type == 'pest_warning')
                            <i class='bx bx-bug' style="font-size: 1.5rem; color: #ef4444;"></i>
                        @else
                            <i class='bx bx-info-circle' style="font-size: 1.5rem; color: var(--primary);"></i>
                        @endif
                        <h2 style="font-size: 1.25rem;">{{ $advisory->title }}</h2>
                    </div>
                    
                    @if($advisory->severity == 'critical')
                        <span class="badge badge-danger" style="animation: pulse 2s infinite;">Critical Alert</span>
                    @elseif($advisory->severity == 'warning')
                        <span class="badge badge-warning">Warning</span>
                    @else
                        <span class="badge badge-success">Info</span>
                    @endif
                </div>

                <div style="display: flex; gap: 15px; color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">
                    <span><i class='bx bx-calendar'></i> Published: {{ $advisory->created_at->format('M d, Y h:i A') }}</span>
                    @if($advisory->target_district)
                        <span><i class='bx bx-target-lock'></i> Target: {{ $advisory->target_district }} District</span>
                    @else
                        <span><i class='bx bx-world'></i> Global Broadcast</span>
                    @endif
                </div>

                <p style="color: var(--text-main); line-height: 1.7;">
                    {{ $advisory->content }}
                </p>
            </div>
        @empty
            <div class="glass-card" style="text-align: center; padding: 4rem 1rem;">
                <i class='bx bx-sun' style="font-size: 4rem; color: var(--primary); margin-bottom: 1rem;"></i>
                <h3>All Clear!</h3>
                <p style="color: var(--text-muted);">There are no active weather warnings or advisories for your region right now.</p>
            </div>
        @endforelse
        
        <div style="margin-top: 2rem;">
            {{ $advisories->links() }}
        </div>
    </div>

    <!-- Sidebar Widget styling for advisories -->
    <div style="width: 350px;">
        <div class="glass-card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none;">
            <h3 style="margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                <i class='bx bx-phone-call'></i> Toll-Free Hotline
            </h3>
            <p style="font-size: 0.95rem; margin-bottom: 1.5rem; opacity: 0.9;">
                Need direct help regarding an advisory? Contact the agricultural response team.
            </p>
            <div style="background: rgba(0,0,0,0.2); padding: 1rem; border-radius: var(--radius-md); text-align: center; font-size: 1.5rem; font-weight: 800; letter-spacing: 2px;">
                16123
            </div>
        </div>
    </div>

</div>

<style>
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
</style>
@endsection
