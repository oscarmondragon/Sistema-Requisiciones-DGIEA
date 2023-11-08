<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\Solicitud;
use App\Models\TipoRequisicion;




class Seguimiento extends Component
{
    public $tipo;
    public $search = '';
    public $categoria = 0;
    public $tipoRequisicion;
    public $f_inicial = 0;
    public $f_final = 0;

    public function render()
    {

        $adquisiciones = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                'estatus_requisiciones.descripcion as estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion'
            );
        //si palabra clave esta vacia no se ejecuta
        if (!empty($this->search)) {
            $adquisiciones->where(function ($query) {
                $query->where('clave_adquisicion', 'like', '%' . $this->search . '%')
                    ->orWhereHas('requerimiento', function ($query) {
                        $query->where('descripcion', 'like', '%' . $this->search . '%');
                    })->orWhereHas('cuentas', function ($query) {
                    $query->where('nombre_cuenta', 'like', '%' . $this->search . '%');
                });
            });
        }
        if ($this->f_inicial != 0 and $this->f_final == 0) {
            //dd('f inicial solo'.$this->f_final.''.$this->f_inicial);
            $adquisiciones->where('adquisiciones.created_at', 'like', '%' . $this->f_inicial . '%');
        }
        if ($this->f_final != 0 and $this->f_inicial == 0) {
            //dd('f final solo'.$this->f_final.''.$this->f_inicial);
            $adquisiciones->where('adquisiciones.created_at', 'like', '%' . $this->f_final . '%');
        }
        if ($this->f_final != 0 and $this->f_inicial != 0) {
            // dd('las dos'.$this->f_final.''.$this->f_inicial);
            $adquisiciones->where('adquisiciones.created_at', 'like', '%' . $this->f_final . '%');

        }

        $adquisiciones->whereIn('estatus_general', [3, 4, 5])->where('clave_proyecto', '=', session('id_proyecto'));

        $solicitudes = Solicitud::join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
            ->select(
                'solicitudes.id as id',
                'solicitudes.clave_solicitud as id_requerimiento',
                'estatus_requisiciones.descripcion as estado',
                'solicitudes.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion'
            );

        if (!empty($this->search)) {
            $solicitudes->where(function ($query) {
                $query->where('clave_solicitud', 'like', '%' . $this->search . '%')
                    ->orWhereHas('requerimientoSolicitud', function ($query) {
                        $query->where('descripcion', 'like', '%' . $this->search . '%');
                    })->orWhereHas('rubroSolicitud', function ($query) {
                    $query->where('nombre_cuenta', 'like', '%' . $this->search . '%');
                });
            });
        }


        $solicitudes->whereIn('estatus_rt', [3, 4, 5])->where('clave_proyecto', '=', session('id_proyecto'));


        if ($this->categoria == 0) {
            $requerimientos = $adquisiciones->union($solicitudes)->orderBy('id')->paginate(10, pageName: 'pendientes');

        } else if ($this->categoria == 1) {

            $requerimientos = $adquisiciones->orderBy('id')->paginate(10, pageName: 'pendientes');

        } else if ($this->categoria == 2) {
            $requerimientos = $solicitudes->orderBy('id')->paginate(10, pageName: 'pendientes');
        }



        return view(
            'livewire.seguimiento',
            ['requerimientos' => $requerimientos]
        );
    }

    public function mount()
    {
        $this->tipoRequisicion = TipoRequisicion::select('id', 'descripcion')->where('estatus', 1)->get();
        //  $this->adquisiciones = Adquisicion::where('tipo_requisicion', 1)->orderBy('id')->paginate(3);
    }

    public function filterByCategory($categoria)
    {
        $this->categoria = $categoria;
        // dd($this->categoria);
    }
}