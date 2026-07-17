<?php

namespace App\Livewire\WorkOrders;

use App\Models\WorkOrder;
use App\Services\WorkOrderManager;
use Livewire\Component;
use Livewire\WithPagination;

class WorkOrderIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = 'all';

    public $dateFrom = '';

    public $dateTo = '';

    public $showConfirmModal = false;

    public $deleteId = null;

    protected $queryString = ['search', 'statusFilter', 'dateFrom', 'dateTo'];

    protected $listeners = [
        'orderSaved' => 'refreshOrders',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function refreshOrders()
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

    public function deleteOrder()
    {
        try {
            $order = WorkOrder::findOrFail($this->deleteId);
            $manager = new WorkOrderManager;
            $manager->delete($order);

            $this->showConfirmModal = false;
            $this->deleteId = null;

            $this->dispatch('notify', message: 'Orden eliminada correctamente.');

        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $manager = new WorkOrderManager;
        $orders = $manager->getAll($this->search, $this->statusFilter, $this->dateFrom, $this->dateTo);

        $statistics = $manager->getStatistics();

        $statuses = [
            'all' => 'Todos',
            'pending' => 'Pendientes',
            'in_progress' => 'En Progreso',
            'completed' => 'Completados',
            'delivered' => 'Entregados',
            'cancelled' => 'Cancelados',
        ];

        return view('livewire.work-orders.work-order-index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'statistics' => $statistics,
        ]);
    }
}
