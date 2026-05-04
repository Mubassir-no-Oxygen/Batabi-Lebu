<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewReceivedNotification extends Notification
{
    use Queueable;

    public $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'new_review',
            'title' => 'New Review Received',
            'message' => "You received a {$this->review->rating}-star review for order #{$this->review->order_id}.",
            'url' => route('farmer.reviews.index'),
            'icon' => 'bi-star-fill text-warning'
        ];
    }
}
