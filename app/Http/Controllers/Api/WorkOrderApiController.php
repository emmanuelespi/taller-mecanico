<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Services\WorkOrderManager;
use Illuminate\Http\Request;

class WorkOrderApiController extends Controller
{
    protected workOrderManager $workOrderManager;

    public function __construct(WorkOrderManager $workOrderManager)
    {
        $this->workOrderManager;
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

    public function show($id)
    {
        $order = WorkOrder::with(['client', 'vehicle', 'mechanic', 'services', 'spareParts'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Orden de trabajo no encontrada'], 400);
        }

        return response()->json($order);
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
            ]);
        }
    }

    public function addSparePart(Request $request, WorkOrder $order)
    {
        $request->validate([
            'part_id' => 'required|integer|exists:spare_part,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0'
        ]);

        try {
            $this->workOrderManager->addSpareParts(
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
}
