<?php

namespace App\Http\Controllers\NewApi;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Coupon;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;

class CouponController extends BaseApiController
{
    /*
    |--------------------------------------------------------------------------
    | Coupon Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the coupons apis in the mobile
    |
    */
    
    use LogErrorAndRedirectTrait;

    public function index($code)
    {
        /**
        * Get Billing History
        * 
        * Doc: get the billing history 
        *
        * @param String $code
        *
        * @return Json
        */

        try
        {
            $data = Coupon::where('coupon_code',$code)->first();

            if(!$data)
            {
                return $this->NewErrorResponse('Coupon code not found');
            }

            return $this->NewResponse(true,$data->percentage_discount,"");
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error getting coupon: ');
        }
    }
    
}
