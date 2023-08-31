<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Seguimiento extends Component
{
    public $tipo;

    public function render()
    {
        return view('livewire.seguimiento');
    }
}