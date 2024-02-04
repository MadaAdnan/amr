<?php

namespace App\Http\Requests\Dashboard\Admin;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserFormRequest extends FormRequest
{
    public function authorize()
    {
        // Define your authorization logic here, for example:
        return Auth::check();
    }

    public function rules()
    {
        $user = Auth::user();

        if ($user->email == $this->input('email')) {
            return [
                'oldPassword' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ];
        }

        return [];
    }
}
