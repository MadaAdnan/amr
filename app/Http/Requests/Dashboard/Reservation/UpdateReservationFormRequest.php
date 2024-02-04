<?php

namespace App\Http\Requests\Dashboard\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationFormRequest extends FormRequest
{
   
    public function rules()
    {
        

        return [
            
            'start_date'=>'required'
        ];
    }

    
}
