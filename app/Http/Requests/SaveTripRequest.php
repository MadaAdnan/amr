<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Services\BuildQueryService;

class SaveTripRequest extends FormRequest
{
    // protected function getValidatorInstance()
    // {
    //     //Edit request format
    //     $buildQueryService = new BuildQueryService();
    //     $data = parent::all();
    //     $new_data = ($buildQueryService->tripQuery($data))->toArray();
    //     $this->getInputSource()->replace($new_data);
        
    //     return parent::getValidatorInstance();
    // }

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
            "service_type" => "required",
            "pick_up_location" => "required",
            "pick_up_date" => "required|date",
            "pick_up_time" => "required",
            "extra_id" => "required",
            "passenger" => "required",
            "vehicle_id" => "required",

            "first_name" => "required|string|max:100|min:2",
            "last_name" => "required|string|max:100|min:2",
            "email" => "required|email",
            "phone" => "required",

            "cardholder_name" => "required",
            "country" => "required",
            "state" => "required",
            "city" => "required",
            "postel_code" => "required",
            "address" => "required"
        ];
    }
}
