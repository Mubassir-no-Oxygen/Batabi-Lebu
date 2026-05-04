<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Review Model — Feature 5: Buyer Ratings & Feedback
 *
 * Stores buyer→farmer reviews after a completed (accepted) order.
 * reviewer_id = buyer's user ID | reviewee_id = farmer's user ID
 */
class Review extends Model
{
    protected $fillable = [
        'order_id',
        'reviewer_id',
        'reviewee_id',
        'rating',
        'comment',
    ];

    // ─── Relationships ────────────────────────────────────────

    /** The order this review is attached to */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /** The user who wrote the review (buyer) */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /** The user who received the review (farmer) */
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    // ─── Helpers ──────────────────────────────────────────────

    /**
     * Returns a star string like "★★★★☆" for a given rating.
     */
    public static function starString(int $rating): string
    {
        return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
    }
}
