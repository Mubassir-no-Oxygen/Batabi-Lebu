<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agreement extends Model
{
    protected $fillable = [
        'order_id', 'farmer_id', 'buyer_id',
        'agreed_quantity', 'quantity_unit',
        'agreed_price_per_unit', 'total_amount',
        'bulk_discount_percent', 'terms', 'status',
        'farmer_signed_at', 'buyer_signed_at', 'document_path',
    ];

    protected $casts = [
        'farmer_signed_at'      => 'datetime',
        'buyer_signed_at'       => 'datetime',
        'agreed_quantity'       => 'decimal:2',
        'agreed_price_per_unit' => 'decimal:2',
        'total_amount'          => 'decimal:2',
        'bulk_discount_percent' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function isFullySigned(): bool
    {
        return $this->farmer_signed_at !== null && $this->buyer_signed_at !== null;
    }
}
