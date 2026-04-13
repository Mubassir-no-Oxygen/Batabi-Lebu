@extends('layouts.app')

@section('title', 'Emergency Support - Batabi Lebu')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Emergency & Farm Rescue 🆘</h1>
    <p style="color: var(--text-muted); font-size: 1.05rem;">Submit immediate distress tickets regarding crop damage, extreme weather impact, or severe pest outbreaks.</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">
    
    <!-- Submit Emergency Form -->
    <div class="glass-card" style="border-top: 5px solid #ef4444; position: sticky; top: 100px;">
        <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;">
            <i class='bx bx-shield-plus' style="color: #ef4444; font-size: 1.5rem;"></i> Request Assistance
        </h2>

        @if ($errors->any())
            <div class="alert alert-danger" style="background: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                <ul style="margin-left: 1rem; font-size: 0.85rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('farmer.emergency.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nature of Emergency</label>
                <select name="type" class="form-control" required>
                    <option value="" disabled selected>Select emergency type...</option>
                    <option value="weather">Extreme Weather / Flood Damage</option>
                    <option value="pest">Severe Pest / Disease Outbreak</option>
                    <option value="finance">Critical Financial Rescue</option>
                    <option value="other">Other Emergency</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Affected Location</label>
                <input type="text" name="location" class="form-control" required value="{{ old('location') }}" placeholder="Specific village/upazila address...">
            </div>

            <div class="form-group">
                <label class="form-label">Detailed Description</label>
                <textarea name="description" class="form-control" rows="4" required placeholder="Describe the damage, total acres affected, and exactly what kind of help you need immediately...">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-danger" style="width: 100%; padding: 1rem; font-size: 1rem; font-weight: 700;">
                <i class='bx bx-error-circle'></i> Submit Distress Signal
            </button>
        </form>
    </div>

    <!-- Past Emergencies -->
    <div class="glass-card">
        <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--text-main);">
            My Past Requests
        </h2>

        @if($requests->count() > 0)
            @foreach($requests as $req)
                <div style="background: var(--surface-hover); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <span class="badge" style="background: var(--bg-color); color: var(--text-muted); margin-bottom: 8px;">Ticket #ER-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <h3 style="font-size: 1.1rem; text-transform: capitalize;"><i class='bx bx-tag'></i> {{ $req->type }} Emergency</h3>
                        </div>
                        
                        @if($req->status == 'open')
                            <span class="badge badge-danger" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">Open / Pending</span>
                        @elseif($req->status == 'in_review')
                            <span class="badge badge-warning">Responders Dispatched</span>
                        @else
                            <span class="badge badge-success">Resolved</span>
                        @endif
                    </div>

                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 1rem;">
                        <i class='bx bx-map-pin'></i> {{ $req->location }} <br><br>
                        "{{ $req->description }}"
                    </p>

                    @if($req->admin_note)
                        <div style="background: rgba(16, 185, 129, 0.05); border-left: 3px solid var(--primary); padding: 1rem; border-radius: 4px;">
                            <strong style="color: var(--primary); display: block; font-size: 0.85rem; margin-bottom: 5px;">Response Note:</strong>
                            <p style="font-size: 0.9rem; color: var(--text-main); margin:0;">{{ $req->admin_note }}</p>
                        </div>
                    @endif
                    
                    <div style="margin-top: 1rem; font-size: 0.75rem; color: var(--text-muted); text-align: right;">
                        Reported: {{ $req->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 1.5rem;">
                {{ $requests->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                <i class='bx bx-check-shield' style="font-size: 4rem; color: var(--primary-light); margin-bottom: 1rem; opacity: 0.5;"></i>
                <h3>No active emergencies</h3>
                <p>You have never submitted an emergency distress ticket. Stay safe!</p>
            </div>
        @endif
    </div>

</div>
@endsection
