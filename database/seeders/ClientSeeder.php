<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            // ── Ciudad de México ──────────────────────────────────────
            [
                'name'        => 'Roberto',
                'last_name'   => 'García López',
                'phone'       => '5551234567',
                'email'       => 'roberto.garcia@email.com',
                'street'      => 'Av. Insurgentes Sur',
                'avenue'      => 'Col. Del Valle',
                'number'      => '1254',
                'postal_code' => '03100',
            ],
            [
                'name'        => 'María',
                'last_name'   => 'González Martínez',
                'phone'       => '5559876543',
                'email'       => 'maria.gonzalez@email.com',
                'street'      => 'Calle Tula',
                'avenue'      => 'Col. Hipódromo',
                'number'      => '47',
                'postal_code' => '06100',
            ],
            [
                'name'        => 'Fernando',
                'last_name'   => 'Pérez Ruiz',
                'phone'       => '5554561234',
                'email'       => 'fperez@gmail.com',
                'street'      => 'Calzada de Tlalpan',
                'avenue'      => 'Col. Portales',
                'number'      => '789',
                'postal_code' => '03300',
            ],
            [
                'name'        => 'Ana',
                'last_name'   => 'Morales Vega',
                'phone'       => '5558765432',
                'email'       => null,
                'street'      => 'Eje Central Lázaro Cárdenas',
                'avenue'      => 'Col. Centro',
                'number'      => '320',
                'postal_code' => '06050',
            ],
            [
                'name'        => 'Luis',
                'last_name'   => 'Rodríguez Sánchez',
                'phone'       => '5553214567',
                'email'       => 'luis.rodriguez@outlook.com',
                'street'      => 'Paseo de la Reforma',
                'avenue'      => 'Col. Juárez',
                'number'      => '500',
                'postal_code' => '06600',
            ],
            // ── Guadalajara ───────────────────────────────────────────
            [
                'name'        => 'Carmen',
                'last_name'   => 'Hernández Díaz',
                'phone'       => '3331234567',
                'email'       => 'carmen.hd@email.com',
                'street'      => 'Av. Vallarta',
                'avenue'      => 'Col. Americana',
                'number'      => '2350',
                'postal_code' => '44130',
            ],
            [
                'name'        => 'Jorge',
                'last_name'   => 'López Castillo',
                'phone'       => '3339876543',
                'email'       => 'jlopez@correo.com',
                'street'      => 'Av. Revolución',
                'avenue'      => 'Col. Moderna',
                'number'      => '88',
                'postal_code' => '44190',
            ],
            [
                'name'        => 'Elena',
                'last_name'   => 'Torres Medina',
                'phone'       => '3337891234',
                'email'       => 'elena.torres@email.com',
                'street'      => 'Calle Prisciliano Sánchez',
                'avenue'      => 'Col. Centro',
                'number'      => '165',
                'postal_code' => '44100',
            ],
            // ── Monterrey ─────────────────────────────────────────────
            [
                'name'        => 'Ricardo',
                'last_name'   => 'Martínez Fuentes',
                'phone'       => '8181234567',
                'email'       => 'rmartinez@negocio.com',
                'street'      => 'Av. Constitución',
                'avenue'      => 'Col. Centro',
                'number'      => '300 Ote',
                'postal_code' => '64000',
            ],
            [
                'name'        => 'Diana',
                'last_name'   => 'Ramírez Garza',
                'phone'       => '8189876543',
                'email'       => 'dianar@gmail.com',
                'street'      => 'Av. Lázaro Cárdenas',
                'avenue'      => 'Col. Residencial San Agustín',
                'number'      => '4567',
                'postal_code' => '66250',
            ],
            [
                'name'        => 'Alejandro',
                'last_name'   => 'Cruz Navarro',
                'phone'       => '8183216549',
                'email'       => null,
                'street'      => 'Calle Modesto Arreola',
                'avenue'      => 'Col. Obispado',
                'number'      => '1100',
                'postal_code' => '64060',
            ],
            // ── Puebla ───────────────────────────────────────────────
            [
                'name'        => 'Sofía',
                'last_name'   => 'Vargas Ortiz',
                'phone'       => '2221234567',
                'email'       => 'sofia.vargas@email.com',
                'street'      => 'Blvd. Valsequillo',
                'avenue'      => 'Col. Azcarate',
                'number'      => '512',
                'postal_code' => '72501',
            ],
            [
                'name'        => 'Manuel',
                'last_name'   => 'Jiménez Aguilar',
                'phone'       => '2229876543',
                'email'       => 'mjimenez@hotmail.com',
                'street'      => 'Av. Juárez',
                'avenue'      => 'Col. Centro Histórico',
                'number'      => '14',
                'postal_code' => '72000',
            ],
            [
                'name'        => 'Valeria',
                'last_name'   => 'Moreno Reyes',
                'phone'       => '2226541234',
                'email'       => 'valeria.moreno@email.com',
                'street'      => 'Calle 11 Sur',
                'avenue'      => 'Col. Centro',
                'number'      => '702',
                'postal_code' => '72000',
            ],
            // ── Tijuana ──────────────────────────────────────────────
            [
                'name'        => 'Héctor',
                'last_name'   => 'Gutiérrez Silva',
                'phone'       => '6641234567',
                'email'       => 'hector.gs@email.com',
                'street'      => 'Blvd. Agua Caliente',
                'avenue'      => 'Col. Aviación',
                'number'      => '4500',
                'postal_code' => '22014',
            ],
        ];

        foreach ($clients as $client) {
            Client::updateOrCreate(
                ['phone' => $client['phone']],
                $client
            );
        }
    }
}
