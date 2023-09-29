<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class SolicitudesForm extends Component
{
    use WithFileUploads;

    public $recurso;
    public $rubroS;
    public $monto_total;
    public $nombre_expedido;
    public $docsbitacoraPdf = [];
    public $bienes;


    public function render()
    {
        return view('livewire.solicitudes-form');
    }

    public $listeners =['addBien' => 'setBien',];

    public function mount()
    {
        $this->bienes = collect();
        $this->docsbitacoraPdf = [];
    }

     /*funcion que valida el monto total ingresado*/
    public function validaMonto()
    {
        if($rubroS = "51260101" and $monto_total > 35000){
            $messages = [
                'monto.required' => 'La monto no puede estar vacia.',
                'importe.required' => 'El importe no puede estar vacio.',
            ];
        }
    }

  
    protected $rules =[
        'monto_total' => 'required'
    ];
    protected $messages = [
        'monto_total.required' => 'La monto no puede estar vacia.',
        'importe.required' => 'El importe no puede estar vacio.',
    ];

   /* public function updated($propertyName){
        $this->validateOnly(field $propertyName);
    }*/

    public function save(){
        $this ->validate();

        if($rubroS = "51260101" and $monto_total > 35000){
            
        }

    }

    public function setBien($_id, $importe, $concepto, $justificacionS, $ffinal, $finicial, $rubroS)
    {
        $this->bienes = collect($this->bienes); //asegurar que bienes sea una coleccion

        if ($_id == 0) { //entramos aqui si el item es nuevo
            // Genera un nuevo ID para el elemento
            $newItemId = $this->bienes->max('_id') + 1;

            if ($rubroS === '51220103' || $rubroS === '51370103') {
                //Agregamos el bien en la coleccion
                $this->bienes->push(['_id' => $newItemId, 'importe' => $importe, 'concepto' => $concepto, 'justificacionS' => $justificacionS, 'finicial' => $finicial, 'ffinal' => $ffinal]);
            } else {
                //Agregamos el bien en la coleccion
                $this->bienes->push(['_id' => $newItemId, 'importe' => $importe, 'concepto' => $concepto, 'justificacionS' => $justificacionS]);
            }

        } else {
            //Si entra aqui es por que entro a la funcion editar, entonces buscamos el item en la collecion por su id
            $item = $this->bienes->firstWhere('_id', $_id);

            if ($item) {
                //actualizamos el item si existe en la busqueda
                $item['concepto'] = $concepto;
                $item['importe'] = $importe;
                $item['justificacionS'] = $justificacionS;
                $item['finicial'] = $finicial;
                $item['ffinal'] = $ffinal;

                //Devolvemos la nueva collecion
                $this->bienes = $this->bienes->map(function ($bien) use ($_id, $concepto, $importe, $justificacionS, $finicial, $ffinal) {
                    if ($bien['_id'] == $_id) {
                        $bien['concepto'] = $concepto;
                        $bien['importe'] = $importe;
                        $bien['justificacionS'] = $justificacionS;
                        $bien['finicial'] = $finicial;
                        $bien['ffinal'] = $ffinal;

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
        $this->bienes->forget($bien);
    }
    
    public function resetearBienes()
    {
        $this->bienes = collect();
    }

    public function eliminarArchivo($tipoArchivo, $index)
    {
        if ($tipoArchivo === 'docsbitacoraPdf') {
            unset($this->docsbitacoraPdf[$index]);
            $this->docsbitacoraPdf = array_values($this->docsbitacoraPdf);

        } 

    }

}
