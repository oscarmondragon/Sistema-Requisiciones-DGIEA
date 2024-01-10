<?php

namespace App\Http\Livewire;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use function env;

class AdquisicionDescriptionModal extends ModalComponent
{
    //Atributos de un elemento de la colletion bienes
    public $_id = 0;
    public string $descripcion = '';
    public $cantidad;
    public $precio_unitario;
    public $checkIva = 1;
    public $iva;
    public $porcentajeIva;
    public $importe = 0;
    public $justificacion_software;
    public $alumnos = 0;
    public $profesores_invest = 0;
    public $administrativos = 0;




    public $id_rubro;
    public $id_rubro_especial;


    //REGLAS DE VALIDACION
    protected $rules = [
        'descripcion' => 'required',
        'cantidad' => 'required|gte:1',
        'precio_unitario' => 'required|gte:1',
        'importe' => 'required',
        'justificacion_software' => 'required_if:id_rubro_especial,1',
        'alumnos' => 'required_if:id_rubro_especial,1|gte:0',
        'profesores_invest' => 'required_if:id_rubro_especial,1|gte:0',
        'administrativos' => 'required_if:id_rubro_especial,1|gte:0'

    ];

    //MENSAJES DE LA VALIDACION
    protected $messages = [
        'descripcion.required' => 'La descripción no puede estar vacía.',
        'cantidad.required' => 'La cantidad no puede estar vacía.',
        'cantidad.gte' => 'La cantidad no puede ser menor a 1.',
        'precio_unitario.required' => 'El precio unitario no puede estar vacío.',
        'precio_unitario.gte' => 'El precio unitario no puede ser menor a 1.',
        'importe.required' => 'El importe no puede estar vacío.',
        'justificacion_software.required_if' => 'La justificación no puede estar vacía.',
        'alumnos.required_if' => 'El número de alumnos no puede estar vacío.',
        'alumnos.gte' => 'El número de alumnos no puede ser negativo.',
        'profesores_invest.required' => 'El número de profesores no puede estar vacío.',
        'profesores_invest.required_if' => 'El número de profesores no puede estar vacío.',
        'profesores_invest.gte' => 'El número de profesores no puede ser negativo.',
        'administrativos.required_if' => 'El número de los administrativos no puede estar vacío.',
        'administrativos.gte' => 'El número de administrativos no puede ser negativo.',

    ];
    public function render()
    {
        return view('livewire.adquisicion-description-modal');
    }

    public function mount()
    {
        $this->porcentajeIva = env('IVA', 16);


    }

    public function calcularIvaImporte()
    {

        //Validamos que sean valores numericos para evitar errores
        if (!is_numeric($this->cantidad)) {
            $this->cantidad = null;
        }
        if (!is_numeric($this->precio_unitario)) {
            $this->precio_unitario = null;
        }

        //Calcula IMPORTE SIN IVA
        $importe = $this->cantidad * $this->precio_unitario;
        //number_format($importe, 2, ',', '.');
        $importe = round($importe, $precision = 2, $mode = PHP_ROUND_HALF_UP);

        //CALCULA IVA E IMPORTE
        if ($this->checkIva) {
            $this->iva = ($importe * $this->porcentajeIva) / 100; //Calcula el IVA
            $this->iva = round($this->iva, $precision = 2, $mode = PHP_ROUND_HALF_UP);
            $importe += ($importe * $this->porcentajeIva) / 100; // Ajusta el importe con el 16% de IVA
            $importe = round($importe, $precision = 2, $mode = PHP_ROUND_HALF_UP);
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
                    $this->precio_unitario,
                    $this->iva,
                    $this->checkIva,
                    $this->importe,
                    $this->justificacion_software,
                    $this->alumnos,
                    $this->profesores_invest,
                    $this->administrativos,
                    $this->id_rubro
                ]
            ] // Ejecuta el metodo y le envia los valores del formulario            
        ]);
        //reseteamos los valores
        $this->descripcion = "";
        $this->cantidad = 0;
        $this->precio_unitario = 0;
        $this->iva = 0;
        $this->importe = 0;
    }
}