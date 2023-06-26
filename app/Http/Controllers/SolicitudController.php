<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;


class SolicitudController extends Controller
{
    //

    public function index()
    {
        $solicitudes = Solicitud::where('tipo_requisicion', 2)->orderBy('id')->paginate(3);

        return view('solicitudes.index', ['solicitudes' => $solicitudes]);
    }
}