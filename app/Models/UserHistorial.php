<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserHistorial extends Authenticatable
{    

    protected $connection = 'mysql';
    protected $table = "users_historial"; //nombre de la tabla

    protected $fillable = [
        'name',
        'apePaterno',
        'apeMaterno',
        'email',
        'password',
        'estatus',
        'rol',
        'id_usuario_sesion',
        'accion',
        'deleted_at'
    ];

}
