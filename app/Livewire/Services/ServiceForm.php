<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Services\ServiceManager;
use Livewire\Component;

class ServiceForm extends Component
{
    public $open = false;

    public $serviceId = null;

    public $name = '';

    public $description = '';

    public $price = '';

    public $active = true;

    protected $listeners = [
        'openModal' => 'openModal',
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:services,name,'.($this->serviceId ?? 'NULL'),
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'active' => 'boolean',
        ];
    }

    protected $message = [
        'name.required' => 'El nombre del servicio es obligatoria.',
        'name.unique' => 'Ya existe un servicio con este nombre.',
        'price.required' => 'El precio es obligatorio.',
        'price.numeric' => 'El precio debe ser un número válido.',
        'price.min' => 'El precio no puede ser negativo.',
    ];

    public function openModal($serviceId = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'price', 'active', 'serviceId']);

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

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'active' => $this->active,
        ];

        $manager = new ServiceManager;

        if ($this->serviceId) {
            $service = Service::findOrFail($this->serviceId);
            $manager->update($service, $data);
            $message = 'Servicio actualizado correctamente.';
        } else {
            $manager->create($data);
            $message = 'Servicio creado correctamente';
        }

        $this->open = false;
        $this->reset(['name', 'description', 'price', 'active', 'serviceId']);
        $this->dispatch('serviceSaved')->to(ServiceIndex::class);
        $this->dispatch('notify', message: $message);
    }

    public function close()
    {
        $this->open = false;
        $this->reset(['name', 'description', 'price', 'active', 'serviceId']);
    }

    public function render()
    {
        return view('livewire.services.service-form');
    }
}
