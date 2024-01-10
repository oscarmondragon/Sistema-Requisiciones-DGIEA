<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\pL\s\-]+$/u'],
            'apePaterno' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\pL\s\-]+$/u'],
            'apeMaterno' => ['string', 'min:3', 'max:50', 'nullable', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre no puede estar vacío.',
            'name.string' => 'Nombre no valido.',
            'name.regex' => 'Nombre no valido.',
            'name.min' => 'Nombre demasiado corto.',
            'name.max' => 'Nombre demasiado largo.',
            'apePaterno.required' => 'El campo apellido paterno no puede estar vacío.',
            'apePaterno.string' => 'Apellido paterno no valido.',
            'apePaterno.regex' => 'Apellido paterno no valido.',
            'apePaterno.min' => 'Apellido paterno demasiado corto.',
            'apePaterno.max' => 'Apellido paterno demasiado largo.',
            'apeMaterno.string' => 'Apellido materno no valido.',
            'apeMaterno.regex' => 'Apellido materno no valido.',
            'apeMaterno.min' => 'Apellido materno demasiado corto.',
            'apeMaterno' => 'Apellido materno demasiado largo.',
            'email.required' => 'El campo correo electrónico no puede estar vacío.',
            'email.email' => 'Correo electrónico no valido.',
            'email.max' => 'Correo electrónico demasiado largo.',
            'email.unique' => 'Este correo electrónico ya existe.',
        ];
    }
}
