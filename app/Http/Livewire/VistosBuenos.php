<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\Solicitud;

class VistosBuenos extends Component
{
    public $tipo;

    public function render()
    {
        $adquisiciones = Adquisicion::where('estatus_general', 1)->orderBy('id')->paginate(10);
        $solicitudes = Solicitud::where('estatus_rt', 1)->orderBy('id')->paginate(10);

        //$requerimientos = $adquisiciones;

        return view('livewire.vistos-buenos', ['adquisiciones' => $adquisiciones], 
                    ['solicitudes' => $solicitudes]);
    }

    public function mount()
    {

        //  $this->adquisiciones = Adquisicion::where('tipo_requisicion', 1)->orderBy('id')->paginate(3);

    }
}