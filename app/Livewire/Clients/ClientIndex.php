<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Services\ClientService;
use Livewire\Component;
use Livewire\WithPagination;

class ClientIndex extends Component
{
    use WithPagination;

    public bool $showConfirmModal = false;

    public ?int $deleteId = null;

    public string $search = '';

    protected $queryString = ['search'];

    protected $listeners = [
        'clientSaved' => '$refresh',
        'refreshClients' => '$refresh',
        'deleteClient' => 'deleteClient',
    ];

    public function deleteClient(): void
    {
        $client = Client::findOrFail($this->deleteId);
        (new ClientService)->delete($client);
        $this->showConfirmModal = false;
        $this->deleteId = null;
        $this->dispatch('notify', message: 'Cliente eliminado correctamente');
    }

    public function cancelDelete(): void
    {
        $this->showConfirmModal = false;
        $this->deleteId = null;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openDeleteModal(int $id): void
    {
        $this->deleteId = $id;
        $this->showConfirmModal = true;
    }

    public function render()
    {
        return view('livewire.clients.client-index', [
            'clients' => (new ClientService)->getAll($this->search),
        ]);
    }
}
