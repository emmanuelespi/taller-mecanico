<?php

namespace App\Notifications;

use App\Models\SparePart;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public SparePart $sparePart;

    /**
     * Create a new notification instance.
     */
    public function __construct(SparePart $sparePart)
    {
        $this->sparePart = $sparePart;
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
            'title' => 'Alerta de Inventario',
            'message' => "El repuesto '{$this->sparePart->name}' está por debajo del límite mínimo. Stock actual: {$this->sparePart->stock} (Mínimo: {$this->sparePart->minimum_stock}).",
            'type' => 'warning',
            'icon' => 'inventory',
            'id_reference' => $this->sparePart->id,
        ];
    }
}
