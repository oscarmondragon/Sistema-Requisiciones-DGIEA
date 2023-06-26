<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adquisicion>
 */
class AdquisicionFactory extends Factory
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
            'clave_adquisicion' => fake()->numberBetween(330000, 340000),
            'id_rubro' => 1,
            'afecta_investigacion' => 0,
            'justificacion_academica' => '',
            'exclusividad' => 0,
            'vobo_admin' => 0,
            'vobo_rt' => 0,
            'id_emisor' => 1,
            'id_revisor' => 1,
            'estatus_general' => 1





        ];
    }
}