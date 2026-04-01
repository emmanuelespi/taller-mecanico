<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceManager
{
    public function getAll(string $search = '', bool $onlyActive = false): LengthAwarePaginator
    {
        $query = Service::query();

        if ($onlyActive) {
            $query->where('active', true);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name', 'asc')->paginate(10);
    }

    public function getActiveServices(): Collection
    {
        return Service::where('active', true)
            ->orderBy('name')
            ->get();
    }

    public function create(array $data): Service
    {
        return Service::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'active' => $data['active'] ?? true,
        ]);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'active' => $data['active'] ?? $service->active,
        ]);

        return $service;
    }

    public function delete(Service $service): void
    {
        if ($service->workOrders()->exists()) {
            throw new \Exception('No se puede eliminar un servicio que está siendo usado en órdenes de trabajo.');
        }

        $service->delete();
    }

    public function toggleActive(Service $service): Service
    {
        $service->active = ! $service->active;
        $service->save();

        return $service;
    }

    public function findById(int $id): ?Service
    {
        return Service::find($id);
    }

    public function findByWithTrashed(int $id): ?Service
    {
        return Service::withTrashed()->find($id);
    }

    public function restore(int $id): Service
    {
        $service = Service::withTrashed()->findOrFail($id);
        $service->restore();

        return $service;
    }

    public function forceDelete(int $id): void
    {
        $service = Service::withTrashed()->findOrFail($id);

        if ($service->workOrders()->exists()) {
            throw new \Exception('No se puede eliminar permanentemente un servicio con órdenes asociadas.');
        }

        $service->forceDelete();
    }
}
