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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;






class AdquisicionesForm extends Component
{

    use WithFileUploads;
    public $adquisicion;


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
    public $tamanyoDocumentos;
    public $tipoDocumento;

    //variables para validar documentos antes de agregarlos al arreglo
    public $cartaExclusividadTemp;
    public $cotizacionFirmadaTemp;
    public $cotizacionPdfTemp;
    public $anexoOtroTemp;






    protected $rules = [
        'id_rubro' => 'required|not_in:0',
        'bienes' => 'required|array|min:1',
        'justificacion_academica' => 'required_if:afecta_investigacion,1',
        'docsCartaExclusividad' => 'required_if:exclusividad,1|array',
        'docsCotizacionesFirmadas' => 'required|array|min:1',
        'docsCotizacionesFirmadas.*' => 'required',
        'docsCotizacionesPdf' => 'required|array|min:1',
        'vobo' => 'accepted'
    ];
    protected $messages = [
        'id_rubro.required' => 'Debe seleccionar un rubro.',
        'id_rubro.not_in' => 'Debe seleccionar un rubro.',
        'bienes.required' => 'Debe agregar por lo menos un bien o servicio.',
        'bienes.array' => 'Debe agregar por lo menos un bien o servicio.',
        'bienes.min' => 'Debe agregar por lo menos un bien o servicio.',
        'justificacion_academica.required_if' => 'La justificación académica no puede estar vacía.',
        'docsCartaExclusividad.required_if' => 'Debe adjuntar la carta de exclusividad.',
        'docsCartaExclusividad.array' => 'Debe adjuntar la carta de exclusividad.',
        //'docsCartaExclusividad.min' => 'Debe adjuntar por lo menos una carta de exclusividad.',
        'docsCotizacionesFirmadas.required' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesFirmadas.array' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesFirmadas.min' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesPdf.required' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesPdf.array' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesPdf.min' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'vobo.accepted' => 'Debe dar el visto bueno.',
        'cartaExclusividadTemp.max' => 'El documento no debe pesar más de 2MB.',
        'cartaExclusividadTemp.mimes' => 'Debe adjuntar documentos con extensión .pdf unicamente',
        'cotizacionFirmadaTemp.max' => 'El documento no debe pesar más de 2MB.',
        'cotizacionFirmadaTemp.mimes' => 'Debe adjuntar documentos con extensión .pdf unicamente',
        'cotizacionPdfTemp.max' => 'El documento no debe pesar más de 2MB.',
        'cotizacionPdfTemp.mimes' => 'Debe adjuntar documentos con extensión .pdf unicamente',
        'anexoOtroTemp.max' => 'El documento no debe pesar más de 2MB.',
        'anexoOtroTemp.mimes' => 'Debe adjuntar documentos con extensión .pdf unicamente',



    ];
    public $listeners = [
        'addBien' => 'setBien',
    ];

    public function mount($id = 0)
    {
        $this->tamanyoDocumentos = env('TAMANYO_MAX_DOCS', 2048);
        $this->tipoDocumento = env('DOCUMENTOS_PERMITIDOS', 'pdf');
        $this->cuentasContables = CuentaContable::where('estatus', 1)->whereIn('tipo_requisicion', [1, 3])->get();

        if ($id != 0) {
            $this->adquisicion = Adquisicion::find($id);
            $this->id_rubro = $this->adquisicion->id_rubro;
            $this->id_rubro_especial = $this->adquisicion->cuentas->cuentaEspecial->id ?? 0;
            $this->justificacion_academica = $this->adquisicion->justificacion_academica;
            $this->afecta_investigacion = $this->adquisicion->afecta_investigacion;
            $this->exclusividad = $this->adquisicion->exclusividad;
            //$this->docsCartaExclusividad = $this->adquisicion->docsCartaExclusividad;
            $this->subtotal = $this->adquisicion->subtotal;
            $this->iva = $this->adquisicion->iva;
            $this->total = $this->adquisicion->total;

            $this->bienesDB = AdquisicionDetalle::where('id_adquisicion', $id)->get();
            $this->bienes = collect($this->bienesDB);
            // $this->documentos = Documento::where('id_requisicion', $id)->where('tipo_requisicion', 1)->get();
            $this->docsCartaExclusividad = [];
            $this->docsCotizacionesFirmadas = [];
            $this->docsCotizacionesPdf = [];




        } else {

            $this->bienes = collect();
            $this->docsCartaExclusividad = [];
            $this->docsCotizacionesFirmadas = [];
            $this->docsCotizacionesPdf = [];
        }

    }
    public function render()
    {
        return view('livewire.adquisiciones-form')->layout('layouts.cvu');
    }

    public function save()
    {
        $this->validate([
            'id_rubro' => 'required|not_in:0',
            'bienes' => 'required|array|min:1',
            'docsCartaExclusividad.*' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
            'docsCotizacionesFirmadas.*' => 'required|' . 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
            'docsCotizacionesPdf.*' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ]);


        $clave_proyecto = Session::get('id_proyecto');
        $id_user = Session::get('id_user');

        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();

        if ($proyecto) {
            DB::beginTransaction();
            try {
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
                    'estatus_general' => 1,
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
                        'precio_unitario' => $bien['precio_unitario'],
                        'iva' => $bien['iva'],
                        'importe' => $bien['importe'],
                        'justificacion_software' => $bien['justificacion_software'],
                        'alumnos' => $bien['alumnos'],
                        'profesores_invest' => $bien['profesores_invest'],
                        'administrativos' => $bien['administrativos'],
                        'id_emisor' => $id_user
                    ]);
                }
                //definimos la ruta temporal de los archivos
                $ruta_archivo = $clave_proyecto . '/Requisiciones/' . $id_adquisicion;
                $i = 1;
                //Revisar si los arreglos contienen datos/

                if (empty($this->docsCartaExclusividad) == false) {

                    foreach ($this->docsCartaExclusividad as $dce) {
                        //extensiond e archivo a depositar
                        $extension = $dce->getClientOriginalExtension();
                        $nombre_doc = $dce->getClientOriginalName();
                        //almacenamos archivo en servidor y obtenemos la ruta para agregar a la BD
                        $pathBD = $dce->storeAs($ruta_archivo . '/CExclusividad', 'doc_exclusividad' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'nombre_doc' => $pathBD,
                            'tipo_documento' => '1',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc

                        ]);
                    }
                    $i = 1;
                    //$this->docsCartaExclusividad = [];
                }

                if (empty($this->docsCotizacionesFirmadas) == 0) {
                    // dd(empty($this->docsCotizacionesFirmadas)); 
                    foreach ($this->docsCotizacionesFirmadas as $dcf) {
                        $extension = $dcf->getClientOriginalExtension();
                        $nombre_doc = $dcf->getClientOriginalName();
                        $pathBD = $dcf->storeAs($ruta_archivo . '/CFirmadas', 'doc_cfirmadas' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'nombre_doc' => $pathBD,
                            'tipo_documento' => '2',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc
                        ]);
                    }
                    $i = 1;
                    // $this->docsCotizacionesFirmadas = [];
                    //dd($this->docsCotizacionesFirmadas);

                }

                if (empty($this->docsCotizacionesPdf) == 0) {
                    foreach ($this->docsCotizacionesPdf as $dcp) {
                        $extension = $dcp->getClientOriginalExtension();
                        $nombre_doc = $dcp->getClientOriginalName();
                        $pathBD = $dcp->storeAs($ruta_archivo . '/CPdf', 'doc_cpdf' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'nombre_doc' => $pathBD,
                            'tipo_documento' => '3',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc
                        ]);
                    }
                    //  $this->docsCotizacionesPdf = [];
                }

                if (empty($this->docsAnexoOtrosDocumentos) == 0) {
                    foreach ($this->docsAnexoOtrosDocumentos as $dao) {
                        $extension = $dao->getClientOriginalExtension();
                        $nombre_doc = $dao->getClientOriginalName();
                        $pathBD = $dao->storeAs($ruta_archivo . '/AnexosOtros', 'doc_otros' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'nombre_doc' => $pathBD,
                            'tipo_documento' => '5',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc
                        ]);
                    }
                    //  $this->docsAnexoOtrosDocumentos = [];
                }

                DB::commit();
                return redirect('/cvu-crear')->with('success', 'Su solicitud ha sido guardada correctamente con el número de clave ' . $clave_adquisicion . '. Recuerde completarla y mandarla a visto bueno.');
            } catch (\Exception $e) {
                DB::rollback();
                dd("Error en catch:" . $e);
                return redirect()->back()->with('error', 'Error en el proceso de guardado ' . $e->getMessage());
            }
        } else {
            // No se encontró ningún proyecto  con esca clave"
            dd("error calve de proyecto");
            return redirect()->back()->with('error', 'No se encontró un proyecto asociado a la clave ' . $clave_proyecto);
        }
    }


    public function saveVobo()
    {
        $this->validate();
        $clave_proyecto = Session::get('id_proyecto');
        $id_user = Session::get('id_user');
        $who_vobo = Session::get('VoBo_Who');
        $fecha_vobo = Carbon::now()->toDateString();

        if ($who_vobo) { //Si el deposito es por parte del Responsable técnico
            $vobo_admin = null;
            $vobo_rt = $fecha_vobo;
        } else { //Si el depósito es por parte del administrativo
            $vobo_admin = $fecha_vobo;
            $vobo_rt = null;
        }

        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();

        if ($proyecto) {
            DB::beginTransaction();
            try {
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
                    'vobo_admin' => $vobo_admin,
                    'vobo_rt' => $vobo_rt,
                    'id_emisor' => $id_user,
                    'estatus_general' => 2,
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
                        'precio_unitario' => $bien['precio_unitario'],
                        'iva' => $bien['iva'],
                        'importe' => $bien['importe'],
                        'justificacion_software' => $bien['justificacion_software'],
                        'alumnos' => $bien['alumnos'],
                        'profesores_invest' => $bien['profesores_invest'],
                        'administrativos' => $bien['administrativos'],
                        'id_emisor' => $id_user
                    ]);
                }
                //definimos la ruta de los archivos a insertar 
                $ruta_archivo = $clave_proyecto . '/Requisiciones/' . $id_adquisicion;
                $i = 1;
                foreach ($this->docsCartaExclusividad as $dce) {
                    //extensiond e archivo a depositar
                    $extension = $dce->getClientOriginalExtension();
                    $nombre_doc = $dce->getClientOriginalName();
                    //almacenamos archivo en servidor y obtenemos la ruta para agregar a la BD
                    $pathBD = $dce->storeAs($ruta_archivo . '/CExclusividad', 'doc_exclusividad' . $i . '.' . $extension);
                    $i++;
                    $documento = Documento::create([
                        'id_requisicion' => $id_adquisicion,
                        'nombre_doc' => $pathBD,
                        'tipo_documento' => '1',
                        'tipo_requisicion' => '1',
                        'nombre_documento' => $nombre_doc
                    ]);
                }
                $i = 1;
                //$docsCartaExclusividad = [];
                foreach ($this->docsCotizacionesFirmadas as $dcf) {
                    $extension = $dcf->getClientOriginalExtension();
                    $nombre_doc = $dcf->getClientOriginalName();
                    $pathBD = $dcf->storeAs($ruta_archivo . '/CFirmadas', 'doc_cfirmadas' . $i . '.' . $extension);
                    $i++;
                    $documento = Documento::create([
                        'id_requisicion' => $id_adquisicion,
                        'nombre_doc' => $pathBD,
                        'tipo_documento' => '2',
                        'tipo_requisicion' => '1',
                        'nombre_documento' => $nombre_doc
                    ]);
                }
                $i = 1;
                //$docsCotizacionesFirmadas = [];

                foreach ($this->docsCotizacionesPdf as $dcp) {
                    $extension = $dcp->getClientOriginalExtension();
                    $nombre_doc = $dcp->getClientOriginalName();
                    $pathBD = $dcp->storeAs($ruta_archivo . '/CPdf', 'doc_cpdf' . $i . '.' . $extension);
                    $i++;
                    $documento = Documento::create([
                        'id_requisicion' => $id_adquisicion,
                        'nombre_doc' => $pathBD,
                        'tipo_documento' => '3',
                        'tipo_requisicion' => '1',
                        'nombre_documento' => $nombre_doc
                    ]);
                }
                //$docsCotizacionesPdf = [];

                if (empty($this->docsAnexoOtrosDocumentos) == 0) {
                    foreach ($this->docsAnexoOtrosDocumentos as $dao) {
                        $extension = $dao->getClientOriginalExtension();
                        $nombre_doc = $dao->getClientOriginalName();
                        $pathBD = $dao->storeAs($ruta_archivo . '/AnexosOtros', 'doc_otros' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'nombre_doc' => $pathBD,
                            'tipo_documento' => '5',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc
                        ]);
                    }
                    //  $this->docsAnexoOtrosDocumentos = [];
                }

                DB::commit();

                return redirect('/cvu-crear')->with('success', 'Su solicitud con clave ' . $clave_adquisicion . ' ha sido  registrada y se ha enviado para visto bueno.');
            } catch (\Exception $e) {
                //dd("Error en el catch".$e); 
                return redirect()->back()->with('error', 'error en el deposito' . $e->getMessage());
            }
        } else {
            // No se encontró ningún proyecto  con esca clave"
            // dd($e); 
            return redirect()->back()->with('error', 'No se encontró un proyecto asociado a la clave ' . $clave_proyecto);
        }
    }

    public function setBien(
        $_id,
        $descripcion,
        $cantidad,
        $precio_unitario,
        $iva,
        $checkIva,
        $importe,
        $justificacion_software,
        $alumnos,
        $profesores_invest,
        $administrativos,
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
                'precio_unitario' => $precio_unitario,
                'iva' => $iva,
                'checkIva' => $checkIva,
                'importe' => $importe,
                'justificacion_software' => $justificacion_software,
                'alumnos' => $alumnos,
                'profesores_invest' => $profesores_invest,
                'administrativos' => $administrativos
            ]);
            //Actualizamos valores de subtotal,iva y total
            $this->subtotal += $cantidad * $precio_unitario;
            $this->subtotal = round($this->subtotal, $precision = 2, $mode = PHP_ROUND_HALF_UP);
            $this->iva += $iva;
            $this->iva = round($this->iva, $precision = 2, $mode = PHP_ROUND_HALF_UP);
            $this->total += $importe;
            $this->total = round($this->total, $precision = 2, $mode = PHP_ROUND_HALF_UP);
        } else {
            //Si entra aqui es por que entro a la funcion editar, entonces buscamos el item en la collecion por su id
            $item = $this->bienes->firstWhere('_id', $_id);

            if ($item) {

                //actualizamos valores de subtotal, iva y total (restamos el anterior valor)
                $this->subtotal -= $item['cantidad'] * $item['precio_unitario'];
                $this->subtotal = round($this->subtotal, $precision = 2, $mode = PHP_ROUND_HALF_UP);
                $this->iva -= $item['iva'];
                $this->iva = round($this->iva, $precision = 2, $mode = PHP_ROUND_HALF_UP);
                $this->total -= $item['importe'];
                $this->total = round($this->total, $precision = 2, $mode = PHP_ROUND_HALF_UP);

                //actualizamos el item si existe en la busqueda
                $item['descripcion'] = $descripcion;
                $item['cantidad'] = $cantidad;
                $item['precio_unitario'] = $precio_unitario;
                $item['iva'] = $iva;
                $item['checkIva'] = $iva;
                $item['importe'] = $importe;
                $item['justificacion_software'] = $justificacion_software;
                $item['alumnos'] = $alumnos;
                $item['profesores_invest'] = $profesores_invest;
                $item['administrativos'] = $administrativos;


                //sumamos los nuevos valores al subtotal,iva y total
                $this->subtotal += $cantidad * $precio_unitario;
                $this->subtotal = round($this->subtotal, $precision = 2, $mode = PHP_ROUND_HALF_UP);
                $this->iva += $iva;
                $this->iva = round($this->iva, $precision = 2, $mode = PHP_ROUND_HALF_UP);
                $this->total += $importe;
                $this->total = round($this->total, $precision = 2, $mode = PHP_ROUND_HALF_UP);


                //Devolvemos la nueva collecion
                $this->bienes = $this->bienes->map(function ($bien) use ($_id, $descripcion, $cantidad, $precio_unitario, $iva, $importe, $justificacion_software, $alumnos, $profesores_invest, $administrativos) {
                    if ($bien['_id'] == $_id) {
                        $bien['descripcion'] = $descripcion;
                        $bien['cantidad'] = $cantidad;
                        $bien['precio_unitario'] = $precio_unitario;
                        $bien['iva'] = $iva;
                        $bien['checkIva'] = $iva;
                        $bien['importe'] = $importe;
                        $bien['justificacion_software'] = $justificacion_software;
                        $bien['alumnos'] = $alumnos;
                        $bien['profesores_invest'] = $profesores_invest;
                        $bien['administrativos'] = $administrativos;
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
        $this->subtotal -= $bien['cantidad'] * $bien['precio_unitario'];
        //$this->subtotal = round($this->subtotal, $precision = 2, $mode = PHP_ROUND_HALF_UP);
        $this->iva -= $bien['iva'];
        //$this->iva = round($this->iva, $precision = 2, $mode = PHP_ROUND_HALF_UP);
        $this->total -= $bien['importe'];
        //$this->total = round($this->total, $precision = 2, $mode = PHP_ROUND_HALF_UP);

        //   $this->bienes->forget($bien);
    }
    public function resetearBienes($idRubroEspecial)
    {
        $this->id_rubro_especial = $idRubroEspecial;
        $this->subtotal = 0;
        $this->iva = 0;
        $this->total = 0;
        $this->docsCartaExclusividad = [];
        $this->docsCotizacionesFirmadas = [];
        $this->docsCotizacionesPdf = [];
        $this->docsAnexoOtrosDocumentos = [];
        $this->justificacion_academica = '';
        $this->vobo = 0;
        $this->afecta_investigacion = 0;
        $this->exclusividad = 0;
        $this->bienes = collect();
    }


    //word/excel/pdf|2MB
    public function eliminarArchivo($tipoArchivo, $index)
    {
        if ($tipoArchivo === 'cartasExclusividad') {
            // Verificar si el índice existe en el array
            if (array_key_exists($index, $this->docsCartaExclusividad)) {
                // Eliminar el archivo del array usando el índice
                unset($this->docsCartaExclusividad[$index]);
                // Reindexar el array para asegurar una secuencia numérica continua
                $this->docsCartaExclusividad = array_values($this->docsCartaExclusividad);
            }
        }

        if ($tipoArchivo === 'cotizacionesFirmadas') {
            // Verificar si el índice existe en el array
            if (array_key_exists($index, $this->docsCotizacionesFirmadas)) {
                // Eliminar el archivo del array usando el índice
                unset($this->docsCotizacionesFirmadas[$index]);
                // Reindexar el array para asegurar una secuencia numérica continua
                $this->docsCotizacionesFirmadas = array_values($this->docsCotizacionesFirmadas);
            }
        }

        if ($tipoArchivo === 'cotizacionesPdf') {
            // Verificar si el índice existe en el array
            if (array_key_exists($index, $this->docsCotizacionesPdf)) {
                // Eliminar el archivo del array usando el índice
                unset($this->docsCotizacionesPdf[$index]);
                // Reindexar el array para asegurar una secuencia numérica continua
                $this->docsCotizacionesPdf = array_values($this->docsCotizacionesPdf);
            }
        }

        if ($tipoArchivo === 'anexoDocumentos') {
            // Verificar si el índice existe en el array
            if (array_key_exists($index, $this->docsAnexoOtrosDocumentos)) {
                // Eliminar el archivo del array usando el índice
                unset($this->docsAnexoOtrosDocumentos[$index]);
                // Reindexar el array para asegurar una secuencia numérica continua
                $this->docsAnexoOtrosDocumentos = array_values($this->docsAnexoOtrosDocumentos);
            }
        }
    }

    public function resetJustificacionAcademica()
    {
        $this->justificacion_academica = '';
    }

    public function resetdocsCartaExclusividad()
    {
        $this->docsCartaExclusividad = [];
        $this->docsAnexoOtrosDocumentos = [];
    }
    public function updatedcartaExclusividadTemp()
    {
        $validatedData = $this->validate([
            'cartaExclusividadTemp' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ]);

        // Validar si la validación fue exitosa antes de agregar los archivos al arreglo
        if (isset($validatedData['cartaExclusividadTemp'])) {
            // Agregar el archivo al arreglo
            $this->docsCartaExclusividad[] = $validatedData['cartaExclusividadTemp'];
        }

        $this->cartaExclusividadTemp = null;
    }

    public function updatedcotizacionFirmadaTemp()
    {
        //dd($this->tipoDocumento);
        $validatedData = $this->validate([
            'cotizacionFirmadaTemp' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ]);

        // Validar si la validación fue exitosa antes de agregar los archivos al arreglo
        if (isset($validatedData['cotizacionFirmadaTemp'])) {
            // Agregar el archivo al arreglo
            $this->docsCotizacionesFirmadas[] = $validatedData['cotizacionFirmadaTemp'];
        }

        $this->cotizacionFirmadaTemp = null;
    }

    public function updatedcotizacionPdfTemp()
    {
        $validatedData = $this->validate([
            'cotizacionPdfTemp' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ]);

        // Validar si la validación fue exitosa antes de agregar los archivos al arreglo
        if (isset($validatedData['cotizacionPdfTemp'])) {
            // Agregar el archivo al arreglo
            $this->docsCotizacionesPdf[] = $validatedData['cotizacionPdfTemp'];
        }

        $this->cotizacionPdfTemp = null;
    }

    public function updatedanexoOtroTemp()
    {
        $validatedData = $this->validate([
            'anexoOtroTemp' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ]);

        // Validar si la validación fue exitosa antes de agregar los archivos al arreglo
        if (isset($validatedData['anexoOtroTemp'])) {
            // Agregar el archivo al arreglo
            $this->docsAnexoOtrosDocumentos[] = $validatedData['anexoOtroTemp'];
        }

        $this->anexoOtroTemp = null;
    }
    public function updated($id_rubro)
    {
        $this->validateOnly($id_rubro);
    }
}