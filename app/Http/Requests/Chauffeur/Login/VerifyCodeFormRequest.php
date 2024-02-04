<?php

namespace App\Http\Requests\Chauffeur\Login;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class VerifyCodeFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            "user_id" => "required",
            "code" => "required",
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
