<?php

namespace App\Http\Requests\Chauffeur\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreChauffeurDocumentFormRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'type' => 'required|in:image,file,text', // Ensure 'type' is one of these values
        ];

        // Conditional validation based on 'type'
        if ($this->input('type') === 'image') {
            $rules['file'] = 'required|mimes:jpeg,jpg';
        } elseif ($this->input('type') === 'file') {
            $rules['file'] = 'required|mimes:pdf';
        } else {
            $rules['file'] = 'nullable'; // No file required for 'text' type
            $rules['value'] = 'required'; // No file required for 'text' type
        }

        return $rules;
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
