<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\AdquisicionDetalle;
use App\Models\Solicitud;
use App\Models\TipoRequisicion;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
//use App\Http\Livewire\DB;

class Seguimiento extends Component
{
    use WithPagination;
    public $tipo;
    public $search = '';
    public $searchSIIA = '';
    public $categoria = 0;
    public $categoriaSIIA = 0;
    public $tipoRequisicion;
    public $f_inicial;
    public $f_inicialSIIA = 0;
    public $f_final;
    public $f_finalSIIA = 0;

    public $sortColumn = 'id';
    public $sortDirection = 'asc';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingCategoria()
    {
        $this->resetPage();
    }

    public function render()
    {
        $adquisicionesDgiea = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                DB::raw('null as concepto'),
                DB::raw('null as claveSIIA'),
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.color as color_estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion as req',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.estatus_general as id_estatus',
                'adquisiciones.observaciones as observaciones',
                'adquisiciones.id_emisor as emisor'
            )->whereIn('estatus_requisiciones.tipo', [2])->where('clave_proyecto', '=', session('id_proyecto'));
        
        $adquisicionD = AdquisicionDetalle::join('adquisiciones', 'adquisicion_detalles.id_adquisicion', '=', 'adquisiciones.id')
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("estatus_requisiciones", "adquisicion_detalles.estatus_rt", "=", "estatus_requisiciones.id")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                'adquisicion_detalles.descripcion as concepto',
                'adquisicion_detalles.clave_siia as claveSIIA',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.color as color_estado',
                'adquisicion_detalles.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion as req',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisicion_detalles.estatus_rt as id_estatus',
                'adquisicion_detalles.observaciones as observaciones',
                'adquisiciones.id_emisor as emisor'
            )->whereIn('estatus_requisiciones.tipo', [2,3,5])
            ->where('clave_proyecto', '=', session('id_proyecto'));

        $solicitudes = Solicitud::join('solicitud_detalles', 'solicitudes.id', '=', 'solicitud_detalles.id_solicitud')
            ->join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
            ->select(
                'solicitudes.id as id',
                'solicitudes.clave_solicitud as id_requerimiento',
                'solicitud_detalles.concepto as concepto',
                'solicitud_detalles.clave_siia as claveSIIA',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.color as color_estado',
                'solicitudes.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion as req',
                'solicitudes.tipo_requisicion as  tipo_requerimiento',
                'solicitudes.estatus_rt as id_estatus',
                'solicitudes.observaciones as observaciones',
                'solicitudes.id_emisor as emisor'
            )->whereIn('estatus_requisiciones.tipo', [2,4,5])->where('clave_proyecto', '=', session('id_proyecto'));

            if (!empty($this->search)) {
                $adquisicionesDgiea->where(function ($query) {
                    $query-> where('adquisiciones.clave_adquisicion', 'like', '%' .$this->search. '%')
                    ->orWhere('cuentas_contables.nombre_cuenta', 'like', '%' .$this->search. '%')
                    ->orWhere('tipo_requisiciones.descripcion', 'like', '%' .$this->search. '%');
                });
                $adquisicionD->where(function ($query) {
                    $query-> where('adquisiciones.clave_adquisicion', 'like', '%' .$this->search. '%')
                    ->orWhere('cuentas_contables.nombre_cuenta', 'like', '%' .$this->search. '%')
                    ->orWhere('adquisicion_detalles.descripcion', 'like', '%' .$this->search. '%')
                    ->orWhere('tipo_requisiciones.descripcion', 'like', '%' .$this->search. '%');
                });
                $solicitudes->where(function ($query) {
                    $query-> where('solicitudes.clave_solicitud', 'like', '%' .$this->search. '%')
                    ->orWhere('cuentas_contables.nombre_cuenta', 'like', '%' .$this->search. '%')
                    ->orWhere('solicitud_detalles.concepto', 'like', '%' .$this->search. '%')
                    ->orWhere('tipo_requisiciones.descripcion', 'like', '%' .$this->search. '%')
                    ->orWhere('estatus_requisiciones.descripcion', 'like', '%' .$this->search. '%');
                });
            }

            if ($this->f_inicial != 0 and ($this->f_final == 0 or $this->f_final =='')) {
                $adquisicionesDgiea->where('adquisiciones.updated_at', 'like', '%' . $this->f_inicial . '%');
                $adquisicionD->where('adquisicion_detalles.updated_at', 'like', '%' . $this->f_inicial . '%');
                $solicitudes->where('solicitudes.updated_at', 'like', '%' . $this->f_inicial . '%');
            }
            if ($this->f_final != 0 and ($this->f_inicial == 0 or $this->f_inicial == '')) {
                $adquisicionesDgiea->where('adquisiciones.updated_at', 'like', '%' . $this->f_final . '%');
                $adquisicionD->where('adquisicion_detalles.updated_at', 'like', '%' . $this->f_final . '%');
                $solicitudes->where('solicitudes.updated_at', 'like', '%' . $this->f_final . '%');
            }
            if ($this->f_final != 0 and $this->f_final != '' and $this->f_inicial != 0 and $this->f_inicial != '') {
                $adquisicionesDgiea->whereDate('adquisiciones.updated_at','>=', $this->f_inicial)
                            ->whereDate('adquisiciones.updated_at','<=', $this->f_final);
                $adquisicionD->whereDate('adquisicion_detalles.updated_at','>=', $this->f_inicial)
                            ->whereDate('adquisicion_detalles.updated_at','<=', $this->f_final);                       
                $solicitudes->whereDate('solicitudes.updated_at','>=', $this->f_inicial)
                            ->whereDate('solicitudes.updated_at','<=', $this->f_final);    
            }

            if ($this->categoria == 0) {
                $requerimientos = $adquisicionesDgiea->union($adquisicionD)->union($solicitudes);    
            } else if ($this->categoria == 1) {    
                $requerimientos = $adquisicionesDgiea->union($adquisicionD);    
            } else if ($this->categoria == 2) {
                $requerimientos = $solicitudes;
            }     

        return view(
            'livewire.seguimiento',
            ['requerimientos' => $requerimientos->orderBy($this->sortColumn, $this->sortDirection)->paginate(10, pageName: 'pendientes')  ]
        );
    }

    public function mount()
    {
        $this->tipoRequisicion = TipoRequisicion::select('id', 'descripcion')->where('estatus', 1)->get();
    }

    public function filterByCategory($categoria)
    {
        $this->categoria = $categoria;
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function limpiarFiltros() {
        $this->categoria = 0;
        $this->search = null;
        $this->f_inicial = null;
        $this->f_final = null;
    }
}