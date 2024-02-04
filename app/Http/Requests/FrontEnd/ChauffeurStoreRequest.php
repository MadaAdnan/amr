<?php

namespace App\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;

class ChauffeurStoreRequest extends FormRequest
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
            'name'=>'required|string',
            'phone'=>'required|string',
            'email'=>'required|string|email',
            'state'=>'required|string',
            'city'=>'required|string',
            'address'=>'required|string',
            'date_of_birth'=>'required|date',
            'experience_years'=>'required|numeric',
            'availability'=>'required|boolean',
            'texas_license'=>'required|boolean',
            'houston_limo_license'=>'required',
            'resume'=>'required|file',
        ];
    }
}
