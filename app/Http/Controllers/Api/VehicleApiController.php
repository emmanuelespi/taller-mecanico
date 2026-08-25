<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Services\VehicleService;
use Illuminate\Http\Request;

class VehicleApiController extends Controller
{
    protected VehicleService $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $vehicles = $this->vehicleService->getAll($search);

        return response()->json($vehicles);
    }

    public function showByPlate(string $plate)
    {
        $vehicle = $this->vehicleService->getVehicleByPlate($plate);

        if (!$vehicle) {
            return response()->json(['message' => 'Vehículo no encontrado'], 404);
        }

        $vehicle->load(['client', 'workOrders' => function ($q) {
            $q->latest();
        }]);

        $vehicle->has_active_orders = $this->vehicleService->hasActiveWorkOrders($vehicle);

        return response()->json($vehicle);
    }

    public function getByClient(int $clientId)
    {
        $vehicles = $this->vehicleService->getVehiclesByClient($clientId);

        return response()->json($vehicles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'plate' => 'required|string|max:20',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'nullable|integer',
            'color' => 'nullable|string|max:30',
        ]);

        try {
            $vehicle = $this->vehicleService->create($validated);
            return response()->json([
                'message' => 'Vehículo creado correctamente.',
                'vehicle' => $vehicle->load('client')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar el vehículo',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function show(int $id)
    {

        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json(['message' => 'Vehiculo no encontrado'], 404);
        }

        $vehicle->load(['client', 'workOrders' => function ($q) {
            $q->latest();
        }]);

        $vehicle->has_active_orders = $this->vehicleService->hasActiveWorkOrders($vehicle);

        return response()->json($vehicle);
    }

    public function update(Request $request, int $id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json(['message' => 'Vehículo no encontrado'], 404);
        }

        $validated = $request->validate([
            'plate' => 'sometimes|string|max:20',
            'brand' => 'sometimes|string|max:50',
            'model' => 'sometimes|string|max:50',
            'year'  => 'sometimes|nullable|integer',
            'color' => 'sometimes|nullable|string|max:30',
        ]);

        $vehicle->update($validated);

        // 🔍 SE AGREGA 'workOrders' Y 'has_active_orders' AL RETORNAR
        $vehicle->load(['client', 'workOrders' => function ($q) {
            $q->latest();
        }]);

        $vehicle->has_active_orders = $this->vehicleService->hasActiveWorkOrders($vehicle);

        return response()->json([
            'message' => 'Vehículo actualizado correctamente',
            'vehicle' => $vehicle
        ]);
    }
}
