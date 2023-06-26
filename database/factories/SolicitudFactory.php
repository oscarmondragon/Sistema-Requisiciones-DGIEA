<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Solicitud>
 */
class SolicitudFactory extends Factory
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
            'clave_solicitud' => fake()->numberBetween(550000, 560000),
            'id_rubro' => 1,
            'monto_total' => 50000,
            'vobo_admin' => 0,
            'vobo_rt' => 0,
            'id_emisor' => 1,
            'id_revisor' => 1,
            'aviso_privacidad' => 1,
            'estatus_dgiea' => 1,
            'estatus_rt' => 1

        ];
    }
}