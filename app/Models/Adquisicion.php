<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adquisicion extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "adquisiciones";


    // $invoices = DB::connection('mysql2')->table('invoices')->get();
}