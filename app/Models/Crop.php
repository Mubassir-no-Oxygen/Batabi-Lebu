<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id', 'crop_name', 'category', 'quantity', 'unit',
        'price_per_unit', 'harvest_date', 'available_from', 'available_until',
        'description', 'image', 'status',
    ];

    protected $casts = [
        'harvest_date'    => 'date',
        'available_from'  => 'date',
        'available_until' => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
