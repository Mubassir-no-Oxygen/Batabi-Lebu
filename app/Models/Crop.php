<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    protected $fillable = [
        'farmer_id',
        'crop_name',
        'category',
        'quantity',
        'unit',
        'price_per_unit',
        'harvest_date',
        'status',
    ];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}