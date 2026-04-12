<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $fillable = [
        'order_id', 'delivery_partner_id', 'pickup_address',
        'delivery_address', 'status', 'tracking_number',
        'expected_at', 'delivered_at',
    ];

    protected $casts = [
        'expected_at'  => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryPartner(): BelongsTo
    {
        // delivery_partner_id → users.id (role = delivery_partner)
        return $this->belongsTo(User::class, 'delivery_partner_id');
    }

    public function statusBadgeColor(): string
    {
        return match($this->status) {
            'pending'    => 'warning',
            'picked_up'  => 'info',
            'in_transit' => 'primary',
            'delivered'  => 'success',
            'returned'   => 'danger',
            default      => 'secondary',
        };
    }

    public static function generateTrackingNumber(): string
    {
        return 'BL-' . strtoupper(substr(md5(uniqid()), 0, 8));
    }
}
