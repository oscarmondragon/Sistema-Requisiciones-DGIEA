<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Adquisicion;
use App\Models\AdquisicionDetalle;
use App\Models\Documento;
use App\Models\CuentaContable;
use App\Models\Proyecto;

use Illuminate\Support\Facades\Session;





class AdquisicionesForm extends Component
{

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
    public bool $afecta_investigacion = false;
    public $justificacion_academica;
    public bool $exclusividad = false;
    public $id_carta_exclusividad;
    public bool $vobo;
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


    protected $rules = [
        'id_rubro' => 'required|not_in:0',

    ];
    protected $messages = [
        'id_rubro.required' => 'Debe seleccionar un rubro.',
        'id_rubro.not_in' => 'Debe seleccionar un rubro.'
    ];
    public $listeners = [
        'addBien' => 'setBien',
    ];

    public function mount()
    {
        $this->bienes = collect();
        $this->docsCartaExclusividad = [];
        $this->docsCotizacionesFirmadas = [];
        $this->docsCotizacionesPdf = [];
        $this->cuentasContables = CuentaContable::where('estatus', 1)->where('tipo_requisicion', 1)->get();

    }
    public function render()
    {
        return view('livewire.adquisiciones-form');
    }

    public function save()
    {
        $this->validate();
        $clave_proyecto = Session::get('id_proyecto');
        $id_user = Session::get('id_user');

        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();

        if ($proyecto) {
            //Inserta la Adquisición en base de datos
            $adquisicion = Adquisicion::create([
                'clave_adquisicion' => '',
                'tipo_requisicion' => $this->tipo_requisicion,
                'clave_proyecto' => $clave_proyecto,
                'clave_espacio_academico' => $proyecto->CveCenCos,
                'clave_rt' => $proyecto->CveEntEmp_Responsable,
                'tipo_financiamiento' => $proyecto->Tipo_Proyecto,
                'id_rubro' => (int) $this->id_rubro,
                'afecta_investigacion' => $this->afecta_investigacion,
                'justificacion_academica' => $this->justificacion_academica,
                'exclusividad' => $this->exclusividad,
                'id_carta_exclusividad' => $this->id_carta_exclusividad,
                'id_emisor' => $id_user,
                'subtotal' => $this->subtotal,
                'iva' => $this->iva,
                'total' => $this->total

            ]);

            // Genera la clave_adquisición con fecha y id
            $id_adquisicion = $adquisicion->id;
            $fecha_actual = date('Ymd');
            $clave_adquisicion = $fecha_actual . 'ADQ' . $id_adquisicion;

            // Actualiza la clave de adquisición en el registro de la adquisición
            $adquisicion->update(['clave_adquisicion' => $clave_adquisicion]);

            //Guarda los bienes o servicios en adquisicion_detalles
            //primero agregamos el id_adquisicion a cada bien

            $this->bienes = $this->bienes->map(function ($bien) use ($id_adquisicion) {
                $bien['id_adquisicion'] = $id_adquisicion;
                return $bien;
            });

            foreach ($this->bienes as $bien) {
                $elemento = AdquisicionDetalle::create([
                    'id_adquisicion' => $bien['id_adquisicion'],
                    'descripcion' => $bien['descripcion'],
                    'cantidad' => $bien['cantidad'],
                    'precio_unitario' => $bien['precioUnitario'],
                    'iva' => $bien['iva'],
                    'importe' => $bien['importe'],
                    'justificacion_software' => $bien['justificacionSoftware'],
                    'alumnos' => $bien['numAlumnos'],
                    'profesores_invest' => $bien['numProfesores'],
                    'administrativos' => $bien['numAdministrativos'],
                    'id_emisor' => $id_user
                ]);
            }

            return redirect('/cvu-crear')->with('success', 'Su solicitud ha sido guardada correctamente. Recuerde completarla y mandarla a visto bueno.');

        } else {
            // No se encontró ningún proyecto  con esca clave"
            return redirect()->back()->with('error', 'No se encontró un proyecto asociado a la clave ' . $clave_proyecto);

        }



    }
    public function setBien(
        $_id,
        $descripcion,
        $cantidad,
        $precioUnitario,
        $iva,
        $checkIva,
        $importe,
        $justificacionSoftware,
        $numAlumnos,
        $numProfesores,
        $numAdministrativos,
        $id_rubro
    ) {
        $this->bienes = collect($this->bienes); //asegurar que bienes sea una coleccion




        if ($_id == 0) { //entramos aqui si el item es nuevo
            // Genera un nuevo ID para el elemento
            $newItemId = $this->bienes->max('_id') + 1;

            //Agregamos el bien en la coleccion
            $this->bienes->push([
                '_id' => $newItemId,
                'descripcion' => $descripcion,
                'cantidad' => $cantidad,
                'precioUnitario' => $precioUnitario,
                'iva' => $iva,
                'checkIva' => $checkIva,
                'importe' => $importe,
                'justificacionSoftware' => $justificacionSoftware,
                'numAlumnos' => $numAlumnos,
                'numProfesores' => $numProfesores,
                'numAdministrativos' => $numAdministrativos
            ]);
            //Actualizamos valores de subtotal,iva y total
            $this->subtotal += $cantidad * $precioUnitario;
            $this->iva += $iva;
            $this->total += $importe;


        } else {
            //Si entra aqui es por que entro a la funcion editar, entonces buscamos el item en la collecion por su id
            $item = $this->bienes->firstWhere('_id', $_id);

            if ($item) {

                //actualizamos valores de subtotal, iva y total (restamos el anterior valor)
                $this->subtotal -= $item['cantidad'] * $item['precioUnitario'];
                $this->iva -= $item['iva'];
                $this->total -= $item['importe'];

                //actualizamos el item si existe en la busqueda
                $item['descripcion'] = $descripcion;
                $item['cantidad'] = $cantidad;
                $item['precioUnitario'] = $precioUnitario;
                $item['iva'] = $iva;
                $item['checkIva'] = $iva;
                $item['importe'] = $importe;
                $item['justificacionSoftware'] = $justificacionSoftware;
                $item['numAlumnos'] = $numAlumnos;
                $item['numProfesores'] = $numProfesores;
                $item['numAdministrativos'] = $numAdministrativos;


                //sumamos los nuevos valores al subtotal,iva y total
                $this->subtotal += $cantidad * $precioUnitario;
                $this->iva += $iva;
                $this->total += $importe;


                //Devolvemos la nueva collecion
                $this->bienes = $this->bienes->map(function ($bien) use ($_id, $descripcion, $cantidad, $precioUnitario, $iva, $importe, $justificacionSoftware, $numAlumnos, $numProfesores, $numAdministrativos) {
                    if ($bien['_id'] == $_id) {
                        $bien['descripcion'] = $descripcion;
                        $bien['cantidad'] = $cantidad;
                        $bien['precioUnitario'] = $precioUnitario;
                        $bien['iva'] = $iva;
                        $bien['checkIva'] = $iva;
                        $bien['importe'] = $importe;
                        $bien['justificacionSoftware'] = $justificacionSoftware;
                        $bien['numAlumnos'] = $numAlumnos;
                        $bien['numProfesores'] = $numProfesores;
                        $bien['numAdministrativos'] = $numAdministrativos;
                    }
                    return $bien;
                });
                //actualizamos indices
                $this->bienes = $this->bienes->values();

            }

        }

    }

    public function deleteBien($bien)
    {
        //EL BIEN SE ESTA ELIMINANDO CON ALPINEJS desde el front
        //actualizamos valores de subtotal, iva y total (restamos el anterior valor)
        $this->subtotal -= $bien['cantidad'] * $bien['precioUnitario'];
        $this->iva -= $bien['iva'];
        $this->total -= $bien['importe'];

        //   $this->bienes->forget($bien);
    }
    public function resetearBienes()
    {
        $this->subtotal = 0;
        $this->iva = 0;
        $this->total = 0;
        $this->bienes = collect();
    }



    public function eliminarArchivo($tipoArchivo, $index)
    {
        if ($tipoArchivo === 'cotizacionesPdf') {
            unset($this->docsCotizacionesPdf[$index]);
            $this->docsCotizacionesPdf = array_values($this->docsCotizacionesPdf);

        } else if ($tipoArchivo === 'cotizacionesFirmadas') {
            unset($this->docsCotizacionesFirmadas[$index]);
            $this->docsCotizacionesFirmadas = array_values($this->docsCotizacionesFirmadas);
        } else if ($tipoArchivo === 'cartasExclusividad') {
            unset($this->docsCartaExclusividad[$index]);
            $this->docsCartaExclusividad = array_values($this->docsCartaExclusividad);
        }

    }

}