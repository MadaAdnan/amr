<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\FleetCategory;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;

class FleetController extends Controller
{
      /*
    |--------------------------------------------------------------------------
    | Fleet Controller
    |--------------------------------------------------------------------------
    |
    | This handles the showing of the fleets in the frontEnd 
    |
    */

    use LogErrorAndRedirectTrait;

    public function index()
    {
        /**
         * Index
         * 
         * 
         * doc: going to the fleets main page
         * 
         * @return View
         */

        try
        {
            #get fleets category where has fleets
            $data = FleetCategory::whereHas('fleets')
            ->get();

            return view('frontEnd.fleets.index',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error in getting the fleets');
            return back();
        }
    }

    public function details($slug)
    {
        /**
         * Details
         * 
         * 
         * doc: go the the details page for the fleet category
         * 
         * @return View
         */

        try
        {
            #get fleets category where has fleets
            $data = FleetCategory::whereHas('fleets')
            ->where('slug',$slug)
            ->firstOrFail();

            return view('frontEnd.fleets.details',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error in getting the fleets');
            return back();
        }
    }

}
