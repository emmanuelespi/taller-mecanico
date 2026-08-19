<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class WorkOrderSeeder extends Seeder
{
    public function run(): void
    {
        $admin       = User::where('role', 'admin')->first();
        $mechanics   = User::where('role', 'mecanico')->where('is_active', true)->get();
        $vehicles    = Vehicle::with('client')->get();
        $services    = Service::where('active', true)->get()->unique('name')->keyBy('name');
        $parts       = SparePart::where('is_active', true)->get()->keyBy('sku');

        // Helper para generar número de orden
        $orderNum = function (string $date) use (&$counter) {
            static $counters = [];
            [$y, $m] = explode('-', substr($date, 0, 7));
            $key = $y . $m;
            $counters[$key] = ($counters[$key] ?? 0) + 1;
            return "OT-{$y}{$m}-" . str_pad($counters[$key], 4, '0', STR_PAD_LEFT);
        };

        // Helper para obtener vehículo por placa
        $byPlate = fn($plate) => $vehicles->firstWhere('plate', $plate);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // ÓRDENES ENTREGADAS (historial — 3 meses atrás)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

        $this->createOrder([
            'order_number'       => $orderNum('2026-04-10'),
            'vehicle'            => $byPlate('ABC-123-A'),
            'mechanic'           => $mechanics[0],
            'created_by'         => $admin,
            'status'             => 'delivered',
            'problem_description' => 'Cliente reporta motor con tirones y consumo de combustible elevado.',
            'diagnosis'          => 'Bujías desgastadas y filtro de aire saturado. Se realiza afinación menor.',
            'observations'       => 'Se recomienda cambio de correa de distribución en próxima visita (km 85,000).',
            'payment_status'     => 'paid',
            'payment_method'     => 'efectivo',
            'entry_date'         => '2026-04-10 09:00:00',
            'delivery_date'      => '2026-04-10 18:00:00',
            'completed_at'       => '2026-04-10 16:30:00',
            'delivered_at'       => '2026-04-10 18:00:00',
            'services_list'      => [
                ['name' => 'Afinación Menor', 'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'ACE-5W30-1L',   'qty' => 4],
                ['sku' => 'FIL-ACE-TN',    'qty' => 1],
                ['sku' => 'FIL-AIRE-GEN',  'qty' => 1],
            ],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-04-15'),
            'vehicle'            => $byPlate('DEF-456-A'),
            'mechanic'           => $mechanics[1],
            'created_by'         => $admin,
            'status'             => 'delivered',
            'problem_description' => 'Frenos hacen ruido al frenar, pedal esponjoso.',
            'diagnosis'          => 'Pastillas delanteras muy desgastadas y líquido de frenos contaminado.',
            'observations'       => 'Discos en buen estado, se limpian y lubrican calibradores.',
            'payment_status'     => 'paid',
            'payment_method'     => 'tarjeta',
            'entry_date'         => '2026-04-15 10:00:00',
            'delivery_date'      => '2026-04-15 17:00:00',
            'completed_at'       => '2026-04-15 15:00:00',
            'delivered_at'       => '2026-04-15 17:00:00',
            'services_list'      => [
                ['name' => 'Cambio de Pastillas de Freno Delanteras', 'qty' => 1],
                ['name' => 'Purgado y Cambio de Líquido de Frenos',   'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'FRE-PAS-DEL-BRE', 'qty' => 1],
                ['sku' => 'FRE-LIQ-DOT4',    'qty' => 1],
            ],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-05-03'),
            'vehicle'            => $byPlate('GHI-012-C'),
            'mechanic'           => $mechanics[2],
            'created_by'         => $admin,
            'status'             => 'delivered',
            'problem_description' => 'Luz de check engine encendida, motor con vibración en ralentí.',
            'diagnosis'          => 'Código P0301: fallo de encendido en cilindro 1. Bujías desgastadas e inyector sucio.',
            'observations'       => 'Se realiza afinación mayor completa. Vehículo entregado sin fallos.',
            'payment_status'     => 'paid',
            'payment_method'     => 'transferencia',
            'entry_date'         => '2026-05-03 08:30:00',
            'delivery_date'      => '2026-05-04 18:00:00',
            'completed_at'       => '2026-05-04 16:00:00',
            'delivered_at'       => '2026-05-04 18:00:00',
            'services_list'      => [
                ['name' => 'Diagnóstico Electrónico',         'qty' => 1],
                ['name' => 'Afinación Mayor Gasolina',        'qty' => 1],
                ['name' => 'Limpieza de Inyectores (Ultrasónico)', 'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'BUJ-NGK-IR4',   'qty' => 1],
                ['sku' => 'FIL-ACE-TN',    'qty' => 1],
                ['sku' => 'FIL-AIRE-GEN',  'qty' => 1],
                ['sku' => 'FIL-COMB-UNI',  'qty' => 1],
                ['sku' => 'ACE-5W30-1L',   'qty' => 4],
            ],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-05-20'),
            'vehicle'            => $byPlate('YZA-890-I'),
            'mechanic'           => $mechanics[0],
            'created_by'         => $admin,
            'status'             => 'delivered',
            'problem_description' => 'Servicio de mantenimiento preventivo de 10,000 km.',
            'diagnosis'          => 'Vehículo en buen estado general. Se realiza cambio de aceite y filtros.',
            'observations'       => null,
            'payment_status'     => 'paid',
            'payment_method'     => 'efectivo',
            'entry_date'         => '2026-05-20 09:00:00',
            'delivery_date'      => '2026-05-20 14:00:00',
            'completed_at'       => '2026-05-20 12:00:00',
            'delivered_at'       => '2026-05-20 14:00:00',
            'services_list'      => [
                ['name' => 'Cambio de Aceite y Filtro', 'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'ACE-10W40-1L', 'qty' => 4],
                ['sku' => 'FIL-ACE-TN',   'qty' => 1],
            ],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-06-02'),
            'vehicle'            => $byPlate('JKL-345-D'),
            'mechanic'           => $mechanics[1],
            'created_by'         => $admin,
            'status'             => 'delivered',
            'problem_description' => 'Vehículo recalentando. Indicador de temperatura llega al máximo.',
            'diagnosis'          => 'Termostato atascado en posición cerrada. Mangueras en buen estado.',
            'observations'       => 'Se recomienda revisar tapa de radiador en próximo servicio.',
            'payment_status'     => 'paid',
            'payment_method'     => 'tarjeta',
            'entry_date'         => '2026-06-02 11:00:00',
            'delivery_date'      => '2026-06-02 18:00:00',
            'completed_at'       => '2026-06-02 16:00:00',
            'delivered_at'       => '2026-06-02 18:00:00',
            'services_list'      => [
                ['name' => 'Cambio de Termostato', 'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'REF-VRD-1L', 'qty' => 2],
            ],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-06-15'),
            'vehicle'            => $byPlate('KLM-012-M'),
            'mechanic'           => $mechanics[2],
            'created_by'         => $admin,
            'status'             => 'delivered',
            'problem_description' => 'Vibración fuerte en volante a alta velocidad y ruido en suspensión delantera.',
            'diagnosis'          => 'Amortiguadores delanteros vencidos. Rueda delantera izquierda desbalanceada.',
            'observations'       => 'Se realiza alineación y balanceo completo tras cambio de amortiguadores.',
            'payment_status'     => 'paid',
            'payment_method'     => 'transferencia',
            'entry_date'         => '2026-06-15 09:00:00',
            'delivery_date'      => '2026-06-16 17:00:00',
            'completed_at'       => '2026-06-16 15:00:00',
            'delivered_at'       => '2026-06-16 17:00:00',
            'services_list'      => [
                ['name' => 'Cambio de Amortiguadores Delanteros',  'qty' => 1],
                ['name' => 'Alineación y Balanceo (4 Ruedas)',     'qty' => 1],
            ],
            'parts_list'         => [],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-06-28'),
            'vehicle'            => $byPlate('PQR-901-F'),
            'mechanic'           => $mechanics[0],
            'created_by'         => $admin,
            'status'             => 'delivered',
            'problem_description' => 'Batería descargada, vehículo no enciende.',
            'diagnosis'          => 'Batería agotada (3 años de uso). Alternador en perfecto estado.',
            'observations'       => null,
            'payment_status'     => 'paid',
            'payment_method'     => 'efectivo',
            'entry_date'         => '2026-06-28 10:00:00',
            'delivery_date'      => '2026-06-28 13:00:00',
            'completed_at'       => '2026-06-28 12:00:00',
            'delivered_at'       => '2026-06-28 13:00:00',
            'services_list'      => [
                ['name' => 'Cambio de Batería', 'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'BAT-BSH-65', 'qty' => 1],
            ],
        ], $services, $parts);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // ÓRDENES COMPLETADAS (listas para entrega)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

        $this->createOrder([
            'order_number'       => $orderNum('2026-07-10'),
            'vehicle'            => $byPlate('STU-234-G'),
            'mechanic'           => $mechanics[1],
            'created_by'         => $admin,
            'status'             => 'completed',
            'problem_description' => 'Servicio de afinación mayor programado a 60,000 km.',
            'diagnosis'          => 'Bujías con desgaste, filtros saturados. Correa de distribución en límite de vida útil.',
            'observations'       => 'Se cambia correa de distribución preventivamente. Vehículo listo para entrega.',
            'payment_status'     => 'pending',
            'payment_method'     => null,
            'entry_date'         => '2026-07-10 08:00:00',
            'delivery_date'      => '2026-07-12 18:00:00',
            'completed_at'       => '2026-07-12 15:00:00',
            'delivered_at'       => null,
            'services_list'      => [
                ['name' => 'Afinación Mayor Gasolina',       'qty' => 1],
                ['name' => 'Cambio de Correa de Distribución', 'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'BUJ-BSH-PT4',  'qty' => 1],
                ['sku' => 'FIL-ACE-TN',   'qty' => 1],
                ['sku' => 'FIL-AIRE-GEN', 'qty' => 1],
                ['sku' => 'ACE-5W30-1L',  'qty' => 5],
                ['sku' => 'COR-DIST-TOY', 'qty' => 1],
            ],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-07-14'),
            'vehicle'            => $byPlate('QRS-678-O'),
            'mechanic'           => $mechanics[2],
            'created_by'         => $admin,
            'status'             => 'completed',
            'problem_description' => 'Cambio de pastillas y revisión general de frenos.',
            'diagnosis'          => 'Pastillas traseras al límite. Delanteras al 40%. Se cambian traseras y limpian calibradores.',
            'observations'       => 'Discos en buenas condiciones. Próximo cambio de pastillas delanteras en ~15,000 km.',
            'payment_status'     => 'paid',
            'payment_method'     => 'tarjeta',
            'entry_date'         => '2026-07-14 09:00:00',
            'delivery_date'      => '2026-07-14 17:00:00',
            'completed_at'       => '2026-07-14 14:00:00',
            'delivered_at'       => null,
            'services_list'      => [
                ['name' => 'Cambio de Pastillas de Freno Traseras', 'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'FRE-PAS-TRA-GEN', 'qty' => 1],
                ['sku' => 'FRE-LIQ-DOT4',    'qty' => 1],
            ],
        ], $services, $parts);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // ÓRDENES EN PROGRESO (trabajo activo)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

        $this->createOrder([
            'order_number'       => $orderNum('2026-07-16'),
            'vehicle'            => $byPlate('NOP-345-N'),
            'mechanic'           => $mechanics[0],
            'created_by'         => $admin,
            'status'             => 'in_progress',
            'problem_description' => 'Ruido metálico al acelerar desde bajo régimen. Posible falla en distribución.',
            'diagnosis'          => 'Se detecta holgura en cadena de distribución. En revisión.',
            'observations'       => null,
            'payment_status'     => 'pending',
            'payment_method'     => null,
            'entry_date'         => '2026-07-16 08:00:00',
            'delivery_date'      => '2026-07-18 18:00:00',
            'completed_at'       => null,
            'delivered_at'       => null,
            'services_list'      => [
                ['name' => 'Diagnóstico Electrónico', 'qty' => 1],
            ],
            'parts_list'         => [],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-07-16'),
            'vehicle'            => $byPlate('HIJ-789-L'),
            'mechanic'           => $mechanics[1],
            'created_by'         => $admin,
            'status'             => 'in_progress',
            'problem_description' => 'Servicio completo de frenos y cambio de aceite.',
            'diagnosis'          => 'Frenos delanteros y traseros al límite. Aceite muy oscuro y contaminado.',
            'observations'       => null,
            'payment_status'     => 'pending',
            'payment_method'     => null,
            'entry_date'         => '2026-07-16 10:00:00',
            'delivery_date'      => '2026-07-17 17:00:00',
            'completed_at'       => null,
            'delivered_at'       => null,
            'services_list'      => [
                ['name' => 'Cambio de Pastillas de Freno Delanteras', 'qty' => 1],
                ['name' => 'Cambio de Pastillas de Freno Traseras',   'qty' => 1],
                ['name' => 'Cambio de Aceite y Filtro',               'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'FRE-PAS-DEL-BRE',  'qty' => 1],
                ['sku' => 'FRE-PAS-TRA-GEN',  'qty' => 1],
                ['sku' => 'ACE-10W40-1L',      'qty' => 4],
                ['sku' => 'FIL-ACE-TN',        'qty' => 1],
            ],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-07-17'),
            'vehicle'            => $byPlate('BCD-123-J'),
            'mechanic'           => $mechanics[2],
            'created_by'         => $admin,
            'status'             => 'in_progress',
            'problem_description' => 'Vehículo no arranca a la primera, embrague duro.',
            'diagnosis'          => 'Kit de clutch desgastado. Motor de arranque débil, batería en proceso de verificación.',
            'observations'       => null,
            'payment_status'     => 'pending',
            'payment_method'     => null,
            'entry_date'         => '2026-07-17 08:30:00',
            'delivery_date'      => '2026-07-19 18:00:00',
            'completed_at'       => null,
            'delivered_at'       => null,
            'services_list'      => [
                ['name' => 'Diagnóstico Electrónico', 'qty' => 1],
                ['name' => 'Cambio de Kit de Clutch',  'qty' => 1],
            ],
            'parts_list'         => [],
        ], $services, $parts);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // ÓRDENES PENDIENTES (recién ingresadas)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

        $this->createOrder([
            'order_number'       => $orderNum('2026-07-17'),
            'vehicle'            => $byPlate('FGH-123-T'),
            'mechanic'           => null, // sin mecánico asignado aún
            'created_by'         => $admin,
            'status'             => 'pending',
            'problem_description' => 'Mantenimiento preventivo 20,000 km. Cambio de aceite, filtros y revisión general.',
            'diagnosis'          => null,
            'observations'       => null,
            'payment_status'     => 'pending',
            'payment_method'     => null,
            'entry_date'         => '2026-07-17 09:00:00',
            'delivery_date'      => '2026-07-17 18:00:00',
            'completed_at'       => null,
            'delivered_at'       => null,
            'services_list'      => [
                ['name' => 'Afinación Menor', 'qty' => 1],
            ],
            'parts_list'         => [
                ['sku' => 'ACE-5W30-1L', 'qty' => 4],
                ['sku' => 'FIL-ACE-TN',  'qty' => 1],
                ['sku' => 'FIL-AIRE-GEN', 'qty' => 1],
            ],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-07-17'),
            'vehicle'            => $byPlate('WXY-234-Q'),
            'mechanic'           => $mechanics[0],
            'created_by'         => $admin,
            'status'             => 'pending',
            'problem_description' => 'Luz de ABS encendida en tablero. Frenos responden pero con pulsación.',
            'diagnosis'          => null,
            'observations'       => null,
            'payment_status'     => 'pending',
            'payment_method'     => null,
            'entry_date'         => '2026-07-17 10:30:00',
            'delivery_date'      => '2026-07-18 18:00:00',
            'completed_at'       => null,
            'delivered_at'       => null,
            'services_list'      => [
                ['name' => 'Diagnóstico Electrónico', 'qty' => 1],
                ['name' => 'Revisión Sistema Eléctrico', 'qty' => 1],
            ],
            'parts_list'         => [],
        ], $services, $parts);

        $this->createOrder([
            'order_number'       => $orderNum('2026-07-17'),
            'vehicle'            => $byPlate('LMN-789-V'),
            'mechanic'           => null,
            'created_by'         => $admin,
            'status'             => 'pending',
            'problem_description' => 'Dirección hidráulica hace ruido al girar y hay pérdida de fluido.',
            'diagnosis'          => null,
            'observations'       => null,
            'payment_status'     => 'pending',
            'payment_method'     => null,
            'entry_date'         => '2026-07-17 11:00:00',
            'delivery_date'      => '2026-07-19 17:00:00',
            'completed_at'       => null,
            'delivered_at'       => null,
            'services_list'      => [],
            'parts_list'         => [
                ['sku' => 'ACE-DH-500', 'qty' => 1],
            ],
        ], $services, $parts);
    }

    // ──────────────────────────────────────────────────────────────────
    // Helper privado para crear una orden con todos sus detalles
    // ──────────────────────────────────────────────────────────────────
    private function createOrder(array $data, $services, $parts): WorkOrder
    {
        $vehicle = $data['vehicle'];

        $order = WorkOrder::create([
            'order_number'        => $data['order_number'],
            'client_id'           => $vehicle->client_id,
            'vehicle_id'          => $vehicle->id,
            'user_id'             => $data['created_by']->id,
            'mechanic_id'         => $data['mechanic']?->id,
            'status'              => $data['status'],
            'problem_description' => $data['problem_description'],
            'diagnosis'           => $data['diagnosis'],
            'observations'        => $data['observations'],
            'payment_status'      => $data['payment_status'],
            'payment_method'      => $data['payment_method'],
            'entry_date'          => $data['entry_date'],
            'delivery_date'       => $data['delivery_date'],
            'completed_at'        => $data['completed_at'],
            'delivered_at'        => $data['delivered_at'],
        ]);

        // Adjuntar servicios
        foreach ($data['services_list'] as $s) {
            $service = $services->get($s['name']);

            if ($service instanceof Collection) {
                $service = $service->first();
                # code...
            }
            if ($service) {
                $unitPrice = $service->price ?? $service->unit_price ?? 0;
                $order->services()->attach($service->id, [
                    'quantity'   => $s['qty'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $s['qty'] * $unitPrice,
                ]);
            }
        }

        // Adjuntar repuestos (sin descontar stock en seed — ya descontado en estados finales)
        foreach ($data['parts_list'] as $p) {
            $part = $parts->get($p['sku']);
            if ($part instanceof Collection) {
                $part = $part->first();
            }
            if ($part) {
                $unitPrice = $part->unit_price ?? $part->price ?? 0;
                $order->spareParts()->attach($part->id, [
                    'quantity'   => $p['qty'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $p['qty'] * $unitPrice,
                ]);
            }
        }

        // Calcular totales
        $order->load(['services', 'spareParts']);
        $order->calculateTotals();

        return $order;
    }
}
