<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'type' => 'required|in:social,normal',
            'country_code' => 'required|numeric',
            'fcm' => 'required|string|max:255',
            'phone' => 'required|string|unique:users|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'string|min:8',
            'user_id' => 'string|max:255|unique:users',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $firstErrorMessage = $validator->errors()->first();

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => '',
                'error' => $firstErrorMessage,
            ], 422)
        );
    }
}
