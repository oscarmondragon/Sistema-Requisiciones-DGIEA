<?php

namespace App\Http\Livewire;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class SolicitudRecursoModal extends ModalComponent
{
    //Atributos de un elemento de la colletion bienes
    public $_id = 0;
    public $importe;
    public $concepto;
    // public string $descripcion = '';
    public $justificacionS;
    public $finicial;
    public $ffinal;

    public $id_rubro;
    public $id_rubro_especial = 0; //variable para saber si es una cuenta especial y agregar o quitar campos
    public $monto_total; //variable que guarda el monto total de la solicitud
    public $monto_sumado = 0; //variable que guarda la suma de lso recursos que se van agregando
    public $importe_editar = 0; //variable auxiliar para restar el valor de importe al monto_sumado despues de editarlo


    public function render()
    {
        return view('livewire.solicitud-recurso-modal');
    }
    //REGLAS DE VALIDACION
    protected $rules = [
        'concepto' => 'required',
        'monto_sumado' => 'lte:monto_total',
        'justificacionS' => 'required',
        'finicial' => 'required_if:id_rubro_especial,2',
        'ffinal' => 'nullable|date|required_if:id_rubro_especial,2|after_or_equal:finicial'
    ];

    //MENSAJES DE LA VALIDACION
    protected $messages = [
        'concepto.required' => 'El concepto no puede estar vacio.',
        'importe.required' => 'El importe no puede estar vacio.',
        'monto_sumado.lte' => 'La suma de los importes de lso recursos listados  no puede superar al monto total de la solicitud.',
        'justificacionS.required' => 'La justificación no puede estar vacía.',
        'finicial.required_if' => 'La fecha inicial no puede estar vacia.',
        'ffinal.required_if' => 'La fecha final no puede estar vacia.',
        'ffinal.after_or_equal' => 'La fecha final debe ser mayor o igual a la fecha inicial.'
    ];

    //Método llamada  en el boton Guardar del modal
    public function agregarElemento()
    {
        $this->validate();
        $this->actualizarMonto(); // actualizamos el monto_sumado, restando el importe_editar y sumando el importe nuevo
        $this->closeModalWithEvents([
            //'childModalEvent', // Emit global event
            //AdquisicionesForm::getName() => 'childModalEvent', // Emit event to specific Livewire component
            SolicitudesForm::getName() => ['addRecurso', [$this->_id, $this->importe, $this->concepto, $this->justificacionS, $this->finicial, $this->ffinal, $this->id_rubro, $this->monto_sumado]] // Ejecuta el metodo y le envia los valores del formulario            
        ]);
        //reseteamos los valores
        $this->concepto = "";
        $this->importe = 0;
        $this->justificacionS = "";

    }
    public function updated($importe)
    {
        $this->validateOnly($importe);
    }

    public function actualizarMonto()
    {
        //Validamos que sean valores numericos para evitar errores
        if (!is_numeric($this->importe)) {
            $this->importe = null;
        }
        if (!is_numeric($this->importe_editar)) {
            $this->importe_editar = null;
        }
        if (!is_numeric($this->monto_sumado)) {
            $this->monto_sumado = null;
        }
        //Restamos el valor anterior de importe al monto_total cuando se entra a editar
        $this->monto_sumado -= $this->importe_editar;

        //Sumamos el importe al monto_sumado
        $this->monto_sumado += $this->importe;

    }

    public function rules()
    {
        $rules = $this->rules;
        $rules['importe'][] = 'required';
        $rules['importe'][] = function ($attribute, $value, $fail) {
            $montoSumado = $this->monto_sumado;
            $montoTotal = $this->monto_total;
            $importeEditar = $this->importe_editar;


            if ($montoSumado - $importeEditar + $value > $montoTotal) {
                $fail('La suma de los importes de cada recurso no puede superar al monto total solicitado.');
            }
        };

        return $rules;
    }
}