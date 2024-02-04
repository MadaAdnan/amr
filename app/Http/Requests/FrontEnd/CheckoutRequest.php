<?php

namespace App\Http\Requests\FrontEnd;

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
            'street' => 'exclude_unless:card,null|required',
            'city' => 'exclude_unless:card,null|required',
            'state' => 'exclude_unless:card,null|required',
            'postal_code' => 'exclude_unless:card,null|required',
            'country' => 'exclude_unless:card,null|required',
            'country_code' => 'exclude_unless:card,null|required',
            'card_number' => 'exclude_unless:card,null|required',
            'cvc' => 'exclude_unless:card,null|required',
            'exp_year' => 'exclude_unless:card,null|required',
            'name' => 'exclude_unless:card,null|required',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $firstErrorMessage = $validator->errors()->first();

        throw new HttpResponseException(
            response()->json([
                'err' => $firstErrorMessage,
                'status' => config('status_codes.client_error.unprocessable'),
            ], config('status_codes.client_error.unprocessable'))
        );
    }




}
