<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CuentaContableHistorial;

class CuentaContable extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = "cuentas_contables"; //nombre de la tabla

    protected $fillable = [
        'id',
        'clave_cuenta',
        'id_cuenta',
        'nombre_cuenta',
        'tipo_requisicion',
        'id_especial',
        'estatus',
        'id_usuario_sesion',
    ];

    public function cuentaEspecial()
    {
        return $this->belongsTo(CuentasEspeciales::class, 'id_especial');
    }

    public function tipoRequisicion()
    {
        return $this->belongsTo(TipoRequisicion::class, 'tipo_requisicion');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cuenta) {
            // Realizar la inserción en la otra tabla
            CuentaContableHistorial::create([
                'clave_cuenta' => $cuenta->id,
                'id_cuenta' => $cuenta->id_cuenta,
                'nombre_cuenta' => $cuenta->nombre_cuenta,
                'tipo_requisicion' => $cuenta->tipo_requisicion,
                'id_especial' => $cuenta->id_especial,
                'estatus' => $cuenta->estatus,
                'id_usuario_sesion' => $cuenta->id_usuario_sesion,
                'accion' => 'CREATE'
            ]);
        });

        static::updating(function ($cuenta) {
            if ($cuenta->original['deleted_at'] == null) {
                CuentaContableHistorial::create([
                    'clave_cuenta' => $cuenta->id,
                    'id_cuenta' => $cuenta->id_cuenta,
                    'nombre_cuenta' => $cuenta->nombre_cuenta,
                    'tipo_requisicion' => $cuenta->tipo_requisicion,
                    'id_especial' => $cuenta->id_especial,
                    'estatus' => $cuenta->estatus,
                    'id_usuario_sesion' => $cuenta->id_usuario_sesion,
                    'deleted_at' => $cuenta->deleted_at,
                    'accion' => 'UPDATE'
                ]);
            } else {
                CuentaContableHistorial::create([
                    'clave_cuenta' => $cuenta->id,
                    'id_cuenta' => $cuenta->id_cuenta,
                    'nombre_cuenta' => $cuenta->nombre_cuenta,
                    'tipo_requisicion' => $cuenta->tipo_requisicion,
                    'id_especial' => $cuenta->id_especial,
                    'estatus' => $cuenta->estatus,
                    'id_usuario_sesion' => $cuenta->id_usuario_sesion,
                    'accion' => 'RESTORE'
                ]);
            }
        });

        static::deleted(function ($cuenta) {
            // Realizar la inserción en la otra tabla
            CuentaContableHistorial::create([
                'clave_cuenta' => $cuenta->id,
                'id_cuenta' => $cuenta->id_cuenta,
                'nombre_cuenta' => $cuenta->nombre_cuenta,
                'tipo_requisicion' => $cuenta->tipo_requisicion,
                'id_especial' => $cuenta->id_especial,
                'estatus' => $cuenta->estatus,
                'id_usuario_sesion' => $cuenta->id_usuario_sesion,
                'accion' => 'DELETE'
            ]);
        });
    }
}
