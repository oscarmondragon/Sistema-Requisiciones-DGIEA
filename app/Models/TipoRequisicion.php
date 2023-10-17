<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRequisicion extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "tipo_requisiciones"; //nombre de la tabla
}