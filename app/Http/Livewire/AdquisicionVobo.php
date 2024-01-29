<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\AdquisicionDetalle;
use App\Models\Documento;
use App\Models\CuentaContable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;



class AdquisicionVobo extends Component
{
    public $vobo = 0;
    public $adquisicion;
    public $documentos;
    public $cuentasContables;

    //atributos de una adquisicion
    public $id_adquisicion; //recupera id 
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
    public $bienes;
    public $docsCartaExclusividad = [];
    public $docsCotizacionesFirmadas = [];
    public $docsCotizacionesPdf = [];
    public $docsAnexoOtrosDocumentos = [];
    public $referer = '';

    public $observacionesVobo;

    protected $rules = [
        'vobo' => 'accepted'
    ];
    protected $messages = [
        'vobo.accepted' => 'Debe dar el visto bueno.'
    ];

    public function mount($id = 0)
    {
        $this->referer = $_SERVER['HTTP_REFERER'];
        // dd($referer);
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
        return view('livewire.adquisicion-vobo')->layout('layouts.cvu');
    }

    protected $listeners = [
        'darVobo',
        'rechazarVobo'
    ];

    public function darVobo()
    {
        $this->validate();

        $who_vobo = Session::get('VoBo_Who');
        $fecha_vobo = Carbon::now()->toDateString();
        $id_user = Session::get('id_user');

        try {
            DB::beginTransaction();
            $adquisicion = Adquisicion::where('id', $this->adquisicion->id)->first();
            if ($adquisicion) {
                if ($who_vobo) { //Si el deposito es por parte del Responsable técnico
                    $vobo_rt = $fecha_vobo;
                    $vobo_admin = $adquisicion->vobo_admin;
                } else { //Si el depósito es por parte del administrativo
                    $vobo_rt = $adquisicion->vobo_rt;
                    $vobo_admin = $fecha_vobo;
                }
                $adquisicion->update([
                    'clave_adquisicion' => $adquisicion->clave_adquisicion,
                    'estatus_general' => 4,
                    'vobo_rt'=> $vobo_rt,
                    'vobo_admin'=> $vobo_admin
                ]);

            }
            DB::commit();
            return redirect('/cvu-vobo')->with('success', 'Su solicitud con clave ' . $clave_adquisicion . ' ha sido  enviada para revision a la DGIEA.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error al intentar confirmar visto bueno. Intente más tarde.' . $e->getMessage());
        }

    }

    public function rechazarVobo($motivo)
    {
        $this->observacionesVobo = $motivo;
        try {
            DB::beginTransaction();
            $adquisicion = Adquisicion::where('id', $this->adquisicion->id)->first();
            if ($adquisicion) {
                $adquisicion->update([
                    'clave_adquisicion' => $adquisicion->clave_adquisicion,
                    'estatus_general' => 3,
                    'observaciones_vobo'=> $this->observacionesVobo
                ]);
            }
            DB::commit();
            return redirect('/cvu-vobo')->with('success', 'Su solicitud con clave ' . $clave_adquisicion . ' ha sido  rechazada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error al intentar rechazar visto bueno. Intente más tarde.' . $e->getMessage());
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
