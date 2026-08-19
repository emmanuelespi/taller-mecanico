<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'             => User::factory(),
            'client_id'           => Client::factory(),
            'vehicle_id'          => Vehicle::factory(),
            'order_number'        => 'OT-' . now()->format('Ym') . '-' . rand(1000, 9999),
            'status'              => 'pending',
            'problem_description' => $this->faker->sentence(),
            'total'               => 0,
            'entry_date'          => now(),
        ];
    }
}
