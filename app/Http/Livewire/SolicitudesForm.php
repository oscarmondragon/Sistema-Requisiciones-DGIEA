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




class SolicitudesForm extends Component
{
    use WithFileUploads;

    //catalogos
    public $cuentasContables;

    //Atributos de una solicitud de recursos
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
    public $vobo_admin = 0;
    public $vobo_rt = 0;

    public $id_emisor;
    public $id_revisor;
    public $comprobacion;
    public $aviso_privacidad;
    public $estatus_dgiea;
    public $estatus_rt;
    public $observaciones;


    public $recursos;
    public $docsbitacoraPdf = [];

    public $monto_sumado = 0;


    public function render()
    {
        return view('livewire.solicitudes-form');
    }

    public $listeners = ['addRecurso' => 'setRecurso',];

    public function mount()
    {
        $this->recursos = collect();
        $this->docsbitacoraPdf = [];
        $this->cuentasContables = CuentaContable::where('estatus', 1)->whereIn('tipo_requisicion', [2, 3])->get();
        $this->nombre_expedido = Session::get('name_rt');
    }

    protected $rules = [
        'id_rubro' => 'required|not_in:0',
        'nombre_expedido' => 'required',
        'recursos' => 'required|array|min:1',
        'docsbitacoraPdf' => 'required_if:id_rubro_especial,3',
        'comprobacion' => 'required_unless:id_rubro_especial,3',
        'aviso_privacidad' => 'accepted',
        'vobo' => 'accepted'
    ];
    protected $messages = [
        'id_rubro.required' => 'Debe seleccionar un rubro.',
        'id_rubro.not_in' => 'Debe seleccionar un rubro.',
        'monto_total.required' => 'El monto no puede estar vacio.',
        'monto_total.lte' => 'El monto no puede ser mayor a $35000.',
        'monto_total.gte' => 'El monto no puede ser menor a la suma de los importes de los recursos solicitados o igual a 0.',
        'nombre_expedido.required' => 'El nombre de quien expide no puede estar vacio',
        'recursos.required' => 'Debe agregar por lo menos un recurso.',
        'recursos.array' => 'Debe agregar por lo menos un recurso.',
        'recursos.min' => 'Debe agregar por lo menos un recurso.',
        'docsbitacoraPdf.required_if' => 'Debe adjuntar la bitacora.',
        'comprobacion.required_unless' => 'Debe de aceptar la condición.',
        'comprobacion.accepted' => 'Debe de aceptar la condición seleccionela.',
        'aviso_privacidad.accepted' => 'Debe de aceptar el aviso de privacidad.',
        'vobo.accepted' => 'Debe dar el visto bueno.'
    ];

    /* public function updated($propertyName){
         $this->validateOnly(field $propertyName);
     }*/

    public function save()
    {

        $this->validate([
            'id_rubro' => 'required|not_in:0',
            'recursos' => 'required|array|min:1',
            'monto_total' => 'required|lte:35000|gte:monto_sumado'
        ]);



        $clave_proyecto = Session::get('id_proyecto');
        $id_user = Session::get('id_user');

        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();

        if ($proyecto) {

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
                'id_bitacora' => $this->id_bitacora,
                'vobo_admin' => $this->vobo_admin,
                'vobo_rt' => $this->vobo_rt,
                'obligo_comprobar' => $this->comprobacion,
                'aviso_privacidad' => $this->aviso_privacidad,
                'id_emisor' => $id_user,
                'estatus_dgiea' => 1,
                'estatus_rt' => 1
            ]);

            // Genera la clave_solicitud con fecha y id
            $id_solicitud = $solicitud->id;
            $fecha_actual = date('Ymd');
            $clave_solicitud = $fecha_actual . 'SOLIC' . $id_solicitud;

            // Actualiza la clave de solicitud en el registro de la solicitud
            $solicitud->update(['clave_solicitud' => $clave_solicitud]);

            //Guarda los recursos en solicitud_detalles
            //primero agregamos el id_solicitud a cada recurso

            $this->recursos = $this->recursos->map(function ($recurso) use ($id_solicitud) {
                $recurso['id_solicitud'] = $id_solicitud;
                return $recurso;
            });

            foreach ($this->recursos as $recurso) {
                $elemento = SolicitudDetalle::create([
                    'id_solicitud' => $recurso['id_solicitud'],
                    'concepto' => $recurso['concepto'],
                    'justificacion' => $recurso['justificacionS'],
                    'importe' => $recurso['importe'],
                    'periodo_inicio' => $recurso['finicial'],
                    'periodo_fin' => $recurso['ffinal']
                ]);
            }

            return redirect('/cvu-crear')->with('success', 'Su solicitud ha sido guardada correctamente. Recuerde completarla y mandarla a visto bueno.');

        } else {
            // No se encontró ningún proyecto  con esca clave"
            return redirect()->back()->with('error', 'No se encontró un proyecto asociado a la clave ' . $clave_proyecto);
        }
    }

    public function saveVobo()
    {

        $this->validate();

        $clave_proyecto = Session::get('id_proyecto');
        $id_user = Session::get('id_user');

        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();

        if ($proyecto) {

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
                'id_bitacora' => $this->id_bitacora,
                'vobo_admin' => $this->vobo_admin,
                'vobo_rt' => $this->vobo_rt,
                'obligo_comprobar' => $this->comprobacion,
                'aviso_privacidad' => $this->aviso_privacidad,
                'id_emisor' => $id_user,
                'estatus_dgiea' => 2,
                'estatus_rt' => 2
            ]);

            // Genera la clave_solicitud con fecha y id
            $id_solicitud = $solicitud->id;
            $fecha_actual = date('Ymd');
            $clave_solicitud = $fecha_actual . 'SOLIC' . $id_solicitud;

            // Actualiza la clave de solicitud en el registro de la solicitud
            $solicitud->update(['clave_solicitud' => $clave_solicitud]);

            //Guarda los recursos en solicitud_detalles
            //primero agregamos el id_solicitud a cada recurso

            $this->recursos = $this->recursos->map(function ($recurso) use ($id_solicitud) {
                $recurso['id_solicitud'] = $id_solicitud;
                return $recurso;
            });

            foreach ($this->recursos as $recurso) {
                $elemento = SolicitudDetalle::create([
                    'id_solicitud' => $recurso['id_solicitud'],
                    'concepto' => $recurso['concepto'],
                    'justificacion' => $recurso['justificacionS'],
                    'importe' => $recurso['importe'],
                    'periodo_inicio' => $recurso['finicial'],
                    'periodo_fin' => $recurso['ffinal']
                ]);
            }

            return redirect('/cvu-crear')->with('success', 'Su solicitud ha sido guardada correctamente. Recuerde completarla y mandarla a visto bueno.');

        } else {
            // No se encontró ningún proyecto  con esca clave"
            return redirect()->back()->with('error', 'No se encontró un proyecto asociado a la clave ' . $clave_proyecto);
        }
    }
    public function updated($monto_total)
    {
        $this->validateOnly($monto_total);
    }


    public function setRecurso($_id, $importe, $concepto, $justificacionS, $finicial, $ffinal, $id_rubro, $monto_sumado)
    {
        $this->recursos = collect($this->recursos); //asegurar que recursos sea una coleccion

        if ($_id == 0) { //entramos aqui si el item es nuevo
            // Genera un nuevo ID para el elemento
            $newItemId = $this->recursos->max('_id') + 1;


            //Agregamos el recurso en la coleccion
            $this->recursos->push(['_id' => $newItemId, 'importe' => $importe, 'concepto' => $concepto, 'justificacionS' => $justificacionS, 'finicial' => $finicial, 'ffinal' => $ffinal]);
        } else {
            //Si entra aqui es por que entro a la funcion editar, entonces buscamos el item en la collecion por su id
            $item = $this->recursos->firstWhere('_id', $_id);

            if ($item) {
                //actualizamos el item si existe en la busqueda
                $item['concepto'] = $concepto;
                $item['importe'] = $importe;
                $item['justificacionS'] = $justificacionS;
                $item['finicial'] = $finicial;
                $item['ffinal'] = $ffinal;

                //Devolvemos la nueva collecion
                $this->recursos = $this->recursos->map(function ($recurso) use ($_id, $concepto, $importe, $justificacionS, $finicial, $ffinal) {
                    if ($recurso['_id'] == $_id) {
                        $recurso['concepto'] = $concepto;
                        $recurso['importe'] = $importe;
                        $recurso['justificacionS'] = $justificacionS;
                        $recurso['finicial'] = $finicial;
                        $recurso['ffinal'] = $ffinal;
                    }
                    return $recurso;
                });
                //actualizamos indices
                $this->recursos = $this->recursos->values();
            }
        }
        //asignamos el monto_sumado
        $this->monto_sumado = $monto_sumado;
    }



    public function deleteRecurso($recurso)
    {
        $this->monto_sumado = $this->monto_sumado - $recurso['importe'];
    }

    public function resetearRecursos($idRubroEspecial)
    {
        $this->id_rubro_especial = $idRubroEspecial; //asigna un valor a la variable rubro_especial para las validaciones de cuentas contables
        $this->recursos = collect();
        $this->monto_sumado = 0;
        $this->resetdocsBitacora();
        $this->monto_total = null;
        $this->comprobacion = 0;
        $this->aviso_privacidad = 0;
        $this->vobo = 0;




    }

    public function eliminarArchivo($tipoArchivo, $index)
    {
        if ($tipoArchivo === 'docsbitacoraPdf') {
            if (array_key_exists($index, $this->docsbitacoraPdf)) {
                // Eliminar el archivo del array usando el índice
                unset($this->docsbitacoraPdf[$index]);
                // Reindexar el array para asegurar una secuencia numérica continua
                $this->docsbitacoraPdf = array_values($this->docsbitacoraPdf);
            }
        }
    }

    public function resetdocsBitacora()
    {
        $this->docsbitacoraPdf = [];
    }

    public function rules()
    {
        $rules = $this->rules;
        $rules['monto_total'][] = 'required';
        $rules['monto_total'][] = 'lte:35000';
        $rules['monto_total'][] = 'min:1';
        $rules['monto_total'][] = function ($attribute, $value, $fail) {
            $montoSumado = $this->monto_sumado;
            $montoTotal = $this->monto_total;

            if ($montoSumado > $value) {
                $fail('El monto no puede ser menor a la suma de los importes de los recursos solicitados o igual a 0.');
            }
        };

        return $rules;
    }
}