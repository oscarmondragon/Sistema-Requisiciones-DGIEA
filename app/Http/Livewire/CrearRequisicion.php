<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CrearRequisicion extends Component
{

    public $tipo = 0;

    public function render()
    {
        return view('livewire.crear-requisicion');
    }
}