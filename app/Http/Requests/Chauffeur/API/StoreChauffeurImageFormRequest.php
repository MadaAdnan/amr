<?php

namespace App\Http\Requests\Chauffeur\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreChauffeurImageFormRequest extends FormRequest
{
   
    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            
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
