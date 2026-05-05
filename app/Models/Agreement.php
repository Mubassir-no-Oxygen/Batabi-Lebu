<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * REQUIREMENT 8: Digital trade agreement generated after order confirmation.
 * Both farmer and buyer must sign before the agreement is finalised.
 */
class Agreement extends Model
{
    protected $fillable = [
        'order_id', 'farmer_id', 'buyer_id',
        'agreed_quantity', 'quantity_unit', 'agreed_price_per_unit',
        'total_amount', 'bulk_discount_percent', 'terms', 'status',
        'farmer_signed_at', 'buyer_signed_at', 'document_path',
    ];

    protected $casts = [
        'farmer_signed_at' => 'datetime',
        'buyer_signed_at'  => 'datetime',
    ];

    public function order()   { return $this->belongsTo(Order::class); }
    public function farmer()  { return $this->belongsTo(Farmer::class); }
    public function buyer()   { return $this->belongsTo(Buyer::class); }

    /** Check if both parties have signed */
    public function isFullySigned(): bool
    {
        return $this->farmer_signed_at && $this->buyer_signed_at;
    }
}
