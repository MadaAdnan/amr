<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;

class LocationController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Location Controller
    |--------------------------------------------------------------------------
    |
    | to show the location
    |
    */

    use LogErrorAndRedirectTrait;

    public function index()
    {
         /**
        * Index
        * 
        * Doc: send the user to the location page 
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = Country::orderBy('created_at','desc')->get();
            return view('dashboard.location.index',compact('data'));    
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting countries in get location: ');
            return back(); 
        }
    }
}
