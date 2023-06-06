<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

class ProyectoController extends Controller
{
    //


    public function home()
    {
        $proyectos = Proyecto::get();

        return view('proyectos', ['proyectos' => $proyectos]);

    }
}