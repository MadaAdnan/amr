<?php

namespace App\Http\Requests\Chauffeur\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreChauffeurRequimentsFormRequest extends FormRequest
{
   

   
    public function rules()
    {
        return [
            'document.*.input_title' => 'required|string|max:255',
            'document.*.type' => 'required',
        ];
    }
}
