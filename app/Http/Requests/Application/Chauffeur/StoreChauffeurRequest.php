<?php

namespace App\Http\Requests\Application\Chauffeur;

use Illuminate\Foundation\Http\FormRequest;

class StoreChauffeurRequest extends FormRequest
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
            "name" => "required|string|max:255",
            "phone" => "required|string",
            "email" => "required|email",
            "state" => "required|string|max:255",
            "city" => "required|string|max:255",
            "address" => "required|string|max:255",
            "date_of_birth" => "required|date_format:Y-m-d",
            "experience_years" => "required|string|max:255",
            "availability" => "required",
            "texas_license" => "required",
            "houston_limo_license" => "required",
            "resume" => "required|mimes:pdf,docx,jpeg,jpg,png|max:5000",
            "additional_information" => "nullable|string|max:255"
        ];
    }
}
