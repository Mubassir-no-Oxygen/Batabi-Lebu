<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'new_order',
            'title' => 'New Order Received',
            'message' => "You have a new order (#{$this->order->id}) for {$this->order->crop->crop_name}.",
            'url' => route('farmer.orders.index'),
            'icon' => 'bi-cart-check text-success'
        ];
    }
}
