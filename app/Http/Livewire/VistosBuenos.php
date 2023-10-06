<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Adquisicion;

class VistosBuenos extends Component
{
    public $tipo;

    public function render()
    {
        $adquisiciones = Adquisicion::where('tipo_requisicion', 1)->orderBy('id')->paginate(3);

        return view('livewire.vistos-buenos', ['adquisiciones' => $adquisiciones]);

    }

    public function mount()
    {

        //  $this->adquisiciones = Adquisicion::where('tipo_requisicion', 1)->orderBy('id')->paginate(3);

    }
}