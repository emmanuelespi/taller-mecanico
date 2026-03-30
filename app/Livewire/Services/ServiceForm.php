<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Services\ServiceManager;
use Livewire\Component;

class ServiceForm extends Component
{
    public bool $open = false;

    public ?int $serviceId = null;

    public string $name = '';

    public string $description = '';

    public string $price = '';

    public bool $active = true;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name,'.($this->serviceId ?? 'NULL'),
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'active' => 'boolean',
        ];
    }

    public function open(?int $serviceId = null): void
    {
        $this->restValidation();
        $this->reset();

        $this->serviceId = $serviceId;

        if ($serviceId) {
            $service = Service::findOrFail($serviceId);
            $this->name = $service->name;
            $this->description = $service->description ?? '';
            $this->price = (string) $service->price;
            $this->active = $service->active;
        }

        $this->open = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => (string) $this->price,
            'active' => $this->active,
        ];

        $serviceManager = new ServiceManager;

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

    public function close(): void
    {
        $this->open = false;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.services.service-form');
    }
}
