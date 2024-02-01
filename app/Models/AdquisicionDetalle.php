<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AdquisicionDetalle extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'clave_siia',
        'factura_siia',
        'id_usuario_sesion'
    ];

    protected static function boot()
    {
        parent::boot();


        static::created(function ($elemento) {
            // Realizar la inserción en la otra tabla
            $adquisicionDetalleHistorial = AdquisicionDetalleHistorial::create([
                'id_adquisicion_detalle' => $elemento->id,
                'id_adquisicion' => $elemento->id_adquisicion,
                'descripcion' => $elemento->descripcion,
                'cantidad' => $elemento->cantidad,
                'precio_unitario' => $elemento->precio_unitario,
                'iva' => $elemento->iva,
                'importe' => $elemento->importe,
                'importe_cotizacion' => $elemento->importe_cotizacion,                
                'justificacion_software' => $elemento->justificacion_software,
                'alumnos' => $elemento->alumnos,
                'profesores_invest' => $elemento->profesores_invest,
                'administrativos' => $elemento->administrativos,
                'estatus_dgiea' =>$elemento->estatus_dgiea,
                'estatus_rt'=>$elemento->estatus_rt,
                'observaciones'=>$elemento->observaciones,
                'clave_siia'=>$elemento->clave_siia,
                'factura_siia'=>$elemento->factura_siia,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'CREATE'
            ]);
        });

        static::updated(function ($elemento) {
            // Actualizar la otra tabla de historial
            // Realizar la inserción en la otra tabla
            $adquisicionDetalleHistorial = AdquisicionDetalleHistorial::create([
                'id_adquisicion_detalle' => $elemento->id,
                'id_adquisicion' => $elemento->id_adquisicion,
                'descripcion' => $elemento->descripcion,
                'cantidad' => $elemento->cantidad,
                'precio_unitario' => $elemento->precio_unitario,
                'iva' => $elemento->iva,
                'importe' => $elemento->importe,
                'importe_cotizacion' => $elemento->importe_cotizacion,                
                'justificacion_software' => $elemento->justificacion_software,
                'alumnos' => $elemento->alumnos,
                'profesores_invest' => $elemento->profesores_invest,
                'administrativos' => $elemento->administrativos,
                'estatus_dgiea' =>$elemento->estatus_dgiea,
                'estatus_rt'=>$elemento->estatus_rt,
                'observaciones'=>$elemento->observaciones,
                'clave_siia'=>$elemento->clave_siia,
                'factura_siia'=>$elemento->factura_siia,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'UPDATE'
            ]);

        });

        static::deleted(function ($elemento) {
            // Realizar la inserción en la otra tabla
            $adquisicionDetalleHistorial = AdquisicionDetalleHistorial::create([
                'id_adquisicion_detalle' => $elemento->id,
                'id_adquisicion' => $elemento->id_adquisicion,
                'descripcion' => $elemento->descripcion,
                'cantidad' => $elemento->cantidad,
                'precio_unitario' => $elemento->precio_unitario,
                'iva' => $elemento->iva,
                'importe' => $elemento->importe,
                'importe_cotizacion' => $elemento->importe_cotizacion,                
                'justificacion_software' => $elemento->justificacion_software,
                'alumnos' => $elemento->alumnos,
                'profesores_invest' => $elemento->profesores_invest,
                'administrativos' => $elemento->administrativos,
                'estatus_dgiea' =>$elemento->estatus_dgiea,
                'estatus_rt'=>$elemento->estatus_rt,
                'observaciones'=>$elemento->observaciones,
                'clave_siia'=>$elemento->clave_siia,
                'factura_siia'=>$elemento->factura_siia,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'DELETE'
            ]);
        });

    }

}