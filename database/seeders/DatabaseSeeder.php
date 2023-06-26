<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\RolUsuario;
use App\Models\Solicitud;
use App\Models\TipoEstatus;
use App\Models\TipoRequisicion;
use App\Models\User;
use App\Models\CuentaContable;
use App\Models\Adquisicion;





use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        //TIPOS DE ESTATUS
        TipoEstatus::factory()->count(3)->sequence(
            ['descripcion' => 'DGIEA'],
            ['descripcion' => 'RT'],
            ['descripcion' => 'SIIA']
        )
            ->create();

        //TIPOS DE REQUISICIONES
        TipoRequisicion::factory()->count(2)->sequence(
            ['descripcion' => 'Adquisicion'],
            ['descripcion' => 'Solicitud'],
        )
            ->create();


        //ROLES USUARIO
        RolUsuario::factory()->count(2)->sequence(
            ['descripcion' => 'Administrador'],
            ['descripcion' => 'Revisor'],
        )
            ->create();

        //USUARIOS
        User::factory()->count(2)->
            sequence(
                ['rol' => RolUsuario::all()->random()],
                ['rol' => RolUsuario::all()->random()],


            )->create();


        //CUENTAS CONTABLES

        CuentaContable::factory()->count(5)->
            sequence(
                ['tipo_requisicion' => 1, 'clave_cuenta' => 51210101, 'nombre_cuenta' => 'PAPELERIA Y ARTICULOS DE ESCRITORIO'],
                ['tipo_requisicion' => 1, 'clave_cuenta' => 51210301, 'nombre_cuenta' => 'MATERIAL PARA COMPUTADORAS Y BIENES INFORMATICOS'],
                ['tipo_requisicion' => 1, 'clave_cuenta' => 56590101, 'nombre_cuenta' => 'LICENCIAMIENTO DE SOFWARE'],
                ['tipo_requisicion' => 2, 'clave_cuenta' => 51260101, 'nombre_cuenta' => 'COMBUSTIBLE'],
                ['tipo_requisicion' => 2, 'clave_cuenta' => 51220103, 'nombre_cuenta' => 'ALIMENTACION PARA PERSONAS'],


            )->create();


        //Adquisiones

        Adquisicion::factory()->count(5)->
            sequence(
                ['tipo_requisicion' => 1, 'id_proyecto' => 6627, 'id_rubro' => 1],
                ['tipo_requisicion' => 1, 'id_proyecto' => 6627, 'id_rubro' => 2],
                ['tipo_requisicion' => 1, 'id_proyecto' => 6881, 'id_rubro' => 2],
                ['tipo_requisicion' => 2, 'id_proyecto' => 6627, 'id_rubro' => 4],
                ['tipo_requisicion' => 2, 'id_proyecto' => 6881, 'id_rubro' => 5],


            )->create();


        //solicitudes

        Solicitud::factory()->count(5)->
            sequence(
                ['tipo_requisicion' => 2, 'id_proyecto' => 6627, 'id_rubro' => 5],
                ['tipo_requisicion' => 2, 'id_proyecto' => 6627, 'id_rubro' => 4],
                ['tipo_requisicion' => 2, 'id_proyecto' => 6881, 'id_rubro' => 4],
                ['tipo_requisicion' => 2, 'id_proyecto' => 6627, 'id_rubro' => 4],
                ['tipo_requisicion' => 2, 'id_proyecto' => 6881, 'id_rubro' => 5],


            )->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}