<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed']
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un maximo de 40 caracteres',
            
            'email.required' => 'El email es requerido',
            'email.email' => 'El email debe tener una estructura valida',

            'password.required' => 'La contraseña es requerida',
            'password.confirmed' => 'La contraseña debe ser confirmada',
            'password.min' => 'La contraseña debe tener un minimo de 6 caracteres',
        ];
    }
}
