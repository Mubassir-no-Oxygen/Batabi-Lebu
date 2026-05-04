<?php

namespace App\Notifications;

use App\Models\Crop;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCropListingNotification extends Notification
{
    use Queueable;

    public $crop;

    public function __construct(Crop $crop)
    {
        $this->crop = $crop;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'new_crop',
            'title' => 'New Local Crop Listed',
            'message' => "{$this->crop->farmer->user->name} just listed {$this->crop->crop_name} near your area.",
            'url' => route('buyer.crops.show', $this->crop->id),
            'icon' => 'bi-tree text-success'
        ];
    }
}
