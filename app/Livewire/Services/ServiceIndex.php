<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Services\ServiceManager;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $showOnlyActive = false;

    public $showConfirmModal = false;

    public $deleteId = null;

    protected $queryString = ['search', 'showOnlyActive'];

    protected $listeners = [
        'serviceSaved' => 'refreshServices',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedShowOnlyActive()
    {
        $this->resetPage();
    }

    public function refreshServices()
    {
        $this->resetPage();
    }

    public function openDeleteModal($id)
    {
        $this->deleteId = $id;
        $this->showConfirmModal = true;
    }

    public function cancelDelete()
    {
        $this->showConfirmModal = false;
        $this->deleteId = null;
    }

    public function deleteService()
    {
        try {
            $service = Service::findOrFail($this->deleteId);
            $manager = new ServiceManager();
            $manager->delete($service);

            $this->showConfirmModal = false;
            $this->deleteId = null;

            $this->dispatch('notify', message: 'Servicio eliminado correctamente');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function toggleActive($id)
    {
        try {
            $service = Service::findOfFail($id);
            $manager = new ServiceManager();
            $manager->toggleActive($service);

            $status = $service->active ? 'activado' : 'desactivado';
            $this->dispatch('notify', message: "Servicio {$status} correctamente");
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }

    }

    public function render()
    {
        $manager = new ServiceManager();
        $services = $manager->getAll($this->search, $this->showOnlyActive);

        return view('livewire.services.service-index', [
            'services' => $services,
        ]);
    }
}
