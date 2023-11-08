<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class AsignacionProyectos extends Component
{

    public $revisores;

    public function mount()
    {
        $this->revisores = User::where('rol', 2)->get();

    }
    public function render()
    {
        return view('livewire.admin.asignacion-proyectos');
    }
}
