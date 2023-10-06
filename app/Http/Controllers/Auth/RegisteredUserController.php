<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\RolUsuario;
use Livewire\WithFileUploads;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
     
     //public $roles_usuario;


    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apePaterno' => ['required', 'string', 'max:255'],
            'apeMaterno' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rol_usuario' => ['required']
        ]);

        $user = User::create([
            'name' => $request->name,
            'apePaterno' => $request->apePaterno,
            'apeMaterno' => $request->apeMaterno,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_usuario' => $request->rol_usuario
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    // public function mount()
    // {
    //     // $this->cuentasContables = CuentaContable::where('estatus', 1)->where('tipo_requisicion', 1)->get();
    //     $this->roles_usuario = RolUsuario::where('estatus', 1)->get();

    // }
}
