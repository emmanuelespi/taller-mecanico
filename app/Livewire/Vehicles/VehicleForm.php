<?php

namespace App\Livewire\Vehicles;

use App\Models\Client;
use App\Models\Vehicle;
use App\Services\VehicleService;
use Livewire\Component;

class VehicleForm extends Component
{
    public bool $open = false;

    public ?int $vehicleId = null;

    public string $client_id = '';

    public string $plate = '';

    public string $brand = '';

    public string $model = '';

    public string $year = '';

    public string $color = '';

    protected function rules(): array
    {
        return [
            'client_id' => 'required|integer|exists:clients,id',
            'plate' => 'required|string|max:20|unique:vehicles,plate, ' .($this->vehicleId ?? 'NULL'). ',id',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'color' => 'required|string|max:50',
        ];
    }

    protected function getListeners()
    {
        return [
            'openModal' => 'open',
        ];
    }

    public function open(?int $vehicleId = null): void
    {
        $this->resetValidation();
        $this->reset();

        $this->vehicleId = $vehicleId;

        if ($vehicleId) {
            $vehicle = Vehicle::findOrFail($vehicleId);
            $this->client_id = (string) $vehicle->client_id;
            $this->plate = $vehicle->plate;
            $this->brand = $vehicle->brand;
            $this->model = $vehicle->model;
            $this->year = (string) $vehicle->year;
            $this->color = $vehicle->color;
        }

        $this->open = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'client_id' => (int) $this->client_id,
            'plate' => strtoupper($this->plate),
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
        ];

        $service = new VehicleService;

        if ($this->vehicleId) {
            $vehicle = Vehicle::findOrFail($this->vehicleId);
            $service->update($vehicle, $data);
            $message = 'Vehículo actualizado correctamente.';
        } else {
            $service->create($data);
            $message = 'Vehículo creado correctamente.';
        }

        $this->open = false;
        $this->reset();
        $this->dispatch('vehicleSaved')->to(VehicleIndex::class);
        $this->dispatch('notify', message: $message);
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.vehicles.vehicle-form', [
            'clients' => Client::orderBy('name')->get(),
        ]);
    }
}
