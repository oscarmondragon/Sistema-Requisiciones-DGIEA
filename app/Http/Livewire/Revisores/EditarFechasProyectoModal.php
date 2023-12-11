<?php

namespace App\Http\Livewire\Revisores;

use App\Models\AsignacionProyecto;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\DB;

class EditarFechasProyectoModal extends ModalComponent
{

    public $fecha_inicio;
    public $fecha_final;
    public $fecha_limite_adquisiciones;
    public $fecha_limite_solicitudes;
    public $clave_digcyn;
    public $id_proyecto;
    public $clave_uaem;

    protected $rules = [
        'fecha_inicio' => 'date|required|after_or_equal:01/01/2023|before:01/01/2100',
        'fecha_final' => 'date|required|after_or_equal:fecha_inicio|before:01/01/2100',
        'fecha_limite_adquisiciones' => 'date|required|after_or_equal:01/01/2023|before:01/01/2100',
        'fecha_limite_solicitudes' => 'date|required|after_or_equal:01/01/2023|before:01/01/2100'

    ];
    protected $messages = [
        'fecha_inicio.required' => 'Debe seleccionar una fecha de inicio del proyecto.',
        'fecha_inicio.date' => 'Debe seleccionar una fecha de inicio del proyecto.',
        'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser posterior al año 2023.',
        'fecha_inicio.before' => 'Fecha incorrecta.',

        'fecha_final.required' => 'Debe seleccionar un fecha de finalización del proyecto.',
        'fecha_final.date' => 'Debe seleccionar un fecha de finalización del proyecto.',
        'fecha_final.after_or_equal' => 'La fecha de finalizacion debe ser posterior a la fecha de inicio.',
        'fecha_final.before' => 'Fecha incorrecta.',

        'fecha_limite_adquisiciones.required' => 'Debe seleccionar una fecha limite para adquisiciones.',
        'fecha_limite_adquisiciones.date' => 'Debe seleccionar una fecha limite para adquisiciones.',
        'fecha_limite_adquisiciones.after_or_equal' => 'La fecha  debe ser posterior al año 2023.',
        'fecha_limite_adquisiciones.before' => 'Fecha incorrecta.',

        'fecha_limite_solicitudes.required' => 'Debe seleccionar una fecha limite para solicitudes.',
        'fecha_limite_solicitudes.date' => 'Debe seleccionar una fecha limite para solicitudes.',
        'fecha_limite_solicitudes.after_or_equal' => 'La fecha debe ser posterior al año 2023.',
        'fecha_limite_solicitudes.before' => 'Fecha incorrecta.',
    ];


    public function render()
    {
        return view('livewire.revisores.editar-fechas-proyecto-modal');
    }

    public function save()
    {
        $this->validate();
        $proyecto = AsignacionProyecto::where('id_proyecto', $this->id_proyecto)->first();

        if ($proyecto) {
            try {
                DB::beginTransaction();
                $proyecto->fecha_inicio = $this->fecha_inicio;
                $proyecto->fecha_final = $this->fecha_final;
                $proyecto->fecha_limite_adquisiciones = $this->fecha_limite_adquisiciones;
                $proyecto->fecha_limite_solicitudes = $this->fecha_limite_solicitudes;
                $proyecto->save();
                DB::commit();
                return redirect('/asignados-revisor')->with(['success' => '¡Edición de fechas del  proyecto exitosa!.']);

            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error al asignar fechas al proyecto' . $e->getMessage());
            }
        } else {
            return 'Error: no se encontro el proyecto.';
        }

    }
}
