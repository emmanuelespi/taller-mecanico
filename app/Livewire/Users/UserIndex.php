<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Services\UserManager;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $roleFilter = 'all';

    public $showConfirmModal = false;

    public $deleteId = null;

    protected $queryString = ['search', 'roleFilter'];

    protected $listeners = [
        'userSaved' => 'refreshUsers',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function refreshUsers()
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

    public function deleteUser()
    {
        try {
            $user = User::findOrFail($this->deleteId);
            $manager = new UserManager;
            $manager->delete($user);

            $this->showConfirmModal = false;
            $this->deleteId = null;

            $this->dispatch('notify', message: 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function toggleActive($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $manager = new UserManager;
            $manager->toggleActive($user);

            $status = $user->trashed() ? 'desactivado' : 'activado';
            $this->dispatch('notify', message: "Usuario {$status} correctamente.");

            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $manager = new UserManager;
        $users = $manager->getAll($this->search, $this->roleFilter);

        return view('livewire.users.user-index', [
            'users' => $users,
            'roles' => User::getRoles(),
        ]);
    }
}
