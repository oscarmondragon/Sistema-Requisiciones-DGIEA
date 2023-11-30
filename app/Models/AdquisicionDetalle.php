<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdquisicionDetalle extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "adquisicion_detalles";

    public function estatus()
    {

        return $this->belongsTo(EstatusRequisiciones::class, 'estatus_rt');
    }
    public function adquisicion()
    {
        return $this->belongsTo(Adquisicion::class, 'id_adquisicion');
    }

    protected $fillable = [
        'id_adquisicion',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'iva',
        'importe',
        'impote_cotizacion',
        'justificacion_software',
        'alumnos',
        'profesores_invest',
        'administrativos',
        'estatus_dgiea',
        'estatus_rt',
        'observaciones',
        'id_usuario_sesion'
    ];

}