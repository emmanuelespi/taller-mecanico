<?php

namespace App\Livewire\Clients;

use App\Livewire\Shared\ConfirmModal;
use App\Services\ClientService;
use Livewire\Component;
use Livewire\WithPagination;

class ClientIndex extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    protected $listeners = [
        'clientSaved' => '$refresh',
        'refreshClients' => '$refresh',
        'deleteClient' => 'deleteClient',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openDeleteModal(int $id): void
    {
        $this->dispatch('confirmDelete',
            event: 'deleteClient',
            params: $id,
            title: '¿Eliminar cliente?',
            message: 'Esta acción eliminará al cliente permanentemente.',
        )->to(ConfirmModal::class);
    }

    public function render()
    {
        return view('livewire.clients.client-index', [
            'clients' => (new ClientService)->getAll($this->search),
        ]);
    }
}
