<?php

namespace App\Http\Livewire\Revisores;

use App\Http\Livewire\AdquisicionDescriptionModal;
use App\Models\AdquisicionDetalle;
use App\Models\AsignacionProyecto;
use App\Models\SolicitudDetalle;
use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\EstatusRequisiciones;
use App\Models\Solicitud;
use App\Models\TipoRequisicion;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ShowRequerimientos extends Component
{

    use WithPagination;
    public $tipo;
    public $search = '';
    public $searchVobo = '';

    public $categoria = 0;
    public $categoriaVobo = 0;
    public $tipoRequisicion;
    public $f_inicial;
    public $f_final;
    public $user;
    public $queryEstatus;


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
        if($user->rol == 1){//proyectos para adminstrador de proyectos externos OSCAR

            $adquisicionesDgiea = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->join("asignacion_proyectos", "adquisiciones.clave_proyecto","=", "asignacion_proyectos.id_proyecto")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                DB::raw('null as clave_siia'),
                DB::raw('null as id_requisicion_detalle'), //no existe aqui un detalle
                DB::raw('null as concepto'),
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'estatus_requisiciones.color as color_estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisiciones.estatus_general as id_estatus',
                'asignacion_proyectos.clave_uaem',
                'asignacion_proyectos.clave_digcyn'
            )->whereIn('estatus_requisiciones.tipo', [2])
            ->where('adquisiciones.tipo_financiamiento', 'like', '%Externo%');
       
            $adquisicionDetalles = AdquisicionDetalle::join('adquisiciones', 'adquisicion_detalles.id_adquisicion', '=', 'adquisiciones.id')
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("estatus_requisiciones", "adquisicion_detalles.estatus_rt", "=", "estatus_requisiciones.id")
            ->join("asignacion_proyectos", "adquisiciones.clave_proyecto","=", "asignacion_proyectos.id_proyecto")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                'adquisicion_detalles.clave_siia as clave_siia',
                'adquisicion_detalles.id as id_requisicion_detalle',
                'adquisicion_detalles.descripcion as concepto',
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'estatus_requisiciones.color as color_estado',
                'adquisicion_detalles.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisicion_detalles.estatus_rt as id_estatus',
                'asignacion_proyectos.clave_uaem',
                'asignacion_proyectos.clave_digcyn'
            )->whereIn('estatus_requisiciones.tipo', [3, 5])
            ->where('adquisiciones.tipo_financiamiento', 'like', '%Externo%');

            $solicitudDetalles = SolicitudDetalle::join('solicitudes', 'solicitud_detalles.id_solicitud', '=', 'solicitudes.id')
            ->join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
            ->join("asignacion_proyectos", "solicitudes.clave_proyecto","=", "asignacion_proyectos.id_proyecto")
            ->select(
                'solicitudes.id as id',
                'solicitudes.clave_solicitud as id_requerimiento',
                'solicitud_detalles.clave_siia as clave_siia',
                'solicitud_detalles.id as id_requisicion_detalle',
                'solicitud_detalles.concepto as concepto',
                'solicitudes.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'estatus_requisiciones.color as color_estado',
                'solicitudes.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'solicitudes.tipo_requisicion as  tipo_requerimiento',
                'solicitudes.vobo_admin as vobo_admin',
                'solicitudes.vobo_rt as vobo_rt',
                'solicitudes.estatus_rt as id_estatus',
                'asignacion_proyectos.clave_uaem',
                'asignacion_proyectos.clave_digcyn'
            )->whereIn('estatus_requisiciones.tipo', [2, 4, 5])
            ->where('solicitudes.tipo_financiamiento', 'like', '%Externo%');

        }elseif($user->rol == 2){//proyectos para adminstrador de proyectos internos
            $adquisicionesDgiea = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->join("asignacion_proyectos", "adquisiciones.clave_proyecto","=", "asignacion_proyectos.id_proyecto")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                DB::raw('null as clave_siia'),
                DB::raw('null as id_requisicion_detalle'), //no existe aqui un detalle
                DB::raw('null as concepto'),
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'estatus_requisiciones.color as color_estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisiciones.estatus_general as id_estatus',
                'asignacion_proyectos.clave_uaem',
                'asignacion_proyectos.clave_digcyn'
            )->whereIn('estatus_requisiciones.tipo', [2])
            ->where('adquisiciones.tipo_financiamiento', 'like', '%UAEM%');
       
            $adquisicionDetalles = AdquisicionDetalle::join('adquisiciones', 'adquisicion_detalles.id_adquisicion', '=', 'adquisiciones.id')
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("estatus_requisiciones", "adquisicion_detalles.estatus_rt", "=", "estatus_requisiciones.id")
            ->join("asignacion_proyectos", "adquisiciones.clave_proyecto","=", "asignacion_proyectos.id_proyecto")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                'adquisicion_detalles.clave_siia as clave_siia',
                'adquisicion_detalles.id as id_requisicion_detalle',
                'adquisicion_detalles.descripcion as concepto',
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'estatus_requisiciones.color as color_estado',
                'adquisicion_detalles.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisicion_detalles.estatus_rt as id_estatus',
                'asignacion_proyectos.clave_uaem',
                'asignacion_proyectos.clave_digcyn'
            )->whereIn('estatus_requisiciones.tipo', [3, 5])
            ->where('adquisiciones.tipo_financiamiento', 'like', '%UAEM%');

            $solicitudDetalles = SolicitudDetalle::join('solicitudes', 'solicitud_detalles.id_solicitud', '=', 'solicitudes.id')
            ->join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
            ->join("asignacion_proyectos", "solicitudes.clave_proyecto","=", "asignacion_proyectos.id_proyecto")
            ->select(
                'solicitudes.id as id',
                'solicitudes.clave_solicitud as id_requerimiento',
                'solicitud_detalles.clave_siia as clave_siia',
                'solicitud_detalles.id as id_requisicion_detalle',
                'solicitud_detalles.concepto as concepto',
                'solicitudes.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'estatus_requisiciones.color as color_estado',
                'solicitudes.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'solicitudes.tipo_requisicion as  tipo_requerimiento',
                'solicitudes.vobo_admin as vobo_admin',
                'solicitudes.vobo_rt as vobo_rt',
                'solicitudes.estatus_rt as id_estatus',
                'asignacion_proyectos.clave_uaem',
                'asignacion_proyectos.clave_digcyn'
            )->whereIn('estatus_requisiciones.tipo', [2, 4, 5])
            ->where('solicitudes.tipo_financiamiento', 'like', '%UAEM%');

        }else{
            //traemos los proyectos asignados al usuario logueado
            $proyectosAsignados = AsignacionProyecto::select('id_proyecto')->where('id_revisor', $user->id)->pluck('id_proyecto');

            $adquisicionesDgiea = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->join("asignacion_proyectos", "adquisiciones.clave_proyecto","=", "asignacion_proyectos.id_proyecto")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                DB::raw('null as clave_siia'),
                DB::raw('null as id_requisicion_detalle'), //no existe aqui un detalle
                DB::raw('null as concepto'),
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'estatus_requisiciones.color as color_estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisiciones.estatus_general as id_estatus',
                'asignacion_proyectos.clave_uaem',
                'asignacion_proyectos.clave_digcyn'
            )->whereIn('estatus_requisiciones.tipo', [2])->whereIn('clave_proyecto', $proyectosAsignados);
        
           $adquisicionDetalles = AdquisicionDetalle::join('adquisiciones', 'adquisicion_detalles.id_adquisicion', '=', 'adquisiciones.id')
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("estatus_requisiciones", "adquisicion_detalles.estatus_rt", "=", "estatus_requisiciones.id")
            ->join("asignacion_proyectos", "adquisiciones.clave_proyecto","=", "asignacion_proyectos.id_proyecto")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                'adquisicion_detalles.clave_siia as clave_siia',
                'adquisicion_detalles.id as id_requisicion_detalle',
                'adquisicion_detalles.descripcion as concepto',
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'estatus_requisiciones.color as color_estado',
                'adquisicion_detalles.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisicion_detalles.estatus_rt as id_estatus',
                'asignacion_proyectos.clave_uaem',
                'asignacion_proyectos.clave_digcyn'
            )->whereIn('estatus_requisiciones.tipo', [3, 5])
            ->whereIn('adquisiciones.clave_proyecto', $proyectosAsignados);

            $solicitudDetalles = SolicitudDetalle::join('solicitudes', 'solicitud_detalles.id_solicitud', '=', 'solicitudes.id')
            ->join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
            ->join("asignacion_proyectos", "solicitudes.clave_proyecto","=", "asignacion_proyectos.id_proyecto")
            ->select(
                'solicitudes.id as id',
                'solicitudes.clave_solicitud as id_requerimiento',
                'solicitud_detalles.clave_siia as clave_siia',
                'solicitud_detalles.id as id_requisicion_detalle',
                'solicitud_detalles.concepto as concepto',
                'solicitudes.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'estatus_requisiciones.color as color_estado',
                'solicitudes.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'solicitudes.tipo_requisicion as  tipo_requerimiento',
                'solicitudes.vobo_admin as vobo_admin',
                'solicitudes.vobo_rt as vobo_rt',
                'solicitudes.estatus_rt as id_estatus',
                'asignacion_proyectos.clave_uaem',
                'asignacion_proyectos.clave_digcyn'
            )->whereIn('estatus_requisiciones.tipo', [2, 4, 5])->whereIn('clave_proyecto', $proyectosAsignados);
        }

        if (!empty($this->search)) {

            $adquisicionesDgiea->where(function ($query) {
                $query-> where('adquisiciones.clave_adquisicion', 'like', '%' .$this->search. '%')
                ->orWhere('cuentas_contables.nombre_cuenta', 'like', '%' .$this->search. '%')
                ->orWhere('tipo_requisiciones.descripcion', 'like', '%' .$this->search. '%')
                ->orWhere('estatus_requisiciones.descripcion', 'like', '%' .$this->search. '%');
            });
            $adquisicionDetalles->where(function ($query) {
                $query-> where('adquisiciones.clave_adquisicion', 'like', '%' .$this->search. '%')
                ->orWhere('cuentas_contables.nombre_cuenta', 'like', '%' .$this->search. '%')
                ->orWhere('adquisicion_detalles.descripcion', 'like', '%' .$this->search. '%')
                ->orWhere('adquisicion_detalles.clave_siia', 'like', '%' . $this->search . '%')
                ->orWhere('tipo_requisiciones.descripcion', 'like', '%' .$this->search. '%')
                ->orWhere('estatus_requisiciones.descripcion', 'like', '%' .$this->search. '%');
            });
            $solicitudDetalles->where(function ($query) {
                $query-> where('solicitudes.clave_solicitud', 'like', '%' .$this->search. '%')
                ->orWhere('cuentas_contables.nombre_cuenta', 'like', '%' .$this->search. '%')
                ->orWhere('solicitud_detalles.concepto', 'like', '%' .$this->search. '%')
                ->orWhere('solicitud_detalles.clave_siia', 'like', '%' . $this->search . '%')
                ->orWhere('tipo_requisiciones.descripcion', 'like', '%' .$this->search. '%')
                ->orWhere('estatus_requisiciones.descripcion', 'like', '%' .$this->search. '%');
            });
        }

        if ($this->f_inicial != 0 and ($this->f_final == 0 or $this->f_final =='')) {
            $adquisicionesDgiea->where('adquisiciones.updated_at', 'like', '%' . $this->f_inicial . '%');
            $adquisicionDetalles->where('adquisicion_detalles.updated_at', 'like', '%' . $this->f_inicial . '%');
            $solicitudDetalles->where('solicitudes.updated_at', 'like', '%' . $this->f_inicial . '%');
        }
        if ($this->f_final != 0 and ($this->f_inicial == 0 or $this->f_inicial == '')) {
            $adquisicionesDgiea->where('adquisiciones.updated_at', 'like', '%' . $this->f_final . '%');
            $adquisicionDetalles->where('adquisicion_detalles.updated_at', 'like', '%' . $this->f_final . '%');
            $solicitudDetalles->where('solicitudes.updated_at', 'like', '%' . $this->f_final . '%');
        }
        if ($this->f_final != 0 and $this->f_final != '' and $this->f_inicial != 0 and $this->f_inicial != '') {
            $adquisicionesDgiea->whereDate('adquisiciones.updated_at','>=', $this->f_inicial)
                        ->whereDate('adquisiciones.updated_at','<=', $this->f_final);
            $adquisicionDetalles->whereDate('adquisicion_detalles.updated_at','>=', $this->f_inicial)
                        ->whereDate('adquisicion_detalles.updated_at','<=', $this->f_final);                       
            $solicitudDetalles->whereDate('solicitudes.updated_at','>=', $this->f_inicial)
                        ->whereDate('solicitudes.updated_at','<=', $this->f_final);    
        }

        if ($this->categoria == 0) {
            $requerimientos = $adquisicionesDgiea->union($adquisicionDetalles)->union($solicitudDetalles)->orderBy($this->sortColumn, $this->sortDirection);
        } else if ($this->categoria == 1) {
            $requerimientos = $adquisicionesDgiea->union($adquisicionDetalles)->orderBy($this->sortColumn, $this->sortDirection);
        } else if ($this->categoria == 2) {
            $requerimientos = $solicitudDetalles->orderBy($this->sortColumn, $this->sortDirection);
        } else if ($this->categoria == 3) {
            $adquisicionesDgiea->where('estatus_general', 4);
            $solicitudDetalles->where('estatus_rt', 4);
            $requerimientos = $adquisicionesDgiea->union($solicitudDetalles)->orderBy($this->sortColumn, $this->sortDirection);
        } else if ($this->categoria == 4) {
            $solicitudDetalles->where('estatus_requisiciones.tipo', [2]);
            $requerimientos = $adquisicionesDgiea->union($solicitudDetalles)->orderBy($this->sortColumn, $this->sortDirection);
        } else if ($this->categoria == 5) {
            $adquisicionDetalles;
            $solicitudDetalles->whereNotIn('estatus_requisiciones.tipo', [2]);
            $requerimientos = $adquisicionDetalles->union($solicitudDetalles)->orderBy($this->sortColumn, $this->sortDirection);
        }

        return view(
            'livewire.revisores.show-requerimientos',
            [ 'requerimientos' => $requerimientos->orderBy('id_requerimiento')->paginate(5, pageName: 'pendientes')
                , 'rol'=>$user->rol,
            ]
        );
    }

    public function limpiarFiltros(){
        $this->categoria = 0;
        $this->search = null;
        $this->f_inicial = null;
        $this->f_final = null;
    }
}
