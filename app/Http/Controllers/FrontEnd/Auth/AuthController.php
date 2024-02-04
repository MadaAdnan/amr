<?php

namespace App\Http\Controllers\FrontEnd\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\RegisterRequest;
use App\Http\Requests\FrontEnd\UpdatePhoneNumberRequest;
use App\Http\Requests\FrontEnd\ForgetPasswordSubmitRequest;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\JsonResponseTrait;
use App\Traits\EmailTrait;
use App\Models\User;
use App\Models\OtpPass;
use App\Traits\OtpTrait;
use Exception;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;

use Illuminate\Support\Str;

class AuthController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Auth Controller Controller
    |--------------------------------------------------------------------------
    |
    | doc: will have all the login methods
    |
    */

    use LogErrorAndRedirectTrait,EmailTrait,OtpTrait,JsonResponseTrait;

    public function login()
    {
        /**
         * Login
         * 
         * 
         * doc: go to login page
         * 
         * @return View
         */

        try
        {
            return view('frontEnd.auth.login');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in login page: ');
            return back();
        }
    }

    public function login_submit(Request $request)
    {
          /**
         * Login Submit
         * 
         * 
         * doc: try to login into the application
         * 
         * @return View
         */

         try
         {
            #get cred
            $cred = $request->only('email','password');

            #attempt to login
            $attempt = Auth::attempt($cred,$request->remember_me ? true : false);

            #if it's wrong cred return back with a message
            if(!$attempt)
            {
                return back()->with('error','wrong credentials, please check your email and password.');
            }

            #send the user to the profile page if it's successfully
            return redirect()->route('frontEnd.user.profile.home');
         }
         catch(Exception $e)
         {
            $this->logErrorAndRedirect($e,'Error in send the login request: ');
            abort(500);
         }

    }

    public function register()
    {
        /**
         * Register
         * 
         * 
         * doc: go to register page
         * 
         * @return View
         */

        try
        {
            return view('frontEnd.auth.register');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in register page: ');
            return back();
        }
    }

    public function register_submit(RegisterRequest $request)
    {
        /**
         * Register Submit
         * 
         * 
         * doc: create a user and send it to the otp page after completion
         * 
         * @param Illuminate\Http\Request $request
         * 
         * @return View
         */

         try
         {
            #get data
            $formData = array_merge($request->all(),[
                'password'=>bcrypt($request->password)
            ]);

            #store the user information in the database
            $data = User::create($formData);

            #attach the customer role to the user
            $data->assignRole('Customer');
            $data->save();

            #login the user
            Auth::login($data);

            #send otp code to the user phone number
            $this->sendOtp($data);
            
            return redirect()->route('frontEnd.auth.otp');
         }
         catch(Exception $e)
         {
            $this->logErrorAndRedirect($e,'error in submitting the user');
            abort(500);
         }
    }

    public function otp()
    {
        /**
         * Otp
         * 
         * 
         * doc: send the user to otp page
         * 
         * @return View
         */

        try
        {
            return view('frontEnd.auth.otp');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in register page: ');
            abort(500);
        }
    }

    public function resendOtp(Request $request)
    {
        /**
         * Resend Otp
         * 
         * 
         * doc: resend the otp to the user 
         * 
         * @return Json
         */

        try
        {
            #get user 
            $user = Auth::user();

            #send otp code to the user phone number
            $sendOtp = $this->sendOtp($user);

            #response json
            $responseObj = [
                'data'=>[
                    'msg'=>'data returned',
                    'is_send'=>$sendOtp,
                    'status'=>config('status_codes.success.ok')
                ]
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in register page: ');
            abort(500);
        }
    }

    public function change_phone_number()
    {
       /**
         * Change phone number
         * 
         * 
         * doc: send the user to change phone number page 
         * 
         * @return Json
         */

         try
         {
             return view('frontEnd.auth.change_phone_number');
         }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'Error in register page: ');
             abort(500);
         }
    }

    public function update_phone_number(UpdatePhoneNumberRequest $request)
    {
        /**
         * Update phone number
         * 
         * doc: update the user phone number when he's not verified
         * 
         * @param App\Http\Requests\FrontEnd\UpdatePhoneNumberRequest $request
         * 
         * @return Redirect
         */

         try
         {
            #get the data from the frontEnd
            $formData = $request->safe()->only(['country_code', 'phone']);

            #update the user information
            $user = Auth::user();
            $user->update($formData);

            #send the user otp code to the new number
             $this->sendOtp($user);

            #after updating the user phone number redirect it to the otp page
            return redirect()->route('frontEnd.auth.otp');
         }
         catch(Exception $e)
         {
            $this->logErrorAndRedirect($e,'Error in updating phone number: ');
            abort(500);
         }
    }

    public function check_unique_phone_number(Request $request)
    {
        /**
         * Check phone number unique
         * 
         * doc: to check if the phone number already available in the database or not, not return data to jquery validation
         * 
         * @param NUMBER $number
         * 
         * @return JSON
         */

         try
         {
            #get phone number
            $checkPhoneNumber = User::where([['phone',$request->phone]]);

            if(Auth::check())
            {
               $checkPhoneNumber = $checkPhoneNumber->where('id','!=',Auth::user()->id);
            }
            $checkPhoneNumber = $checkPhoneNumber->first();
            
            
            #error msg
            $msg = 'Phone number is already in use.';

            #success response
            $responseObj = [ !$checkPhoneNumber ? true : $msg ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
         }
         catch(Exception $e)
         {
            throw $e;
            $this->logErrorAndRedirect($e,'error in checking the unique phone number: ');
            abort(500);
         }
    }

    public function verify_code(Request $request)
    {
         /**
         * Verify Code
         * 
         * doc: check if the code was sent is correct
         * 
         * @param Illuminate\Http\Request $request
         * 
         * @return JSON
         */

         try
         {
            #user phone number
            $phoneNumber = Auth::user()->phone;

            #get otp code
            $otpCode = OtpPass::where([
                ['phone',$phoneNumber],
                ['is_used',0],
                ['otp_code',$request->otp]
            ])->first();

            #if otp found update it's data and make it used and verify the user 
            if($otpCode)
            {
                #make the otp code used
                $otpCode->update([
                    'is_used'=>1
                ]);

                #verify the user phone number.
                Auth::user()->update([
                    'phone_verified_at'=>Carbon::now()
                ]);
            }

            #return to the client if json response
            $responseObject = [
                'data'=>[
                    'is_correct'=> $otpCode ? true : false,
                    'redirect_url'=>route('frontEnd.user.profile.home')
                ]
            ];
            return $this->successResponse($responseObject);
         }
         catch(Exception $e)
         {
            $this->logErrorJson($e,'error in checking the unique phone number: ');
            abort(500);
         }

    }

    public function forget_password()
    {
       /**
         * Forget Password
         * 
         * doc: send the user to forget password page
         * 
         * @param Illuminate\Http\Request $request
         * 
         * @return View
         */ 

         try
         {
            return view('frontEnd.auth.forget_password');
         }
         catch(Exception $e)
         {
            $this->logErrorAndRedirect($e,'going to forget password page: ');
            abort(500);
         }
    }

    public function forget_password_submit(ForgetPasswordSubmitRequest $request)
    {
       /**
         * Forget Password Submit
         * 
         * doc: send an email 
         * 
         * @param Illuminate\Http\Request $request
         * 
         * @return View
         */ 

         try
         {
            
            Password::sendResetLink(
                $request->only('email')
            );
        
            return redirect()->route('frontEnd.auth.login');
         }
         catch(Exception $e)
         {
            $this->logErrorAndRedirect($e,'forget password submit: ');
            abort(500);

         }

    }

    public function reset_password(Request $request)
    {
        /**
         * Reset user password
         * 
         * doc: update the user password
         * 
         * @param Illuminate\Http\Request $request
         * 
         * @return Redirect
         */ 
        try
        {
            //reset user password
            Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => bcrypt($password)
                    ])->setRememberToken(Str::random(60));
                    $user->save(); 
                      
                }
            );

            return redirect()->route('frontEnd.auth.login');
        }
        catch(Exception $e)
        {
            throw $e;
            $this->logErrorAndRedirect($e,'forget password submit: ');
            abort(500);
        }
    }

    public function check_if_email_exist(Request $request , $update_email)
    {
        /**
         * Check if email exist
         * 
         * doc: check if email exist or not
         * 
         * @param Illuminate\Http\Request $request
         * @param Any $update_email : if the email dose't exist in the database return true 
         * 
         * @return View
         */

         try
         {
            #check if there is a user with this email
            $checkEmailAddress = User::where('email',$request->email)->first();

            #error msg
            $msg = $update_email ? 'Email already in use' :'The provided email does not exist.';

            #success response
            if($update_email)
            {
                $responseObj = [ !$checkEmailAddress ? true : $msg ];
            }
            else
            {
                $responseObj = [ $checkEmailAddress ? true : $msg ];
            }

            return $this->successResponse($responseObj,config('status_codes.success.ok'));


         }
         catch(Exception $e)
         {
            $this->logErrorAndRedirect($e,'checking the email exist: ');
            abort(500);
         }
    }

    public function logout()
    {
        /**
         * Logout
         * 
         * doc: logout the user from the app
         * 
         * @param Illuminate\Http\Request $request
         * 
         * @return Redirect
         */

        try
        {
            Auth::logout();

            return redirect()->route('frontEnd.auth.login');
        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,'error in logging out: ');
            abort(500);

        }
    }
    
}
