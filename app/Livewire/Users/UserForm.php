<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Services\UserManager;
use Livewire\Component;

class UserForm extends Component
{
    public $open = false;

    public $userId = null;

    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $role = 'receptionista';

    protected $listeners = [
        'openUserModal' => 'openModal',
    ];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.($this->userId ?? 'NULL'),
            'role' => 'required|in:admin,recepcionista,mecanico',
        ];

        if (! this->userId) {
            $rules['password'] = 'required|min:6|confirmed';
        } else {
            $rules['password'] = 'nullable|min:6|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'Ingrese un email válido.',
        'email.unique' => 'Este email ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'role.required' => 'Debe seleccionar un rol.',
        'role.in' => 'El rol seleccionado no es válido.',
    ];

    public function openModal($user = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role', 'userId']);

        $this->userId = $userId;

        if ($userId) {
            $user = User::findOrFail($userId);
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
        } else {
            $this->role = 'recepcionista';
        }

        $this->open = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        $manager = new UserManager;

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $manager->update($user, $data);
            $message = 'Usuario actualizado correctamente.';
        } else {
            $manager->create($data);
            $message = 'Usuario creado correctamente.';
        }

        $this->open = false;
        
    }

    public function render()
    {
        return view('livewire.users.user-form');
    }
}
