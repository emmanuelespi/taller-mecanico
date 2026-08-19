<?php

namespace App\Livewire\WorkOrders;

use App\Models\Client;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Services\WorkOrderManager;
use Livewire\Component;

class WorkOrderForm extends Component
{
    public $open = false;

    public $orderId = null;

    public $client_id = '';

    public $vehicle_id = '';

    public $mechanic_id = '';

    public $problem_description = '';

    public $observations = '';

    public $delivery_date = '';

    public $services = [];

    public $spareParts = [];

    public $subtotal = 0;

    public $tax = 0;

    public $total = 0;

    public $selectedServiceId = '';

    public $selectedServiceQuantity = 1;

    public $selectedSparePartId = '';

    public $selectedSparePartQuantity = 1;

    protected $listeners = [
        'openOrderModal' => 'openModal',
    ];

    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'mechanic_id' => 'nullable|exists:users,id',
            'problem_description' => 'required|string|min:10',
            'observations' => 'nullable|string',
            'delivery_date' => 'nullable|date|after:today',
        ];
    }

    protected $messages = [
        'client_id.required' => 'Debe seleccionar un cliente.',
        'vehicle_id.required' => 'Debe seleccionar un vehículo.',
        'problem_description.required' => 'La descripción del problema es obligatoria.',
        'problem_description.min' => 'La descripción debe tener al menos 10 caracteres.',
        'delivery_date.after' => 'La fecha de entrega debe ser posterior a hoy.',
    ];

    public function openModal($orderId = null)
    {
        $this->resetValidation();
        $this->reset();

        $this->orderId = $orderId;

        if ($orderId) {
            $order = WorkOrder::with(['services', 'spareParts'])->findOrFail($orderId);
            $this->client_id = $order->client_id;
            $this->vehicle_id = $order->vehicle_id;
            $this->mechanic_id = $order->mechanic_id;
            $this->problem_description = $order->problem_description;
            $this->observations = $order->observations;
            $this->delivery_date = $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '';

            foreach ($order->services as $service) {
                $this->services[] = [
                    'id' => $service->id,
                    'name' => $service->name,
                    'quantity' => $service->pivot->quantity,
                    'unit_price' => $service->pivot->unit_price,
                    'total' => $service->pivot->unit_price * $service->pivot->quantity,
                ];
            }

            foreach ($order->spareParts as $part) {
                $this->spareParts[] = [
                    'id' => $part->id,
                    'name' => $part->name,
                    'quantity' => $part->pivot->quantity,
                    'unit_price' => $part->pivot->unit_price,
                    'total' => $part->pivot->unit_price * $part->pivot->quantity,
                ];
            }
            $this->calculateTotals();
        }

        $this->open = true;
    }

    public function updatedClientId()
    {
        $this->vehicle_id = '';
        $this->dispatch('clientChanged', client_id: $this->client_id);
    }

    public function addService()
    {
        $this->validate([
            'selectedServiceId' => 'required|exists:services,id',
            'selectedServiceQuantity' => 'required|integer|min:1',
        ], [
            'selectedServiceId.required' => 'Debe seleccionar un servicio.',
            'selectedServiceQuantity.min' => 'La cantidad debe ser al menos 1.',
        ]);

        $service = Service::findOrFail($this->selectedServiceId);

        $existingKey = collect($this->services)->search(fn($item) => $item['id'] == $service->id);

        if ($existingKey !== false) {
            $this->services[$existingKey]['quantity'] += $this->selectedServiceQuantity;
            $this->services[$existingKey]['total'] = $this->services[$existingKey]['quantity'] * $this->services[$existingKey]['unit_price'];
        } else {
            $this->services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'quantity' => $this->selectedServiceQuantity,
                'unit_price' => $service->price,
                'total' => $service->price * $this->selectedServiceQuantity,
            ];
        }

        $this->reset(['selectedServiceId', 'selectedServiceQuantity']);
        $this->calculateTotals();
        $this->dispatch('notify', message: 'Servicio agregado correctamente.');
    }

    public function removeService($index)
    {
        unset($this->services[$index]);
        $this->services = array_values($this->services);
        $this->calculateTotals();
        $this->dispatch('notify', message: 'Servicio eliminado');
    }

    public function addSparePart()
    {
        $this->validate([
            'selectedSparePartId' => 'required|exists:spare_parts,id',
            'selectedSparePartQuantity' => 'required|integer|min:1',
        ], [
            'selectedSparePartId.required' => 'Debe seleccionar un repuesto.',
            'selectedSparePartQuantity.min' => 'La cantidad debe ser al menos 1.',
        ]);

        $part = SparePart::findOrFail($this->selectedSparePartId);

        if ($part->stock < $this->selectedSparePartQuantity) {
            $this->dispatch('notify', message: "Stock insuficiente para {$part->name}. Disponible: {$part->stock}", type: 'error');

            return;
        }

        $existingKey = collect($this->spareParts)->search(fn($item) => $item['id'] == $part->id);

        if ($existingKey !== false) {
            $newQuantity = $this->spareParts[$existingKey]['quantity'] + $this->selectedSparePartQuantity;

            if ($part->stock < $newQuantity) {
                $this->dispatch('notify', message: "Stock insifuciente. Total requerido: {$newQuantity}, disponible: {$part->stock}", type: 'error');

                return;
            }
            $this->spareParts[$existingKey]['quantity'] = $newQuantity;
            $this->spareParts[$existingKey]['total'] = $this->spareParts[$existingKey]['quantity'] * $this->spareParts[$existingKey]['unit_price'];
        } else {
            $this->spareParts[] = [
                'id' => $part->id,
                'name' => $part->name,
                'quantity' => $this->selectedSparePartQuantity,
                'unit_price' => $part->unit_price,
                'total' => $part->unit_price * $this->selectedSparePartQuantity,
            ];
        }
        $this->reset(['selectedSparePartId', 'selectedSparePartQuantity']);
        $this->calculateTotals();
        $this->dispatch('notify', message: 'Repuesto agregado correctamente');
    }

    public function removeSparePart($index)
    {
        unset($this->spareParts[$index]);
        $this->spareParts = array_values($this->spareParts);
        $this->calculateTotals();
        $this->dispatch('notify', message: 'Repuesto eliminado.');
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->services as $service) {
            $this->subtotal += $service['total'];
        }

        foreach ($this->spareParts as $part) {
            $this->subtotal += $part['total'];
        }

        $this->tax = $this->subtotal * 0.16;
        $this->total = $this->subtotal + $this->tax;
    }

    public function save()
    {
        $this->validate();

        if (empty($this->services) && empty($this->spareParts)) {
            $this->dispatch('notify', message: 'Debe agregar al menos un servicio o repuesto', type: 'error');

            return;
        }

        $data = [
            'client_id' => $this->client_id,
            'vehicle_id' => $this->vehicle_id,
            'mechanic_id' => $this->mechanic_id ?: null,
            'problem_description' => $this->problem_description,
            'observations' => $this->observations,
            'delivery_date' => $this->delivery_date ?: null,
        ];

        $manager = new WorkOrderManager;

        if ($this->orderId) {
            // Actualizar orden existente
            $order = WorkOrder::findOrFail($this->orderId);
            $order->services()->detach();
            $order->spareParts()->detach();
            $manager->update($order, $data);
            $message = 'Orden actualizada correctamente';
        } else {
            // Crear nueva orden
            $data['user_id'] = auth()->id();
            $order = $manager->create($data);
            $message = 'Orden creada correctamente';
        }

        foreach ($this->services as $service) {
            $order->services()->attach($service['id'], [
                'quantity' => $service['quantity'],
                'unit_price' => $service['unit_price'],
                'subtotal' => $service['total'],
            ]);
        }

        foreach ($this->spareParts as $part) {
            $order->spareParts()->attach($part['id'], [
                'quantity' => $part['quantity'],
                'unit_price' => $part['unit_price'],
                'subtotal' => $part['total'],
            ]);
        }

        $order->calculateTotals();

        $this->open = false;
        $this->reset();
        $this->dispatch('orderSaved')->to(WorkOrderIndex::class);
        $this->dispatch('notify', message: $message);
    }

    public function close()
    {
        $this->open = false;
        $this->reset();
    }

    public function render()
    {
        $clients = Client::orderBy('name')->get();
        $vehicles = Vehicle::where('client_id', $this->client_id)
            ->orderBy('plate')
            ->get()
            ->map(function ($vehicle) {
                $hasActiveOrder = $vehicle->workOrders()
                    ->whereIn('status', [
                        \App\Enums\WorkOrderStatus::PENDING,
                        \App\Enums\WorkOrderStatus::IN_PROGRESS
                    ])
                    ->when($this->orderId, function ($q) {
                        $q->where('id', '!=', $this->orderId);
                    })
                    ->exists();
                $vehicle->has_active_order = $hasActiveOrder;
                return $vehicle;
            });
        $mechanics = User::where('role', 'mecanico')->where('is_active', true)->orderBy('name')->get();
        $services = Service::where('active', true)->orderBy('name')->get();
        $spareParts = SparePart::where('is_active', true)->where('stock', '>', 0)->orderBy('name')->get();

        return view('livewire.work-orders.work-order-form', [
            'clients' => $clients,
            'vehicles' => $vehicles,
            'mechanics' => $mechanics,
            'services' => $services,
            'spareParts' => $spareParts,
        ]);
    }
}
