<?php

namespace App\Http\Controllers\NewAPI\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\RequestVerifyPhoneRequest;
use App\Models\OtpPhone;
use App\Models\User;
use App\Services\TwilioService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stripe\StripeClient;
use App\Http\Requests\Api\SocialLoginRequest as ApiSocialLoginRequest;
use App\Http\Requests\Api\VerifyPhoneRequest as ApiVerifyPhoneRequest;
use App\Traits\UtilitiesTrait;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\EmailTrait;
use Laravel\Socialite\Facades\Socialite;

class ApiAuthController extends Controller
{
     /*
   |--------------------------------------------------------------------------
   | Auth Controller
   |--------------------------------------------------------------------------
   |
   | responsible for all authentication action for the mobile
   |
   */

    use UtilitiesTrait,JsonResponseTrait,LogErrorAndRedirectTrait,EmailTrait;

    public function login(LoginRequest $request)
    {
        /**
        * Login
        * 
        * Doc: login for the customer
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try 
        {            
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) 
            {
                return $this->NewResponse(false , null, "The provided credentials are incorrect." ,config('status_codes.client_error.unprocessable'));
            } 
            else 
            {
                if(!$user->hasRole('Customer'))
                {
                    $user->tokens()->delete();
                    return $this->NewResponse(false , null, "User not authorized to login ." ,config('status_codes.client_error.unprocessable'));
                }

                #get the fcm and update it
                $user->fcm = $request->fcm;
                $user->save();
                
                $token = $user->createToken($user->id . $request->device_name . date('Y-m-d H:i:s'))->accessToken;

                return $this->NewResponse(true , $this->response($user,$token), null ,config('status_codes.success.ok'));
            }
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in login api');
        }
    }

    public function register(RegisterRequest $request)
    {
         /**
        * Register
        * 
        * Doc: register for the customer
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            #get the user password
            $request['password'] = $request->password ? bcrypt($request->password) : $this->userGeneratePassword();
    
            #verify the user if it's from google/apple
            $user_data = array_merge([
                'email_verified_at'=>$request->type != 'normal' ? Carbon::now():null
            ],$request->all());
    
            $user = User::create($user_data);
    
            #create customer role
            $user->assignRole('Customer');
            $user->save();
    
            #send an email    
            if($request->type == "normal")
            {
                $this->verifyEmail($user);
            }
    
            $token = $user->createToken($user->id . $request->device_name . date('Y-m-d H:i:s'))->accessToken;
    
            return $this->NewResponse(true , $this->response($user,$token), null ,200);
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in registering api');
        }

    }

    public function logout()
    {
         /**
        * Logout
        * 
        * Doc: logout for the customer
        *
        *
        * @return Json
        */
        
        try 
        {
            $user = Auth::user();
            $user->tokens()->delete();

            return $this->NewResponse(true, "You have been successfully logged out!", null, config('status_codes.success.ok'));
        } 
        catch (\Exception $e) 
        {
            return $this->logErrorJson($e,'Error in logout: ');
        }
    }

    private function add_customer_id($user)
    {
         /**
        * Add Customer id 
        * 
        * Doc: add customer id to stripe if the user dose't have one
        *
        * @param App\Models\User $user
        *
        * @return Json
        */

        try
        {
            $user_stripe_id = $user->stripe_id;
            $stripe_id = $user_stripe_id;
            
            if(!$stripe_id)
            {
                $stripe = new StripeClient(env('STRIPE_SECRET'));
                
                $customer = $stripe->customers->create([
                    'email' => $user->email, 
                    'name' => $user->full_name,
                ]);
             
                $stripe_id = $customer->id;
    
                $user = User::find($user->id);
                $user->stripe_id = $stripe_id;
                $user->save();
            }
    
            return $stripe_id;
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in add customer id: ');
        }
       
    }

    public function requestVerifyPhone(RequestVerifyPhoneRequest $request)
    {
        /**
         * Request Verify Phone
         * 
         * Doc: send otp code to the sent phone number 
         * 
         * @param Illuminate\Http\Request $request will have the phone information
         * 
         * 
         * @return json;
         */
        try
        {
            $twilioService = new TwilioService();
            $phoneNumber = "+".$request->country_code.$request->phone;
            $otp = $twilioService->generate_otp($request->device_id, $phoneNumber );
    
            if(isset($otp['errors']))
            {
                return $this->NewResponse(false , null, $otp['errors'] ,401);
            }
    
            return $this->NewResponse(true , $otp, null ,200);
        }
        catch(Exception $e)
        {
            Log::error('sending otp: ',$e->getMessage());

            $this->errorResponse('error sending otp',500);
        }
      
    }

    public function verifyPhone(ApiVerifyPhoneRequest $request)
    {
        /**
         * Verify phone
         * 
         * Doc: verify the phone number for the user and update the user status.
         * 
         * @param Illuminate\Http\Request $request will have the phone information 
         * 
         * 
         * @return JSON
         */

         try
         {
            $phoneNumber = '+'.$request->country_code.$request->number;

            // check the otp for this device with phone number
            $phone_otp = OtpPhone::where([['phone' , $phoneNumber], ['otp_code' , $request->otp_code], ['status' , OtpPhone::STATUS_VALID]])->whereHas('device',function ($q) use($request){
                    $q->where('device',$request->device_id);
                })->first();
    
            if(!$phone_otp)
            {
                return $this->NewResponse(false , null, "The OTP is invalid." ,401);
            }
    
            $phone_otp->status = OtpPhone::STATUS_INVALID;
            $phone_otp->save();
    
            /** update user  */
            User::find(Auth::user()->id)->update([
                'phone_verified_at'=>Carbon::now()
            ]);
    
            return $this->NewResponse(true , "The phone is valid", null ,200);
    
         }
         catch(Exception $e)
         {
            return $this->logErrorJson($e,'Error verifying phone: ');
         }
    }

    public function deactivatedUser()
    {
        /**
         * Deactivated User
         * 
         * Doc: deactivate user.
         * 
         * 
         * @return JSON
         */

        try 
        {
            
            $user = Auth::user();

            #DEACTIVATE THE USER
            $user->is_deactivated = User::DEACTIVATED;
            $user->save();
            $user->tokens()->delete();

            return $this->NewResponse(true, "The Account has been deactivated successfully!", null, config('status_codes.success.ok'));

        } 
        catch (\Exception $e) 
        {

            return $this->logErrorJson($e,'Error in deactivate: ');
        }
    }

    public function recoveryUser(Request $request)
    {
         /**
         * Recovery User
         * 
         * Doc: deactivate user.
         * 
         * 
         * @return JSON
         */

        try 
        {
            #Get the current authenticated user
            $user = Auth::user();
            $user->is_deactivated = 0;
            $user->save();

            return $this->NewResponse(true, "The Account has been reactivated successfully!", null, 200);

        } 
        catch (\Exception $exception) 
        {
            return $this->NewResponse(false, null, $exception, 405);
        }
    }

    private function response($user,$token = null)
    {
        /**
         * User Response
         * 
         * Doc: deactivate user.
         * 
         * 
         * @return JSON
         */

        $response = [
            "user_id" => $user->id,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "phone" => $user->phone,
            "country_code" => (int)$user->country_code,
            "image" => $user->image,
            "role_id" => $user->role_id,
            "deactivated" =>!$user->is_deactivated?0:$user->is_deactivated,
            'is_verified'=>$this->check_customer_is_verified($user),
            "customer_id" => $this->add_customer_id($user),
            'is_phone_verify'=>$user->phone_verified_at ? true : false
        ];

        if($token)
        {
            $response['token'] = $token;
        }

        return $response;
    }

    private function check_customer_is_verified($user)
    {
          /**
         * Check customer is verified
         * 
         * Doc: check if user verified
         * 
         * @param App\Models\User $user
         * 
         * @return JSON
         */

       /** DATE OF CREATION FOR VERIFIED EMAIL*/
       $validation_date = Carbon::create(2023, 9, 5);
       $creation_date = Carbon::create($user->created_at);
       $isBefore = $creation_date->isBefore($validation_date);

       if($isBefore&&!$user->email_verified_at) {
        $user->update([
            'email_verified_at'=>Carbon::now()
        ]);
        return true;
       }
       if(!$user->email_verified_at)
       {
            return false;
       }

       return true;

    }

    public function send_verification_email()
    {
         /**
         * Send a verification email
         * 
         * Doc: send a verification email to the user
         * 
         * @param App\Models\User $user
         * 
         * @return JSON
         */

        try
        {
            $user = Auth::user();

            if($user->email_verified_at)
            {
                return $this->errorResponse('email was already verified',422);
            }
            $this->verifyEmail($user);
                        
            return $this->NewResponse(true ,'Email was sent', null ,200);

        }
        catch(Exception $e)
        {
            throw $e;
            return $this->errorResponse('Server Error',422);
        }
    }

    public function check_if_user_verify()
    {
        /**
         * Check if user verify
         * 
         * Doc: check if user verify
         * 
         * @param App\Models\User $user
         * 
         * @return JSON
         */

        try
        {
            $user = Auth::user();
            return $this->NewResponse(true ,[
                'is_verified'=>$user->email_verified_at?true:false
            ], null ,200);
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in checking if user verify api');
        }
       
    }

    public function details()
    {
         /**
         * Details
         * 
         * Doc: get the user details
         * 
         * @param App\Models\User $user
         * 
         * @return JSON
         */
        try
        {
            $user = Auth::user();
            return  $this->NewResponse(true , $this->response($user), null ,200);
    
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,"Error in details api: ");
        }
    }

    public function social_login(ApiSocialLoginRequest $request)
    {
   
        /**
         * Social Login
         * 
         * Doc: handle the login by any social media platform
         * 
         * @param Illuminate\Http\Request
         * 
         * @return JSON
         */

        try
        {
            #data object
            $responseObj = null;

            #login by apple throw the token
            $data = Socialite::driver($request->type)->userFromToken($request->token);

            #get social id
            $socialId = $data->user['sub'];
            $email = $data->email;
            $name = $data->name;

            #search for the user in the database throw the social id
            $user = User::where('social_id',$socialId)->first();

            #check if the user found return user data
            if($user)
            {
                $token = $user->createToken($user->id)->accessToken;

                $responseObj = $this->response($user,$token);
            }

           return $this->NewResponse(true ,[
            'data'=>$responseObj,
            'is_new'=> $user ? false : true,
            'social_id'=> $socialId,
            'email'=>$email,
            'name'=>$name
           ], null ,200);
        
        }
        catch (\Exception $e)
        {
            return $this->errorResponse('Check the sent token',422);
        }

    }
    
}