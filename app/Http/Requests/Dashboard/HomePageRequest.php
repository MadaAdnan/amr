<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class HomePageRequest extends FormRequest
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
            'title_about_us'=>'required',
            'description_about_us'=>'required',
            'title_our_services'=>'required',
            'description_our_services'=>'required',
            // 'image_one_title'=>'required',
            // 'image_one_description'=>'required',
            'image_two_title'=>'required',
            'image_two_description'=>'required',
        ];
    }
}
