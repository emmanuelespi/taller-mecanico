<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SparePart>
 */
class SparePartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => 'Filtro de Aceite',
            'sku'            => 'REF-' . rand(10000, 99999),
            'unit_price'     => 150.00,
            'purchase_price' => 100.00,
            'stock'          => 10,
            'minimum_stock'  => 2,
            'is_active'      => true,
        ];
    }
}
