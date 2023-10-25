<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

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
}