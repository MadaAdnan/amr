<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventsRequest extends FormRequest
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
            'start_date'=>'required',
            'city_id'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            'radius'=>'required',
            'radius_area'=>'required',
            'price'=>'required'
        ];
    }
}
