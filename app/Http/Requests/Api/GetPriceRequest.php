<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetPriceRequest extends FormRequest
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
            'city_name'=>'string|max:255',
            'service_id'=>'required|numeric',
            'distance'=>'numeric|required_if:service_id,1',
            'duration'=>'required_if:service_id,2|numeric',
            'is_round_trip'=>'required|numeric',
            'pick_up_location'=>'required',
            'drop_off_location'=>'required_if:service_id,1',
            'pick_up_date'=>'required',
            'pick_up_time'=>'required',
            'child_seats'=>'array',
            'lat'=>'required|numeric',
            'long'=>'required|numeric'
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $firstErrorMessage = $validator->errors()->first();

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'results' => '',
                'error' => $firstErrorMessage,
            ], 422)
        );
    }


    
}
