<?php

namespace App\Livewire\WorkOrders;

use App\Models\WorkOrder;
use App\Services\WorkOrderManager;
use Livewire\Component;

class WorkOrderDetails extends Component
{
    public WorkOrder $order;

    public $showConfirmModal = false;

    public $confirmAction = '';

    public $confirmMessage = '';

    public $newStatus = '';

    protected $listeners = [
        'refreshOrder' => '$refresh',
    ];

    public function mount(WorkOrder $order)
    {
        $this->order = $order->load(['client', 'vehicle', 'user', 'mechanic', 'services', 'spareParts']);
    }

    public function changeStatus($status)
    {
        $this->newStatus = $status;
        $messages = [
            'in_progress' => '¿Estás seguro de marcar esta orden como "En Progreso"?',
            'completed' => '¿Estás seguro de marcar esta orden como "Completada"? Esto descontará el stock de los repuestos.',
            'delivered' => '¿Estás seguro de marcar esta orden como "Entregada"?',
            'cancelled' => '¿Estás seguro de cancelar esta orden?',
        ];

        $this->confirmMessage = $messages[$status] ?? '¿Estás seguro de realizar esta acción?';
        $this->confirmAction = 'confirmStatusChange';
        $this->showConfirmModal = true;
    }

    public function confirmStatusChange()
    {
        try {
            $manager = new WorkOrderManager;
            $this->order = $manager->changeStatus($this->order, $this->newStatus);

            $statusLabels = [
                'in_progress' => 'En Progreso',
                'completed' => 'Completada',
                'delivered' => 'Entregada',
                'cancelled' => 'Cancelada',
            ];

            $this->dispatch('notify', message: "Órden marcada como {$statusLabels[$this->newStatus]} correctamente.");
            $this->dispatch('orderSaved')->to(WorkOrderIndex::class);

        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }

        $this->showConfirmModal = false;
        $this->confirmAction = '';
    }

    public function cancelConfirm()
    {
        $this->showConfirmModal = false;
        $this->confirmAction = '';
    }

    public function render()
    {
        return view('livewire.work-orders.work-order-details', [
            'order' => $this->order,
        ]);
    }
}
