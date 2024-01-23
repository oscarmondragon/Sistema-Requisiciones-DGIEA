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
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdquisicionesForm extends Component
{

    use WithFileUploads;
    public $adquisicion;


    //catalogos
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
    public $iva;
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
        // regex:/^[a-zA-Z-Z0-9.,$:;#%()\s]+$/u
        'justificacion_academica' => 'required_if:afecta_investigacion,1|max:800',
        'docsCartaExclusividad' => 'required_if:exclusividad,1|array',
        'docsCotizacionesFirmadas' => 'required|array|min:1',
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
        'justificacion_academica.max' => 'La justificación académica es demasiado larga.',
        'docsCartaExclusividad.required_if' => 'Debe adjuntar la carta de exclusividad.',
        'docsCartaExclusividad.array' => 'Debe adjuntar la carta de exclusividad.',
        'docsCotizacionesFirmadas.required' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesFirmadas.array' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesFirmadas.min' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesPdf.required' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesPdf.array' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'docsCotizacionesPdf.min' => 'Debe adjuntar por lo menos una cotización PDF firmada.',
        'vobo.accepted' => 'Debe dar el visto bueno.',


    ];
    public $listeners = [
        'addBien' => 'setBien',
        'save',
        'saveVobo',
        'eliminarArchivo'
    ];

    public function mount(Request $request, $id = 0)
    {

        // dd($request->path());
        $this->referer = $request->path();
        $this->tamanyoDocumentos = env('TAMANYO_MAX_DOCS', 2048);
        $this->tipoDocumento = env('DOCUMENTOS_PERMITIDOS', 'pdf');
        $this->cuentasContables = CuentaContable::where('estatus', 1)->whereIn('tipo_requisicion', [1, 3])->get();

        if ($id != 0) { //entra aqui si es una requisicion existente. Ejemplo para editar
            $this->adquisicion = Adquisicion::find($id);
            $this->id_adquisicion = $id;
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
            $this->bienes = collect($this->bienesDB)->map(function ($item, $key) {
                $item['_id'] = $key + 1;
                return $item;
            });
            $this->documentos = Documento::where('id_requisicion', $id)->where('tipo_requisicion', 1)->get();

            foreach ($this->documentos as $documento) {
                if ($documento->tipo_documento == 1) {
                    $this->docsCartaExclusividad[] = ['datos' => $documento];
                    //  dd($this->docsCartaExclusividad);
                }
            }
            //  dd($this->docsCartaExclusividad[0]['nombre_documento']);
            foreach ($this->documentos as $documento) {
                if ($documento->tipo_documento == 2) {
                    $this->docsCotizacionesFirmadas[] = ['datos' => $documento];

                }
            }

            foreach ($this->documentos as $documento) {
                if ($documento->tipo_documento == 3) {
                    $this->docsCotizacionesPdf[] = ['datos' => $documento];
                }
            }

            foreach ($this->documentos as $documento) {
                if ($documento->tipo_documento == 5) {
                    $this->docsAnexoOtrosDocumentos[] = ['datos' => $documento];
                }
            }
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
            'bienes' => 'required|array|min:1'
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
                $fecha_actual = date('ymd');
                $clave_adquisicion = 'R' . $clave_proyecto . $fecha_actual . $id_adquisicion;

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
                        $nombre_doc = $dce['datos']['nombre_documento'];
                        $extension = $dce['datos']['extension_documento'];
                        //dd($dce);
                        //almacenamos archivo en servidor y obtenemos la ruta para agregar a la BD
                        $pathBD = $dce['datos']['archivo']->storeAs($ruta_archivo . '/CExclusividad', 'doc_exclusividad' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'ruta_documento' => $pathBD,
                            'tipo_documento' => '1',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc,
                            'extension_documento' => $extension,


                        ]);
                    }
                    $i = 1;
                    //$this->docsCartaExclusividad = [];
                }

                if (empty($this->docsCotizacionesFirmadas) == 0) {
                    // dd(empty($this->docsCotizacionesFirmadas)); 
                    foreach ($this->docsCotizacionesFirmadas as $dcf) {
                        $nombre_doc = $dcf['datos']['nombre_documento'];
                        $extension = $dcf['datos']['extension_documento'];
                        $pathBD = $dcf['datos']['archivo']->storeAs($ruta_archivo . '/CFirmadas', 'doc_cfirmadas' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'ruta_documento' => $pathBD,
                            'tipo_documento' => '2',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc,
                            'extension_documento' => $extension
                        ]);
                    }
                    $i = 1;
                    // $this->docsCotizacionesFirmadas = [];
                    //dd($this->docsCotizacionesFirmadas);

                }

                if (empty($this->docsCotizacionesPdf) == 0) {
                    foreach ($this->docsCotizacionesPdf as $dcp) {
                        $nombre_doc = $dcp['datos']['nombre_documento'];
                        $extension = $dcp['datos']['extension_documento'];
                        $pathBD = $dcp['datos']['archivo']->storeAs($ruta_archivo . '/CPdf', 'doc_cpdf' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'ruta_documento' => $pathBD,
                            'tipo_documento' => '3',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc,
                            'extension_documento' => $extension
                        ]);
                    }
                    //  $this->docsCotizacionesPdf = [];
                }

                if (empty($this->docsAnexoOtrosDocumentos) == 0) {
                    foreach ($this->docsAnexoOtrosDocumentos as $dao) {
                        $nombre_doc = $dao['datos']['nombre_documento'];
                        $extension = $dao['datos']['extension_documento'];
                        $pathBD = $dao['datos']['archivo']->storeAs($ruta_archivo . '/AnexosOtros', 'doc_otros' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'ruta_documento' => $pathBD,
                            'tipo_documento' => '5',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc,
                            'extension_documento' => $extension

                        ]);
                    }
                    //  $this->docsAnexoOtrosDocumentos = [];
                }

                DB::commit();
                return redirect('/cvu-crear')->with('success', 'Su requerimiento ha sido guardada correctamente con la clave ' . $clave_adquisicion . '. Recuerde completarlo y mandarlo a visto bueno.');

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
            if (isset($this->id_adquisicion)) { //entra  aqui desde editar
                try {
                    DB::beginTransaction();
                    $adquisicion = Adquisicion::where('id', $this->id_adquisicion)->first();
                    $id_adquisicion = $adquisicion->id;
                    if ($adquisicion) {
                        $adquisicion->id_rubro = $this->id_rubro;
                        $adquisicion->afecta_investigacion = $this->afecta_investigacion;
                        $adquisicion->justificacion_academica = $this->justificacion_academica;
                        $adquisicion->exclusividad = $this->exclusividad;
                        $adquisicion->estatus_general = 2;
                        $adquisicion->vobo_admin = $vobo_admin;
                        $adquisicion->vobo_rt = $vobo_rt;
                        $adquisicion->subtotal = $this->subtotal;
                        $adquisicion->iva = $this->iva;
                        $adquisicion->total = $this->total;

                        $adquisicion->save();

                        //Guarda o actualizamos los bienes o servicios en adquisicion_detalles
                        //primero agregamos el id_adquisicion a cada bien
                        $this->bienes = $this->bienes->map(function ($bien) use ($id_adquisicion) {
                            $bien['id_adquisicion'] = $id_adquisicion;
                            return $bien;
                        });
                        //Inserta o actualiza los bienes segun corresponda
                        foreach ($this->bienes as $bien) {
                            if (isset($bien['id'])) { //si existe lo editamos
                                $elementoFound = AdquisicionDetalle::where('id', $bien['id'])->first();
                                if ($elementoFound) {
                                    $elementoFound->descripcion = $bien['descripcion'];
                                    $elementoFound->cantidad = $bien['cantidad'];
                                    $elementoFound->precio_unitario = $bien['precio_unitario'];
                                    $elementoFound->iva = $bien['iva'];
                                    $elementoFound->importe = $bien['importe'];
                                    $elementoFound->justificacion_software = $bien['justificacion_software'];
                                    $elementoFound->alumnos = $bien['alumnos'];
                                    $elementoFound->profesores_invest = $bien['profesores_invest'];
                                    $elementoFound->administrativos = $bien['administrativos'];
                                    $elementoFound->save();
                                }
                            } else { //si no existe lo insertamos
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
                        }


                        //definimos la ruta de los archivos a insertar 
                        $ruta_archivo = $clave_proyecto . '/Requisiciones/' . $id_adquisicion;

                        $i = 1;
                        if (empty($this->docsCartaExclusividad) == false) {
                            foreach ($this->docsCartaExclusividad as $dce) {
                                //extensiond e archivo a depositar
                                $nombre_doc = $dce['datos']['nombre_documento'];
                                $extension = $dce['datos']['extension_documento'];
                                //dd($dce);
                                //almacenamos archivo en servidor y obtenemos la ruta para agregar a la BD
                                if (empty($dce['datos']['id'])) {
                                    $pathBD = $dce['datos']['archivo']->storeAs($ruta_archivo . '/CExclusividad', 'doc_exclusividad-editar' . $i . '.' . $extension);
                                    $i++;

                                    $documento = Documento::create([
                                        'id_requisicion' => $id_adquisicion,
                                        'ruta_documento' => $pathBD,
                                        'tipo_documento' => '1',
                                        'tipo_requisicion' => '1',
                                        'nombre_documento' => $nombre_doc,
                                        'extension_documento' => $extension,


                                    ]);
                                }

                            }
                        }

                        $i = 1;
                        //$docsCartaExclusividad = [];
                        if (empty($this->docsCotizacionesFirmadas) == false) {
                            foreach ($this->docsCotizacionesFirmadas as $dcf) {
                                $nombre_doc = $dcf['datos']['nombre_documento'];
                                $extension = $dcf['datos']['extension_documento'];

                                if (empty($dcf['datos']['id'])) {
                                    $pathBD = $dcf['datos']['archivo']->storeAs($ruta_archivo . '/CFirmadas', 'doc_cfirmadas-editar' . $i . '.' . $extension);
                                    $i++;
                                    $documento = Documento::create([
                                        'id_requisicion' => $id_adquisicion,
                                        'ruta_documento' => $pathBD,
                                        'tipo_documento' => '2',
                                        'tipo_requisicion' => '1',
                                        'nombre_documento' => $nombre_doc,
                                        'extension_documento' => $extension
                                    ]);
                                }
                            }
                        }

                        $i = 1;
                        //$docsCotizacionesFirmadas = [];
                        if (empty($this->docsCotizacionesPdf) == false) {
                            foreach ($this->docsCotizacionesPdf as $dcp) {
                                $nombre_doc = $dcp['datos']['nombre_documento'];
                                $extension = $dcp['datos']['extension_documento'];
                                if (empty($dcp['datos']['id'])) {
                                    $pathBD = $dcp['datos']['archivo']->storeAs($ruta_archivo . '/CPdf', 'doc_cpdf-editar' . $i . '.' . $extension);
                                    $i++;
                                    $documento = Documento::create([
                                        'id_requisicion' => $id_adquisicion,
                                        'ruta_documento' => $pathBD,
                                        'tipo_documento' => '3',
                                        'tipo_requisicion' => '1',
                                        'nombre_documento' => $nombre_doc,
                                        'extension_documento' => $extension
                                    ]);
                                }
                            }
                        }

                        $i = 1;
                        if (empty($this->docsAnexoOtrosDocumentos) == 0) {
                            foreach ($this->docsAnexoOtrosDocumentos as $dao) {
                                $nombre_doc = $dao['datos']['nombre_documento'];
                                $extension = $dao['datos']['extension_documento'];
                                if (empty($dao['datos']['id'])) {
                                    $pathBD = $dao['datos']['archivo']->storeAs($ruta_archivo . '/AnexosOtros', 'doc_otros-editar' . $i . '.' . $extension);
                                    $i++;
                                    $documento = Documento::create([
                                        'id_requisicion' => $id_adquisicion,
                                        'ruta_documento' => $pathBD,
                                        'tipo_documento' => '5',
                                        'tipo_requisicion' => '1',
                                        'nombre_documento' => $nombre_doc,
                                        'extension_documento' => $extension
                                    ]);
                                }
                            }
                            //  $this->docsAnexoOtrosDocumentos = [];
                        }

                        DB::commit();
                        return redirect('/cvu-crear')->with('success', 'Su requerimiento con clave ' . $adquisicion->clave_adquisicion . ' ha sido  actualizado y se ha enviado para visto bueno.');

                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'error en el deposito' . $e->getMessage());
                }
            } else {
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
                        // 'id_carta_exclusividad' => $this->id_carta_exclusividad,
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
                    $fecha_actual = date('ymd');
                    $clave_adquisicion = 'R' . $clave_proyecto . $fecha_actual . $id_adquisicion;

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
                    if (empty($this->docsCartaExclusividad) == false) {
                        foreach ($this->docsCartaExclusividad as $dce) {
                            //extensiond e archivo a depositar
                            $nombre_doc = $dce['datos']['nombre_documento'];
                            $extension = $dce['datos']['extension_documento'];
                            //dd($dce);
                            //almacenamos archivo en servidor y obtenemos la ruta para agregar a la BD
                            $pathBD = $dce['datos']['archivo']->storeAs($ruta_archivo . '/CExclusividad', 'doc_exclusividad' . $i . '.' . $extension);
                            $i++;
                            $documento = Documento::create([
                                'id_requisicion' => $id_adquisicion,
                                'ruta_documento' => $pathBD,
                                'tipo_documento' => '1',
                                'tipo_requisicion' => '1',
                                'nombre_documento' => $nombre_doc,
                                'extension_documento' => $extension,


                            ]);
                        }
                        $i = 1;
                        //$this->docsCartaExclusividad = [];
                    }

                    $i = 1;
                    //$docsCartaExclusividad = [];
                    if (empty($this->docsCotizacionesFirmadas) == false) {
                        foreach ($this->docsCotizacionesFirmadas as $dcf) {
                            $nombre_doc = $dcf['datos']['nombre_documento'];
                            $extension = $dcf['datos']['extension_documento'];
                            $pathBD = $dcf['datos']['archivo']->storeAs($ruta_archivo . '/CFirmadas', 'doc_cfirmadas' . $i . '.' . $extension);
                            $i++;
                            $documento = Documento::create([
                                'id_requisicion' => $id_adquisicion,
                                'ruta_documento' => $pathBD,
                                'tipo_documento' => '2',
                                'tipo_requisicion' => '1',
                                'nombre_documento' => $nombre_doc,
                                'extension_documento' => $extension,

                            ]);
                        }
                    }
                    $i = 1;
                    //$docsCotizacionesFirmadas = [];

                    foreach ($this->docsCotizacionesPdf as $dcp) {
                        $nombre_doc = $dcp['datos']['nombre_documento'];
                        $extension = $dcp['datos']['extension_documento'];
                        $pathBD = $dcp['datos']['archivo']->storeAs($ruta_archivo . '/CPdf', 'doc_cpdf' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_adquisicion,
                            'ruta_documento' => $pathBD,
                            'tipo_documento' => '3',
                            'tipo_requisicion' => '1',
                            'nombre_documento' => $nombre_doc,
                            'extension_documento' => $extension,

                        ]);
                    }
                    $i = 1;
                    if (empty($this->docsAnexoOtrosDocumentos) == 0) {
                        foreach ($this->docsAnexoOtrosDocumentos as $dao) {
                            $nombre_doc = $dao['datos']['nombre_documento'];
                            $extension = $dao['datos']['extension_documento'];
                            $pathBD = $dao['datos']['archivo']->storeAs($ruta_archivo . '/AnexosOtros', 'doc_otros' . $i . '.' . $extension);
                            $i++;
                            $documento = Documento::create([
                                'id_requisicion' => $id_adquisicion,
                                'ruta_documento' => $pathBD,
                                'tipo_documento' => '5',
                                'tipo_requisicion' => '1',
                                'nombre_documento' => $nombre_doc,
                                'extension_documento' => $extension,
                            ]);
                        }
                    }

                    DB::commit();
                    return redirect('/cvu-crear')->with('success', 'Su requerimiento con clave ' . $clave_adquisicion . ' ha sido  registrado y se ha enviado para visto bueno.');
                } catch (\Exception $e) {
                    //dd("Error en el catch".$e); 
                    DB::rollback();
                    return redirect()->back()->with('error', 'error en el deposito' . $e->getMessage());
                }
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

        //Comprobamos si el bien ya esta en bd para eliminarlo y actualizar datos   
        if (isset($bien['id'])) {
            $bienBd = AdquisicionDetalle::findOrFail($bien['id']);
            if ($bienBd) { // si lo encuentra lo eliminamos
                //Buscamos la adquisicion para actualizar iva, importe y total
                $adquisicionBuscada = Adquisicion::findOrFail($bien['id_adquisicion']);
                if ($adquisicionBuscada) {
                    $adquisicionBuscada->update(['subtotal' => $this->subtotal, 'iva' => $this->iva, 'total' => $this->total]);

                }
                //Por ultimo eliminamos el bien
                $bienBd->delete();
            }
        }


        //$this->total = round($this->total, $precision = 2, $mode = PHP_ROUND_HALF_UP);

        $this->bienes = collect($this->bienes); //CONVERTIMOS NUEVAMENTE BIENES EN COLLECTION
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
        // dd($this->docsCartaExclusividad);
        //Proceso para eliminar archivo si ya lo habian precargado
        if ($tipoArchivo === 'cartasExclusividad') {
            //eliminamos el archivo de bd y sistema de archivos
            if (isset($this->docsCartaExclusividad[$index]['datos']['id'])) { //si el archivo ya existia en nuetra bd y sistema de archivos lo borramos

                $documentoFound = Documento::where('id', $this->docsCartaExclusividad[$index]['datos']['id'])->first();
                if ($documentoFound) {
                    //obtenemos la ruta
                    $filePath = $filePath = $documentoFound->ruta_documento;
                    // Checamos si existe el archivo en la ruta      
                    $fileExists = Storage::disk('local')->exists($filePath);

                    if ($fileExists) {
                        Storage::disk('local')->delete($filePath);
                    }
                    //Eliminamos de bd
                    $documentoFound->delete();

                }

            }
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
                //eliminamos el archivo de bd y sistema de archivos
                if (isset($this->docsCotizacionesFirmadas[$index]['datos']['id'])) { //si el archivo ya existia en nuetra bd y sistema de archivos lo borramos

                    $documentoFound = Documento::where('id', $this->docsCotizacionesFirmadas[$index]['datos']['id'])->first();
                    if ($documentoFound) {
                        //obtenemos la ruta
                        $filePath = $filePath = $documentoFound->ruta_documento;
                        // Checamos si existe el archivo en la ruta      
                        $fileExists = Storage::disk('local')->exists($filePath);

                        if ($fileExists) {
                            Storage::disk('local')->delete($filePath);
                        }
                        //Eliminamos de bd
                        $documentoFound->delete();

                    }

                }
                // Eliminar el archivo del array usando el índice
                unset($this->docsCotizacionesFirmadas[$index]);
                // Reindexar el array para asegurar una secuencia numérica continua
                $this->docsCotizacionesFirmadas = array_values($this->docsCotizacionesFirmadas);

            }
        }

        if ($tipoArchivo === 'cotizacionesPdf') {
            // Verificar si el índice existe en el array
            if (array_key_exists($index, $this->docsCotizacionesPdf)) {
                //eliminamos el archivo de bd y sistema de archivos
                if (isset($this->docsCotizacionesPdf[$index]['datos']['id'])) { //si el archivo ya existia en nuetra bd y sistema de archivos lo borramos

                    $documentoFound = Documento::where('id', $this->docsCotizacionesPdf[$index]['datos']['id'])->first();
                    if ($documentoFound) {
                        //obtenemos la ruta
                        $filePath = $filePath = $documentoFound->ruta_documento;
                        // Checamos si existe el archivo en la ruta      
                        $fileExists = Storage::disk('local')->exists($filePath);

                        if ($fileExists) {
                            Storage::disk('local')->delete($filePath);
                        }
                        //Eliminamos de bd
                        $documentoFound->delete();

                    }

                }
                // Eliminar el archivo del array usando el índice
                unset($this->docsCotizacionesPdf[$index]);
                // Reindexar el array para asegurar una secuencia numérica continua
                $this->docsCotizacionesPdf = array_values($this->docsCotizacionesPdf);

            }
        }

        if ($tipoArchivo === 'anexoDocumentos') {
            // Verificar si el índice existe en el array
            if (array_key_exists($index, $this->docsAnexoOtrosDocumentos)) {
                //eliminamos el archivo de bd y sistema de archivos
                if (isset($this->docsAnexoOtrosDocumentos[$index]['datos']['id'])) { //si el archivo ya existia en nuetra bd y sistema de archivos lo borramos

                    $documentoFound = Documento::where('id', $this->docsAnexoOtrosDocumentos[$index]['datos']['id'])->first();
                    if ($documentoFound) {
                        //obtenemos la ruta
                        $filePath = $filePath = $documentoFound->ruta_documento;
                        // Checamos si existe el archivo en la ruta      
                        $fileExists = Storage::disk('local')->exists($filePath);

                        if ($fileExists) {
                            Storage::disk('local')->delete($filePath);
                        }
                        //Eliminamos de bd
                        $documentoFound->delete();

                    }

                }
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

    public function resetdocsCartaExclusividad($id = 0)
    {
        $this->docsCartaExclusividad = [];
        $this->docsAnexoOtrosDocumentos = [];
        if ($id != 0) {
            $docs = Documento::select()->where('id_requisicion', $id)->where('tipo_requisicion', 1);
            if (isset($docs)) {
                $docs->delete();
            }
        }

    }


    public function updatedcartaExclusividadTemp()
    {
        $validatedData = $this->validate([
            'cartaExclusividadTemp' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ], [
            'cartaExclusividadTemp.mimes' => 'Debe adjuntar documentos únicamente con extensión: ' . $this->tipoDocumento,
            'cartaExclusividadTemp.max' => 'El tamaño del documento no puede ser mayor a ' . $this->tamanyoDocumentos,
        ]);

        // Validar si la validación fue exitosa antes de agregar los archivos al arreglo
        if (isset($validatedData['cartaExclusividadTemp'])) {
            // Obtener el nombre original del archivo
            $nombreDocumento = $validatedData['cartaExclusividadTemp']->getClientOriginalName();
            $extensionDoc = $validatedData['cartaExclusividadTemp']->getClientOriginalExtension();

            // Crear un nuevo array con la llave "archivo"
            $mergedArray = [
                'datos' => [
                    'nombre_documento' => $nombreDocumento,
                    'extension_documento' => $extensionDoc,
                    'archivo' => $validatedData['cartaExclusividadTemp'],
                ],
            ];

            // Agregar el array fusionado al arreglo docsCartaExclusividad
            $this->docsCartaExclusividad[] = $mergedArray;
        }


        $this->cartaExclusividadTemp = null;
    }
    public function updatedcotizacionFirmadaTemp()
    {
        //dd($this->tipoDocumento);
        $validatedData = $this->validate([
            'cotizacionFirmadaTemp' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ], [
            'cotizacionFirmadaTemp.mimes' => 'Debe adjuntar documentos únicamente con extensión: ' . $this->tipoDocumento,
            'cotizacionFirmadaTemp.max' => 'El tamaño del documento no puede ser mayor a ' . $this->tamanyoDocumentos,
        ]);

        // Validar si la validación fue exitosa antes de agregar los archivos al arreglo
        if (isset($validatedData['cotizacionFirmadaTemp'])) {
            // Obtener el nombre original del archivo
            $nombreDocumento = $validatedData['cotizacionFirmadaTemp']->getClientOriginalName();
            $extensionDoc = $validatedData['cotizacionFirmadaTemp']->getClientOriginalExtension();

            // Crear un nuevo array con la llave "archivo"
            $mergedArray = [
                'datos' => [
                    'nombre_documento' => $nombreDocumento,
                    'extension_documento' => $extensionDoc,
                    'archivo' => $validatedData['cotizacionFirmadaTemp'],
                ],
            ];

            // Agregar el array fusionado al arreglo docsCartaExclusividad
            $this->docsCotizacionesFirmadas[] = $mergedArray;
        }

        $this->cotizacionFirmadaTemp = null;
    }

    public function updatedcotizacionPdfTemp()
    {
        $validatedData = $this->validate([
            'cotizacionPdfTemp' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ], [
            'cotizacionPdfTemp.mimes' => 'Debe adjuntar documentos únicamente con extensión: ' . $this->tipoDocumento,
            'cotizacionPdfTemp.max' => 'El tamaño del documento no puede ser mayor a ' . $this->tamanyoDocumentos,
        ]);

        // Validar si la validación fue exitosa antes de agregar los archivos al arreglo
        if (isset($validatedData['cotizacionPdfTemp'])) {
            // Obtener el nombre original del archivo
            $nombreDocumento = $validatedData['cotizacionPdfTemp']->getClientOriginalName();
            $extensionDoc = $validatedData['cotizacionPdfTemp']->getClientOriginalExtension();

            // Crear un nuevo array con la llave "archivo"
            $mergedArray = [
                'datos' => [
                    'nombre_documento' => $nombreDocumento,
                    'extension_documento' => $extensionDoc,
                    'archivo' => $validatedData['cotizacionPdfTemp'],
                ],
            ];

            // Agregar el array fusionado al arreglo docsCartaExclusividad
            $this->docsCotizacionesPdf[] = $mergedArray;
        }

        $this->cotizacionPdfTemp = null;
    }

    public function updatedanexoOtroTemp()
    {
        $validatedData = $this->validate([
            'anexoOtroTemp' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ], [
            'anexoOtroTemp.mimes' => 'Debe adjuntar documentos únicamente con extensión: ' . $this->tipoDocumento,
            'anexoOtroTemp.max' => 'El tamaño del documento no puede ser mayor a ' . $this->tamanyoDocumentos,
        ]);

        // Validar si la validación fue exitosa antes de agregar los archivos al arreglo
        if (isset($validatedData['anexoOtroTemp'])) {
            // Obtener el nombre original del archivo
            $nombreDocumento = $validatedData['anexoOtroTemp']->getClientOriginalName();
            $extensionDoc = $validatedData['anexoOtroTemp']->getClientOriginalExtension();

            // Crear un nuevo array con la llave "archivo"
            $mergedArray = [
                'datos' => [
                    'nombre_documento' => $nombreDocumento,
                    'extension_documento' => $extensionDoc,
                    'archivo' => $validatedData['anexoOtroTemp'],
                ],
            ];

            // Agregar el array fusionado al arreglo docsCartaExclusividad
            $this->docsAnexoOtrosDocumentos[] = $mergedArray;

        }

        $this->anexoOtroTemp = null;
    }
    public function updated($id_rubro)
    {
        $this->validateOnly($id_rubro);
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
