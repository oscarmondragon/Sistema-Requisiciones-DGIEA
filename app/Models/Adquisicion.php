<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adquisicion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = "adquisiciones";

    public function cuentas()
    {

        return $this->belongsTo(CuentaContable::class, 'id_rubro');
    }

    public function estatus()
    {

        return $this->belongsTo(EstatusRequisiciones::class, 'estatus_general');
    }

    public function requerimiento()
    {

        return $this->belongsTo(TipoRequisicion::class, 'tipo_requisicion');
    }

    public function detalless()
    {
        return $this->hasMany(AdquisicionDetalle::class, 'id_adquisicion');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_requisicion')->where('tipo_requisicion', 1);
        ;
    }

    protected $fillable = [
        'clave_adquisicion',
        'tipo_requisicion',
        'clave_proyecto',
        'clave_espacio_academico',
        'clave_rt',
        'tipo_financiamiento',
        'id_rubro',
        'afecta_investigacion',
        'justificacion_academica',
        'exclusividad',
        'id_carta_exclusividad',
        'vobo_admin',
        'vobo_rt',
        'id_emisor',
        'id_revisor',
        'estatus_general',
        'observaciones',
        'observaciones_vobo',
        'subtotal',
        'iva',
        'total'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($adquisicion) {
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
                'id_usuario_sesion' => $adquisicion->id_emisor,
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
                    'id_usuario_sesion' => $adquisicion->id_emisor,
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
                'id_usuario_sesion' => $adquisicion->id_emisor,
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
