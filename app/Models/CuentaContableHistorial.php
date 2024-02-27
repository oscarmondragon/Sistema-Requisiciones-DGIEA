<?php

namespace App\Models;

use App\Http\Livewire\Admin\CuentasContables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaContableHistorial extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "cuentas_contables_historial"; //nombre de la tabla

    protected $fillable = [
        'clave_cuenta',
        'id_cuenta',
        'nombre_cuenta',
        'tipo_requisicion',
        'id_especial',
        'estatus',
        'id_usuario_sesion',
        'accion'
    ];
}
