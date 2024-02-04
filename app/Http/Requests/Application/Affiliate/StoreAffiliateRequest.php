<?php

namespace App\Http\Requests\Application\Affiliate;

use Illuminate\Foundation\Http\FormRequest;

class StoreAffiliateRequest extends FormRequest
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
            "state" => "required|string|max:255",
            "city" => "required|string|max:255",
            "address" => "required|string|max:255",
            "zip_code" => "required",
            "phone" => "required|string",
            "email" => "required|email",
            "website" => "required|string",
            
            "contact_person" => "required|string|max:255",
            "contact_phone" => "required|string|max:255",
            "contact_email" => "required|email",

            "fleet_size" => "required",
            "area_of_service" => "required|string|max:255",
            "airports" => "required|string|max:255",
            "tax_id" => "required|string|max:255",
        ];
    }
}
