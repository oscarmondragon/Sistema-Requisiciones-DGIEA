<?php

namespace App\Http\Livewire\Admin;

use App\Models\CuentaContable;
use App\Models\CuentaContableHistorial;
use App\Models\TipoRequisicion;
use Livewire\Component;
use Livewire\WithPagination;

class CuentasContables extends Component
{
    use WithPagination;

    public $cuentaContable;
    public $categoria = 1;
    public $categoriaRequisicion = 0;
    public $tiposRequisiciones;
    public $search = '';

    public $sortColumn = 'nombre_cuenta';
    public $sortDirection = 'asc';

    protected $listeners = ['delete', 'restaurarCuenta'];

    public function mount()
    {
        $this->tiposRequisiciones = TipoRequisicion::get();
    }

    public function render()
    {

        $cuentas = CuentaContable::select();

        if ($this->categoriaRequisicion == 0 && ($this->categoria == 1 || $this->categoria == 0)) {
            $cuentas->whereIn('tipo_requisicion', [1, 2, 3])->where('estatus', $this->categoria);
        } elseif ($this->categoriaRequisicion == 0 && $this->categoria == 2) {
            $cuentas->onlyTrashed()->whereIn('tipo_requisicion', [1, 2, 3]);
        } elseif ($this->categoriaRequisicion == 1 && ($this->categoria == 0 || $this->categoria == 1)) {
            $cuentas->where('tipo_requisicion', 1)->where('estatus', $this->categoria);
        } elseif ($this->categoriaRequisicion == 1 && $this->categoria == 2) {
            $cuentas->onlyTrashed()->where('tipo_requisicion', 1);
        } elseif ($this->categoriaRequisicion == 2 && ($this->categoria == 0 || $this->categoria == 1)) {
            $cuentas->where('tipo_requisicion', 2)->where('estatus', $this->categoria);
        } elseif ($this->categoriaRequisicion == 2 && $this->categoria == 2) {
            $cuentas->onlyTrashed()->where('tipo_requisicion', 2);
        } elseif ($this->categoriaRequisicion == 3 && ($this->categoria == 0 || $this->categoria == 1)) {
            $cuentas->where('tipo_requisicion', 3)->where('estatus', $this->categoria);
        } else if ($this->categoriaRequisicion == 3 && $this->categoria == 2) {
            $cuentas->onlyTrashed()->where('tipo_requisicion', 3);
        }

        if (!empty($this->search)) {
            $cuentas->where(function ($query) {
                $query-> where('id', 'like', '%' . $this->search . '%')
                ->orWhere('nombre_cuenta', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.cuentas-contables',
         ['cuentas' => $cuentas->orderBy($this->sortColumn, $this->sortDirection)->paginate(10),]);
    }

    public function delete($id)
    {
        $idCuenta = CuentaContable::where('id', $id)->first();
        $idCuenta->delete();
    }

    public function restaurarCuenta($id){
        $cuenta = CuentaContable::withTrashed()->where('id', $id)->first();
        //dd($cuenta);
        $cuenta->restore();
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function limpiarFiltros(){
        $this->categoriaRequisicion = 0;
        $this->categoria = 1;
        $this->search = null;
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
