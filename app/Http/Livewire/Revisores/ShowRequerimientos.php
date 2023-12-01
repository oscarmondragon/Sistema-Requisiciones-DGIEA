<?php

namespace App\Http\Livewire\Revisores;

use App\Http\Livewire\AdquisicionDescriptionModal;
use App\Models\AdquisicionDetalle;
use App\Models\AsignacionProyecto;
use App\Models\SolicitudDetalle;
use Livewire\Component;
use App\Models\Adquisicion;
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
    public $f_inicial = 0;
    public $f_final = 0;
    public $f_inicial_vobo = 0;
    public $f_final_vobo = 0;
    public $user;


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
        if($user->rol == 1){//proyectos para adminstrador de proyectos externos
            dd("usuario_rol: estoy en proyectos externos");
            $adquisiciones = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                DB::raw('null as clave_siia'),
                DB::raw('null as concepto'),
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisiciones.estatus_general as id_estatus',
            )->where('adquisiciones.tipo_fianciamiento', 'like', '%Externo%')
            ->whereIn('estatus_requisiciones.tipo', [2,3,5]);

            $requerimientos = $adquisiciones;

        }elseif($user->rol == 2){//proyectos para adminstrador de proyectos internos
            $adquisiciones = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                DB::raw('null as clave_siia'),
                DB::raw('null as concepto'),
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisiciones.estatus_general as id_estatus',
            )->where('adquisiciones.tipo_financiamiento', 'like', '%UAEM%')
            ->whereIn('estatus_requisiciones.tipo', [2,4,5]);

            $solicitud = SolicitudDetalle::join('solicitudes', 'solicitud_detalles.id_solicitud', '=', 'solicitudes.id')
            ->join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
            ->select(
                'solicitudes.id as id',
                'solicitudes.clave_solicitud as id_requerimiento',
                'solicitud_detalles.clave_siia as clave_siia',
                'solicitud_detalles.id as id_requisicion_detalle',
                'solicitud_detalles.concepto as concepto',
                'solicitudes.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'solicitudes.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'solicitudes.tipo_requisicion as  tipo_requerimiento',
                'solicitudes.vobo_admin as vobo_admin',
                'solicitudes.vobo_rt as vobo_rt',
                'solicitudes.estatus_rt as id_estatus',
            )->where('solicitudes.tipo_financiamiento', 'like', '%UAEM%')
            ->whereIn('estatus_requisiciones.tipo', [2, 4, 5]);

            $requerimientos = $adquisiciones->union($solicitud);
            //dd("usuario_rol: estoy en proyectos internos".$requerimientos);
        }else{
            dd("usuario_rol: espero no estar aqui");
        //traemos los proyectos asignados al usuario logueado
        $proyectosAsignados = AsignacionProyecto::select('id_proyecto')->where('id_revisor', $user->id)->pluck('id_proyecto');

        //  dd($proyectosAsignados);
        //Obtenemos adquisiciones con estatus Aceptado,E en revision y rechazados por DGIEA
        /*  $adquisiciones = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
             ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
             ->join('adquisicion_detalles', 'adquisiciones.id', '=', 'adquisicion_detalles.id_adquisicion')
             ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
             ->select(
                 'adquisiciones.id as id',
                 'adquisiciones.clave_adquisicion as id_requerimiento',
                 'adquisicion_detalles.descripcion as concepto',
                 'adquisiciones.clave_proyecto as clave_proyecto',
                 'estatus_requisiciones.descripcion as estado',
                 'adquisiciones.updated_at as modificacion',
                 'cuentas_contables.nombre_cuenta',
                 'tipo_requisiciones.descripcion',
                 'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                 'adquisiciones.vobo_admin as vobo_admin',
                 'adquisiciones.vobo_rt as vobo_rt',
                 'adquisiciones.estatus_general as id_estatus',
             )->whereIn('estatus_general', [3, 5, 6])->whereIn('clave_proyecto', $proyectosAsignados); */

        /*  $adquisicionesDgiea = AdquisicionDetalle::join('adquisiciones', 'adquisicion_detalles.id_adquisicion', '=', 'adquisiciones.id')
             ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
             ->join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
             ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
             ->select(
                 'adquisiciones.id as id',
                 'adquisiciones.clave_adquisicion as id_requerimiento',
                 DB::raw('ANY_VALUE(adquisicion_detalles.descripcion) as concepto'),
                 'adquisiciones.clave_proyecto as clave_proyecto',
                 'estatus_requisiciones.descripcion as estado',
                 'adquisiciones.updated_at as modificacion',
                 'cuentas_contables.nombre_cuenta',
                 'tipo_requisiciones.descripcion',
                 'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                 'adquisiciones.vobo_admin as vobo_admin',
                 'adquisiciones.vobo_rt as vobo_rt',
                 'adquisicion_detalles.estatus_rt as id_estatus',
             )->whereIn('adquisiciones.estatus_general', [3, 5, 6])
             ->whereIn('adquisiciones.clave_proyecto', $proyectosAsignados)
             ->groupBy('adquisicion_detalles.id_adquisicion'); */

        $adquisicionesDgiea = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                DB::raw('null as clave_siia'),
                DB::raw('null as id_requisicion_detalle'), //no existe aqui un detalle
                DB::raw('null as concepto'),
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisiciones.estatus_general as id_estatus',
            )->whereIn('estatus_requisiciones.tipo', [2])->whereIn('clave_proyecto', $proyectosAsignados);
        //dd($adquisicionesDgiea->first());
        $adquisicionDetalles = AdquisicionDetalle::join('adquisiciones', 'adquisicion_detalles.id_adquisicion', '=', 'adquisiciones.id')
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("estatus_requisiciones", "adquisicion_detalles.estatus_rt", "=", "estatus_requisiciones.id")
            ->select(
                'adquisiciones.id as id',
                'adquisiciones.clave_adquisicion as id_requerimiento',
                'adquisicion_detalles.clave_siia as clave_siia',
                'adquisicion_detalles.id as id_requisicion_detalle',
                'adquisicion_detalles.descripcion as concepto',
                'adquisiciones.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'adquisiciones.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'adquisiciones.tipo_requisicion as  tipo_requerimiento',
                'adquisiciones.vobo_admin as vobo_admin',
                'adquisiciones.vobo_rt as vobo_rt',
                'adquisicion_detalles.estatus_rt as id_estatus',
            )->whereIn('estatus_requisiciones.tipo', [3, 5])
            ->whereIn('adquisiciones.clave_proyecto', $proyectosAsignados);
        // dd($adquisicionDetalles->first());

        $solicitudDetalles = SolicitudDetalle::join('solicitudes', 'solicitud_detalles.id_solicitud', '=', 'solicitudes.id')
            ->join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
            ->select(
                'solicitudes.id as id',
                'solicitudes.clave_solicitud as id_requerimiento',
                'solicitud_detalles.clave_siia as clave_siia',
                'solicitud_detalles.id as id_requisicion_detalle',
                'solicitud_detalles.concepto as concepto',
                'solicitudes.clave_proyecto as clave_proyecto',
                'estatus_requisiciones.descripcion as estado',
                'estatus_requisiciones.tipo as tipo_estado',
                'solicitudes.updated_at as modificacion',
                'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion',
                'solicitudes.tipo_requisicion as  tipo_requerimiento',
                'solicitudes.vobo_admin as vobo_admin',
                'solicitudes.vobo_rt as vobo_rt',
                'solicitudes.estatus_rt as id_estatus',
            )->whereIn('estatus_requisiciones.tipo', [2, 4, 5])->whereIn('clave_proyecto', $proyectosAsignados);

        // dd($solicitudDetalles->first());
        if (!empty($this->search)) {
            $adquisicionesDgiea->where(function ($query) {
                $query->where('clave_adquisicion', 'like', '%' . $this->search . '%')
                    ->orWhereHas('requerimiento', function ($query) {
                        $query->where('descripcion', 'like', '%' . $this->search . '%');
                    })->orWhereHas('cuentas', function ($query) {
                    $query->where('nombre_cuenta', 'like', '%' . $this->search . '%');
                });
            });

        }


        if ($this->categoria == 0) {
            $requerimientos = $adquisicionesDgiea->union($adquisicionDetalles)->union($solicitudDetalles)->orderBy($this->sortColumn, $this->sortDirection);
            //dd($requerimientos);
        } else if ($this->categoria == 1) {
            $requerimientos = $adquisicionesDgiea->union($adquisicionDetalles)->orderBy($this->sortColumn, $this->sortDirection);
        } else if ($this->categoria == 2) {
            $requerimientos = $solicitudDetalles->orderBy($this->sortColumn, $this->sortDirection);
        } else if ($this->categoria == 3) {
            $adquisicionesDgiea->where('estatus_general', 4);
            $solicitudDetalles->where('estatus_rt', 4);
            $requerimientos = $adquisicionesDgiea->union($solicitudDetalles)->orderBy($this->sortColumn, $this->sortDirection);
            //dd($requerimientos);
        } else if ($this->categoria == 4) {
            $adquisicionesDgiea->whereIn('estatus_requisiciones.tipo', [2]);
            $solicitudDetalles->whereIn('estatus_requisiciones.tipo', [2]);
            $requerimientos = $adquisicionesDgiea->union($solicitudDetalles)->orderBy($this->sortColumn, $this->sortDirection);
        } else if ($this->categoria == 5) {
            $adquisicionDetalles;
            $solicitudDetalles->whereNotIn('estatus_requisiciones.tipo', [2]);
            $requerimientos = $adquisicionDetalles->union($solicitudDetalles)->orderBy($this->sortColumn, $this->sortDirection);
        }
    }
   // dd($user->rol);
        return view(
            'livewire.revisores.show-requerimientos',
            [ 'requerimientos' => $requerimientos->orderBy('id_requerimiento')->paginate(5, pageName: 'pendientes')
                , 'rol'=>$user->rol,
            ]
        );
    }

}
