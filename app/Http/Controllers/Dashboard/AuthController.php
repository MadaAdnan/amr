<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\LogErrorAndRedirectTrait;
use Illuminate\Http\Request;
use Auth , Alert, Exception;

class AuthController extends Controller
{
    use LogErrorAndRedirectTrait;

    /*
    |--------------------------------------------------------------------------
    | Auth Controller
    |--------------------------------------------------------------------------
    |
    | This handles the auth actions for the dashboard
    |
    */

    public function login()
    {
        /**
         * Login
         * 
         * Doc: send the user to the login page
         * 
         * @return \Illuminate\View\View
         */

        try
        {
            #send the user to the login view
            return view('dashboard.auth.login');
        }
        catch(Exception $e)
        {
             $this->logErrorAndRedirect($e,'Error in Login Page Dashboard:');
        }
    }

    public function login_submit(Request $request)
    {
         /**
         * Login Submit
         * 
         * Doc: The post request the user will send to login
         * 
         * @param request will have the user credentials
         * @return \Illuminate\Http\RedirectResponse
         */

        try
        {
            #get the user credentials
            $credentials = $request->only('email','password');
            
            #attempt to login if the login it's false return back with wrong cred message
            if(Auth::attempt(array_merge($credentials,['password'=>trim($request->password)])))
            {
                $user = Auth::user();
                
                #if the user is deactivated return back with a message
                if($user->is_deactivated)
                {
                    Auth::logout();
                    return back()->withErrors("Your Account was deactivated");
                }

                Alert::toast("Welcome back!", 'success');

                return redirect()->route('dashboard.home');
            }
            else
            {
                return back()->withErrors("Wrong email/password");
            }
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in Login Submit Dashboard:');
        }

    }

    public function logout()
    {
         /**
         * Logout
         * 
         * Doc: Will logout the user from the system
         * 
         * @param request will have the user credentials
         * @return \Illuminate\Http\RedirectResponse
         */

        try
        {
            #Logout the user
            Auth::logout();

            #Send the user login page
            return redirect()->route('dashboard.login');    
        }
        catch(Exception $e)
        {
            #Log the exception
            $this->logErrorAndRedirect($e,'Error in Logout:');
        }
    }

}
