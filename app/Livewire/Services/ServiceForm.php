<?php

namespace App\Livewire\Services;

use App\Services\ServiceManager;
use Livewire\Component;

class ServiceForm extends Component
{
    public function save(): void
{
    $this->validate();

    $data = [
        'name' => $this->name,
        'description' => $this->description,
        'price' => (float) $this->price,
        'active' => $this->active,
    ];

    $serviceManager = new ServiceManager();

    if ($this->serviceId) {
        $service = Service::findOrFail($this->serviceId);
        $serviceManager->update($service, $data);
        $message = 'Servicio actualizado correctamente.';
    } else {
        $serviceManager->create($data);
        $message = 'Servicio creado correctamente.';
    }

    $this->open = false;
    $this->reset();
    $this->dispatch('serviceSaved')->to(ServiceIndex::class);
    $this->dispatch('notify', message: $message);
}
    public function render()
    {
        return view('livewire.services.service-form');
    }
}
