<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Services\ServiceManager;
use Livewire\Component;

class ServiceIndex extends Component
{
    public function render()
    {
        $serviceManager = new ServiceManager;
        $services = $serviceManager->getAll($this->search, $this->showOnlyActive);

        return view('livewire.services.service-index', [
            'services' => $services,
        ]);
    }

    public function deleteService(): void
    {
        try {
            $service = Service::findOrFail($this->deleteId);
            (new ServiceManager)->delete($service);

            $this->showConfirmModal = false;
            $this->deleteId = null;

            $this->dispatch('notify', message: 'Servicio eliminado correctamente');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function toggleActive(int $id): void
    {
        $service = Service::findOrFail($id);
        (new ServiceManager)->toggleActive($service);

        $status = $service->active ? 'activado' : 'desactivado';
        $this->dispatch('notify', message: "Servicio {$status} correctamente");
    }
}
