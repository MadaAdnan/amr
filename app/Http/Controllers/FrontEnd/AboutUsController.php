<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;

class AboutUsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | About Us Controller
    |--------------------------------------------------------------------------
    |
    | This handles the About us form
    |
    */

    use LogErrorAndRedirectTrait;

    public function index()
    {
        /**
         * Index
         * 
         * 
         * doc: going to the about us page
         * 
         * @return View
         */

        try
        {
            return view('frontEnd.aboutUs.index');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error entering the about us page');
            return back();
        }
    }

}
