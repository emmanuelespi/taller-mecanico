<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserManager
{
    public function getAll(string $search = '', string $role = '', string $status = 'all'): LengthAwarePaginator
    {
        $query = User::query();

        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name')->paginate(10);
    }

    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }

    public function update(User $user, array $data): User
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return $user;
    }

    public function delete(User $user): void
    {
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            throw new \Exception('No se puede eliminar el último administrador del sistema.');
        }

        if (auth()->id() === $user->id) {
            throw new \Exception('No puedes elimminar tu propio usario.');
        }

        $user->delete();
    }

    public function toggleActive(User $user): User
    {
        if ($user->isAdmin() && $user::where('role', 'admin')->where('is_active', true)->count() <= 1 && $user->is_active) {
            throw new \Exception('No se puede desactivar el último administrador activo del sistema');
        }

        if (auth()->id() === $user->id && $user->is_active) {
            throw new \Exception('No se puede desactivar tu propio usuario.');
        }
        $user->is_active = $user->is_active;
        $user->save();

        return $user;
    }

    public function restore(int $userId): User
    {
        $user = User::withTrashed()->findOrFail($userId);
        $user->restore();

        return $user;
    }
}
