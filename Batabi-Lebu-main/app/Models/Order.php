<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'buyer_id', 'crop_id', 'requested_quantity',
        'offered_price', 'final_price', 'bulk_discount_percent',
        'note', 'admin_note', 'status',
        'accepted_at', 'completed_at',
    ];

    protected $casts = [
        'accepted_at'           => 'datetime',
        'completed_at'          => 'datetime',
        'requested_quantity'    => 'decimal:2',
        'offered_price'         => 'decimal:2',
        'final_price'           => 'decimal:2',
        'bulk_discount_percent' => 'decimal:2',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }

    public function negotiations(): HasMany
    {
        return $this->hasMany(Negotiation::class)->latest();
    }

    public function latestNegotiation(): HasOne
    {
        return $this->hasOne(Negotiation::class)->latestOfMany();
    }

    public function agreement(): HasOne
    {
        return $this->hasOne(Agreement::class);
    }

    public function getFarmer()
    {
        return $this->crop?->farmer;
    }

    public function statusBadgeColor(): string
    {
        return match($this->status) {
            'pending'   => 'warning',
            'accepted'  => 'success',
            'rejected'  => 'danger',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }

    public function totalAmount(): float
    {
        $price    = $this->final_price ?? $this->offered_price ?? $this->crop?->price_per_unit ?? 0;
        $discount = $this->bulk_discount_percent ?? 0;
        $subtotal = $price * $this->requested_quantity;
        return $subtotal - ($subtotal * $discount / 100);
    }
}
