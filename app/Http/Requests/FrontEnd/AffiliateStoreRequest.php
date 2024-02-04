<?php

namespace App\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;

class AffiliateStoreRequest extends FormRequest
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
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required|email',
            'address'=>'required',
            'state'=>'required',
            'city'=>'required',
            'zip_code'=>'required',
            'tax_id'=>'required',
            'website'=>'required',
            'contact_person'=>'required',
            'contact_phone'=>'required',
            'contact_email'=>'required',
            'area_of_service'=>'required',
            'fleet_size'=>'required|numeric',
            'airports'=>'required',
        ];
    }
}
