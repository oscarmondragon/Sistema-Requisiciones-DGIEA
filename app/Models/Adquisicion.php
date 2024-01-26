<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adquisicion extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function detalless()
    {
        return $this->hasMany(AdquisicionDetalle::class, 'id_adquisicion');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_requisicion')->where('tipo_requisicion', 1);

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
        'observaciones_vobo',
        'subtotal',
        'iva',
        'total'
    ];
}
