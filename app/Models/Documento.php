<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}