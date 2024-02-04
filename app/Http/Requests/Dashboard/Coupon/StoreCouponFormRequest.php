<?php

namespace App\Http\Requests\Dashboard\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponFormRequest extends FormRequest
{
    public function rules()
    {

        return [
           'coupon_name'=>'required',
            'coupon_code' =>'required|unique:coupons,coupon_code,',
            'usage_limit'=>'required|min:1',            
            'active_from'=>'required',
            'active_to'=>'required',            
            
             

        ];
    }

    public function messages()
    {
        return [
            'coupon_name.required' => 'The coupon name is required.',
            'coupon_code.required' => 'The coupon code is required.',
            'coupon_code.unique' => 'The coupon code must be unique.',
            'usage_limit.required' => 'The usage limit is required.',
            'usage_limit.min' => 'The min usage limit is 1.',
            'percentage_discount.required' => 'The percentage discount is required.',
            'active_from.required' => 'The active from is required.',
            'active_to.required' => 'The active to is required.',
           
        ];
    }
}
