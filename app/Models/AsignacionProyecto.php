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
        'id_revisor',
        'id_usuario_sesion',
    ];

}
