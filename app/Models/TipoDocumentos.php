<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumentos extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "tipo_documentos"; //nombre de la tabla
}