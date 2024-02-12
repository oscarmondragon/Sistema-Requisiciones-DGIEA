<?php

namespace App\Http\Livewire\Revisores;

use Livewire\Component;
use App\Models\User;
use App\Models\Proyecto;
use App\Models\AsignacionProyecto;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProyectosAsignadosRevisor extends Component
{

    use WithPagination;

    public $fechaHoy;
    public $search = "";
    public $idConvocatoria;
    public $idTipoProyecto;
    public $idEspacioAcademico;

    public $idConvocatoriaAsignados;
    public $idTipoProyectoAsignados;

    public $idEspacioAcademicoAsignados;

    public $tipoProyectos;

    public $claveUaem;

    public $sortColumn = 'id_espacio_academico';
    public $sortDirection = 'asc';


    public function mount()
    {
        $this->fechaHoy = Carbon::now();
        $user = auth()->user();
        //OBTENEMOS LOS PROYECTOS DE LA VISTA

        if ($user->rol === 3) {
            //Si es un revisor externo
            // so obtienes tipos de proyectos para externos y espacios academicos
            $this->tipoProyectos = Proyecto::select(['CveEntCnv', 'NomEntCnv', 'Tipo_Proyecto'])->where('Tipo_Proyecto', '<>', 'UAEM')->distinct('Tipo_Proyecto')->get();
            $this->espaciosAcademicos = Proyecto::select(['CveCenCos', 'NomCenCos'])->where('Tipo_Proyecto', '<>', 'UAEM')->distinct('CveCenCos')->get();
            //CONVERTIMOS A COLLECCIONES PARA EVITAR QUE SE VEAN AFECTADOS POR LAS CONSULTAS DEL SEARCH
            $this->tipoProyectos = collect($this->tipoProyectos);

        } else if ($user->rol === 4) { //si es interno
            //se obtienen convocatorias internas y espacios academicos
            $this->convocatorias = Proyecto::select(['CveEntCnv', 'NomEntCnv', 'Tipo_Proyecto'])->where('Tipo_Proyecto', 'UAEM')->distinct('CveEntCnv')->get();
            $this->espaciosAcademicos = Proyecto::select(['CveCenCos', 'NomCenCos'])->where('Tipo_Proyecto', 'UAEM')->distinct('CveCenCos')->get();
            //CONVERTIMOS A COLLECCIONES PARA EVITAR QUE SE VEAN AFECTADOS POR LAS CONSULTAS DEL SEARCH
            $this->convocatorias = collect($this->convocatorias);

        }

        //CONVERTIMOS A COLLECCIONES PARA EVITAR QUE SE VEAN AFECTADOS POR LAS CONSULTAS DEL SEARCH
        $this->espaciosAcademicos = collect($this->espaciosAcademicos);

    }
    public function updatingSearch()
    {
        $this->resetPage('asignados');
        $this->gotoPage(1);
    }

    public function updatingIdEspacioAcademico()
    {
        $this->resetPage('asignados');
    }
    public function updatingIdTipoProyecto()
    {
        $this->resetPage('asignados');
    }
    public function updatingIdConvocatoria()
    {
        $this->resetPage('asignados');
    }


    public function render()
    {

        //Variables para guardar proyectos
        $proyectosAsignados = AsignacionProyecto::query();

        $idAsignados = AsignacionProyecto::pluck('id_proyecto'); // Obtener  los id_proyecto en proyectosAsignados


        if (auth()->user()->rol === 3) { //revisor externo

            $proyectosAsignados = AsignacionProyecto::select([
                'id_proyecto',
                'clave_uaem',
                'clave_digcyn',
                'nombre_proyecto',
                'id_espacio_academico',
                'espacio_academico',
                'convocatoria',
                'id_revisor',
                'fecha_inicio',
                'fecha_final',
                'fecha_limite_adquisiciones',
                'fecha_limite_solicitudes'

            ])->where('tipo_proyecto', '<>', 'UAEM')->where('id_revisor', auth()->user()->id);
        } else if (auth()->user()->rol === 4) { //revior interno

            $proyectosAsignados = AsignacionProyecto::select([
                'id_proyecto',
                'clave_uaem',
                'clave_digcyn',
                'nombre_proyecto',
                'id_espacio_academico',
                'espacio_academico',
                'convocatoria',
                'id_revisor',
                'fecha_inicio',
                'fecha_final',
                'fecha_limite_adquisiciones',
                'fecha_limite_solicitudes'
            ])
                ->where('tipo_proyecto', 'UAEM')->where('id_revisor', auth()->user()->id);
        }


        //Buscador proyectos asignados
        if (!empty($this->search)) {
            $proyectosAsignados->where(function ($proyectosAsignados) {
                $proyectosAsignados->where('nombre_proyecto', 'like', '%' . $this->search . '%')
                    ->orWhere('clave_uaem', 'like', '%' . $this->search . '%')
                    ->orWhere('clave_digcyn', 'like', '%' . $this->search . '%')
                    ->orWhere('espacio_academico', 'like', '%' . $this->search . '%')
                    ->orWhere('tipo_proyecto', 'like', '%' . $this->search . '%');
            });
        }

        //Filtros proyectos asignados
        if ($this->idConvocatoriaAsignados) {
            $proyectosAsignados->where('id_convocatoria', $this->idConvocatoriaAsignados);

        }
        if ($this->idTipoProyectoAsignados) {
            $proyectosAsignados->where('tipo_proyecto', $this->idTipoProyectoAsignados);
        }
        if ($this->idEspacioAcademicoAsignados) {
            $proyectosAsignados->where('id_espacio_academico', $this->idEspacioAcademicoAsignados);
        }


        return view('livewire.revisores.proyectos-asignados-revisor', [
            'proyectosAsignados' => $proyectosAsignados->orderBy($this->sortColumn, $this->sortDirection)->paginate(5, pageName: 'asignados')
        ]);
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            foreach ($this->proyectosSeleccionados as $id_proyecto) {
                $asigando = AsignacionProyecto::where('id_proyecto', $id_proyecto)->first();
                if (!$asigando) {
                    $datosProyecto = Proyecto::where('CveEntPry', $id_proyecto)->first();
                    //dd($datosProyecto);
                    $elemento = AsignacionProyecto::create([
                        'id_proyecto' => $id_proyecto,
                        'clave_uaem' => $datosProyecto->CvePryUaem,
                        'clave_digcyn' => $datosProyecto->Clave_DIGCYN,
                        'nombre_proyecto' => $datosProyecto->NomEntPry,
                        'id_espacio_academico' => $datosProyecto->CveCenCos,
                        'espacio_academico' => $datosProyecto->NomCenCos,
                        'id_convocatoria' => $datosProyecto->CveEntCnv,
                        'convocatoria' => $datosProyecto->NomEntCnv,
                        'tipo_proyecto' => $datosProyecto->Tipo_Proyecto,
                        'id_revisor' => $this->idRevisor,
                        'id_usuario_sesion' => auth()->user()->id,
                    ]);
                }
            }
            DB::commit();

            //Borramos datos de variable de sesion, preoyectos seleccionados y el revisor
            $this->resetearSeleccionados();
            $this->idRevisor = '';
            $this->search = '';

            return redirect()->back()->with('success', '¡Asignacion de proyectos exitosa!');
        } catch (\Exception $e) {
            //dd("Error en el catch".$e); 
            DB::rollback();
            return redirect()->back()->with('error', '¡error al asignar proyectos!' . $e->getMessage());
        }
    }

    public function updatedidRevisor()
    {
        $this->validate();
    }


    public function limpiarFiltros()
    {
        $this->idConvocatoriaAsignados = 0;
        $this->idTipoProyectoAsignados = 0;
        $this->idEspacioAcademicoAsignados = 0;
        $this->search = null;
        $this->idRevisorAsignados = 0;
    }


}
