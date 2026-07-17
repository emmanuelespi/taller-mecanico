<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Services\ClientService;
use Livewire\Component;

class ClientForm extends Component
{
    public bool $open = false;

    public ?int $clientId = null;

    public string $name = '';

    public string $last_name = '';

    public string $phone = '';

    public string $email = '';

    public string $street = '';

    public string $avenue = '';

    public string $number = '';

    public string $postal_code = '';

    protected function getListeners()
    {
        return [
            'openModal' => 'open',
        ];
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'street' => 'nullable|string|max:150',
            'avenue' => 'nullable|string|max:150',
            'number' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:10',
        ];
    }

    public function open(?int $clientId = null): void
    {
        $this->resetValidation();
        $this->resetExcept(['open', 'clientId']);

        $this->clientId = $clientId;

        if ($clientId) {
            $client = Client::findOrFail($clientId);
            $this->name = $client->name;
            $this->last_name = $client->last_name;
            $this->phone = $client->phone;
            $this->email = $client->email;
            $this->street = $client->street;
            $this->avenue = $client->avenue;
            $this->number = $client->number;
            $this->postal_code = $client->postal_code;
        }
        $this->open = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email ?: null,
            'street' => $this->street ?: null,
            'avenue' => $this->avenue ?: null,
            'number' => $this->number ?: null,
            'postal_code' => $this->postal_code ?: null,
        ];

        $service = new ClientService;

        if ($this->clientId) {
            $client = Client::findOrFail($this->clientId);
            $service->update($client, $data);
            $message = 'Cliente actualizado correctamente';
        } else {
            $service->create($data);
            $message = 'Cliente creado correctamente.';
        }

        $this->open = false;
        $this->reset();
        $this->dispatch('clientSaved')->to(ClientIndex::class);
        $this->dispatch('notify', message: $message);
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset();

        $this->dispatch('modalClosed');
    }

    public function render()
    {
        return view('livewire.clients.client-form');
    }
}
