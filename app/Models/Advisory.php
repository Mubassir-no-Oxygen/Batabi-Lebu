<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * REQUIREMENT 13: Weather alerts and farming advisories for farmers.
 * Published by admin; targeted by district or broadcast nationwide.
 */
class Advisory extends Model
{
    protected $fillable = [
        'title', 'content', 'type', 'target_district',
        'severity', 'published_by', 'is_active', 'expires_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function publisher() { return $this->belongsTo(User::class, 'published_by'); }

    /** Scope: only active, non-expired advisories */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(fn($q) => $q->whereNull('expires_at')
                                         ->orWhere('expires_at', '>', now()));
    }
}
