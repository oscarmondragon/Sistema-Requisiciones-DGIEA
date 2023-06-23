<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CuentaContable>
 */
class CuentaContableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'clave_cuenta' => fake()->numberBetween(100000, 150000),
            'nombre_cuenta' => fake()->sentence(),
            'tipo_requisicion' => 1,
            'estatus' => 1
        ];
    }
}