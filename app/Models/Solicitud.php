<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = "solicitudes";

    public function store(Request $request)
    {
        $solicitud = new Solicitud;
        $solicitud->monto_total = $request->monto_total;
        $solicitud->id_bitacora = $request->id_bitacora;
        $solicitud->save();

        return redirect()->route('solicitud.index');
    }


}