<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:100|min:2',
            'last_name' => 'required|string|max:100|min:2',
            'phone' => 'required|unique:users|string|max:100|min:4',
            'email' => 'required|unique:users|string',
            'password' => 'required|string',
//            'birthdate' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => 'Mobile does already exist',
            'email.unique' => 'Email does already exist'
        ];
    }
}
