<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Proyecto;
use App\Models\CuentaContable;


class SolicitudesForm extends Component
{
    use WithFileUploads;

    //catalogos
    public $cuentasContables;

    //Atributos de una solicitud de recursos
    public $id_rubro;
    public $id_rubro_especial; //variable para determinar si es una cuenta especial (software por ejemplo)
    public $monto_total;
    public $nombre_expedido;
    public $recursos;
    public $docsbitacoraPdf = [];
    public $checkMeobligo;
    public $checkAceptoPrivacidad;
    public $vobo;


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
    }

    protected $rules = [
        'monto_total' => 'required'
    ];
    protected $messages = [
        'monto_total.required' => 'La monto no puede estar vacia.',
        'importe.required' => 'El importe no puede estar vacio.',
    ];

    /* public function updated($propertyName){
         $this->validateOnly(field $propertyName);
     }*/

    public function save()
    {
        $this->validate();
    }

    public function setRecurso($_id, $importe, $concepto, $justificacionS, $finicial, $ffinal, $id_rubro)
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
    }

    public function deleteRecurso($recurso)
    {
        $this->recursos->forget($recurso);
    }

    public function resetearRecursos($idRubroEspecial)
    {
        $this->id_rubro_especial = $idRubroEspecial;
        $this->recursos = collect();
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


}
