<?php

namespace App\Http\Livewire;

use Livewire\Component;

class VistosBuenos extends Component
{
    public $tipo;
    public function render()
    {
        return view('livewire.vistos-buenos');
    }
}