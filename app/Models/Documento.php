<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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
        'tipo_requisicion',
        'ruta_documento',
        'extension_documento',
        'nombre_documento',
        'tipo_documento'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($documento) {
            // Realizar la inserción en la otra tabla
            $documentoHistorial = DocumentoHistorial::create([
                'id_requisicion' => $documento->id_requisicion,
                'ruta_documento' => $documento->ruta_documento,
                'tipo_documento' => $documento->tipo_documento,
                'tipo_requisicion' => $documento->tipo_requisicion,
                'nombre_documento' => $documento->nombre_documento,
                'extension_documento' => $documento->extension_documento,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'CREATE'
            ]);
        });

        static::deleted(function ($documento) {
            // Realizar la inserción en la otra tabla
            $documentoHistorial = DocumentoHistorial::create([
                'id_requisicion' => $documento->id_requisicion,
                'ruta_documento' => $documento->ruta_documento,
                'tipo_documento' => $documento->tipo_documento,
                'tipo_requisicion' => $documento->tipo_requisicion,
                'nombre_documento' => $documento->nombre_documento,
                'extension_documento' => $documento->extension_documento,
                'id_usuario_sesion' => Session::has('id_user')? Session::get('id_user'): Auth::user()->id,
                'accion' => 'DELETE'
            ]);
        });
    }
}