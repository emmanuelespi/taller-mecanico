<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Poblar la base de datos con datos de demostración.
     *
     * Orden de ejecución (respeta foreign keys):
     *   1. Users        — roles: admin, recepcionista, mecánico
     *   2. Clients      — 15 clientes de distintas ciudades
     *   3. Vehicles     — 24 vehículos asignados a los clientes
     *   4. Services     — 23 servicios del catálogo mecánico
     *   5. SpareParts   — 25 repuestos con movimientos de inventario inicial
     *   6. WorkOrders   — 16 órdenes en distintos estados (historial + activas)
     */
    public function run(): void
    {
        // Deshabilitar foreign key checks para permitir truncar tablas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            VehicleSeeder::class,
            ServiceSeeder::class,
            SparePartSeeder::class,
            WorkOrderSeeder::class,
        ]);

        // Enviar notificaciones de demostración para inicializar el header
        $recepcionists = \App\Models\User::whereIn('role', ['admin', 'recepcionista'])->get();
        $lowStockPart1 = \App\Models\SparePart::where('sku', 'ACE-DH-500')->first();
        $lowStockPart2 = \App\Models\SparePart::where('sku', 'FRE-DISC-DEL')->first();
        $completedOrder = \App\Models\WorkOrder::where('status', 'completed')->first();

        if ($lowStockPart1) {
            \Illuminate\Support\Facades\Notification::send($recepcionists, new \App\Notifications\LowStockNotification($lowStockPart1));
        }
        if ($lowStockPart2) {
            \Illuminate\Support\Facades\Notification::send($recepcionists, new \App\Notifications\LowStockNotification($lowStockPart2));
        }
        if ($completedOrder) {
            \Illuminate\Support\Facades\Notification::send($recepcionists, new \App\Notifications\OrderCompletedNotification($completedOrder));
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
