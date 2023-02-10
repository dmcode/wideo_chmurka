<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SingupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:30|unique:users',
            'email' => 'required|email|min:3|max:128|unique:users',
            'password' => 'required|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nazwa użytkownika jest wymagana',
            'name.min' => 'Nazwa użytkownika musi posiadać min. 2 znaki',
            'name.max' => 'Nazwa użytkownika może posiadać max. 30 znaków',
            'name.unique' => 'Nazwa użytkownika jest już zajęta',
            'email.required' => 'Adres email jest wymagany',
            'email.min' => 'Adres e-mail musi posiadać min.3 znaki',
            'email.max' => 'Adres e-mail może posiadać max. 30 znaków',
            'email.email' => 'Podaj poprawny adres e-mail',
            'email.unique' => 'Adres e-mail został już wykorzystany przy rejestracji',
            'password.required' => 'Podanie hasła jest wymagane',
            'password.confirmed' => 'Podane hasła są różne'
        ];
    }
}
