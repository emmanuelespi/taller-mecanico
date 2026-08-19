<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Services\WorkOrderManager;
use Illuminate\Http\Request;

class WorkOrderApiController extends Controller
{
    protected WorkOrderManager $workOrderManager;

    public function __construct(WorkOrderManager $workOrderManager)
    {
        $this->workOrderManager = $workOrderManager;
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        $orders = $this->workOrderManager->getAll($search, $status, $dateFrom, $dateTo);

        return response()->json($orders);
    }

    public function stats()
    {
        return response()->json($this->workOrderManager->getStatistics());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'user_id' => 'nullable|exists:users,id',
            'description' => 'required|string',
            'status' => 'nullable|string',
        ]);

        $validated['problem_description'] = $validated['description'];
        unset($validated['description']);

        try {
            $order = $this->workOrderManager->create($validated);

            return response()->json([
                'message' => 'Orden creada correctamente',
                'order' => $order->load(['client', 'vehicle', 'mechanic']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la orden',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function changeStatus(Request $request, WorkOrder $order)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        try {
            $updatedOrder = $this->workOrderManager->changeStatus($order, $request->status);

            return response()->json([
                'message' => 'Estado actualizado exitosamente',
                'order' => $updatedOrder,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cambiar de estado',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function addSparePart(Request $request, WorkOrder $order)
    {
        $request->validate([
            'part_id' => 'required|integer|exists:spare_parts,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0'
        ]);

        try {
            $upatedOrder = $this->workOrderManager->addSpareParts(
                $order,
                $request->part_id,
                $request->quantity,
                $request->unit_price
            );

            return response()->json([
                'message' => 'Repuesto agregado con exito',
                'order' => $order->load('spareParts')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudo entregar la refacción',
                'order' => $e->getMessage()
            ], 422);
        }
    }

    public function removeSpartPart(WorkOrder $order, int $partId)
    {
        try {
            $this->workOrderManager->removeSparePart($order, $partId);

            return response()->json([
                'message' => 'Repuesto removido de la orden con éxito',
                'order' => $order->fresh(['spareParts', 'services'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al remover la refacción',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function addService(Request $request, WorkOrder $order)
    {
        $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'unit_price' => 'required|numeric|min:0',
            'quantity'   => 'nullable|integer|min:1'
        ]);

        try {
            $this->workOrderManager->addService(
                $order,
                $request->service_id,
                $request->unit_price,
                $request->get('quantity', 1)
            );

            return response()->json([
                'message' => 'Servicio agregado con éxito',
                'order'   => $order->fresh(['spareParts', 'services'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudo agregar el servicio',
                'error'   => $e->getMessage()
            ], 422);
        }
    }

    public function removeService(WorkOrder $order, int $serviceId)
    {
        try {
            $this->workOrderManager->removeService($order, $serviceId);

            return response()->json([
                'message' => 'Servicio removido con éxito',
                'order'   => $order->fresh(['spareParts', 'services'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al remover el servicio',
                'error'   => $e->getMessage()
            ], 422);
        }
    }
}
