<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientService
{
    public function getAll(string $search = ''): LengthAwarePaginator
    {
        return Client::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%{search}%')
                    ->orWhere('last_name', 'like', '%{search}')
                    ->orWhere('phone', 'like', '%{search}')
                    ->orWhere('email', 'like', '%{search}');
            })
            ->latest()
            ->paginate(10);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);

        return $client;
    }

    public function delete(Client $client): void
    {
        $client->delete();
    }
}
