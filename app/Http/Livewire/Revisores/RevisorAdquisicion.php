<?php

namespace App\Http\Livewire\Revisores;

use App\Models\EstatusRequisiciones;
use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\AdquisicionDetalle;
use App\Models\Documento;
use App\Models\CuentaContable;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RevisorAdquisicion extends Component
{
    //Campos que se llenan en este formulario
    public $estatus = 0;
    public $observaciones_estatus;
    public $claveSiia;
    public $estatus_generales;
    //atrubutos de la requisicion recuperada
    public $adquisicion;
    public $documentos;
    public $cuentasContables;

    //atributos de una adquisicion
    public $id_adquisicion; //recupera id 
    public $id_adquisicion_detalle; //recupera id
    public $id_rubro = 0;
    public $id_rubro_especial; //variable para determinar si es una cuenta especial (software por ejemplo)
    public $afecta_investigacion = '0';
    public $justificacion_academica;
    public $exclusividad = '0';
    public $id_carta_exclusividad;
    public $id_emisor;
    public $estatus_general;
    public $subtotal = 0;
    public $iva = 0;
    public $total = 0;

    public $vobo;
    public $bienesDB;
    public $bienes;
    public $docsCartaExclusividad = [];
    public $docsCotizacionesFirmadas = [];
    public $docsCotizacionesPdf = [];
    public $docsAnexoOtrosDocumentos = [];

    public $observacionesVobo;
    public $tipoEstatus;

    public $verDetalles = 0;

    protected $rules = [
        'estatus' => 'required_if:estatus,0|not_in:0',
        'observaciones_estatus' => 'required_if:estatus,5',
        'claveSiia' => 'required_if:estatus,7',
    ];
    protected $messages = [
        'estatus.required_if' => 'Debe seleccionar un estado.',
        'estatus.not_in' => 'Debe seleccionar un estado.',
        'observaciones_estatus.required_if' => 'Debe escribir las observaciones o motivos de rechazo.',
        'claveSiia.required_if' => 'Debe de escribir la clave del SIIA.',
    ];

    public $listeners = [
        'save',
    ];


    public function mount($id = 0, $id_requisicion_detalle = null)
    {
        //Recuperamos valores enviados en la ruta
        $this->id_adquisicion = $id;
        $this->id_adquisicion_detalle = $id_requisicion_detalle;

        $this->tipoEstatus = EstatusRequisiciones::where('id', $this->estatus)->first();

        if ($this->id_adquisicion_detalle != null) {
            $this->estatus_generales = EstatusRequisiciones::whereIn('tipo', [3, 5])->get();
        } else {
            $this->estatus_generales = EstatusRequisiciones::whereIn('tipo', [2, 3, 5])->get();
        }

        $this->adquisicion = Adquisicion::find($id);

        $this->id_rubro = $this->adquisicion->id_rubro;
        $this->id_rubro_especial = $this->adquisicion->cuentas->cuentaEspecial->id ?? 0;
        $this->justificacion_academica = $this->adquisicion->justificacion_academica;
        $this->afecta_investigacion = $this->adquisicion->afecta_investigacion;
        $this->exclusividad = $this->adquisicion->exclusividad;
        $this->docsCartaExclusividad = $this->adquisicion->docsCartaExclusividad;
        $this->subtotal = $this->adquisicion->subtotal;
        $this->iva = $this->adquisicion->iva;
        $this->total = $this->adquisicion->total;
        $this->vobo = 0;

        if ($this->id_adquisicion_detalle != 0) {
            $this->bienesDB = AdquisicionDetalle::where('id_adquisicion', $id)->where('id', $this->id_adquisicion_detalle)->get();
        } else {
            $this->bienesDB = AdquisicionDetalle::where('id_adquisicion', $id)->get();
        }

        $this->bienes = collect($this->bienesDB);
        $this->cuentasContables = CuentaContable::where('estatus', 1)->whereIn('tipo_requisicion', [1, 3])->get();

        $this->documentos = Documento::where('id_requisicion', $id)->where('tipo_requisicion', 1)->get();

        foreach ($this->documentos as $documento) {
            if ($documento->tipo_documento == 1) {
                $this->docsCartaExclusividad[] = $documento;
                //  dd($this->docsCartaExclusividad);
            }
        }
        //  dd($this->docsCartaExclusividad[0]['nombre_documento']);
        foreach ($this->documentos as $documento) {
            if ($documento->tipo_documento == 2) {
                $this->docsCotizacionesFirmadas[] = $documento;
            }
        }

        foreach ($this->documentos as $documento) {
            if ($documento->tipo_documento == 3) {
                $this->docsCotizacionesPdf[] = $documento;
            }
        }

        foreach ($this->documentos as $documento) {
            if ($documento->tipo_documento == 5) {
                $this->docsAnexoOtrosDocumentos[] = $documento;
            }
        }
    }


    public function render()
    {
        return view('livewire.revisores.revisor-adquisicion');
    }

    public function updated($estatus)
    {
        $this->validateOnly($estatus);
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

    public function save()
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            if ($this->id_adquisicion_detalle == null) {            
                if (in_array($this->tipoEstatus->tipo, [3, 5])) { // Selecciono un estatus por partida    
                    // $bienesDB = AdquisicionDetalle::where('id_adquisicion', $this->id_adquisicion)->get();
                    $bienesDB = $this->adquisicion->detalless()->get();
                    //dd($this->adquisicion->detalless()->get());
                    foreach ($bienesDB as $bien) {
                        if ($bien) {
                            $bien->estatus_dgiea = $this->estatus;
                            $bien->estatus_rt = $this->estatus;                           
                            $bien->clave_siia = $this->claveSiia;
                            
                            $bien->save();
                        }

                        $adquisicion = Adquisicion::where('id', $this->adquisicion->id)->first();
                        if ($adquisicion) {
                            $adquisicion->estatus_general = $this->estatus;

                            if ($this->estatus == 5) {
                                $adquisicion->observaciones = $this->observaciones_estatus;
                            } else {
                                $adquisicion->observaciones = null;
                            }

                            $adquisicion->save();
                        }
                    }
                } else {
                    // Selecciono un estatus para una adquisicion en general
                    // Actualizamos en adquisiciones
                    $adquisicion = Adquisicion::where('id', $this->id_adquisicion)->first();
                    if ($adquisicion) {
                        $adquisicion->estatus_general = $this->estatus;

                        if ($this->estatus == 5) {
                            $adquisicion->observaciones = $this->observaciones_estatus;
                        } else {
                            $adquisicion->observaciones = null;
                        }

                        $adquisicion->save();
                    }
                }
            } else {
                // Actualizamos en detalles, entro a una partida
                DB::beginTransaction();
                $bienesDB = AdquisicionDetalle::where('id', $this->id_adquisicion_detalle)->first();
                if ($bienesDB) {
                    $bienesDB->estatus_dgiea = $this->estatus;
                    $bienesDB->estatus_rt = $this->estatus;
                    $bienesDB->clave_siia = $this->claveSiia;

                    $bienesDB->save();
                }
            }

            DB::commit();
            return redirect('/dashboard')->with('success', 'Su solicitud con clave');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error al intentar confirmar visto bueno. Intente más tarde.' . $e->getMessage());
        }
    }

    public function actualizarTipoEstatus($tipo){
        $this->tipoEstatus = $tipo;
        $this->observaciones_estatus = null;
        $this->claveSiia = null;
    }
}
