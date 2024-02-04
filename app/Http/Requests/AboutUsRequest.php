<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutUsRequest extends FormRequest
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
            'section_one_title'=>'required',
            'section_one_description'=>'required',
            'section_one_paragraph_one_title'=>'required',
            'section_one_paragraph_one_description'=>'required',
            'section_one_paragraph_two_title'=>'required',
            'section_one_paragraph_two_description'=>'required',
            'section_two_title'=>'required',
            'section_two_description'=>'required',
            'section_two_paragraph_one_title'=>'required',
            'section_two_paragraph_one_description'=>'required',
            'section_two_paragraph_two_title'=>'required',
            'section_two_paragraph_two_description'=>'required',
            'section_three_title'=>'required',
            'section_two_description'=>'required',

            
        ];
    }
}
