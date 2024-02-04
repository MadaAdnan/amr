<?php

namespace App\Http\Controllers\NewAPI\Auth;

use App\Http\Controllers\api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\ForgetPasswordApiRequest;
use App\Http\Requests\Api\VerifyApiRequest;
use App\Models\User;
use App\Models\ForgetPassword;
use Illuminate\Support\Facades\Hash;
use App\Traits\EmailTrait;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;

class ForgetPasswordController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Forget Password Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the forget password actions in the mobile
    |
    */

    use EmailTrait,LogErrorAndRedirectTrait;

    public function index(ForgetPasswordApiRequest $request)
    {
        /** 
        * Forget password
        * 
        * Doc: send forget password email 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = User::Where("email", $request->email)->first();

            if ($user) 
            {
                #send forget password email
               $this->forgetPasswordEmail($user);

                
                return $this->NewResponse(true , ['user_id' => $user->id], null , config('status_codes.success.ok'));
            }

            return $this->NewResponse(false , null, "The email is not found" , config('status_codes.client_error.unprocessable'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in sending forget password email');
        }

    }

    public function verifyCode(VerifyApiRequest $request)
    {
         /**
        * Verify code
        * 
        * Doc: check the code 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            #get the forget password record in the database to check the code
            $forgetPassword = ForgetPassword::Where("user_id", $request->user_id)->first();

            if ($forgetPassword) 
            {
                if ($forgetPassword->code == $request->code) 
                {
                    return $this->NewResponse(true , "success", null , 200);
                } 
                else 
                {
                    return $this->NewResponse(false , null, "wrong code" , 200);
                }
            }

            return $this->NewResponse(false , null, "Not Found" , 422);
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in sending forget password email');
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
         /**
        * Change the password for the user
        * 
        * Doc: check the code 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            #find the user
            $user = User::findOrFail($request->user_id);

            if ($user) 
            {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return $this->NewResponse(true , "true", null , 200);
            }

                return $this->NewResponse(false , null, "Not Found" , 422);
            }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in sending forget password email');
        }
    }
}
