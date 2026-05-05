<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * CROSS-CUTTING: In-app notifications for all platform events.
 * UUID primary key — compatible with Laravel's built-in notification system.
 */
class Notification extends Model
{
    protected $primaryKey = 'id';
    public    $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'user_id', 'type', 'title', 'message', 'data', 'read_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'read_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function isRead(): bool  { return !is_null($this->read_at); }

    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }
}
