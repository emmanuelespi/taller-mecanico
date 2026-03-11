<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use App\Services\VehicleService;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleIndex extends Component
{
    use WithPagination;

    public bool $showConfirmModal = false;

    public ?int $deleteId = null;

    public string $search = '';

    protected $queryString = ['search'];

    protected $listeners = [
        'vehicleSalved' => '$refresh',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openDeleteModal(int $id): void
    {
        $this->deleteId = $id;
        $this->showConfirmModal = true;
    }

    public function deleteVehicle(): void
    {
        $vehicle = Vehicle::findOrFail($this->deleteId);
        (new VehicleService)->delete($vehicle);
        $this->showConfirmModal = false;
        $this->deleteId = null;
        $this->dispatch('notify', message: 'Vehiculo eliminado correctamente');
    }

    public function cancelDelete(): void
    {
        $this->showConfirmModal = false;
        $this->deleteId = null;
    }

    public function render()
    {
        return view('livewire.vehicles.vehicle-index', [
            'vehicles' => (new VehicleService)->getAll($this->search),
        ]);
    }
}
