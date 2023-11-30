<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionProyecto extends Model
{
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
        'id_usuario_sesion',
    ];

    public function nameUser()
    {
        return $this->belongsTo(User::class, 'id_revisor');
    }
}
