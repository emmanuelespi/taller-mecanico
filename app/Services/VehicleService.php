<?php

namespace App\Services;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VehicleService
{
    public function getAll(string $search = ''): LengthAwarePaginator
    {
        return Vehicle::query()
            ->with('client')
            ->when($search, function ($query, $search) {
                $query->where('plate', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10);
    }

    public function create(array $data): Vehicle
    {
        if (Vehicle::where('plate', $data['plate'])->exists()) {
            throw new \Exception('Ya existe un vehículo con esta placa');
        }

        return Vehicle::create($data);
    }

    public function update(Vehicle $vehicle, array $data): Vehicle
    {
        if (isset($data['plate']) && $data['plate'] !== $vehicle->plate) {
            if (Vehicle::where('plate', $data['plate'])->where('id', '!=', $vehicle->id)->exists()) {
                throw new \Exception('Ya existe otro vehículo con esta placa.');
            }
        }

        $vehicle->update($data);

        return $vehicle;
    }

    public function delete(Vehicle $vehicle): void
    {
        $vehicle->delete();
    }

    public function getVehiclesByClient(int $clientId): Collection
    {
        return Vehicle::where('client_id', $clientId)
            ->orderBy('plate')
            ->get();
    }

    public function getVehicleByPlate(string $plate): ?Vehicle
    {
        return Vehicle::where('plate', $plate)->first();
    }

    public function hasActiveWorkOrders(Vehicle $vehicle): bool
    {
        return $vehicle->workOrders()
            ->whereIn('status', ['pending', 'in_progress'])
            ->exists();
    }
}
