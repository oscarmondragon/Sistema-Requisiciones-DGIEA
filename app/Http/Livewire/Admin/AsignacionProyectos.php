<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Proyecto;
use App\Models\AsignacionProyecto;

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class AsignacionProyectos extends Component
{
    use WithPagination;


    public $search = "";
    public $searchAsignados = "";

    public $idRevisor;
    public $idConvocatoria;
    public $idTipoProyecto;
    public $idEspacioAcademico;

    public $idRevisorAsignados;
    public $idConvocatoriaAsignados;
    public $idTipoProyectoAsignados;

    public $idEspacioAcademicoAsignados;
    public $revisores;
    public $convocatorias;
    public $tipoProyectos;

    public $claveUaem;

    public $sortColumn = 'NomCenCos';
    public $sortColumnProyAsig = 'espacio_academico';
    public $sortDirection = 'asc';

    public $espaciosAcademicos;
    public $proyectosSeleccionados = [];
    public $activeTab = 1;
    protected $rules = [
        'idRevisor' => 'required|not_in:0',
        'proyectosSeleccionados' => 'required|array|min:1',

    ];
    protected $messages = [
        'idRevisor.required' => 'Debe seleccionar un revisor.',
        'idRevisor.not_in' => 'Debe seleccionar un rubro.',
        'proyectosSeleccionados.required' => 'Debe seleccionar por lo menos un proyecto.',
        'proyectosSeleccionados.array' => 'Debe seleccionar por lo menos un proyecto.',
        'proyectosSeleccionados.min' => 'Debe seleccionar por lo menos un proyecto.',
    ];

    public function mount()
    {
        if (session('success-asignacion')) {
            $this->activeTab = 2;
        }
        // Si el parámetro activeTab existe en la URL, se utilizará su valor. De lo contrario, se utilizará 1 como valor predeterminado.

        //OBTENEMOS DATOS DE USUARIO LOGUEADO
        $user = auth()->user();
        //OBTENEMOS LOS PROYECTOS DE LA VISTA

        if ($user->rol === 1) {
            //Si es un administrador externo, se traen los revisores externos
            $this->revisores = User::where('rol', 3)->get();
            // so obtienes tipos de proyectos para externos y espacios academicos
            $this->tipoProyectos = Proyecto::select(['CveEntCnv', 'NomEntCnv', 'Tipo_Proyecto'])->where('Tipo_Proyecto', '<>', 'UAEM')->distinct('Tipo_Proyecto')->get();
            $this->espaciosAcademicos = Proyecto::select(['CveCenCos', 'NomCenCos'])->where('Tipo_Proyecto', '<>', 'UAEM')->distinct('CveCenCos')->get();
        } else if ($user->rol === 2) { //si es interno se traen revisores internos
            $this->revisores = User::where('rol', 4)->get();
            //se obtienen convocatorias internas y espacios academicos
            $this->convocatorias = Proyecto::select(['CveEntCnv', 'NomEntCnv', 'Tipo_Proyecto'])->where('Tipo_Proyecto', 'UAEM')->distinct('CveEntCnv')->get();
            $this->espaciosAcademicos = Proyecto::select(['CveCenCos', 'NomCenCos'])->where('Tipo_Proyecto', 'UAEM')->distinct('CveCenCos')->get();
        }

        //CONVERTIMOS A COLLECCIONES PARA EVITAR QUE SE VEAN AFECTADOS POR LAS CONSULTAS DEL SEARCH
        $this->revisores = collect($this->revisores);
        $this->convocatorias = collect($this->convocatorias);
        $this->tipoProyectos = collect($this->tipoProyectos);
        $this->espaciosAcademicos = collect($this->espaciosAcademicos);
        //Proyectos que se seleccionan de la tabla con el checkbox los guardamos en una variable de session para no perder los valores al cambiar pagina
        if (session()->has('proyectos_seleccionados')) {
            $this->proyectosSeleccionados = session('proyectos_seleccionados');
        }
    }
    public function updatingSearch()
    {
        $this->resetPage('sinasignar');
        $this->gotoPage(1);
    }

    public function updatingIdEspacioAcademico()
    {
        $this->resetPage('sinasignar');
    }
    public function updatingIdTipoProyecto()
    {
        $this->resetPage('sinasignar');
    }
    public function updatingIdConvocatoria()
    {
        $this->resetPage('sinasignar');
    }

    public function updatingSearchAsignados()
    {
        $this->resetPage('asignados');
    }
    public function updatingIdRevisorAsignados()
    {
        $this->resetPage('asignados');
    }
    public function updatingIdEspacioAcademicoAsignados()
    {
        $this->resetPage('asignados');
    }
    public function updatingIdTipoProyectoAsignados()
    {
        $this->resetPage('asignados');
    }
    public function updatingIdConvocatoriaAsignados()
    {
        $this->resetPage('asignados');
    }


    public function render()
    {

        //Variables para guardar proyectos
        $proyectosSinAsignar = Proyecto::query();
        $proyectosAsignados = AsignacionProyecto::query();

        $idAsignados = AsignacionProyecto::pluck('id_proyecto'); // Obtener  los id_proyecto en proyectosAsignados


        if (auth()->user()->rol === 1) {
            $proyectosSinAsignar = Proyecto::select(['CveEntPry', 'NomEntPry', 'NomCenCos', 'NomEntCnv', 'Tipo_Proyecto', 'Clave_DIGCYN', 'CvePryUaem'])
                ->where('Tipo_Proyecto', '<>', 'UAEM')
                ->whereNotIn('CveEntPry', $idAsignados);
            $proyectosAsignados = AsignacionProyecto::select([
                'id_proyecto', 'clave_uaem', 'clave_digcyn', 'nombre_proyecto',
                'id_espacio_academico', 'espacio_academico', 'convocatoria', 'tipo_proyecto', 'id_revisor'
            ])->where('tipo_proyecto', '<>', 'UAEM');
        } else if (auth()->user()->rol === 2) {

            $proyectosSinAsignar = Proyecto::select(['CveEntPry', 'NomEntPry', 'NomCenCos', 'NomEntCnv', 'Tipo_Proyecto', 'Clave_DIGCYN', 'CvePryUaem'])
                ->where('Tipo_Proyecto', 'UAEM')
                ->whereNotIn('CveEntPry', $idAsignados);

            $proyectosAsignados = AsignacionProyecto::select(['id_proyecto', 'clave_uaem', 'clave_digcyn', 'nombre_proyecto', 'id_espacio_academico', 'espacio_academico', 'convocatoria', 'tipo_proyecto', 'id_revisor'])
                ->where('tipo_proyecto', 'UAEM');
        }
        //$proyectosAsignados = $proyectosAsignados->get();


        //Buscador proyectos asignados
        if (!empty($this->searchAsignados)) {
            $proyectosAsignados->where(function ($proyectosAsignados) {
                $proyectosAsignados->where('nombre_proyecto', 'like', '%' . $this->searchAsignados . '%')
                    ->orWhere('clave_uaem', 'like', '%' . $this->searchAsignados . '%')
                    ->orWhere('clave_digcyn', 'like', '%' . $this->searchAsignados . '%')
                    ->orWhere('espacio_academico', 'like', '%' . $this->searchAsignados . '%')
                    ->orWhere('tipo_proyecto', 'like', '%' . $this->searchAsignados . '%');
            });
        }

        //Buscador proyectos sin asignar
        if (!empty($this->search)) {
            $proyectosSinAsignar->where(function ($query) {
                $query->where('NomEntPry', 'like', '%' . $this->search . '%')
                    ->orWhere('CvePryUaem', 'like', '%' . $this->search . '%')
                    ->orWhere('Clave_DIGCYN', 'like', '%' . $this->search . '%')
                    ->orWhere('NomCenCos', 'like', '%' . $this->search . '%')
                    ->orWhere('Tipo_Proyecto', 'like', '%' . $this->search . '%');
            });
        }

        //Filtros proyectos sin asignar
        if ($this->idConvocatoria) {
            $proyectosSinAsignar->where('CveEntCnv', $this->idConvocatoria);
        }
        if ($this->idTipoProyecto) {
            $proyectosSinAsignar->where('Tipo_Proyecto', $this->idTipoProyecto);
        }
        if ($this->idEspacioAcademico) {
            $proyectosSinAsignar->where('CveCenCos', $this->idEspacioAcademico);
        }
        //Filtros proyectos asignados
        if ($this->idConvocatoriaAsignados) {
            $proyectosAsignados->where('id_convocatoria', $this->idConvocatoriaAsignados);
            //dd($this->idConvocatoriaAsignados);
            //dd($proyectosSinAsignar);
        }
        if ($this->idTipoProyectoAsignados) {
            $proyectosAsignados->where('tipo_proyecto', $this->idTipoProyectoAsignados);
        }
        if ($this->idEspacioAcademicoAsignados) {
            $proyectosAsignados->where('id_espacio_academico', $this->idEspacioAcademicoAsignados);
        }

        if ($this->idRevisorAsignados) {
            $proyectosAsignados->where('id_revisor', $this->idRevisorAsignados);
        }

        //dd($this->idRevisorAsignados);

        //PAGINAMOS LOS PROYECTOS ASIGNADOS
        // $proyectosAsignados = $proyectosAsignados->orderBy('espacio_academico')->paginate(5, pageName: 'asignados');
        // //AGREGAMOS LOS DATOS DEL REVISOR 
        // $id_revisoresAsignados = $proyectosAsignados->getCollection()->map(function ($proyecto) {
        //     // Obtenemos el id de cada revisor 
        //     $id_revisorAsignado = AsignacionProyecto::where('id_proyecto', $proyecto->CveEntPry)
        //         ->value('id_revisor');
        //     // Obtenemos el nombre completo del revisor de la tabla users
        //     $nombreRevisor = User::where('id', $id_revisorAsignado)->value('name');
        //     // Asignamos los atributos para que sean accesibles desde la vista
        //     $proyecto->id_revisor = $id_revisorAsignado; // Agrega el nuevo parámetro al elemento
        //     $proyecto->nombre_revisor = $nombreRevisor; // Agrega el nuevo parámetro al elemento
        //     return $proyecto;
        // });
        // //asignamos los datos del revisor a proyectosAsignados
        // $proyectosAsignados->setCollection($id_revisoresAsignados);

        // Filtrar por id_revisor
        // if ($this->idRevisorAsignados) {
        //     $idRevisor = $this->idRevisorAsignados;
        //     $proyectosAsignados = $proyectosAsignados->filter(function ($proyecto) use ($idRevisor) {
        //         return $proyecto->id_revisor == $idRevisor;
        //     });
        // }

        return view('livewire.admin.asignacion-proyectos', [

            'proyectosSinAsignar' => $proyectosSinAsignar->orderBy($this->sortColumn, $this->sortDirection)->paginate(10, pageName: 'sinasignar'),
            'proyectosAsignados' => $proyectosAsignados->orderBy($this->sortColumnProyAsig, $this->sortDirection)->paginate(10, pageName: 'asignados')
        ]);
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function sortProyAsignados($column)
    {
        $this->sortColumnProyAsig = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function toggleProyectoSeleccionado($cveEntPry)
    {
        if (in_array($cveEntPry, $this->proyectosSeleccionados)) {
            $this->proyectosSeleccionados = array_diff($this->proyectosSeleccionados, [$cveEntPry]);
        } else {
            $this->proyectosSeleccionados[] = $cveEntPry;
        }
        session(['proyectos_seleccionados' => $this->proyectosSeleccionados]);
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

    public function resetearSeleccionados()
    {
        $this->proyectosSeleccionados = [];
        session()->forget('proyectos_seleccionados');
    }

    public function limpiarFiltros()
    {
        $this->idConvocatoria = 0;
        $this->idTipoProyecto = 0;
        $this->idEspacioAcademico = 0;
        $this->search = "";
    }

    public function limpiarFiltrosProyAsignados()
    {
        $this->idConvocatoriaAsignados = 0;
        $this->idTipoProyectoAsignados = 0;
        $this->idEspacioAcademicoAsignados = 0;
        $this->searchAsignados = "";
        $this->idRevisorAsignados = 0;
    }
}