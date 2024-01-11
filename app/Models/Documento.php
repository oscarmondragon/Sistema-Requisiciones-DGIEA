<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Documento extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = "documentos";

    //return $this->hasOne('App\Profile');

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumentos::class, 'tipo_documento');
    }


    protected $fillable = [
        'id_requisicion',
        'ruta_documento',
        'tipo_documento',
        'tipo_requisicion',
        'nombre_documento',
        'extension_documento',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($documento) {
            // Realizar la inserción en la otra tabla
            $documentoHistorial = DocumentoHistorial::create([
                'id_requisicion' => $documento->id_requisicion,
                'ruta_documento' => $documento->ruta_documento,
                'tipo_documento' => '5',
                'tipo_requisicion' => '1',
                'nombre_documento' => $documento->nombre_documento,
                'extension_documento' => $documento->extension_documento,
                'id_usuario_sesion' => Session::get('id_user'),
                'accion' => 'CREATE'
            ]);
        });

        static::deleted(function ($documento) {
            // Realizar la inserción en la otra tabla
            $documentoHistorial = DocumentoHistorial::create([
                'id_requisicion' => $documento->id_requisicion,
                'ruta_documento' => $documento->ruta_documento,
                'tipo_documento' => '5',
                'tipo_requisicion' => '1',
                'nombre_documento' => $documento->nombre_documento,
                'extension_documento' => $documento->extension_documento,
                'id_usuario_sesion' => Session::get('id_user'),
                'accion' => 'DELETE'
            ]);
        });
    }
}