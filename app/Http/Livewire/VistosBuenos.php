<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\Solicitud;
use Livewire\WithPagination;

class VistosBuenos extends Component
{

    use WithPagination;
    public $tipo;
    public $search = '';
    public $searchVobo = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSearcVobo()
    {
        $this->resetPage();
    }
    public function render()
    {
        // $adquisiciones = Adquisicion::where('estatus_general', 1)->orderBy('id')->paginate(3);
        $adquisiciones = Adquisicion::where(function ($query) {
            $query->where('clave_adquisicion', 'like', '%' . $this->search . '%')
                ->orWhereHas('requerimiento', function ($query) {
                    $query->where('descripcion', 'like', '%' . $this->search . '%');
                })->orWhereHas('cuentas', function ($query) {
                $query->where('nombre_cuenta', 'like', '%' . $this->search . '%');
            });
        })->where('estatus_general', 1)->orderBy('id')->paginate(3);

        $adquisicionesVistosBuenos = Adquisicion::where(function ($query) {
            $query->where('clave_adquisicion', 'like', '%' . $this->searchVobo . '%')
                ->orWhereHas('requerimiento', function ($query) {
                    $query->where('descripcion', 'like', '%' . $this->searchVobo . '%');
                });
        })->where('estatus_general', 2)->orderBy('id')->paginate(3);

        //$adquisicionesVistosBuenos = Adquisicion::where('estatus_general', 2)->orderBy('id')->paginate(10);

        $solicitudes = Solicitud::where('estatus_rt', 1)->orderBy('id')->paginate(10);

        return view(
            'livewire.vistos-buenos',
            ['adquisiciones' => $adquisiciones, 'solicitudes' => $solicitudes, 'adquisicionesVistosBuenos' => $adquisicionesVistosBuenos]
        );
    }

    public function mount()
    {

        //  $this->adquisiciones = Adquisicion::where('tipo_requisicion', 1)->orderBy('id')->paginate(3);

    }

    public function deleteAdquisicion($id)
    {
        $adquisicion = Adquisicion::findOrFail($id);
        $adquisicion->delete();
    }

}