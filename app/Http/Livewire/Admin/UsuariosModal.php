<?php

namespace App\Http\Livewire\Admin;

use App\Models\RolUsuario;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;

class UsuariosModal extends ModalComponent
{
    public $idUser;
    public $nombre;
    public $apellidoPaterno;
    public $apellidoMaterno;
    public $email;
    public $password;
    public $passwordConfirm;
    public $estatus;
    public $rolUsuario;

    public $rolesUsuarios;

    protected $rules = [
        'nombre' => 'required|max:40',
        'apellidoPaterno' => 'required|max:40',
        'apellidoMaterno' => 'max:40|nullable',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|min:6|max:20',
        'rolUsuario' => 'required'
        //|gt:0
    ];

    protected $messages = [
        'nombre.required' => 'El nombre no puede ser vacío.',
        'nombre.max' => 'El nombre es demasiado largo.',
        'apellidoPaterno.required' => 'El apellido paterno no puede estar vacío.',
        'apellidoPaterno.max' => 'El apellido paterno es demasiado largo.',
        'apellidoMaterno.max' => 'El apellido materno es demasiado largo.',
        'email.required' => 'El correo electrónico no puede estar vacío.',
        'email.email' => 'El correo electrónico no es valido.',
        'email.max' => 'El correo electrónico es demasiado largo.',
        'email.unique' => 'El correo electrónico ya existe.',
        'password.required' => 'La contraseña no puede estar vacía.',
        'password.min' => 'La contraseña debe de tener al menos 6 caracteres.',
        'password.max' => 'La contraseña es demasiado larga.',
        'passwordConfirm.required' => 'La contraseña de confirmación no puede estar vacía.',
        'rolUsuario.required' => 'El rol del usuario no puede estar vacío.',
        'rolUsuario.gt' => 'Selecciona un rol de usuario.',
    ];


    public function mount()
    {
        $this->rolesUsuarios = RolUsuario::get();
    }

    public function render()
    {
        return view('livewire.admin.usuarios-modal');
    }

    public function updated($nombre)
    {
        $this->validateOnly($nombre);
    }

    public function store()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            User::create([
                'name' => $this->nombre,
                'apePaterno' => $this->apellidoPaterno,
                'apeMaterno' => $this->apellidoMaterno,
                'email' => $this->email,
                'password' => $this->password,
                'estatus' => 1,
                'rol' => $this->rolUsuario,
                'id_usuario_sesion' => Auth::user()->id,
            ]);

            DB::commit();
            return redirect('/usuarios')->with('success', 'Usuario guardado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: Al intentar guardar el usuario. Intente más tarde.' . $e->getMessage());
        }
    }

    public function update()
    {

        $this->validate([
            'rolUsuario' => 'gt:0'
        ]);

        if ($this->estatus != 0 && $this->estatus != 1) {
            return redirect()->back()->with('error', 'Error: El estatus seleccionado no existe.');
        }

        $usuario = User::where('id', $this->idUser)->first();

        try {
            DB::beginTransaction();

            $usuario->update([
                'estatus' => $this->estatus,
                'rol' => $this->rolUsuario
            ]);



            DB::commit();
            return redirect('/usuarios')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: Al intentar actualizar el usuario. Intente más tarde.' . $e->getMessage());
        }
    }

    public function rules()
    {
        $rules = $this->rules;
        $rules['passwordConfirm'][] = 'required';
        $rules['passwordConfirm'][] = function ($attribute, $value, $fail) {


            if ($this->password != $this->passwordConfirm) {
                $fail('La contraseña nueva no coincide con la contraseña de confirmación.');
            }
        };

        return $rules;
    }
}
