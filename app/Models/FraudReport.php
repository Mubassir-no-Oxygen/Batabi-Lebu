<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudReport extends Model
{
    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'order_id',
        'type',
        'description',
        'evidence_path',
        'status',
        'admin_note',
        'resolved_at',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}