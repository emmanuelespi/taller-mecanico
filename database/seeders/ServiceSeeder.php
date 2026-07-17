<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // ── Afinación Menor ───────────────────────────────────────
            [
                'name'        => 'Afinación Menor',
                'description' => 'Cambio de aceite de motor, filtro de aceite, filtro de aire, revisión de niveles (refrigerante, frenos, dirección), inspección visual general.',
                'price'       => 650.00,
                'active'      => true,
            ],
            [
                'name'        => 'Cambio de Aceite y Filtro',
                'description' => 'Cambio de aceite de motor con filtro de aceite. Incluye hasta 5 litros de aceite semisintético.',
                'price'       => 380.00,
                'active'      => true,
            ],
            [
                'name'        => 'Revisión de Niveles',
                'description' => 'Revisión y ajuste de niveles de aceite, refrigerante, líquido de frenos, dirección hidráulica y limpiaparabrisas.',
                'price'       => 120.00,
                'active'      => true,
            ],

            // ── Afinación Mayor ───────────────────────────────────────
            [
                'name'        => 'Afinación Mayor Gasolina',
                'description' => 'Servicio completo: cambio de bujías, cables de bujías, filtro de aire, filtro de combustible, aceite y filtro de aceite, revisión de correas, limpieza de inyectores y diagnóstico electrónico.',
                'price'       => 1850.00,
                'active'      => true,
            ],
            [
                'name'        => 'Afinación Mayor Diesel',
                'description' => 'Servicio completo para motores diesel: cambio de filtros (aire, combustible, aceite), purga de inyectores, revisión de turbo y diagnóstico electrónico.',
                'price'       => 2200.00,
                'active'      => true,
            ],
            [
                'name'        => 'Cambio de Correa de Distribución',
                'description' => 'Cambio de correa de distribución y kit completo (tensor, polea y bomba de agua). Incluye inspección de sello de árbol de levas.',
                'price'       => 2800.00,
                'active'      => true,
            ],

            // ── Sistema de Frenos ─────────────────────────────────────
            [
                'name'        => 'Cambio de Pastillas de Freno Delanteras',
                'description' => 'Cambio de pastillas de freno delanteras. Incluye limpieza y lubricación de calibradores, revisión de discos.',
                'price'       => 680.00,
                'active'      => true,
            ],
            [
                'name'        => 'Cambio de Pastillas de Freno Traseras',
                'description' => 'Cambio de pastillas de freno traseras. Incluye limpieza de tambores o revisión de discos según modelo.',
                'price'       => 620.00,
                'active'      => true,
            ],
            [
                'name'        => 'Cambio de Discos y Pastillas (Eje Completo)',
                'description' => 'Sustitución de discos de freno y pastillas en un eje (delantero o trasero). Incluye calibración.',
                'price'       => 1650.00,
                'active'      => true,
            ],
            [
                'name'        => 'Purgado y Cambio de Líquido de Frenos',
                'description' => 'Purga completa del sistema de frenos y sustitución por líquido DOT 4 nuevo.',
                'price'       => 280.00,
                'active'      => true,
            ],

            // ── Sistema de Suspensión ─────────────────────────────────
            [
                'name'        => 'Cambio de Amortiguadores Delanteros',
                'description' => 'Sustitución de amortiguadores delanteros. Incluye revisión de rines, tornillos y geometría básica.',
                'price'       => 1200.00,
                'active'      => true,
            ],
            [
                'name'        => 'Cambio de Amortiguadores Traseros',
                'description' => 'Sustitución de amortiguadores traseros.',
                'price'       => 950.00,
                'active'      => true,
            ],
            [
                'name'        => 'Alineación y Balanceo (4 Ruedas)',
                'description' => 'Alineación computarizada de 4 ruedas y balanceo de neumáticos. Incluye revisión de presión.',
                'price'       => 450.00,
                'active'      => true,
            ],

            // ── Motor ─────────────────────────────────────────────────
            [
                'name'        => 'Diagnóstico Electrónico',
                'description' => 'Lectura y análisis de códigos de falla con scanner OBD-II. Reporte detallado de fallas activas y pendientes.',
                'price'       => 350.00,
                'active'      => true,
            ],
            [
                'name'        => 'Limpieza de Inyectores (Ultrasónico)',
                'description' => 'Limpieza de inyectores de combustible por ultrasonido. Incluye prueba de caudal pre y post limpieza.',
                'price'       => 950.00,
                'active'      => true,
            ],
            [
                'name'        => 'Cambio de Bomba de Agua',
                'description' => 'Sustitución de bomba de agua del sistema de enfriamiento. Incluye cambio de mangueras si es necesario.',
                'price'       => 1100.00,
                'active'      => true,
            ],
            [
                'name'        => 'Cambio de Termostato',
                'description' => 'Sustitución de termostato del sistema de enfriamiento y revisión de mangueras.',
                'price'       => 420.00,
                'active'      => true,
            ],

            // ── Sistema Eléctrico ─────────────────────────────────────
            [
                'name'        => 'Revisión Sistema Eléctrico',
                'description' => 'Diagnóstico completo del sistema eléctrico: batería, alternador, motor de arranque, luces y fusibles.',
                'price'       => 300.00,
                'active'      => true,
            ],
            [
                'name'        => 'Cambio de Batería',
                'description' => 'Sustitución de batería de arranque. Incluye prueba del sistema de carga y diagnóstico de alternador.',
                'price'       => 250.00, // mano de obra, batería aparte
                'active'      => true,
            ],

            // ── Transmisión ───────────────────────────────────────────
            [
                'name'        => 'Cambio de Aceite de Transmisión Manual',
                'description' => 'Cambio de aceite de caja de velocidades manual y revisión de fugas.',
                'price'       => 480.00,
                'active'      => true,
            ],
            [
                'name'        => 'Cambio de Aceite de Transmisión Automática',
                'description' => 'Cambio de aceite ATF de transmisión automática. Incluye revisión de fugas y comportamiento en cambios.',
                'price'       => 750.00,
                'active'      => true,
            ],

            // ── Servicio de Clutch ────────────────────────────────────
            [
                'name'        => 'Cambio de Kit de Clutch',
                'description' => 'Sustitución completa del kit de embrague: disco, prensa y collarín. Incluye revisión del volante.',
                'price'       => 3200.00,
                'active'      => true,
            ],

            // ── Servicio Inactivo (para demo) ─────────────────────────
            [
                'name'        => 'Instalación de Autoestéreo',
                'description' => 'Instalación de radio/estéreo de auto con kit de adaptación.',
                'price'       => 400.00,
                'active'      => false, // servicio ya no ofrecido
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
