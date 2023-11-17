<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = "View_1_OcaPry"; //nombre de la vista
    protected $primaryKey = 'CveEntPry';

}