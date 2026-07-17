<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewWorkOrderNotification extends Notification
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
            'title' => 'Nueva Orden Registrada',
            'message' => "Se ha ingresado la orden de trabajo {$this->workOrder->order_number} para el vehículo {$this->workOrder->vehicle->brand} {$this->workOrder->vehicle->model} (Placa: {$this->workOrder->vehicle->plate}).",
            'type' => 'info',
            'icon' => 'order_created',
            'id_reference' => $this->workOrder->id,
        ];
    }
}
