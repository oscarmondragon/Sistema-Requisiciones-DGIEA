<?php

namespace App\Http\Livewire\Revisores;

use App\Models\AsignacionProyecto;
use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\Solicitud;
use App\Models\TipoRequisicion;
use Livewire\WithPagination;

class ShowRequerimientos extends Component
{

    use WithPagination;
    public $tipo;
    public $search = '';
    public $searchVobo = '';

    public $categoria = 0;
    public $categoriaVobo = 0;
    public $tipoRequisicion;
    public $f_inicial = 0;
    public $f_final = 0;
    public $f_inicial_vobo = 0;
    public $f_final_vobo = 0;


    public $sortColumn = 'id';
    public $sortDirection = 'asc';

    protected $listeners = ['deleteAdquisicion', 'deleteSolicitud'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSearchVobo()
    {
        $this->resetPage();
    }
    public function updatingCategoria()
    {
        $this->resetPage();
    }
    public function updatingCategoriaVobo()
    {
        $this->resetPage();
    }
    public function mount()
    {
        $this->tipoRequisicion = TipoRequisicion::select('id', 'descripcion')->where('estatus', 1)->get();
        //  $this->adquisiciones = Adquisicion::where('tipo_requisicion', 1)->orderBy('id')->paginate(3);
    }
    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        //dd($this->sortColumn);
    }

    public function render()
    {
        //Usuario logueado
        $user = auth()->user();
        //traemos los proyectos asignados al usuario logueado
        $proyectosAsignados = AsignacionProyecto::select('id_proyecto')->where('id_revisor', $user->id)->pluck('id_proyecto');

        //  dd($proyectosAsignados);
        $adquisiciones = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisiciones.estatus_general as id_estatus',
            )->whereIn('estatus_general', [3, 5, 6])->whereIn('clave_proyecto', $proyectosAsignados);


        $solicitudes = Solicitud::join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
            ->select(
                'solicitudes.id as id',
                'solicitudes.clave_solicitud as id_requerimiento',
                'solicitudes.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'solicitudes.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'solicitudes.tipo_requisicion as  tipo_requerimiento',
                'solicitudes.vobo_admin as vobo_admin',
                'solicitudes.vobo_rt as vobo_rt',
                'solicitudes.estatus_rt as id_estatus',

            )->whereIn('estatus_rt', [3, 5, 6])->whereIn('clave_proyecto', $proyectosAsignados);

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

            $solicitudes->where(function ($query) {
                $query->where('clave_solicitud', 'like', '%' . $this->search . '%')
                    ->orWhereHas('requerimientoSolicitud', function ($query) {
                        $query->where('descripcion', 'like', '%' . $this->search . '%');
                    })->orWhereHas('rubroSolicitud', function ($query) {
                    $query->where('nombre_cuenta', 'like', '%' . $this->search . '%');
                });
            });
        }



        if ($this->f_inicial != 0 and $this->f_final == 0) {
            $adquisiciones->where('adquisiciones.created_at', 'like', '%' . $this->f_inicial . '%');
            $solicitudes->where('solicitudes.created_at', 'like', '%' . $this->f_inicial . '%');
        }


        if ($this->f_inicial != 0 and $this->f_final == 0) {
            $adquisiciones->where('adquisiciones.created_at', 'like', '%' . $this->f_inicial . '%');
            $solicitudes->where('solicitudes.created_at', 'like', '%' . $this->f_inicial . '%');
        }
        if ($this->f_final != 0 and $this->f_inicial == 0) {
            $adquisiciones->where('adquisiciones.created_at', 'like', '%' . $this->f_final . '%');
            $solicitudes->where('solicitudes.created_at', 'like', '%' . $this->f_final . '%');
        }
        if ($this->f_final != 0 and $this->f_inicial != 0) {
            // dd('las dos'.$this->f_final.''.$this->f_inicial);
            /* $adquisiciones->whereBetween('adquisiciones.created_at', [$this->f_inicial, $this->f_final]);
                   $solicitudes->whereBetween('solicitudes.created_at', [$this->f_inicial, $this->f_final]);*/
            $adquisiciones->whereDate('adquisiciones.created_at', '>=', $this->f_inicial)
                ->whereDate('adquisiciones.created_at', '<=', $this->f_final);
            $solicitudes->whereDate('solicitudes.created_at', '>=', $this->f_inicial)
                ->whereDate('solicitudes.created_at', '<=', $this->f_final);
        }


        if ($this->categoria == 0) {
            $requerimientos = $adquisiciones->union($solicitudes)->orderBy($this->sortColumn, $this->sortDirection)->paginate(10, pageName: 'pendientes');
            //dd($requerimientos);
        } else if ($this->categoria == 1) {
            $requerimientos = $adquisiciones->orderBy($this->sortColumn, $this->sortDirection)->paginate(10, pageName: 'pendientes');
        } else if ($this->categoria == 2) {
            $requerimientos = $solicitudes->orderBy($this->sortColumn, $this->sortDirection)->paginate(10, pageName: 'pendientes');
        } else if ($this->categoria == 3) {
            $adquisiciones->where('estatus_general', 1);
            $solicitudes->where('estatus_rt', 1);
            $requerimientos = $adquisiciones->union($solicitudes)->orderBy($this->sortColumn, $this->sortDirection)->paginate(10, pageName: 'pendientes');
            //dd($requerimientos);
        } else if ($this->categoria == 4) {
            $adquisiciones->where('estatus_general', 4);
            $solicitudes->where('estatus_rt', 4);
            $requerimientos = $adquisiciones->union($solicitudes)->orderBy($this->sortColumn, $this->sortDirection)->paginate(10, pageName: 'pendientes');
        }

        return view(
            'livewire.revisores.show-requerimientos',
            ['adquisiciones' => $requerimientos]
        );
    }
}
