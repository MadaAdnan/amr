<?php

namespace App\Http\Requests\Frontend;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;

class ContactusFormRequest extends FormRequest
{
    
    public function rules()
    {
        
        return [
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'message' => 'required',
            'g-recaptcha-response'=>['required',function (string $attribute, mixed $value, Closure $fail) {
                $g_response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify',[
                    'secret' => config('services.recaptcha.site_key'),
                    'response' => $value,
                    'remoteip'=> \request()->ip()
                ]);
                if ($value === 'foo') {
                    $fail("The {$attribute} is invalid.");
                }
            },]
                ];
    }

}
