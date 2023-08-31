<?php

namespace App\Http\Livewire;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AdquisicionDescriptionModal extends ModalComponent
{
    //Atributos de un elemento de la colletion bienes
    public $_id = 0;
    public string $descripcion = '';
    public $importe = 0;
    public $justificacionSoftware = '';
    public $numAlumnos = 0;
    public $numProfesores = 0;
    public $numAdministrativos = 0;




    public $rubro;

    //REGLAS DE VALIDACION
    protected $rules = [
        'descripcion' => 'required',
        'importe' => 'required',
    ];

    //MENSAJES DE LA VALIDACION
    protected $messages = [
        'descripcion.required' => 'La descripción no puede estar vacia.',
        'importe.required' => 'El importe no puede estar vacio.',
    ];
    public function render()
    {
        return view('livewire.adquisicion-description-modal');
    }

    //Método llamada  en el boton Guardar del modal
    public function agregarElemento()
    {
        $this->validate();
        $this->closeModalWithEvents([
            //'childModalEvent', // Emit global event
            //AdquisicionesForm::getName() => 'childModalEvent', // Emit event to specific Livewire component
            AdquisicionesForm::getName() => ['addBien', [$this->_id, $this->descripcion, $this->importe, $this->justificacionSoftware, $this->numAlumnos, $this->numProfesores, $this->numAdministrativos, $this->rubro]] // Ejecuta el metodo y le envia los valores del formulario            
        ]);
        //reseteamos los valores
        $this->descripcion = "";
        $this->importe = 0;

    }


}