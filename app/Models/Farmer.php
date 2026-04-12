<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farmer extends Model
{
    protected $fillable = [
        'user_id', 'farm_name', 'district', 'sub_district',
        'land_size', 'land_unit', 'crops_grown',
        'verification_status', 'rejection_reason',
        'verified_at', 'verified_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'land_size'   => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function crops(): HasMany
    {
        return $this->hasMany(Crop::class);
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(Agreement::class);
    }

    public function isApproved(): bool
    {
        return $this->verification_status === 'approved';
    }
}
