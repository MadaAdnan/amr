<?php

namespace App\Http\Requests\Dashboard\Company;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyFormRequest extends FormRequest
{
   
    public function rules()
    {
        return [
            'company_name'=>'required',
            'email'=> 'required|unique:companies,email,',
            'phone'=>'required|unique:companies,phone,',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'street'=>'required',
            'postal_code'=>'required'
        ];
    }
}
