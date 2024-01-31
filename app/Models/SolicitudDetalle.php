<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


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
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud');
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

    protected static function boot()
    {
        parent::boot();


        static::created(function ($elemento) {
            // Realizar la inserción en la otra tabla
            $solicitudDetalleHistorial = SolicitudDetalleHistorial::create([
                'id_solicitud' => $elemento->id_solicitud,
                'concepto' => $elemento->concepto,
                'justificacion' => $elemento->justificacion,
                'importe' => $elemento->importe,
                'clave_siia' => $elemento->clave_siia,
                'periodo_inicio' => $elemento->periodo_inicio,
                'periodo_fin' => $elemento->periodo_fin,                
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'CREATE'
            ]);
        });

        static::updated(function ($elemento) {
            // Actualizar la otra tabla de historial
            // Realizar la inserción en la otra tabla
            $solicitudDetalleHistorial = SolicitudDetalleHistorial::create([
                'id_solicitud' => $elemento->id_solicitud,
                'concepto' => $elemento->concepto,
                'justificacion' => $elemento->justificacion,
                'importe' => $elemento->importe,
                'clave_siia' => $elemento->clave_siia,
                'periodo_inicio' => $elemento->periodo_inicio,
                'periodo_fin' => $elemento->periodo_fin, 
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'UPDATE'
            ]);

        });

        static::deleted(function ($elemento) {
            // Realizar la inserción en la otra tabla
            $solicitudDetalleHistorial = SolicitudDetalleHistorial::create([
                'id_solicitud' => $elemento->id_solicitud,
                'concepto' => $elemento->concepto,
                'justificacion' => $elemento->justificacion,
                'importe' => $elemento->importe,
                'clave_siia' => $elemento->clave_siia,
                'periodo_inicio' => $elemento->periodo_inicio,
                'periodo_fin' => $elemento->periodo_fin, 
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'DELETE'
            ]);
        });

    }

    
}