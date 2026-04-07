<?php

namespace App\Enums;

enum ProductsCategory: string
{
    case OIL = 'aceite';
    case FILTER = 'filtro';
    case SPARK_PLUG = 'bujia';
    case BRAKE = 'freno';
    case COOLANT = 'refrigerante';
    case BELT = 'correa';
    case BATTERY = 'bateria';
    case TIRE = 'neumatico';
    case OTHER = 'otro';

    public function label(): string
    {
        return match ($this) {
            self::OIL => 'Aceites',
            self::FILTER => 'Filtros',
            self::SPARK_PLUG => 'Bujías',
            self::BRAKE => 'Freno',
            self::COOLANT => 'Refrigerante',
            self::BELT => 'Correa',
            self::BATTERY => 'Bateria',
            self::TIRE => 'Neumáticos',
            self::OTHER => 'Otro',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->label(),
        ])->toArray();
    }
}
