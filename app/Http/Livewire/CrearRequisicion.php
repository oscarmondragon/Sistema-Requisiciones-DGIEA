<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TipoRequisicion;

class CrearRequisicion extends Component
{

    public $tipo = 0;
    //catalogos
    public $tiposRequisicion;

    public function mount()
    {
        $this->tiposRequisicion = TipoRequisicion::where('estatus', 1)->get();
    }

    public function render()
    {
        return view('livewire.crear-requisicion');
    }
}