<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionProyecto extends Model {
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = "asignacion_proyectos";


    protected $fillable = [
        'id_proyecto',
        'clave_uaem',
        'clave_digcyn',
        'nombre_proyecto',
        'id_espacio_academico',
        'espacio_academico',
        'id_convocatoria',
        'convocatoria',
        'tipo_proyecto',
        'id_revisor',
        'fecha_inicio',
        'fecha_final',
        'fecha_limite_adquisiciones',
        'fecha_limite_solicitudes',
        'id_usuario_sesion'
    ];

    public function nameUser() {
        return $this->belongsTo(User::class, 'id_revisor');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($asignacionProyecto) {
            // Realizar la inserción en la otra tabla
            $asignacionProyectoHistorial = AsignacionProyecto::create([
                'id_proyecto' => $asignacionProyecto->id_proyecto,
                'clave_uaem' => $asignacionProyecto->clave_uaem,
                'clave_digcyn' => $asignacionProyecto->clave_digcyn,
                'nombre_proyecto' => $asignacionProyecto->nombre_proyecto,
                'id_espacio_academico' => $asignacionProyecto->id_espacio_academico,
                'espacio_academico' => $asignacionProyecto->espacio_academico,
                'id_convocatoria' => $asignacionProyecto->id_convocatoria,
                'convocatoria' => $asignacionProyecto->convocatoria,
                'tipo_proyecto' => $asignacionProyecto->tipo_proyecto,
                'fecha_inicio' => $asignacionProyecto->fecha_inicio,
                'fecha_final' => $asignacionProyecto->fecha_final,
                'fecha_limite_adquisiciones' => $asignacionProyecto->fecha_limite_adquisiciones,
                'fecha_limite_solicitudes' => $asignacionProyecto->fecha_limite_solicitudes,
                'id_usuario_sesion' => Auth::user()->id,
                'accion' => 'CREATE'
            ]);
        });

        static::updated(function ($adquisicion) {
            // Verificar si la clave_adquisicion ha sido actualizada
            // Actualizar la otra tabla
            if ($adquisicion->isDirty('clave_adquisicion')) { //verifica si la clave_adquisicion ha sido modificada
                AdquisicionHistorial::where('id_adquisicion', $adquisicion->id)
                    ->update(['clave_adquisicion' => $adquisicion->clave_adquisicion]);
            } else {
                $adquisicionHistorial = AdquisicionHistorial::create([
                    'id_adquisicion' => $adquisicion->id,
                    'clave_adquisicion' => $adquisicion->clave_adquisicion,
                    'tipo_requisicion' => $adquisicion->tipo_requisicion,
                    'clave_proyecto' => $adquisicion->clave_proyecto,
                    'clave_espacio_academico' => $adquisicion->clave_espacio_academico,
                    'clave_rt' => $adquisicion->clave_rt,
                    'tipo_financiamiento' => $adquisicion->tipo_financiamiento,
                    'id_rubro' => (int) $adquisicion->id_rubro,
                    'afecta_investigacion' => $adquisicion->afecta_investigacion,
                    'justificacion_academica' => $adquisicion->justificacion_academica,
                    'exclusividad' => $adquisicion->exclusividad,
                    'id_carta_exclusividad' => $adquisicion->id_carta_exclusividad,
                    'vobo_admin' => $adquisicion->vobo_admin,
                    'vobo_rt' => $adquisicion->vobo_rt,
                    'id_emisor' => $adquisicion->id_emisor,
                    'estatus_general' => $adquisicion->estatus_general,
                    'subtotal' => $adquisicion->subtotal,
                    'iva' => $adquisicion->iva,
                    'total' => $adquisicion->total,
                    'id_usuario_sesion' => Auth::user()->id,
                    'accion' => 'UPDATE'
                ]);
            }

        });

        static::deleted(function ($adquisicion) {
            // Realizar la inserción en la otra tabla
            $adquisicionHistorial = AdquisicionHistorial::create([
                'id_adquisicion' => $adquisicion->id,
                'clave_adquisicion' => $adquisicion->clave_adquisicion,
                'tipo_requisicion' => $adquisicion->tipo_requisicion,
                'clave_proyecto' => $adquisicion->clave_proyecto,
                'clave_espacio_academico' => $adquisicion->clave_espacio_academico,
                'clave_rt' => $adquisicion->clave_rt,
                'tipo_financiamiento' => $adquisicion->tipo_financiamiento,
                'id_rubro' => (int) $adquisicion->id_rubro,
                'afecta_investigacion' => $adquisicion->afecta_investigacion,
                'justificacion_academica' => $adquisicion->justificacion_academica,
                'exclusividad' => $adquisicion->exclusividad,
                'id_carta_exclusividad' => $adquisicion->id_carta_exclusividad,
                'vobo_admin' => $adquisicion->vobo_admin,
                'vobo_rt' => $adquisicion->vobo_rt,
                'id_emisor' => $adquisicion->id_emisor,
                'estatus_general' => $adquisicion->estatus_general,
                'subtotal' => $adquisicion->subtotal,
                'iva' => $adquisicion->iva,
                'total' => $adquisicion->total,
                'id_usuario_sesion' => Auth::user()->id,
                'accion' => 'DELETE'
            ]);
        });

        static::deleting(function ($adquisicion) {

            //Eliminamos en cascada
            $adquisicion->detalless()->delete();
            $adquisicion->documentos()->delete();

        });
    }

}
