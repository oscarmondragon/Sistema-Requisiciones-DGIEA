<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Usuarios extends Component
{
    use WithPagination;

    public $search = '';
    public $categoria = 0;

    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    protected $listeners = ['delete', 'restaurarUsuario'];

    public function mount()
    {
    }

    public function render()
    {
        $users = User::select();

        if ($this->categoria == 0) {
            $users->where('estatus', 1);
        }

        if ($this->categoria == 1) {
            $users->where('rol', 1)->where('estatus', 1);
        }

        if ($this->categoria == 2) {
            $users->where('rol', 2)->where('estatus', 1);
        }

        if ($this->categoria == 3) {
            $users->where('rol', 3)->where('estatus', 1);
        }

        if ($this->categoria == 4) {
            $users->where('rol', 4)->where('estatus', 1);
        }

        if ($this->categoria == 5) {
            $users->where('estatus', 0);
        }

        if ($this->categoria == 6) {
            $users->onlyTrashed();
        }


        if (!empty($this->search)) {
            $users->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('apePaterno', 'like', '%' . $this->search . '%')
                    ->orWhere('apeMaterno', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view(
            'livewire.admin.usuarios',
            ['users' => $users->orderBy($this->sortColumn, $this->sortDirection)->paginate(10)]

        );
    }

    public function delete($id)
    {
        $usuarioDelete = User::where('id', $id)->first();
        $usuarioDelete->delete();
    }

    public function restaurarUsuario($id)
    {
        $usuarioRest = User::withTrashed()->where('id', $id)->first();
        $usuarioRest->restore();
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function limpiarFiltros()
    {
        $this->search = null;
        $this->categoria = 0;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoria()
    {
        $this->resetPage();
    }
}
