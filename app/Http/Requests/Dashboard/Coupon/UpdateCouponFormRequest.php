<?php

namespace App\Http\Requests\Dashboard\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponFormRequest extends FormRequest
{
    public function rules()
    {

        return [
            'coupon_name'=>'required',
            'coupon_code' =>'required|unique:coupons,coupon_code,' . $this->id,
            'usage_limit'=>'required',            
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
            'percentage_discount.required' => 'The percentage discount is required.',
            'active_from.required' => 'The active from is required.',
            'active_to.required' => 'The active to is required.',
           
        ];
    }
}
