<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adquisicion extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "adquisiciones";

    protected $fillable = [
        'clave_adquisicion',
        'tipo_requisicion',
        'clave_proyecto',
        'clave_espacio_academico',
        'clave_rt',
        'tipo_financiamiento',
        'id_rubro',
        'afecta_investigacion',
        'justificacion_academica',
        'exclusividad',
        'id_carta_exclusividad',
        'vobo_admin',
        'vobo_rt',
        'id_emisor',
        'id_revisor',
        'estatus_general',
        'observaciones',
        'subtotal',
        'iva',
        'total'
    ];


}