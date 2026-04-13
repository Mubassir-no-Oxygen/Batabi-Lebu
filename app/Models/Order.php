<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'buyer_id',
        'crop_id',
        'requested_quantity',
        'offered_price',
        'final_price',
        'bulk_discount_percent',
        'note',
        'admin_note',
        'status',
        'accepted_at',
        'completed_at',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}