<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Adquisicion;


class SeguimientoSiia extends Component
{
    public function render()
    {
        $adquisiciones = Adquisicion::where('tipo_requisicion', 1)->orderBy('id')->paginate(3);
        return view('livewire.seguimiento-siia', ['adquisiciones' => $adquisiciones]);
    }

}