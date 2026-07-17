<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla sin afectar foreign keys (soft deletes activos)
        User::withTrashed()->forceDelete();

        // ── Administrador ─────────────────────────────────────────────
        User::create([
            'name'       => 'Carlos Ramírez',
            'email'      => 'admin@taller.com',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'is_active'  => true,
        ]);

        // ── Recepcionistas ────────────────────────────────────────────
        User::create([
            'name'       => 'Laura Mendoza',
            'email'      => 'laura@taller.com',
            'password'   => Hash::make('password'),
            'role'       => 'recepcionista',
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'Patricia Solis',
            'email'      => 'patricia@taller.com',
            'password'   => Hash::make('password'),
            'role'       => 'recepcionista',
            'is_active'  => true,
        ]);

        // ── Mecánicos ─────────────────────────────────────────────────
        User::create([
            'name'       => 'José Hernández',
            'email'      => 'jose@taller.com',
            'password'   => Hash::make('password'),
            'role'       => 'mecanico',
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'Miguel Torres',
            'email'      => 'miguel@taller.com',
            'password'   => Hash::make('password'),
            'role'       => 'mecanico',
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'Andrés Flores',
            'email'      => 'andres@taller.com',
            'password'   => Hash::make('password'),
            'role'       => 'mecanico',
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'Roberto Jiménez',
            'email'      => 'roberto@taller.com',
            'password'   => Hash::make('password'),
            'role'       => 'mecanico',
            'is_active'  => false, // mecánico inactivo para demostración
        ]);
    }
}
