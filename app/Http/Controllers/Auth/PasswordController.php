<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Redirect;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag(
            'updatePassword',
            [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed', 'max:16'],

            ],
            [
                'current_password.required' => 'El campo contraseña actual no puede estar vacío.',
                'current_password.current_password' => 'Contraseña actual incorrecta.',
                'password.required' => 'El campo nueva contraseña no puede estar vacío.',
                'password.confirmed' => 'La contraseña de confirmación no coincide con la nueva contraseña.',
                'password.min' => 'Nueva contraseña demasiado corta (la nueva contraseña debe tener mínimo 8 caracteres).',
                'password.max' => 'Nueva contraseña demasiado larga (la nueva contraseña debe tener máximo 16 caracteres).',
            ]
        );

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('profile.update')->with('success', 'Su contraseña fue actualizada correctamente.');
        //return back()->with('status', 'password-updated');
    }
}
