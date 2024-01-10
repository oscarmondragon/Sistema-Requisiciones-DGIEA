<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adquisicion;

class AdquisicionController extends Controller
{
    //

    public function index()
    {
        $adquisiciones = Adquisicion::where('tipo_requisicion', 1)->orderBy('id')->paginate(3);

        return view('adquisiciones.index', ['adquisiciones' => $adquisiciones]);

    }



    public function prueba()
    {

        return view('adquisiciones.prueba');

    }
}