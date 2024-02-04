<?php

namespace App\Http\Controllers\NewAPI;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckPinRequest;
use App\Http\Requests\Api\SetPinCodeRequest;
use App\Http\Requests\Api\UpdatePasswordRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Requests\Api\UploadImageRequest;
use App\Models\User;
use App\Traits\NotificationTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\EmailTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\UserResponseTrait;

class UserController extends Controller
{
    use NotificationTrait,EmailTrait,UserResponseTrait,LogErrorAndRedirectTrait;

     /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the users in the mobile
    |
    */

    public function update(UpdateUserRequest $request)
    {
        /**
        * Update
        * 
        * Doc: update the user information
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();

            $input_request = array_merge($request->all());
    
            #check if the email exist if it's return 422
            if (isset($request->email) && $user->email != $request->email) 
            {
    
                $isEmailExists = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
    
                if ($isEmailExists)
                {
                    return $this->NewErrorResponse('Email already exists', config('status_codes.client_error.unprocessable'));
                }
            }
    
            #update user information
            $user = User::find($user->id);
            $user->update($input_request);
    
            #send verification email if the email not verified
            if(!$user->email_verified_at)
            {
                $this->verifyEmail($user);
            }
            
            return $this->NewResponse(true, $this->userResponse($user), null, config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error update user information');
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        /**
        * Update password
        * 
        * Doc: update the user password
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();

            #check if the old password is the same as current
            if (!Hash::check($request->current_password, $user->password)) 
            {
                return $this->NewResponse(false, null, 'The current password is incorrect!', 422);
            }
            #update the password
            $user->password = Hash::make($request->new_password);
            $user->save();
    
            return $this->NewResponse(true, 'Your password has been updated successfully.', null, 200);
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error update password: ');
        }
    }

    public function upload_image(UploadImageRequest $request)
    {
        /**
        * Upload image
        * 
        * Doc: update the user avatar
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();
            $user->clearMediaCollection('images')
                ->addMedia($request->file('image'))
                ->toMediaCollection('images');
    
            return $this->NewResponse(true, $user->image, null, config('status_codes.success.updated'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error updating avatar: ');
        }
    }

    public function setPinCode(SetPinCodeRequest $request)
    {
        /**
        * Set pin code
        * 
        * Doc: update the pin code
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();
            $user->local_pin = $request->local_pin;
            $user->save();

            return $this->NewResponse(true, "The pin has been added successfully", null, config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,"Error sting the new pin.");
        }

    }

    public function checkPinCode(CheckPinRequest $request)
    {
        /**
        * Check pin code
        * 
        * Doc: check if the pin code is correct
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */
        
        try
        {
            $user = Auth::user();
    
            if ($user->local_pin == $request->local_pin) 
            {
                return $this->NewResponse(true, "The pin is correct", null, config('status_codes.success.ok'));

            } 
            else 
            {
                return $this->NewResponse(false, null, "The code is invalid!", config('status_codes.client_error.unprocessable'));
            }
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,"check pin code.");
        }
    }

    public function notifications()
    {
        /**
        * Notifications
        * 
        * Doc: make the user notification 
        *
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();

            #make the unread notification read before
            foreach ($user->unreadNotifications as $notification) 
            {
                $notification->markAsRead();
            }
    
            #get the notifications
            $data = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(config('general.api_pagination'))
            ->map(function ($item) {
                return $this->response($item);
            });

            return $this->NewResponse(true, $data, config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
           return $this->logErrorJson($e,"Error in getting notification: ");
        }
    }
}