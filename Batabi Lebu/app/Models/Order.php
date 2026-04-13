<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id', 'crop_id', 'requested_quantity',
        'offered_price', 'final_price', 'bulk_discount_percent',
        'note', 'admin_note', 'status',
        'accepted_at', 'completed_at',
    ];

    protected $casts = [
        'accepted_at'   => 'datetime',
        'completed_at'  => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    /** Future: price negotiation thread */
    public function negotiations()
    {
        return $this->hasMany(Negotiation::class);
    }

    /** Future: payment record */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /** Future: delivery tracking */
    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    /** Future: post-trade review */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /** Req 8: Digital agreement generated after order confirmation */
    public function agreement()
    {
        return $this->hasOne(Agreement::class);
    }

    /** Req 10: Fraud reports linked to this order */
    public function fraudReports()
    {
        return $this->hasMany(FraudReport::class);
    }
}
