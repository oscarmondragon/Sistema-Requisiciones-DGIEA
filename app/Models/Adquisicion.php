<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adquisicion extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "adquisiciones";

    public function cuentas()
    {

        return $this->belongsTo(CuentaContable::class, 'id_rubro');
    }

    public function estatus()
    {

        return $this->belongsTo(EstatusRequisiciones::class, 'estatus_general');
    }

    public function requerimiento()
    {

        return $this->belongsTo(TipoRequisicion::class, 'tipo_requisicion');
    }

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
