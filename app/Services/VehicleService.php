<?php

namespace App\Services;

use App\Models\Vehicle;

class VechileService
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
                            ->orWhere('last_name', 'like', '%{search}%');
                    });
            })
            ->latest()
            ->paginate(10);
    }

    public function create(array $data): Vehicle
    {
        return Vehicle::create($data);
    }

    public function update(Vehicle $vehicle, array $data): Vehicle
    {
        $vehicle->update($data);

        return $vehicle;
    }

    public function delete(Vehicle $vehicle): void
    {
        $vehicle->delete();
    }
}
