<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Asigna vehículos a los 15 clientes por ID posicional
        $clientIds = Client::orderBy('id')->pluck('id')->toArray();

        $vehicles = [
            // Cliente 1 — Roberto García (2 vehículos)
            ['client_index' => 0, 'plate' => 'ABC-123-A', 'brand' => 'Toyota',    'model' => 'Corolla',    'year' => 2019, 'color' => 'Blanco'],
            ['client_index' => 0, 'plate' => 'XYZ-789-B', 'brand' => 'Nissan',    'model' => 'Frontier',   'year' => 2016, 'color' => 'Gris Oscuro'],

            // Cliente 2 — María González (1 vehículo)
            ['client_index' => 1, 'plate' => 'DEF-456-A', 'brand' => 'Chevrolet', 'model' => 'Aveo',       'year' => 2020, 'color' => 'Rojo'],

            // Cliente 3 — Fernando Pérez (2 vehículos)
            ['client_index' => 2, 'plate' => 'GHI-012-C', 'brand' => 'Honda',     'model' => 'CR-V',       'year' => 2021, 'color' => 'Negro'],
            ['client_index' => 2, 'plate' => 'JKL-345-D', 'brand' => 'Ford',      'model' => 'F-150',      'year' => 2018, 'color' => 'Azul Marino'],

            // Cliente 4 — Ana Morales (1 vehículo)
            ['client_index' => 3, 'plate' => 'MNO-678-E', 'brand' => 'Volkswagen','model' => 'Jetta',      'year' => 2017, 'color' => 'Plata'],

            // Cliente 5 — Luis Rodríguez (3 vehículos)
            ['client_index' => 4, 'plate' => 'PQR-901-F', 'brand' => 'Toyota',    'model' => 'Hilux',      'year' => 2022, 'color' => 'Blanco'],
            ['client_index' => 4, 'plate' => 'STU-234-G', 'brand' => 'BMW',       'model' => 'Serie 3',    'year' => 2020, 'color' => 'Negro'],
            ['client_index' => 4, 'plate' => 'VWX-567-H', 'brand' => 'Audi',      'model' => 'A4',         'year' => 2019, 'color' => 'Gris Plata'],

            // Cliente 6 — Carmen Hernández (1 vehículo)
            ['client_index' => 5, 'plate' => 'YZA-890-I', 'brand' => 'Nissan',    'model' => 'Sentra',     'year' => 2021, 'color' => 'Blanco Perla'],

            // Cliente 7 — Jorge López (2 vehículos)
            ['client_index' => 6, 'plate' => 'BCD-123-J', 'brand' => 'Ford',      'model' => 'Escape',     'year' => 2018, 'color' => 'Verde Oscuro'],
            ['client_index' => 6, 'plate' => 'EFG-456-K', 'brand' => 'Chevrolet', 'model' => 'Silverado',  'year' => 2015, 'color' => 'Rojo Vino'],

            // Cliente 8 — Elena Torres (1 vehículo)
            ['client_index' => 7, 'plate' => 'HIJ-789-L', 'brand' => 'Kia',       'model' => 'Sportage',   'year' => 2022, 'color' => 'Azul Cobalto'],

            // Cliente 9 — Ricardo Martínez (2 vehículos)
            ['client_index' => 8, 'plate' => 'KLM-012-M', 'brand' => 'Mercedes',  'model' => 'Clase C',    'year' => 2020, 'color' => 'Negro'],
            ['client_index' => 8, 'plate' => 'NOP-345-N', 'brand' => 'Toyota',    'model' => 'Land Cruiser','year' => 2017, 'color' => 'Blanco'],

            // Cliente 10 — Diana Ramírez (1 vehículo)
            ['client_index' => 9, 'plate' => 'QRS-678-O', 'brand' => 'Honda',     'model' => 'Civic',      'year' => 2023, 'color' => 'Gris Lunar'],

            // Cliente 11 — Alejandro Cruz (1 vehículo)
            ['client_index' => 10,'plate' => 'TUV-901-P', 'brand' => 'Mazda',     'model' => 'CX-5',       'year' => 2019, 'color' => 'Rojo Soul'],

            // Cliente 12 — Sofía Vargas (2 vehículos)
            ['client_index' => 11,'plate' => 'WXY-234-Q', 'brand' => 'Nissan',    'model' => 'March',      'year' => 2021, 'color' => 'Amarillo'],
            ['client_index' => 11,'plate' => 'ZAB-567-R', 'brand' => 'Toyota',    'model' => 'RAV4',       'year' => 2018, 'color' => 'Plata'],

            // Cliente 13 — Manuel Jiménez (1 vehículo)
            ['client_index' => 12,'plate' => 'CDE-890-S', 'brand' => 'Jeep',      'model' => 'Wrangler',   'year' => 2016, 'color' => 'Verde Militar'],

            // Cliente 14 — Valeria Moreno (1 vehículo)
            ['client_index' => 13,'plate' => 'FGH-123-T', 'brand' => 'Hyundai',   'model' => 'Tucson',     'year' => 2022, 'color' => 'Azul Zafiro'],

            // Cliente 15 — Héctor Gutiérrez (2 vehículos)
            ['client_index' => 14,'plate' => 'IJK-456-U', 'brand' => 'Ford',      'model' => 'Mustang',    'year' => 2020, 'color' => 'Rojo Rubi'],
            ['client_index' => 14,'plate' => 'LMN-789-V', 'brand' => 'Chevrolet', 'model' => 'Traverse',   'year' => 2019, 'color' => 'Gris Grafito'],
        ];

        foreach ($vehicles as $v) {
            Vehicle::create([
                'client_id' => $clientIds[$v['client_index']],
                'plate'     => $v['plate'],
                'brand'     => $v['brand'],
                'model'     => $v['model'],
                'year'      => $v['year'],
                'color'     => $v['color'],
            ]);
        }
    }
}
