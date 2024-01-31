<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudDetalle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = "solicitud_detalles";

    public function estatusSolicitud()
    {
        return $this->belongsTo(EstatusRequisiciones::class, 'estatus_rt');
    }


    protected $fillable = [
        'id_solicitud',
        'concepto',
        'justificacion',
        'importe',
        'clave_siia',
        'periodo_inicio',
        'periodo_fin'
    ];
}