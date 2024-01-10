<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaContable extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "cuentas_contables"; //nombre de la tabla

    public function cuentaEspecial()
    {
        return $this->belongsTo(CuentasEspeciales::class, 'id_especial');
    }

}