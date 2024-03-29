<?php

namespace App\Http\Requests\Dashboard\ChildSeat;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChildeSeatFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title'=>'required',
            'description'=>'required',
            'price'=>'required|min:1',
            'status'=>'required',
        ];
    }
}
