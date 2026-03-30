<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Services\ServiceManager;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showOnlyActive = false;

    public bool $showConfirmModal = false;

    public ?int $deleteId = null;

    protected $queryString = ['search', 'showOnlyActive'];

    protected $listeners = [
        'serviceSaved' => 'refreshServices',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedShowOnlyActive(): void
    {
        $this->resetPage();
    }

    public function refreshService(): void
    {
        $this->resetPage();
    }

    public function OpenDeleteModal(int $id): void
    {
        $this->deleteId = $id;
        $this->showConfirmModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showConfirmModal = false;
        $this->deleteId = null;
    }

    public function render()
    {
        $serviceManager = new ServiceManager();
        $services = $serviceManager->getAll($this->search, $this->showOnlyActive);

        return view('livewire.services.service-index', [
            'services' => $services,
        ]);
    }

    public function deleteService(): void
    {
        try {
            $service = Service::findOrFail($this->deleteId);
            $serviceManager = new ServiceManager;
            $serviceManager->delete($service);

            $this->showConfirmModal = false;
            $this->deleteId = null;

            $this->dispatch('notify', message: 'Servicio eliminado correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function toggleActive(int $id): void
    {
        try {
            $service = Service::findOrFail($id);
            $serviceManager = new ServiceManager;
            $serviceManager->toggleActive($service);

            $status = $service->active ? 'activado' : 'desactivado';
            $this->dispatch('notify', message: "Sevicio {$status} correctamente");
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }
}
