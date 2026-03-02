<?php

namespace App\Livewire\Clients;

use App\Models\Client;
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
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteClient(int $id): void
    {
        $client = Client::findOrFail($id);
        (new ClientService)->delete($client);
        $this->dispatch('notify', message: 'Cliente eliminado correctamente');

        $this->dispatch('clientDeleted');
    }

    public function render()
    {
        return view('livewire.clients.client-index', [
            'clients' => (new ClientService)->getAll($this->search),
        ]);
    }
}
