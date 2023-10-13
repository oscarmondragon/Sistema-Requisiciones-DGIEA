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


class AdquisicionEditar extends Component
{

    public $adquisicion;

    use WithFileUploads;

    //catalogos
    public $cuentasContables;

    //atributos de una adquisicion
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

    //variables para validar documentos antes de agregarlos al arreglo
    public $cartaExclusividadTemp;
    public $cotizacionFirmadaTemp;
    public $cotizacionPdfTemp;
    public $anexoOtroTemp;
    public function mount($id)
    {
        $this->adquisicion = Adquisicion::find($id);
        $this->justificacion_academica = $this->adquisicion->justificacion_academica;
        $this->afecta_investigacion = $this->adquisicion->afecta_investigacion;


        $this->bienes = collect();
        $this->docsCartaExclusividad = [];
        $this->docsCotizacionesFirmadas = [];
        $this->docsCotizacionesPdf = [];


        $this->cuentasContables = CuentaContable::where('estatus', 1)->whereIn('tipo_requisicion', [1, 3])->get();

    }
    public function render()
    {
        return view('livewire.adquisicion-editar')->layout('layouts.cvu');

    }
}