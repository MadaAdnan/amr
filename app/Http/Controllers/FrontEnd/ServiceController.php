<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;
use App\Models\Services;

class ServiceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Services Controller
    |--------------------------------------------------------------------------
    |
    | This handles the showing of the services in the frontEnd 
    |
    */

    use LogErrorAndRedirectTrait;

    public function details($slug)
    {
        /**
         * Details
         * 
         * doc: get the services
         */

        try
        {
            $data = Services::where('slug',$slug)->firstOrFail();

            return view('frontEnd.services.details',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in getting the service frontEnd');
            return back();
        }
    }
}
