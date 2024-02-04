<?php

namespace App\Http\Controllers\NewAPI\Users;

use App\Http\Controllers\api\BaseApiController;
use App\Http\Requests\Api\SetPinRequest;
use App\Http\Requests\Api\UpdatePasswordRequestProfile;
use App\Http\Requests\Api\UpdateUserProfileRequest;
use App\Http\Requests\Api\UploadImageRequestProfile;
use App\Models\User;
use App\Traits\NotificationTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\EmailTrait;
use App\Traits\LogErrorAndRedirectTrait;
use Carbon\Carbon;
use Stripe\StripeClient;

class UserController extends BaseApiController
{

      /*
   |--------------------------------------------------------------------------
   | User Controller
   |--------------------------------------------------------------------------
   |
   | responsible for user actions
   |
   */

    use NotificationTrait , LogErrorAndRedirectTrait , EmailTrait;

    public function update(UpdateUserProfileRequest $request)
    {
        /**
         * Update 
         * 
         * doc: update user information 
         * 
         * @param Illuminate\Http\Request
         * 
         * @return JSON
         */

        try
        {
            $user = Auth::user();

            if ($request->first_name && $user->first_name != $request->first_name) {
                $user->first_name = $request->first_name;
            }
    
            if ($request->last_name && $user->last_name != $request->last_name) {
                $user->last_name = $request->last_name;
            }
    
            if (isset($request->email) && $user->email != $request->email) {
    
                $isEmailExists = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
                if (!!$isEmailExists)
                    return $this->NewErrorResponse('Email already exists', 422);
    
    
                $user_token = Str::random(32);
                $user = User::where('id', $user->id)->first();
    
                $user->update([
                    'email' => $request->email,
                    'remember_token' => $user_token,
                ]);
    
            }
    
            if ($user->save()) 
            {
                if (!$user->email_verified_at) 
                {
                    $this->verifyEmail($user);
                }
            
                return $this->NewResponse(true, $this->userResponse($user), null, 200);
            }
            else 
            {
                return $this->NewResponse(false, null, "Update failed!", 422);
            }
    
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in updating the user api: ');
        }
    }

    public function updatePassword(UpdatePasswordRequestProfile $request)
    {
         /**
         * Update Password 
         * 
         * doc: Update user password 
         * 
         * @param Illuminate\Http\Request
         * 
         * @return JSON
         */

         try
         {
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->NewResponse(false, null, 'The current password is incorrect!', 422);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();
    
            return $this->NewResponse(true, 'Your password has been updated successfully.', null, 200);    
         }
         catch(Exception $e)
         {
            return $this->logErrorJson($e,'Error in updating password api: ');
         }
    }

    public function upload_image(UploadImageRequestProfile $request)
    {
        /**
         * Upload image
         * 
         * doc: update the user image 
         * 
         * @param Illuminate\Http\Request
         * 
         * @return JSON
         */

        try
        {
            if ($request->hasFile('image')) 
            {
                $user = Auth::user();
                $user->clearMediaCollection('images')
                    ->addMedia($request->file('image'))
                    ->toMediaCollection('images');
    
                return $this->NewResponse(true, $user->image, null, 201);
            }
    
            return $this->NewResponse(false, null, 'Image upload failed!', 422);
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Upload image problem api: ');
        }
       
    }

    public function setPinCode(SetPinRequest $request)
    {
        /**
         * Set pin code
         * 
         * doc: setting the pin 
         * 
         * @param Illuminate\Http\Request
         * 
         * @return JSON
         */

        try
        {
    
            $user = Auth::user();
            $user->local_pin = $request->local_pin;
            $user->save();
    
            return $this->NewResponse(true, "The pin has been added successfully", null, 200);
    
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in setting the pin: ');
        }
    }

    public function checkPinCode(SetPinRequest $request)
    {
         /**
         * Check pin code
         * 
         * doc: check if the pin the user entered is correct 
         * 
         * @param Illuminate\Http\Request
         * 
         * @return JSON
         */
        try
        {
            $user = Auth::user();    
            if ($user->local_pin == $request->local_pin) 
            {
                return $this->NewResponse(true, "The pin is correct", null, 200);

            } 
            else 
            {
                return $this->NewResponse(false, null, "The code is invaild!", 422);
            }
    
        }
        catch(Exception $e)
        {

        }
    }

    public function notifications()
    {
          /**
         * Notification
         * 
         * doc: get the user notification 
         * 
         * @param Illuminate\Http\Request
         * 
         * @return JSON
         */

         try
         {
            $user = Auth::user();
            
            foreach ($user->unreadNotifications as $notification) 
            {
                $notification->markAsRead();
            }

            $data = $user->notifications()->orderBy('created_at', 'desc')->paginate(10)->map(function ($item) {
                return $this->response($item);
            });
    
            return $this->NewResponse(true, $data, '');
         }
         catch(Exception $e)
         {
            return $this->logErrorJson($e,'Error in getting notification: ');
         }
    }

    public function getUnreadNotifications()
    {
          /**
         * Get Unread Notifications
         * 
         * doc: get the user notification 
         * 
         * @param Illuminate\Http\Request
         * 
         * @return JSON
         */

        try 
        {
            $user = Auth::user();

            $unreadCount = $user->unreadNotifications()->count(); 
            $data = $user->unreadNotifications()->paginate(10)->map(function ($item) use ($unreadCount) {
                return $this->response($item, $unreadCount);
            });

            return $this->NewResponse(true, $data, '');

        } 
        catch (Exception $e) 
        {
            return $this->NewErrorResponse('Error with notification', 500);
        }
    }

    private function userResponse($user,$token = null)
    {
        /**
         * User Response
         * 
         * doc: user response formate
         * 
         * @param App\Models\User $user
         * @param String $token
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
         * Check if the user verified
         * 
         * doc: check if the user verified and if it was created before 5/9/2023 it should be verified
         * 
         * @param App\Models\User $user
         * 
         * @return Boolean
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

    public function add_customer_id($user)
    {
         /**
         * Add Customer id
         * 
         * doc: create a customer id for the user who don't have
         * 
         * @param App\Models\User $user
         * 
         * @return Boolean
         */

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

}