<?php

namespace Tests\Feature;

use App\Models\InventoryMovement;
use App\Models\SparePart;
use App\Models\User;
use App\Models\WorkOrder;
use App\Services\WorkOrderManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Exception;

class WorkOrderInventoryTest extends TestCase
{
    use RefreshDatabase;

    protected WorkOrderManager $manager;
    protected User $user;
    protected WorkOrder $order;
    protected SparePart $sparePart;

    /**
     * Este método equivale al 'beforeEach':
     * Se ejecuta automáticamente ANTES de cada prueba.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = app(WorkOrderManager::class);

        // Creamos y autenticamos un usuario
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->user);

        // Creamos la orden y el repuesto de prueba
        $this->order = WorkOrder::factory()->create([
            'status' => 'pending',
            'total'  => 0,
        ]);

        $this->sparePart = SparePart::factory()->create([
            'name'          => 'Filtro de Aceite',
            'stock'         => 10,
            'minimum_stock' => 2,
        ]);
    }

    /** @test */
    public function descuenta_stock_y_registra_movimiento_al_agregar_repuesto()
    {
        // 1. Agregar 3 unidades
        $this->manager->addSpareParts($this->order, $this->sparePart->id, 3, 150.00);

        // 2. Verificar que el stock bajó a 7
        $this->assertEquals(7, $this->sparePart->fresh()->stock);

        // 3. Verificar que la orden recalculó su total (3 * $150 = $450)
        $this->assertEquals(450.00, (float) $this->order->fresh()->total);

        // 4. Verificar el registro en la bitácora de inventario (salida)
        $this->assertDatabaseHas('inventory_movements', [
            'spare_part_id' => $this->sparePart->id,
            'type'          => 'out',
            'quantity'      => 3,
            'reference'     => $this->order->order_number,
        ]);
    }

    /** @test */
    public function lanza_excepcion_si_el_stock_es_insuficiente()
    {
        // Indicamos que esperamos que esta función lance una excepción de error
        $this->expectException(Exception::class);

        // Intentamos agregar 15 unidades cuando solo hay 10
        $this->manager->addSpareParts($this->order, $this->sparePart->id, 15, 150.00);
    }

    /** @test */
    public function repon_stock_y_registra_movimiento_al_quitar_repuesto()
    {
        // Agregamos primero 4 piezas
        $this->manager->addSpareParts($this->order, $this->sparePart->id, 4, 100.00);
        $this->assertEquals(6, $this->sparePart->fresh()->stock);

        // Removemos la pieza de la orden
        $this->manager->removeSparePart($this->order, $this->sparePart->id);

        // 1. Verificar que el stock regresó a 10
        $this->assertEquals(10, $this->sparePart->fresh()->stock);

        // 2. Verificar que el total de la orden volvió a 0
        $this->assertEquals(0.00, (float) $this->order->fresh()->total);

        // 3. Verificar el registro en la bitácora de inventario (entrada)
        $this->assertDatabaseHas('inventory_movements', [
            'spare_part_id' => $this->sparePart->id,
            'type'          => 'in',
            'quantity'      => 4,
            'reason'        => "Removido de la orden {$this->order->order_number}",
        ]);
    }
}
