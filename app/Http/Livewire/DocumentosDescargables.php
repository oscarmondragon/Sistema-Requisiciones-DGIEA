<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DocumentosDescargables extends Component
{
    public function render()
    {
        return view('livewire.documentos-descargables')->layout('layouts.cvu');
    }
}
