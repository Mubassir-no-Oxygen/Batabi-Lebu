<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'farm_name', 'district', 'sub_district',
        'land_size', 'land_unit', 'crops_grown',
        'verification_status', 'rejection_reason',
        'verified_at', 'verified_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function crops()
    {
        return $this->hasMany(Crop::class);
    }

    /** Future: emergency support requests */
    public function emergencyRequests()
    {
        return $this->hasMany(EmergencyRequest::class);
    }

    // ─── Helpers ──────────────────────────────────────────────
    public function isApproved(): bool
    {
        return $this->verification_status === 'approved';
    }
}
