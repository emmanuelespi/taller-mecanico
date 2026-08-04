<?php

namespace App\Enums;

enum WorkOrderStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendiente',
            self::IN_PROGRESS => 'En Progreso',
            self::COMPLETED => 'Completada',
            self::DELIVERED => 'Entregada',
            self::CANCELLED => 'Cancelada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'text-yellow-400 bg-yellow-500/10',
            self::IN_PROGRESS => 'text-blue-400 bg-blue-500/10',
            self::COMPLETED => 'text-green-400 bg-green-500/10',
            self::DELIVERED => 'text-purple-400 bg-purple-500/10',
            self::CANCELLED => 'text-red-400 bg-red-500/10',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->label(),
        ])->toArray();
    }
}
