<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Exception;
use App\Traits\LogErrorAndRedirectTrait;

class PolicyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Policy Controller
    |--------------------------------------------------------------------------
    |
    | This handles the showing of the fleets in the frontEnd 
    |
    */

    use LogErrorAndRedirectTrait;

    public function terms_condition()
    {
        /**
         * Terms Condition
         * 
         * 
         * doc: going to the terms and condition page
         * 
         * @return View
         */

         try
         {
            #get terms settings
            $getSetting = AppSettings::where('text', 'Terms')->firstOrFail();
            $data = json_decode($getSetting->value)->terms;

             return view('frontEnd.policy.terms',compact('data'));
         }
         catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
          abort(404);
        }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'error in getting the fleets');
             return back();
         }

    }

    public function privacy_policy()
    {
        /**
         * Terms Condition
         * 
         * 
         * doc: going to the terms and condition page
         * 
         * @return View
         */

         try
         {
            #get terms settings
            $getSetting = AppSettings::where('text', 'Terms')->firstOrFail();
            $data = json_decode($getSetting->value)->policy;

             return view('frontEnd.policy.privacy_policy',compact('data'));
         }
         catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
          abort(404);
        }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'error in getting the fleets');
             return back();
         }

    }

}
