<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\Solicitud;
use App\Models\TipoRequisicion;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seguimiento extends Component
{
    use WithPagination;
    public $tipo;
    public $search = '';
    public $searchSIIA = '';
    public $categoria = 0;
    public $categoriaSIIA = 0;
    public $tipoRequisicion;
    public $f_inicial = 0;
    public $f_inicialSIIA = 0;
    public $f_final = 0;
    public $f_finalSIIA = 0;

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
        $adquisiciones = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
            ->select( 'adquisiciones.id as id', 'adquisiciones.id_emisor as id_emisor',
                'adquisiciones.clave_adquisicion as id_requerimiento', 'adquisiciones.tipo_requisicion as tRequisicion',
                'estatus_requisiciones.descripcion as estado', 'adquisiciones.estatus_general as estadoReq',
                'adquisiciones.updated_at as modificacion', 'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion', 'adquisiciones.observaciones as observaciones')
            ->whereIn('estatus_general', [3, 6, 5])->where('clave_proyecto', '=', session('id_proyecto'));
        $solicitudes = Solicitud::join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
            ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
            ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
            ->select('solicitudes.id as id', 'solicitudes.id_emisor as id_emisor',
                'solicitudes.clave_solicitud as id_requerimiento', 'solicitudes.tipo_requisicion as tRequisicion',
                'estatus_requisiciones.descripcion as estado', 'solicitudes.estatus_rt as estadoReq',
                'solicitudes.updated_at as modificacion',  'cuentas_contables.nombre_cuenta',
                'tipo_requisiciones.descripcion','solicitudes.observaciones as observaciones' )
            ->whereIn('estatus_rt', [3, 6, 5])->where('clave_proyecto', '=', session('id_proyecto'));    
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
        if ($this->f_inicial != 0 and ($this->f_final == 0 or $this->f_final =='')) {
            $adquisiciones->where('adquisiciones.updated_at', 'like', '%' . $this->f_inicial . '%');
            $solicitudes->where('solicitudes.updated_at', 'like', '%' . $this->f_inicial . '%');
           // dd('inicial '.$this->f_final.'-fin-'.$this->f_inicial.'-');
        }
        if ($this->f_final != 0 and ($this->f_inicial == 0 or $this->f_inicial == '')) {
            $adquisiciones->where('adquisiciones.updated_at', 'like', '%' . $this->f_final . '%');
            $solicitudes->where('solicitudes.updated_at', 'like', '%' . $this->f_final . '%');
            //dd('final'.$this->f_final.'-inic-'.$this->f_inicial);
        }
        if ($this->f_final != 0 and $this->f_final != '' and $this->f_inicial != 0 and $this->f_inicial != '') {
            $adquisiciones->whereDate('adquisiciones.updated_at','>=', $this->f_inicial)
                        ->whereDate('adquisiciones.updated_at','<=', $this->f_final);
            $solicitudes->whereDate('solicitudes.updated_at','>=', $this->f_inicial)
                        ->whereDate('solicitudes.updated_at','<=', $this->f_final);
          // dd('las dos'.$this->f_final.''.$this->f_inicial);

        }

        if ($this->categoria == 0) {
            $requerimientos = $adquisiciones->union($solicitudes);

        } else if ($this->categoria == 1) {

            $requerimientos = $adquisiciones;

        } else if ($this->categoria == 2) {
            $requerimientos = $solicitudes;
        }

        $adquisicionesSIIA = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
        ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
        ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
        ->select( 'adquisiciones.id as id', 'adquisiciones.id_emisor as id_emisor',
            'adquisiciones.clave_adquisicion as id_requerimiento', 'adquisiciones.tipo_requisicion as tRequisicion',
            'estatus_requisiciones.descripcion as estado', 'adquisiciones.estatus_general as estadoReq',
            'adquisiciones.updated_at as modificacion', 'cuentas_contables.nombre_cuenta',
            'tipo_requisiciones.descripcion', 'adquisiciones.observaciones as observaciones')
        ->whereIn('estatus_general', [7,8,9,10,11,12,13])->where('clave_proyecto', '=', session('id_proyecto'));
    $solicitudesSIIA = Solicitud::join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
        ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
        ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
        ->select('solicitudes.id as id', 'solicitudes.id_emisor as id_emisor',
            'solicitudes.clave_solicitud as id_requerimiento', 'solicitudes.tipo_requisicion as tRequisicion',
            'estatus_requisiciones.descripcion as estado', 'solicitudes.estatus_rt as estadoReq',
            'solicitudes.updated_at as modificacion',  'cuentas_contables.nombre_cuenta',
            'tipo_requisiciones.descripcion','solicitudes.observaciones as observaciones' )
        ->whereIn('estatus_rt', [7,8,9,10,11,12,13])->where('clave_proyecto', '=', session('id_proyecto'));    
    //si palabra clave esta vacia no se ejecuta
    if (!empty($this->searchSIIA)) {
        $adquisicionesSIIA->where(function ($query) {
            $query->where('clave_adquisicion', 'like', '%' . $this->searchSIIA . '%')
                ->orWhereHas('detalless', function ($query) {
                    $query->where('descripcion', 'like', '%' . $this->searchSIIA . '%');
                })->orWhereHas('estatus', function ($query) {
                $query->where('descripcion', 'like', '%' . $this->searchSIIA . '%');
            });
        });
        $solicitudesSIIA->where(function ($query) {
            $query->where('clave_solicitud', 'like', '%' . $this->searchSIIA . '%')
                ->orWhereHas('detalless', function ($query) {
                    $query->where('descripcion', 'like', '%' . $this->searchSIIA . '%');
                })->orWhereHas('estatusSolicitud', function ($query) {
                $query->where('descripcion', 'like', '%' . $this->searchSIIA . '%');
            });
        });
    }
    if ($this->f_inicialSIIA != 0 and ($this->f_finalSIIA == 0 or $this->f_finalSIIA =='')) {
        $adquisicionesSIIA->where('adquisiciones.updated_at', 'like', '%' . $this->f_inicialSIIA . '%');
        $solicitudesSIIA->where('solicitudes.updated_at', 'like', '%' . $this->f_inicialSIIA . '%');
       // dd('inicial '.$this->f_final.'-fin-'.$this->f_inicial.'-');
    }
    if ($this->f_finalSIIA != 0 and ($this->f_inicialSIIA == 0 or $this->f_inicialSIIA == '')) {
        $adquisicionesSIIA->where('adquisiciones.updated_at', 'like', '%' . $this->f_finalSIIA . '%');
        $solicitudesSIIA->where('solicitudes.updated_at', 'like', '%' . $this->f_finalSIIA . '%');
        //dd('final'.$this->f_final.'-inic-'.$this->f_inicial);
    }
    if ($this->f_finalSIIA != 0 and $this->f_finalSIIA != '' and $this->f_inicialSIIA != 0 and $this->f_inicialSIIA != '') {
        $adquisicionesSIIA->whereDate('adquisiciones.updated_at','>=', $this->f_inicialSIIA)
                    ->whereDate('adquisiciones.updated_at','<=', $this->f_finalSIIA);
        $solicitudesSIIA->whereDate('solicitudes.updated_at','>=', $this->f_inicialSIIA)
                    ->whereDate('solicitudes.updated_at','<=', $this->f_finalSIIA);
      // dd('las dos'.$this->f_final.''.$this->f_inicial);

    }

    if ($this->categoriaSIIA == 0) {
        $requerimientosSIIA = $adquisicionesSIIA->union($solicitudesSIIA)->orderBy('id')->paginate(5, pageName: 'pendientesSIIA');
        
    } else if ($this->categoriaSIIA == 1) {
        $requerimientosSIIA = $adquisicionesSIIA->orderBy('id')->paginate(5, pageName: 'pendientesSIIA');

    } else if ($this->categoriaSIIA == 2) {
        $requerimientosSIIA = $solicitudesSIIA->orderBy('id')->paginate(5, pageName: 'pendientesSIIA');
    }

        return view(
            'livewire.seguimiento',
            ['requerimientos' => $requerimientos->orderBy('id')->paginate(5, pageName: 'pendientes'), 'requerimientosSIIA'=> $requerimientosSIIA]
        );
    }

    public function mount()
    {
        $this->tipoRequisicion = TipoRequisicion::select('id', 'descripcion')->where('estatus', 1)->get();
    }

    public function filterByCategory($categoria)
    {
        $this->categoria = $categoria;
        // dd($this->categoria);
    }
}