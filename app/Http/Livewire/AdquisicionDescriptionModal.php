<?php

namespace App\Http\Livewire;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AdquisicionDescriptionModal extends ModalComponent
{
    //Atributos de un elemento de la colletion bienes
    public $_id = 0;
    public string $descripcion = '';
    public $cantidad = 0;
    public $precioUnitario = 0;
    public $checkIva = 0;
    public $iva = 0;
    public $importe = 0;
    public $justificacionSoftware;
    public $numAlumnos;
    public $numProfesores;
    public $numAdministrativos;




    public $id_rubro;

    //REGLAS DE VALIDACION
    protected $rules = [
        'descripcion' => 'required',
        'cantidad' => 'required',
        'precioUnitario' => 'required',
        'importe' => 'required',

    ];

    //MENSAJES DE LA VALIDACION
    protected $messages = [
        'descripcion.required' => 'La descripción no puede estar vacia.',
        'cantidad.required' => 'La cantidad no puede estar vacia.',
        'precioUnitario.required' => 'El precio unitario no puede estar vacio.',
        'importe.required' => 'El importe no puede estar vacio.',

    ];
    public function render()
    {
        return view('livewire.adquisicion-description-modal');
    }

    public function calcularIvaImporte()
    {

        //Validamos que sean valores numericos para evitar errores
        if (!is_numeric($this->cantidad)) {
            $this->cantidad = null;
        }
        if (!is_numeric($this->precioUnitario)) {
            $this->precioUnitario = null;
        }

        //Calcula IMPORTE SIN IVA
        $importe = $this->cantidad * $this->precioUnitario;
        //CALCULA IVA E IMPORTE
        if ($this->checkIva) {
            $this->iva = $importe * 0.16; //Calcula el IVA
            $importe += $importe * 0.16; // Ajusta el importe con el 16% de IVA

        } else {
            $this->iva = 0;
        }

        $this->importe = $importe;
    }

    //Método llamada  en el boton Guardar del modal
    public function agregarElemento()
    {
        $this->validate();
        $this->closeModalWithEvents([
            //'childModalEvent', // Emit global event
            //AdquisicionesForm::getName() => 'childModalEvent', // Emit event to specific Livewire component
            AdquisicionesForm::getName() => [
                'addBien',
                [
                    $this->_id,
                    $this->descripcion,
                    $this->cantidad,
                    $this->precioUnitario,
                    $this->iva,
                    $this->checkIva,
                    $this->importe,
                    $this->justificacionSoftware,
                    $this->numAlumnos,
                    $this->numProfesores,
                    $this->numAdministrativos,
                    $this->id_rubro
                ]
            ] // Ejecuta el metodo y le envia los valores del formulario            
        ]);
        //reseteamos los valores
        $this->descripcion = "";
        $this->cantidad = 0;
        $this->precioUnitario = 0;
        $this->iva = 0;
        $this->importe = 0;

    }


}