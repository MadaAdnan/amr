<?php

namespace App\Http\Requests\Application\Package;

use Illuminate\Foundation\Http\FormRequest;

class SendPackageRequest extends FormRequest
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
            "package_type" => "required",
            "name" => "required|string|max:255",
            "phone" => "required|string",
            "email" => "required|email",
            "state" => "required|string|max:255",
            "city" => "required|string|max:255",
            "zip_code" => "required",
            "pick_up_address" => "required|string|max:255",
            "pick_up_date" => "required",
            "pick_up_time" => "required",
            "vehicle" => "required",
            "comment" => "nullable|string|max:255",
        ];
    }
}
