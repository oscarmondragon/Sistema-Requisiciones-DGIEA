<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Adquisicion;
use Livewire\WithFileUploads;
use App\Models\AdquisicionDetalle;
use App\Models\Documento;
use App\Models\CuentaContable;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use Illuminate\Support\Collection;
use Monolog\Handler\IFTTTHandler;
use Illuminate\Support\Facades\Storage;


class AdquisicionesVobo extends Component
{

    public $adquisicion;
    public $documentos;
    public $cuentasContables;

    //atributos de una adquisicion
    public $id_adquisicion; //recupera id en editar
    public $clave_requisicion = '';
    public $tipo_requisicion = '1';
    public $clave_proyecto = '';
    public $clave_espacio_academico = '';
    public $clave_rt = '';
    public $clave_tipo_financiamiento = '';
    public $id_rubro = 0;
    public $id_rubro_especial; //variable para determinar si es una cuenta especial (software por ejemplo)
    public $afecta_investigacion = '0';
    public $justificacion_academica;
    public $exclusividad = '0';
    public $id_carta_exclusividad;
    public $vobo;
    public $id_emisor;
    public $id_revisor;
    public $estatus_general;
    public $observaciones;
    public $subtotal = 0;
    public $iva = 0;
    public $total = 0;
    public $bienes;
    public $docsCartaExclusividad = [];
    public $docsCotizacionesFirmadas = [];
    public $docsCotizacionesPdf = [];
    public $docsAnexoOtrosDocumentos = [];
    public $ruta_archivo = '';

    public function mount($id)
    {
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

        $this->bienesDB = AdquisicionDetalle::where('id_adquisicion', $id)->get();
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
        return view('livewire.adquisiciones-vobo')->layout('layouts.cvu');
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