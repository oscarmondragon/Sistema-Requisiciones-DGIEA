<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Proyecto;
use App\Models\CuentaContable;
use Illuminate\Support\Facades\Session;
use App\Models\Solicitud;
use App\Models\SolicitudDetalle;
use App\Models\Documento;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use RealRashid\SweetAlert\Facades\Alert;


class SolicitudesForm extends Component
{
    use WithFileUploads;

    public $solicitud;


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
    public $tipoDocumento;

    public function render()
    {
        return view('livewire.solicitudes-form')->layout('layouts.cvu');
    }

    public function mount($id = 0)
    {

        $this->cuentasContables = CuentaContable::where('estatus', 1)->whereIn('tipo_requisicion', [2, 3])->get();
        $this->nombre_expedido = Session::get('name_rt');
        $this->tamanyoDocumentos = env('TAMANYO_MAX_DOCS', 2048);
        $this->tipoDocumento = env('DOCUMENTOS_PERMITIDOS', 'pdf');

        if ($id != 0) { //entra aqui si es una requisicion existente. Ejemplo para editar
            $this->solicitud = Solicitud::find($id);
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


            $documentos = Documento::where('id_requisicion', $id)->where('tipo_requisicion', 2)->get();
            foreach ($documentos as $documento) {
                if ($documento->tipo_documento == 4) {
                    $this->docsbitacoraPdf[] = ['datos' => $documento];
                    //  dd($this->docsCartaExclusividad);
                }
            }
        } else {

            $this->docsbitacoraPdf = [];
        }
    }

    protected $rules = [
        'id_rubro' => 'required|not_in:0',
        'monto_total' => 'required|lte:35000|gte:1',
        'nombre_expedido' => 'required',
        'docsbitacoraPdf' => 'required_if:id_rubro_especial,3',
        'tipo_comprobacion' => 'required_if:id_rubro_especial,3',
        //'comprobacion' => 'required_unless:tipo_comprobacion,"vale"|accepted_if:tipo_comprobacion,"ficha"',
        'aviso_privacidad' => 'accepted',
        'vobo' => 'accepted',
        'concepto' => 'required',
        'justificacionS' => 'required',
        'finicial' => 'nullable|date|required_if:id_rubro_especial,2|after_or_equal:14 days',
        'ffinal' => 'nullable|date|required_if:id_rubro_especial,2|after_or_equal:finicial',
    ];
    protected $messages = [
        'id_rubro.required' => 'Debe seleccionar un rubro.',
        'id_rubro.not_in' => 'Debe seleccionar un rubro.',
        'monto_total.required' => 'El monto no puede estar vacío.',
        'monto_total.lte' => 'El monto no puede ser mayor a $35000.',
        'monto_total.gte' => 'El monto no puede ser menor o igual a 0.',
        'nombre_expedido.required' => 'El nombre de quien expide no puede estar vacío',
        'docsbitacoraPdf.required_if' => 'Debe adjuntar la bitacora.',
        'bitacoraPdfTemp' => 'Solo puedes adjuntar archivos con extensión pdf ',
        'bitacoraPdfTemp.max' => 'El archivo no debe pesar mas de 2MB',
        //'comprobacion.required_unless' => 'Debe de aceptar la condición.',
        //'comprobacion.accepted_if' => 'Debe de aceptar la condición seleccionela.',
        'comprobacion.accepted' => 'Debe de aceptar la condición seleccionela.',
        'tipo_comprobacion.required_if' => 'Debe de elegir una opción.',
        'aviso_privacidad.accepted' => 'Debe de aceptar el aviso de privacidad.',
        'vobo.accepted' => 'Debe dar el visto bueno.',
        'concepto.required' => 'El concepto no puede estar vacío.',
        'importe.required' => 'El importe no puede estar vacío.',
        'justificacionS.required' => 'La justificación no puede estar vacía.',
        'finicial.required_if' => 'La fecha inicial no puede estar vacía.',
        'finicial.after_or_equal' => 'La fecha inicial debe ser una fecha posterior o igual a 15 días.',
        'ffinal.required_if' => 'La fecha final no puede estar vacía.',
        'ffinal.after_or_equal' => 'La fecha final debe ser mayor o igual a la fecha inicial.',
    ];

    /* public function updated($propertyName){
         $this->validateOnly(field $propertyName);

     }*/

    protected $listeners = [
        'save',
        'saveVobo',
        'eliminarArchivo'
    ];

    public function save()
    {
        //dd($this->finicial);
        $this->validate([
            'id_rubro' => 'required|not_in:0',
            'monto_total' => 'required|lte:35000',
        ]);

        if ($this->finicial != "") {
            $this->validate([
                'finicial' => 'nullable|date|after_or_equal:14 days',
            ]);
        } else {
            $this->finicial = null;
        }

        if ($this->ffinal != "") {
            $this->validate([
                'ffinal' => 'nullable|date|after_or_equal:finicial',
            ]);
        } else {
            $this->ffinal = null;
        }

        $clave_proyecto = Session::get('id_proyecto');
        $id_user = Session::get('id_user');

        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();

        if ($proyecto) {
            DB::beginTransaction();
            try {

                //Inserta la solicitud en base de datos
                $solicitud = Solicitud::create([
                    'clave_solicitud' => '',
                    'tipo_requisicion' => $this->tipo_requisicion,
                    'clave_proyecto' => $clave_proyecto,
                    'clave_espacio_academico' => $proyecto->CveCenCos,
                    'clave_rt' => $proyecto->CveEntEmp_Responsable,
                    'tipo_financiamiento' => $proyecto->Tipo_Proyecto,
                    'id_rubro' => (int) $this->id_rubro,
                    'monto_total' => $this->monto_total,
                    'nombre_expedido' => $this->nombre_expedido,
                    'id_bitacora' => $this->id_bitacora,
                    'vobo_admin' => null,
                    'vobo_rt' => null,
                    'obligo_comprobar' => $this->comprobacion,
                    'aviso_privacidad' => $this->aviso_privacidad,
                    'id_emisor' => $id_user,
                    'estatus_dgiea' => 1,
                    'estatus_rt' => 1,
                    'tipo_comprobacion' => $this->tipo_comprobacion
                ]);

                // Genera la clave_solicitud con fecha y id
                $id_solicitud = $solicitud->id;
                $fecha_actual = date('y');
                $clave_solicitud = 'S' . $clave_proyecto . $fecha_actual . $id_solicitud;

                // Actualiza la clave de solicitud en el registro de la solicitud
                $solicitud->update(['clave_solicitud' => $clave_solicitud]);

                //Guarda  en solicitud_detalles
                $elemento = SolicitudDetalle::create([
                    'id_solicitud' => $id_solicitud,
                    'concepto' => $this->concepto,
                    'justificacion' => $this->justificacionS,
                    'importe' => $this->monto_total,
                    'periodo_inicio' => $this->finicial,
                    'periodo_fin' => $this->ffinal
                ]);
                $ruta_archivo = $clave_proyecto . '/Solicitudes/' . $id_solicitud;
                $i = 1;
                //Revisar si los arreglos contienen datos/

                if (empty($this->docsbitacoraPdf) == false) {
                    foreach ($this->docsbitacoraPdf as $dbp) {
                        //extensiond e archivo a depositar
                        $nombre_doc = $dbp['datos']['nombre_documento'];
                        $extension = $dbp['datos']['extension_documento'];
                        //dd($dce);
                        //almacenamos archivo en servidor y obtenemos la ruta para agregar a la BD
                        $pathBD = $dbp['datos']['archivo']->storeAs($ruta_archivo . '/Bitacoras', 'doc_bitacora' . $i . '.' . $extension);
                        $i++;
                        $documento = Documento::create([
                            'id_requisicion' => $id_solicitud,
                            'ruta_documento' => $pathBD,
                            'tipo_documento' => '4',
                            'tipo_requisicion' => '2',
                            'nombre_documento' => $nombre_doc,
                            'extension_documento' => $extension,
                        ]);
                    }
                }
                DB::commit();
                return redirect('/cvu-crear')->with('success', 'Su solicitud ha sido guardada correctamente con el número ' . $clave_solicitud . ', recuerde completarla y mandarla a visto bueno.');
            } catch (\Exception $e) {
                DB::rollback();
                dd("Error en el catch" . $e);
                return redirect()->back()->with('error', 'Error en el proceso de guardado ' . $e->getMessage());
            }
        } else {
            // No se encontró ningún proyecto  con esca clave"
            return redirect()->back()->with('error', 'No se encontró un proyecto asociado a la clave ' . $clave_proyecto);
        }
    }

    public function saveVobo()
    {
        $this->emit('saveVoBo');

        $this->validate();

        $clave_proyecto = Session::get('id_proyecto');
        $id_user = Session::get('id_user');
        $who_vobo = Session::get('VoBo_Who');
        $fecha_vobo = Carbon::now()->toDateString();

        if ($who_vobo) { //Si el deposito es por parte del Responsable técnico
            $vobo_admin = null;
            $this->vobo_rt = $fecha_vobo;
        } else { //Si el depósito es por parte del administrativo
            $this->vobo_admin = $fecha_vobo;
            $vobo_rt = null;
        }

        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();

        if ($proyecto) {
            if (isset($this->solicitud)) {

                try {
                    DB::beginTransaction();
                    $solicitud = Solicitud::where('id', $this->id_solicitud)->first();
                    $id_solicitud = $solicitud->id;
                    $clave_solicitud = $solicitud->clave_solicitud;
                    if ($solicitud) {
                        $solicitud->id_rubro = $this->id_rubro;
                        $solicitud->monto_total = $this->monto_total;
                        $solicitud->nombre_expedido = $this->nombre_expedido;
                        $solicitud->tipo_comprobacion = $this->tipo_comprobacion;
                        // $solicitud->vobo_admin = $this->$this->vobo_admin;
                        //  $solicitud->vobo_rt = $this->$this->vobo_rt;
                        $solicitud->estatus_rt = 2;
                        $solicitud->obligo_comprobar = $this->comprobacion;
                        $solicitud->aviso_privacidad = $this->aviso_privacidad;

                        $solicitud->save();

                        $solicitudDetalle = SolicitudDetalle::where('id_solicitud', $this->id_solicitud)->first();
                        if ($solicitudDetalle) {

                            $solicitudDetalle->concepto = $this->concepto;
                            $solicitudDetalle->justificacion = $this->justificacionS;
                            $solicitudDetalle->importe = $this->monto_total;
                            $solicitudDetalle->periodo_inicio = $this->finicial;
                            $solicitudDetalle->periodo_fin = $this->ffinal;

                            $solicitudDetalle->save();
                        }
                        $i = 1;
                        if (empty($this->docsbitacoraPdf) == false) {
                            foreach ($this->docsbitacoraPdf as $dbp) {
                                //extensiond e archivo a depositar
                                $nombre_doc = $dbp['datos']['nombre_documento'];
                                $extension = $dbp['datos']['extension_documento'];
                                //dd($dce);
                                $ruta_archivo = $clave_proyecto . '/Solicitudes/' . $id_solicitud;
                                //almacenamos archivo en servidor y obtenemos la ruta para agregar a la BD
                                if (empty($dbp['datos']['id'])) {
                                    $pathBD = $dbp['datos']['archivo']->storeAs($ruta_archivo . '/Bitacoras', 'doc_bitacora-editar' . $i . '.' . $extension);
                                    $i++;
                                    $documento = Documento::create([
                                        'id_requisicion' => $id_solicitud,
                                        'ruta_documento' => $pathBD,
                                        'tipo_documento' => '4',
                                        'tipo_requisicion' => '2',
                                        'nombre_documento' => $nombre_doc,
                                        'extension_documento' => $extension,
                                    ]);
                                }
                            }
                        }
                    }
                    DB::commit();
                    return redirect('/cvu-crear')->with('success', 'Su solicitud con clave ' . $clave_solicitud . ' ha sido  registrada y se ha enviado para visto bueno.');
                } catch (\Exception $e) {
                    DB::rollback();
                    dd("Error en el catch" . $e);
                    return redirect()->back()->with('error', 'Error en el proceso de guardado ' . $e->getMessage());
                }
            } else {
                try {
                    DB::beginTransaction();
                    //Inserta la Adquisición en base de datos
                    $solicitud = Solicitud::create([
                        'clave_solicitud' => '',
                        'tipo_requisicion' => $this->tipo_requisicion,
                        'clave_proyecto' => $clave_proyecto,
                        'clave_espacio_academico' => $proyecto->CveCenCos,
                        'clave_rt' => $proyecto->CveEntEmp_Responsable,
                        'tipo_financiamiento' => $proyecto->Tipo_Proyecto,
                        'id_rubro' => (int) $this->id_rubro,
                        'monto_total' => $this->monto_total,
                        'nombre_expedido' => $this->nombre_expedido,
                        'vobo_admin' => $this->vobo_admin,
                        'vobo_rt' => $this->vobo_rt,
                        'obligo_comprobar' => $this->comprobacion,
                        'aviso_privacidad' => $this->aviso_privacidad,
                        'id_emisor' => $id_user,
                        'estatus_dgiea' => 2,
                        'estatus_rt' => 2,
                        'tipo_comprobacion' => $this->tipo_comprobacion
                    ]);

                    // Genera la clave_solicitud con clave_proyecto, fecha y id
                    $id_solicitud = $solicitud->id;
                    $fecha_actual = date('y');
                    $clave_solicitud = 'S' . $clave_proyecto . $fecha_actual . $id_solicitud;

                    // Actualiza la clave de solicitud en el registro de la solicitud
                    $solicitud->update(['clave_solicitud' => $clave_solicitud]);

                    //Guarda en solicitud_detalles

                    $elemento = SolicitudDetalle::create([
                        'id_solicitud' => $id_solicitud,
                        'concepto' => $this->concepto,
                        'justificacion' => $this->justificacionS,
                        'importe' => $this->monto_total,
                        'periodo_inicio' => $this->finicial,
                        'periodo_fin' => $this->ffinal
                    ]);

                    $ruta_archivo = $clave_proyecto . '/Solicitudes/' . $id_solicitud;
                    $i = 1;
                    if (empty($this->docsbitacoraPdf) == false) {
                        foreach ($this->docsbitacoraPdf as $dbp) {
                            //extensiond e archivo a depositar
                            $nombre_doc = $dbp['datos']['nombre_documento'];
                            $extension = $dbp['datos']['extension_documento'];
                            //dd($dce);
                            //almacenamos archivo en servidor y obtenemos la ruta para agregar a la BD
                            $pathBD = $dbp['datos']['archivo']->storeAs($ruta_archivo . '/Bitacoras', 'doc_bitacora' . $i . '.' . $extension);
                            $i++;
                            $documento = Documento::create([
                                'id_requisicion' => $id_solicitud,
                                'ruta_documento' => $pathBD,
                                'tipo_documento' => '4',
                                'tipo_requisicion' => '2',
                                'nombre_documento' => $nombre_doc,
                                'extension_documento' => $extension,
                            ]);
                        }
                    }
                    $i = 1;
                    DB::commit();
                    return redirect('/cvu-crear')->with('success', 'Su solicitud con clave ' . $clave_solicitud . ' ha sido  registrada y se ha enviado para visto bueno.');
                } catch (\Exception $e) {
                    DB::rollback();
                    dd("Error en el catch" . $e);
                    return redirect()->back()->with('error', 'Error en el proceso de guardado ' . $e->getMessage());
                }
            }
        } else {
            // No se encontró ningún proyecto  con esca clave"
            return redirect()->back()->with('error', 'No se encontró un proyecto asociado a la clave ' . $clave_proyecto);
        }
    }
    public function updated($monto_total)
    {
        $this->validateOnly($monto_total);
    }


    public function resetearRecursos($idRubroEspecial)
    {
        $this->id_rubro_especial = $idRubroEspecial; //asigna un valor a la variable rubro_especial para las validaciones de cuentas contables
        $this->resetdocsBitacora();
        $this->monto_total = null;
        $this->concepto = "";
        $this->justificacionS = "";
        $this->finicial = null;
        $this->ffinal = null;
        $this->tipo_comprobacion = null;
        $this->comprobacion = 0;
        $this->aviso_privacidad = 0;
        $this->vobo = 0;
    }



    public function eliminarArchivo($tipoArchivo, $index)
    {
        if ($tipoArchivo === 'docsbitacoraPdf') {
            //eliminamos el archivo de bd y sistema de archivos
            if (isset($this->docsbitacoraPdf[$index]['datos']['id'])) { //si el archivo ya existia en nuetra bd y sistema de archivos lo borramos

                $documentoFound = Documento::where('id', $this->docsbitacoraPdf[$index]['datos']['id'])->first();
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
            unset($this->docsbitacoraPdf[$index]);
            // Reindexar el array para asegurar una secuencia numérica continua
            $this->docsbitacoraPdf = array_values($this->docsbitacoraPdf);
        }
    }

    public function resetdocsBitacora()
    {
        $this->docsbitacoraPdf = [];
    }

    public function updatedbitacoraPdfTemp()
    {
        $validatedData = $this->validate([
            'bitacoraPdfTemp' => 'mimes:' . $this->tipoDocumento . '|max:' . $this->tamanyoDocumentos . '',
        ]);

        // Validar si la validación fue exitosa antes de agregar los archivos al arreglo
        if (isset($validatedData['bitacoraPdfTemp'])) {
            // Obtener el nombre original del archivo
            $nombreDocumento = $validatedData['bitacoraPdfTemp']->getClientOriginalName();
            $extensionDoc = $validatedData['bitacoraPdfTemp']->getClientOriginalExtension();

            // Crear un nuevo array con la llave "archivo"
            $mergedArray = [
                'datos' => [
                    'nombre_documento' => $nombreDocumento,
                    'extension_documento' => $extensionDoc,
                    'archivo' => $validatedData['bitacoraPdfTemp'],
                ],
            ];

            // Agregar el array fusionado al arreglo docsCartaExclusividad
            $this->docsbitacoraPdf[] = $mergedArray;
        }

        $this->bitacoraPdfTemp = null;
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

    public function rules()
    {
        $rules = $this->rules;
        if ($this->tipo_comprobacion != 'vale') {
            $rules['comprobacion'][] = 'accepted';
        }
        return $rules;
    }
}
