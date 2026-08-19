<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'brand'     => 'Toyota',
            'model'     => 'Corolla',
            'year'      => 2020,
            'color'     => $this->faker->safeColorName(),
            'plate'     => $this->faker->unique()->bothify('???-####'),
        ];
    }
}
