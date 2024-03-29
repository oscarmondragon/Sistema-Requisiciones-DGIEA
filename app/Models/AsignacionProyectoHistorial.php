<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionProyectoHistorial extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = "asignacion_proyectos_historial";


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
        'id_usuario_sesion',
        'accion'
    ];

    public function nameUser() {
        return $this->belongsTo(User::class, 'id_revisor');
    }

}
