<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Solicitud extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = "solicitudes";

    public function rubroSolicitud()
    {
        return $this->belongsTo(CuentaContable::class, 'id_rubro');
    }

    public function requerimientoSolicitud()
    {
        return $this->belongsTo(TipoRequisicion::class, 'tipo_requisicion');
    }

    public function estatusSolicitud()
    {
        return $this->belongsTo(EstatusRequisiciones::class, 'estatus_rt');
    }
    public function cuentas()
    {

        return $this->belongsTo(CuentaContable::class, 'id_rubro');
    }
    public function solicitudDetalle()
    {

        return $this->hasOne(SolicitudDetalle::class, 'id_solicitud');
    }
    public function detalless()
    {
        return $this->hasMany(SolicitudDetalle::class, 'id_solicitud');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_requisicion')->where('tipo_requisicion', 2);
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
        'id_revisor',
        'estatus_dgiea',
        'estatus_rt',
        'observaciones',
        'observaciones_vobo',
        'tipo_comprobacion',
        'id_usuario_sesion',
        'accion'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($solicitud) {
            // Realizar la inserción en la otra tabla
            $solicitudHistorial = SolicitudHistorial::create([
                'id_solicitud' => $solicitud->id,
                'clave_solicitud' => $solicitud->clave_solicitud,
                'tipo_requisicion' => $solicitud->tipo_requisicion,
                'clave_proyecto' => $solicitud->clave_proyecto,
                'clave_espacio_academico' => $solicitud->clave_espacio_academico,
                'clave_rt' => $solicitud->clave_rt,
                'tipo_financiamiento' => $solicitud->tipo_financiamiento,
                'id_rubro' => (int) $solicitud->id_rubro,
                'monto_total' => $solicitud->monto_total,
                'nombre_expedido' => $solicitud->nombre_expedido,
                'id_bitacora' => $solicitud->id_bitacora,
                'vobo_admin' => $solicitud->vobo_admin,
                'vobo_rt' => $solicitud->vobo_rt,
                'obligo_comprobar' => $solicitud->obligo_comprobar,
                'aviso_privacidad' => $solicitud->aviso_privacidad,
                'id_emisor' => $solicitud->id_emisor,
                'id_revisor' => $solicitud->id_revisor,
                'estatus_dgiea' => $solicitud->estatus_general,
                'estatus_rt' => $solicitud->estatus_rt,
                'observaciones' => $solicitud->observaciones,
                'observaciones_vobo' => $solicitud->observaciones_vobo,
                'tipo_comprobacion' => $solicitud->tipo_comprobacion,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'CREATE'
            ]);
        });

        static::updated(function ($solicitud) {
            // Verificar si la clave_solicitud ha sido actualizada
            // Actualizar la otra tabla
            if ($solicitud->isDirty('clave_solicitud')) { //verifica si la clave_solicitud ha sido modificada
                SolicitudHistorial::where('id_solicitud', $solicitud->id)
                    ->update(['clave_solicitud' => $solicitud->clave_solicitud]);
            } else {
                $solicitudHistorial = SolicitudHistorial::create([
                    'id_solicitud' => $solicitud->id,
                    'clave_solicitud' => $solicitud->clave_solicitud,
                    'tipo_requisicion' => $solicitud->tipo_requisicion,
                    'clave_proyecto' => $solicitud->clave_proyecto,
                    'clave_espacio_academico' => $solicitud->clave_espacio_academico,
                    'clave_rt' => $solicitud->clave_rt,
                    'tipo_financiamiento' => $solicitud->tipo_financiamiento,
                    'id_rubro' => (int) $solicitud->id_rubro,
                    'monto_total' => $solicitud->monto_total,
                    'nombre_expedido' => $solicitud->nombre_expedido,
                    'id_bitacora' => $solicitud->id_bitacora,
                    'vobo_admin' => $solicitud->vobo_admin,
                    'vobo_rt' => $solicitud->vobo_rt,
                    'obligo_comprobar' => $solicitud->obligo_comprobar,
                    'aviso_privacidad' => $solicitud->aviso_privacidad,
                    'id_emisor' => $solicitud->id_emisor,
                    'id_revisor' => $solicitud->id_revisor,
                    'estatus_dgiea' => $solicitud->estatus_general,
                    'estatus_rt' => $solicitud->estatus_rt,
                    'observaciones' => $solicitud->observaciones,
                    'observaciones_vobo' => $solicitud->observaciones_vobo,
                    'tipo_comprobacion' => $solicitud->tipo_comprobacion,
                    'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                    'accion' => 'UPDATE'
                ]);
            }

        });

        static::deleted(function ($solicitud) {
            // Realizar la inserción en la otra tabla
            $solicitudHistorial = SolicitudHistorial::create([
                'id_solicitud' => $solicitud->id,
                'clave_solicitud' => $solicitud->clave_solicitud,
                'tipo_requisicion' => $solicitud->tipo_requisicion,
                'clave_proyecto' => $solicitud->clave_proyecto,
                'clave_espacio_academico' => $solicitud->clave_espacio_academico,
                'clave_rt' => $solicitud->clave_rt,
                'tipo_financiamiento' => $solicitud->tipo_financiamiento,
                'id_rubro' => (int) $solicitud->id_rubro,
                'monto_total' => $solicitud->monto_total,
                'nombre_expedido' => $solicitud->nombre_expedido,
                'id_bitacora' => $solicitud->id_bitacora,
                'vobo_admin' => $solicitud->vobo_admin,
                'vobo_rt' => $solicitud->vobo_rt,
                'obligo_comprobar' => $solicitud->obligo_comprobar,
                'aviso_privacidad' => $solicitud->aviso_privacidad,
                'id_emisor' => $solicitud->id_emisor,
                'id_revisor' => $solicitud->id_revisor,
                'estatus_dgiea' => $solicitud->estatus_general,
                'estatus_rt' => $solicitud->estatus_rt,
                'observaciones' => $solicitud->observaciones,
                'observaciones_vobo' => $solicitud->observaciones_vobo,
                'tipo_comprobacion' => $solicitud->tipo_comprobacion,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'DELETE'
            ]);
        });

        static::deleting(function ($solicitud) {

            //Eliminamos en cascada
            $solicitud->detalless()->delete();
            $solicitud->documentos()->delete();

        });
    }


}