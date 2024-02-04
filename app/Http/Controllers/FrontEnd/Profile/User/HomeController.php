<?php

namespace App\Http\Controllers\FrontEnd\Profile\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\UpdatePersonalInformationRequest;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Exception;

class HomeController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | doc: responsible for the home page to the user profile
    |
    */

    public function index()
    {
        /**
         * Index
         * 
         * 
         * doc: send the user to the main page of the profile
         * 
         * @return View
         */

         try
         {
            return view('frontEnd.profile.user.index');
         }
         catch(Exception $e)
         {
            $this->logErrorAndRedirect($e,'Error in profile page: ');
            return back();
         }

    }

    public function update_personal_information(UpdatePersonalInformationRequest $request)
    {
       /**
         * Update personal information
         * 
         * 
         * doc: update the user personal
         * 
         * @return Redirect
      */

      try
      {
         #get user
         $data = auth()->user();

         #update user data
         $data->update($request->all());

         #update use image
         if($request->hasFile('image'))
         {
            $data->clearMediaCollection('images')
            ->addMedia($request->file('image'))
            ->toMediaCollection('images');
         }

         #return the user to back
         return back();
      }
      catch(Exception $e)
      {
         $this->logErrorAndRedirect($e,'Error in update profile page: ');
         return back();
      }
    }

    public function update_email_address(Request $request)
    {
      
      /**
       * Update Email Address 
       * 
       * doc: update the user email address and send verification link
       *
       * @param
       * 
       * @return JSON
       */
      
       try
       {
         $data = auth()->user();

         if($request->email)
         {
            #update user email
            $data->update([
               'email'=>$request->email, // if the email was't sent keep the email as it's 
               'email_verified_at'=>null
            ]);
         }

         #send verification code to the new email
         event(new Registered($data));

         #return
         return back();
       }
       catch(Exception $e)
       {
         throw $e;
         $this->logErrorAndRedirect($e,'Error in update profile page: ');
         return back();
       }
    }
}
