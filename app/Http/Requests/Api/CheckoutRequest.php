<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class CheckoutRequest extends FormRequest
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
            'pick_up_location'=>'required',
            'drop_off_location'=>'required_if:service_id,1',
            'pick_up_date'=>'required|date',
            'pick_up_time'=>'required',
            'service_type'=>'required',
            'price'=>'required',
            'duration'=>'required_if:service_id,2|numeric',
            'distance'=>'numeric|required',
            'category_id'=>'required|numeric',
            'return_date'=>'required_if:is_round_trip,1',
            'return_time'=>'required_if:is_round_trip,1',
            'tip'=>'numeric',
            'phone_primary'=>'',
            'dropoff_latitude'=>'required',
            'dropoff_longitude'=>'required',
            'is_round_trip'=>'numeric',
            'coupon'=>'nullable|string',
            'child_seats'=>'array',
            'airline_name'=>'string',
            'flight_number'=>'string',
            'card_id'=>'',
            'latitude'=>'numeric|required',
            'longitude'=>'numeric|required',
            'comment'=>'string',
            'paymentIntent_Id'=>''
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