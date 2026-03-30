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
            $query->active('active', true);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}")
                    ->orWhere('description', 'like', "%{$search}");
            });
        }

        return $query->orderBy('name')
            ->paginate(10);
    }

    public function getActiveServices(): Collection
    {
        return Service::active()
            ->orderBy('name')
            ->get();
    }

    public function create(array $data): Service
    {
        return Service::create($array);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update($data);

        return $service;
    }

    public function delete(Service $service): void
    {
        if ($service->workOrders()->exists()) {
            throw new \Exception('No se puede eliminar un servicio que está siendo usado en órdenes de trabajo');
        }

        $service->delete();
    }

    public function toggleActive(Service $service): Service
    {
        $service->active = ! $service->active;
        $service->save();

        return $service;
    }
}
