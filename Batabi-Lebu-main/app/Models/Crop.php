<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crop extends Model
{
    protected $fillable = [
        'farmer_id', 'crop_name', 'category', 'quantity', 'unit',
        'price_per_unit', 'harvest_date', 'available_from',
        'available_until', 'description', 'image', 'status',
    ];

    protected $casts = [
        'harvest_date'   => 'date',
        'available_from' => 'date',
        'available_until'=> 'date',
        'quantity'       => 'decimal:2',
        'price_per_unit' => 'decimal:2',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
