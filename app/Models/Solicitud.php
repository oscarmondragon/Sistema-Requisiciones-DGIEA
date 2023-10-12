<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "solicitudes";

    public function rubroSolicitud(){
        return $this->belongsTo(CuentaContable::class, 'id_rubro');
    }

    public function requerimientoSolicitud(){
        return $this->belongsTo(TipoRequisicion::class, 'tipo_requisicion');
    }

    public function estatusSolicitud(){
        return $this->belongsTo(EstatusRequisiciones::class, 'estatus_rt');
    }

    protected $fillable = [
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
        'estatus_dgiea',
        'estatus_rt'
    ];


}