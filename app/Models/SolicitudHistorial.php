<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudHistorial extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = "solicitudes_historial";

    public function cuentas()
    {

        return $this->belongsTo(CuentaContable::class, 'id_rubro');
    }

    public function estatus()
    {

        return $this->belongsTo(EstatusRequisiciones::class, 'estatus_rt');
    }

    public function requerimiento()
    {

        return $this->belongsTo(TipoRequisicion::class, 'tipo_requisicion');
    }

    public function solicitudDetalle()
    {

        return $this->hasOne(SolicitudDetalle::class, 'id_solicitud');
    }
    protected $fillable = [
        'id_solicitud',
        'clave_solicitud',
        'tipo_requisicion',
        'clave_proyecto',
        'clave_espacio_academico',
        'clave_rt',
        'tipo_financiamiento',
        'id_rubro',
        'monto_total',
        'nombre_expedido',
        'id_bitacora',
        'vobo_admin',
        'vobo_rt',
        'obligo_comprobar',
        'aviso_privacidad',
        'id_emisor',
        'id_revisor',
        'estatus_dgiea',
        'estatus_rt',
        'observaciones',
        'observaciones_vobo',
        'tipo_comprobacion',
        'observaciones_vobo',
        'id_usuario_sesion',
        'accion'
    ];
}
