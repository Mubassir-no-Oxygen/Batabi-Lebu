<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * REQUIREMENT 6: Market price suggestions for crop listings.
 * Admin/system populates this; queried when farmer adds a new crop.
 */
class PriceSuggestion extends Model
{
    protected $fillable = [
        'crop_name', 'category', 'season', 'district',
        'suggested_min_price', 'suggested_max_price', 'unit',
        'demand_level', 'source', 'notes', 'is_active',
        'valid_from', 'valid_until', 'created_by',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'valid_from'  => 'date',
        'valid_until' => 'date',
    ];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    /**
     * Look up a price suggestion for a given crop, season, and district.
     * Falls back to nationwide (district = null) if no local entry found.
     */
    public static function suggest(string $cropName, string $category, string $district): ?self
    {
        $season = self::currentSeason();

        // Try district-specific first
        $suggestion = self::where('crop_name', 'like', "%{$cropName}%")
            ->where('category', $category)
            ->where(fn($q) => $q->where('season', $season)->orWhere('season', 'all'))
            ->where('district', $district)
            ->where('is_active', true)
            ->latest()
            ->first();

        // Fall back to nationwide
        return $suggestion ?? self::where('crop_name', 'like', "%{$cropName}%")
            ->where('category', $category)
            ->where(fn($q) => $q->where('season', $season)->orWhere('season', 'all'))
            ->whereNull('district')
            ->where('is_active', true)
            ->latest()
            ->first();
    }

    /** Determine current Bangladesh agricultural season by month */
    public static function currentSeason(): string
    {
        $month = (int) now()->format('n');
        // Rabi: November–February | Kharif: March–October
        return in_array($month, [11, 12, 1, 2]) ? 'rabi' : 'kharif';
    }
}
