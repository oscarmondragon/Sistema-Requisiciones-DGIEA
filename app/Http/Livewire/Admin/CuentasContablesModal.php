<?php

namespace App\Http\Livewire\Admin;

use App\Models\CuentaContable;
use App\Models\CuentasEspeciales;
use App\Models\TipoRequisicion;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;

class CuentasContablesModal extends ModalComponent
{

    public $tiposRequisicion;
    public $cuentasEspeciales;
    public $cuentaContable;
    //formulario
    public $idClave;
    public $idCuenta;
    public $nombreCuenta;
    public $tipoRequisicion;
    public $idEspecial;
    public $estatus;
    public $idUsuario;
    public $cuenta;


    protected $rules = [
        'idClave' => 'required|numeric|gt:0',
        'nombreCuenta' => 'required',
        'tipoRequisicion' => 'required',
        'idEspecial' => 'nullable'
    ];

    protected $messages = [
        'idClave.numeric' => 'La clave cuenta debe de ser numerica.',
        'idClave.required' => 'La clave cuenta no puede estar vacía.',
        'idClave.gt' => 'La clave cuenta debe de ser mayor a 0.',
        'idClave.unique' => 'La clave cuenta ya existe.',
        'nombreCuenta.required' => 'El nombre de la cuenta no puede estar vacío.',
        'tipoRequisicion.required' => 'El tipo de requisición no puede estar vacío.',
    ];

    public function mount()
    {
        $this->tiposRequisicion = TipoRequisicion::select()->get();

        $this->cuentasEspeciales = CuentasEspeciales::where('estatus', 1)->get();

        if ($this->tipoRequisicion == 1) {
            $this->cuentasEspeciales = CuentasEspeciales::where('tipo_requisicion', 1)->get();
        } elseif ($this->tipoRequisicion == 2) {
            $this->cuentasEspeciales = CuentasEspeciales::where('tipo_requisicion', 2)->get();
        } elseif ($this->tipoRequisicion == 3) {
            $this->cuentasEspeciales = CuentasEspeciales::where('estatus', 1)->get();
        }

        $this->cuenta = CuentaContable::where('id_cuenta', $this->idCuenta)->first();        
    }

    public function render()
    {
        return view('livewire.admin.cuentas-contables-modal');
    }

    public function updated($estatus)
    {
        $this->validateOnly($estatus);
    }

    public function actualizarTipoRequisicion()
    {
        if ($this->tipoRequisicion == 0 || $this->tipoRequisicion == 3) {
            $this->cuentasEspeciales = CuentasEspeciales::where('estatus', 1)->get();
        } elseif ($this->tipoRequisicion == 1) {
            $this->cuentasEspeciales = CuentasEspeciales::where('tipo_requisicion', 1)->get();
        } elseif ($this->tipoRequisicion == 2) {
            $this->cuentasEspeciales = CuentasEspeciales::where('tipo_requisicion', 2)->get();
        }
    }

    public function store()
    { 

        $this->validate([
            'idClave' => 'unique:cuentas_contables,id',
        ]);

        $this->validate();
        $idCuentaMax = CuentaContable::max('id_cuenta');
        $idCuentaMax++;

        try {
            DB::beginTransaction();
            
            CuentaContable::create([
                'id' => $this->idClave,
                'id_cuenta' => $idCuentaMax,
                'nombre_cuenta' => strtoupper($this->nombreCuenta),
                'tipo_requisicion' => $this->tipoRequisicion,
                'id_especial' => $this->idEspecial != 0 ? $this->idEspecial : null,
                'estatus' => 1,
                'id_usuario_sesion' => Auth::user()->id
            ]);

            DB::commit();
            return redirect('/cuentas-contables')->with('success', 'Cuenta creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: Al intentar guardar cuenta contable. Intente más tarde.' . $e->getMessage());
        }
    
    }

    public function update()
    {

        //$cuenta = CuentaContable::where('id', $this->idClave)->first();
        if ($this->cuenta->id == $this->idClave) {
            $this->validate();
        }else{
            //dd("entre");
            $this->validate([
                'idClave' => 'unique:cuentas_contables,id',
            ]);
            $this->validate();
        }

        if ($this->cuenta) {
            try {
                DB::beginTransaction();

                if ($this->estatus == "0") {
                    $this->estatus = false;
                } elseif ($this->estatus == "1") {
                    $this->estatus = 1;
                } else {
                    return redirect()->back()->with('error', 'Error: Estatus no valido.');
                }

                $this->cuenta->update([
                    'id' => $this->idClave,
                    'nombre_cuenta' => strtoupper($this->nombreCuenta),
                    'tipo_requisicion' => $this->tipoRequisicion,
                    'id_especial' => $this->idEspecial != 0 ? $this->idEspecial : null,
                    'estatus' => $this->estatus,
                    'id_usuario_sesion' => Auth::user()->id
                ]);

                DB::commit();
                return redirect('/cuentas-contables')->with('success', 'Cuenta actualizada correctamente.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Error: Al intentar agregar una cuenta contable. Intente más tarde.' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Error: Cuenta contable no encontrada.');
        }
    }

}
