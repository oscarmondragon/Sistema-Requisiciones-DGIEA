<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentasEspeciales extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "cuentas_especiales"; //nombre de la tabla

}