<?php

namespace App\Http\Livewire\Admin;

use App\Models\AsignacionProyecto;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReasignarProyectoModal extends ModalComponent
{


    public $revisores;
    public $nuevoRevisor;
    public $id_proyecto;
    public $id_revisor;
    public $clave_uaem;
    public $clave_digcyn;


    protected $rules = [
        'nuevoRevisor' => 'required|not_in:0',
    ];
    protected $messages = [
        'nuevoRevisor.required' => 'Debe seleccionar un revisor.',
        'nuevoRevisor.not_in' => 'Debe seleccionar un revisor.',
    ];

    public function mount()
    {
        //OBTENEMOS DATOS DE USUARIO LOGUEADO
        $user = auth()->user();
        //OBTENEMOS LOS PROYECTOS DE LA VISTA

        if ($user->rol === 1) {
            //Si es un administrador externo, se traen los revisores externos
            $this->revisores = User::where('rol', 3)->get();

        } else if ($user->rol === 2) { //si es interno se traen revisores internos
            $this->revisores = User::where('rol', 4)->get();

        }

    }
    public function render()
    {
        return view('livewire.admin.reasignar-proyecto-modal');

    }

    public function saveReasignar()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $asignacionPasada = AsignacionProyecto::where('id_proyecto', $this->id_proyecto)->first();
            if ($asignacionPasada) {
                $asignacionPasada->update(['id_revisor' => $this->nuevoRevisor]);
            } else {
                return redirect()->back()->with('error', 'El proyecto que desea reasignar no cuenta con asignación aun.');
            }
            DB::commit();
            return redirect('/asignacion-proyectos')->with(['success-asignacion' => '¡Reasignación de proyecto exitosa!.', 'activeTab' => 2]);
            //  return redirect('/asignacion-proyectos?activeTab=2')->with('success', '¡Reasignación de proyecto exitosa!.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al reasignar el proyecto.' . $e->getMessage());
        }
    }

}