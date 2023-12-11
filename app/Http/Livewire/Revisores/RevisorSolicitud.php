<?php

namespace App\Http\Livewire\Revisores;

use Livewire\Component;
use App\Models\CuentaContable;
use Illuminate\Support\Facades\Session;
use App\Models\Solicitud;
use App\Models\SolicitudDetalle;
use App\Models\Documento;
use App\Models\EstatusRequisiciones;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RevisorSolicitud extends Component
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
    public $observacionesVobo;

    public $tipo_comprobacion;
    public $docsbitacoraPdf = [];

    public $bitacoraPdfTemp;

    public $tamanyoDocumentos;
    public $referer = '';

    public $estatusSolicitud = 0;
    public $estatus_solicitudes;
    public $tipoEstatus = 0;
    public $observaciones_estatus;
    public $solicitud_detalles;
    public $clave;
    public $sClaveSiia;


    protected $rules = [
        'estatusSolicitud' => 'required_if:estatusSolicitud,0|not_in:0',
        'sClaveSiia' => 'required_if:tipoEstatus,4|required_if:tipoEstatus,5',
        'observaciones_estatus' => 'required_if:estatusSolicitud,5|required_if:estatusSolicitud,12|required_if:estatusSolicitud,14'
    ];

    protected $messages = [
        'estatusSolicitud.required_if' => 'Debe de seleccionar un estado.',
        'estatusSolicitud.not_in' => 'Debe de seleccionar un estado.',
        'sClaveSiia.required_if' => 'La clave SIIA no puede estar vacía.',
        'observaciones_estatus.required_if' => 'El motivo de rechazo no puede estar vacío.'
    ];

    public $listeners = [
        'save',
    ];

    public function mount($id = 0)
    {
        $this->referer = $_SERVER['HTTP_REFERER'];
        $this->solicitud = Solicitud::find($id);

        $this->estatus_solicitudes = EstatusRequisiciones::whereIn('tipo', [2, 4, 5])->get();

        $this->cuentasContables = CuentaContable::where('estatus', 1)->whereIn('tipo_requisicion', [2, 3])->get();
        $this->nombre_expedido = Session::get('name_rt');
        $this->id_solicitud = $this->solicitud->id;

        $this->solicitud_detalles = SolicitudDetalle::where('id_solicitud', $this->id_solicitud)->first();
        $this->clave = SolicitudDetalle::select('clave_siia')->where('id_solicitud', $this->id_solicitud)->first();
        $this->clave = $this->clave->clave_siia;

        if ($this->clave != null) {
            $this->sClaveSiia = $this->clave;
        } else {
            $this->clave = null;
        }

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
        return view('livewire.revisores.revisor-solicitud');
    }

    public function updated($estatus)
    {
        $this->validateOnly($estatus);
        //$this->validateOnly($sClaveSiia);
    }

    public function save()
    {
        //dd($this->tipoEstatus);
        $this->validate();

        try {
            DB::beginTransaction();

            if ($this->solicitud) {
                $this->solicitud->estatus_dgiea = $this->estatusSolicitud;
                $this->solicitud->estatus_rt = $this->estatusSolicitud;

                if ($this->estatusSolicitud == 5 || $this->estatusSolicitud == 12 || $this->estatusSolicitud == 14) {
                    $this->solicitud->observaciones = $this->observaciones_estatus;
                } else {
                    $this->solicitud->observaciones = null;
                }

                $this->solicitud->save();
            }

            if (($this->tipoEstatus == 4 || $this->tipoEstatus == 5) && $this->clave == null) {
                if ($this->solicitud_detalles) {
                    $this->solicitud_detalles->clave_siia = $this->sClaveSiia;

                    $this->solicitud_detalles->save();
                }
            }


            DB::commit();
            return redirect('/requerimientos-dgiea')->with('success', 'Estatus de la solicitud ' . $this->solicitud->clave_solicitud . ' actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error al intentar confirmar visto bueno. Intente más tarde.' . $e->getMessage());
        }
    }

    public function actualizarTipoEstatus($tipo)
    {
        $this->tipoEstatus = $tipo;
        $this->observaciones_estatus = null;

        if ($this->clave == null) {
            $this->sClaveSiia = null;
        }
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