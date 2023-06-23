<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adquisicion;

class AdquisicionController extends Controller
{
    //

    public function index()
    {
        $adquisiciones = Adquisicion::get();

        return view('adquisiciones.index', ['adquisiciones' => $adquisiciones]);

    }
}