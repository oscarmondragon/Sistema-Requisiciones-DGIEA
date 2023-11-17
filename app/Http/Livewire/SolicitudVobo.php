<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Proyecto;
use App\Models\CuentaContable;
use Illuminate\Support\Facades\Session;
use App\Models\Solicitud;
use App\Models\SolicitudDetalle;
use App\Models\Documento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SolicitudVobo extends Component
{


    public $solicitud;

    public $documentos;
    //catalogos
    public $cuentasContables;

    //Atributos de una solicitud de recursos
    public $id_solicitud; //recupera id en editar
    public $clave_solicitud = '';
    public $tipo_requisicion = '2';
    public $clave_proyecto = '';
    public $clave_espacio_academico = '';

    public $clave_rt = '';
    public $clave_tipo_financiamiento = '';
    public $id_rubro = 0;
    public $id_rubro_especial; //variable para determinar si es una cuenta especial (software por ejemplo)
    public $monto_total;
    public $nombre_expedido;
    public $id_bitacora;
    public $vobo;
    public $vobo_admin = null;
    public $vobo_rt = null;
    public $concepto = "";
    public $justificacionS = "";
    public $finicial;
    public $ffinal;

    public $id_emisor;
    public $id_revisor;
    public $comprobacion;
    public $aviso_privacidad;
    public $estatus_dgiea;
    public $estatus_rt;
    public $observaciones;

    public $tipo_comprobacion;
    public $docsbitacoraPdf = [];

    public $bitacoraPdfTemp;

    public $tamanyoDocumentos;
    public $referer ='';


    protected $rules = [
        'vobo' => 'accepted'
    ];
    protected $messages = [
        'vobo.accepted' => 'Debe dar el visto bueno.'
    ];

    public function mount($id = 0)
    {
        $this->referer = $_SERVER['HTTP_REFERER'];
        $this->solicitud = Solicitud::find($id);

        $this->cuentasContables = CuentaContable::where('estatus', 1)->whereIn('tipo_requisicion', [2, 3])->get();
        $this->nombre_expedido = Session::get('name_rt');
        $this->id_solicitud = $id;
        $this->id_rubro = $this->solicitud->id_rubro;
        $this->id_rubro_especial = $this->solicitud->cuentas->cuentaEspecial->id ?? 0;
        $this->monto_total = $this->solicitud->monto_total;
        $this->nombre_expedido = $this->solicitud->nombre_expedido;
        $this->concepto = $this->solicitud->solicitudDetalle->concepto;
        $this->justificacionS = $this->solicitud->solicitudDetalle->justificacion;
        $this->finicial = $this->solicitud->solicitudDetalle->periodo_inicio;
        $this->ffinal = $this->solicitud->solicitudDetalle->periodo_fin;
        $this->tipo_comprobacion = $this->solicitud->tipo_comprobacion;
        $this->vobo = 0;


        $this->documentos = Documento::where('id_requisicion', $id)->where('tipo_requisicion', 2)->get();
        /* foreach ($this->documentos as $documento) {
            if ($documento->tipo_documento == 4) {
                $this->docsbitacoraPdf[] = $documento;
                // dd($this->docsbitacoraPdf);
            }
        } */

        foreach ($this->documentos as $documento) {
            if ($documento->tipo_documento == 4) {
                $this->docsbitacoraPdf[] = ['datos' => $documento];
                //  dd($this->docsCartaExclusividad);
            }
        }


    }

    public function render()
    {
        return view('livewire.solicitud-vobo')->layout('layouts.cvu');
    }

    protected $listeners = [
        'darVobo'
    ];

    public function darVobo()
    {
        $this->validate();
        
        $who_vobo = Session::get('VoBo_Who');
        $fecha_vobo = Carbon::now()->toDateString();
        $id_user = Session::get('id_user');
        try {
            DB::beginTransaction();
            $solicitud = Solicitud::where('id', $this->solicitud->id)->first();
            if ($solicitud) {
                $clave_solicitud = $solicitud->clave_solicitud;
                $solicitud->estatus_rt = 3;
                $solicitud->estatus_dgiea = 3;

                if ($who_vobo) { //Si el deposito es por parte del Responsable técnico
                    $solicitud->vobo_rt = $fecha_vobo;
                } else { //Si el depósito es por parte del administrativo
                    $solicitud->vobo_admin = $fecha_vobo;
                }
                $solicitud->save();

            }
            DB::commit();
            return redirect('/cvu-vobo')->with('success', 'Su solicitud con clave ' . $clave_solicitud . ' ha sido  enviada para revision a la DGIEA.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error al intentar confirmar visto bueno. Intente más tarde.' . $e->getMessage());
        }




    }
    public function updated($vobo)
    {
        $this->validateOnly($vobo);
    }

    public function descargarArchivo($rutaDocumento, $nombreDocumento)
    {
        $rutaArchivo = storage_path('app/' . $rutaDocumento);

        if (Storage::exists($rutaDocumento)) {
            return response()->download(storage_path('app/' . $rutaDocumento), $nombreDocumento);
        } else {
            abort(404);
        }
    }
}
