<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCompletedNotification extends Notification
{
    use Queueable;

    public WorkOrder $workOrder;

    /**
     * Create a new notification instance.
     */
    public function __construct(WorkOrder $workOrder)
    {
        $this->workOrder = $workOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Orden Completada',
            'message' => "La orden {$this->workOrder->order_number} de {$this->workOrder->client->name} {$this->workOrder->client->last_name} está completada y lista para entrega.",
            'type' => 'success',
            'icon' => 'order_completed',
            'id_reference' => $this->workOrder->id,
        ];
    }
}
