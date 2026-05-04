<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
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
        $status = ucfirst($this->order->status);
        $icon = $this->order->status === 'accepted' ? 'bi-check-circle text-success' : 'bi-x-circle text-danger';

        return [
            'type' => 'order_status',
            'title' => "Order {$status}",
            'message' => "Your order (#{$this->order->id}) for {$this->order->crop->crop_name} was {$this->order->status}.",
            'url' => route('buyer.orders.index'),
            'icon' => $icon
        ];
    }
}
