<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'profile_photo',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Role checks ──────────────────────────────────────────
    public function isFarmer(): bool  { return $this->role === 'farmer'; }
    public function isBuyer(): bool   { return $this->role === 'buyer'; }
    public function isAdmin(): bool   { return $this->role === 'admin'; }

    // ─── Relationships ────────────────────────────────────────
    public function farmer()
    {
        return $this->hasOne(Farmer::class);
    }

    public function buyer()
    {
        return $this->hasOne(Buyer::class);
    }

    /** Future: complaints filed by this user */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
