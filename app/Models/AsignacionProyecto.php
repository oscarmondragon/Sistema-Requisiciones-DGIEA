<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
            $asignacionProyectoHistorial = AsignacionProyectoHistorial::create([
                'id_proyecto' => $asignacionProyecto->id_proyecto,
                'clave_uaem' => $asignacionProyecto->clave_uaem,
                'clave_digcyn' => $asignacionProyecto->clave_digcyn,
                'nombre_proyecto' => $asignacionProyecto->nombre_proyecto,
                'id_espacio_academico' => $asignacionProyecto->id_espacio_academico,
                'espacio_academico' => $asignacionProyecto->espacio_academico,
                'id_convocatoria' => $asignacionProyecto->id_convocatoria,
                'convocatoria' => $asignacionProyecto->convocatoria,
                'tipo_proyecto' => $asignacionProyecto->tipo_proyecto,
                'id_revisor' => $asignacionProyecto->id_revisor,
                'fecha_inicio' => $asignacionProyecto->fecha_inicio,
                'fecha_final' => $asignacionProyecto->fecha_final,
                'fecha_limite_adquisiciones' => $asignacionProyecto->fecha_limite_adquisiciones,
                'fecha_limite_solicitudes' => $asignacionProyecto->fecha_limite_solicitudes,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'CREATE'
            ]);
        });

        static::updated(function ($asignacionProyecto) {
            // Verificar si la clave_adquisicion ha sido actualizada
            // Actualizar la otra tabla
            $asignacionProyectoHistorial = AsignacionProyectoHistorial::create([
                'id_proyecto' => $asignacionProyecto->id_proyecto,
                'clave_uaem' => $asignacionProyecto->clave_uaem,
                'clave_digcyn' => $asignacionProyecto->clave_digcyn,
                'nombre_proyecto' => $asignacionProyecto->nombre_proyecto,
                'id_espacio_academico' => $asignacionProyecto->id_espacio_academico,
                'espacio_academico' => $asignacionProyecto->espacio_academico,
                'id_convocatoria' => $asignacionProyecto->id_convocatoria,
                'convocatoria' => $asignacionProyecto->convocatoria,
                'tipo_proyecto' => $asignacionProyecto->tipo_proyecto,
                'id_revisor' => $asignacionProyecto->id_revisor,
                'fecha_inicio' => $asignacionProyecto->fecha_inicio,
                'fecha_final' => $asignacionProyecto->fecha_final,
                'fecha_limite_adquisiciones' => $asignacionProyecto->fecha_limite_adquisiciones,
                'fecha_limite_solicitudes' => $asignacionProyecto->fecha_limite_solicitudes,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'UPDATE'
            ]);

        });

        static::deleted(function ($asignacionProyecto) {
            // Realizar la inserción en la otra tabla
            $asignacionProyectoHistorial = AsignacionProyectoHistorial::create([
                'id_proyecto' => $asignacionProyecto->id_proyecto,
                'clave_uaem' => $asignacionProyecto->clave_uaem,
                'clave_digcyn' => $asignacionProyecto->clave_digcyn,
                'nombre_proyecto' => $asignacionProyecto->nombre_proyecto,
                'id_espacio_academico' => $asignacionProyecto->id_espacio_academico,
                'espacio_academico' => $asignacionProyecto->espacio_academico,
                'id_convocatoria' => $asignacionProyecto->id_convocatoria,
                'convocatoria' => $asignacionProyecto->convocatoria,
                'tipo_proyecto' => $asignacionProyecto->tipo_proyecto,
                'id_revisor' => $asignacionProyecto->id_revisor,
                'fecha_inicio' => $asignacionProyecto->fecha_inicio,
                'fecha_final' => $asignacionProyecto->fecha_final,
                'fecha_limite_adquisiciones' => $asignacionProyecto->fecha_limite_adquisiciones,
                'fecha_limite_solicitudes' => $asignacionProyecto->fecha_limite_solicitudes,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'DELETE'
            ]);
        });


    }

}
