<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class CvuController extends Controller
{
    //

    public function cvuVerificar(Request $request)
    {
        $usercvu = $request->input('id_investigador');
        $proyectocvu = $request->input('id_proyecto');
        $accion = $request->input('id_accion');

        session(['id_user' => $usercvu, 'id_proyecto' => $proyectocvu]);

        return view('cvu.index', ['accion' => $accion]);

    }

    public function create()
    {
        return view('cvu.index', ['accion' => 1]);
    }

    public function darVobo()
    {
        return view('cvu.index', ['accion' => 2]);
    }

    public function seguimiento()
    {
        return view('cvu.index', ['accion' => 3]);
    }


}