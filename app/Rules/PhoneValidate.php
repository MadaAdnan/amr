<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneValidate implements Rule
{
    public function passes($attribute, $value)
    {
        // Define your phone number regular expression pattern
        $pattern = '/^\d{7,15}$/';

        return preg_match($pattern, $value);
    }

    public function message()
    {
        return 'The :attribute must be a format +12015550188';
    }
}
