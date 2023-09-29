<?php

namespace App\Http\Livewire;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class SolicitudRecursoModal extends ModalComponent
{
   //Atributos de un elemento de la colletion bienes
   public $_id = 0;
   public $importe = 0;
   public $concepto='';
  // public string $descripcion = '';
   public $justificacionS = '';
   public $finicial = 0;
   public $ffinal = 0;
   
   public $rubroS;

   public function render()
   {
       return view('livewire.solicitud-recurso-modal');
   }
   //REGLAS DE VALIDACION
   protected $rules = [
       'concepto' => 'required',
       'importe' => 'required',
       'justificacionS' => 'required',
   ];

   //MENSAJES DE LA VALIDACION
   protected $messages = [
       'concepto.required' => 'El concepto no puede estar vacio.',
       'importe.required' => 'El importe no puede estar vacio.',
       'justificacionS.required' => 'La justificación no puede estar vacía'
   ];
   
   //Método llamada  en el boton Guardar del modal
   public function agregarElemento()
   {
       $this->validate();
       $this->closeModalWithEvents([
           //'childModalEvent', // Emit global event
           //AdquisicionesForm::getName() => 'childModalEvent', // Emit event to specific Livewire component
           SolicitudesForm::getName() => ['addBien', [$this->_id, $this->importe, $this->concepto, $this->justificacionS, $this->finicial, $this->ffinal, $this->rubroS]] // Ejecuta el metodo y le envia los valores del formulario            
       ]);
       //reseteamos los valores
       $this->concepto = "";
       $this->importe = 0;
       $this->justificacionS = "";

   }

}
