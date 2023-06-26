<?php

namespace App\Http\Controllers;

use App\Models\SeguimientoSiia;
use Illuminate\Http\Request;

class SeguimientoSiiaController extends Controller
{
    //

    public function index()
    {
        $requisiciones = SeguimientoSiia::get();

        return view('seguimientoSiia.index', ['requisiciones' => $requisiciones]);

    }
}