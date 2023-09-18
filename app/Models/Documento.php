<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "documentos";


    protected $fillable = [
        'id_requisicion',
        'nombre_doc',
        'tipo_documento'
    ];
}