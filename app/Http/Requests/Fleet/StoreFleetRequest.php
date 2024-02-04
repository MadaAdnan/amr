<?php

namespace App\Http\Requests\Fleet;

use Illuminate\Foundation\Http\FormRequest;

class StoreFleetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !!auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" => "required|string|max:255",
            "content" => "required|string",
            "status" => "required",
            "seats" => "required|integer",
            "luggage" => "required|integer",
            "passengers" => "required|integer",
            "seo_title" => "nullable|string",
            "seo_description" => "nullable|string",
            "seo_keyphrase" => "nullable|string",
        ];
    }
}
