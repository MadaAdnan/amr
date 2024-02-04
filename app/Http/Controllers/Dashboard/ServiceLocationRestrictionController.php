<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\UtilitiesTrait;
use App\Models\City;
use App\Models\ServiceLocationRestriction;
use Illuminate\Http\Request;
use Exception;

class ServiceLocationRestrictionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Service Location Restriction Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the Service Location actions in the dashboard
    |
    */


    use UtilitiesTrait, LogErrorAndRedirectTrait,JsonResponseTrait;

    public function index()
    {
        /**
        * Index
        * 
        * Doc: get all reservations
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = ServiceLocationRestriction::orderBy('created_at','desc')
            ->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.service_location_restriction.index',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in service restriction index: ');
            return back();
        }


    }

    public function create()
    {
         /**
        * Create
        * 
        * Doc: go to create page with the cities
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $cities = City::where('status',City::STATUS_ACTIVE)->get();
            return view('dashboard.service_location_restriction.create',compact('cities'));    
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in create service restriction: ');
            return back(); 
        }
    }

    public function edit($id)
    {
         /**
        * Edit
        * 
        * Doc: go to create page with the cities
        *
        * @param Integer App\Models\ServiceLocationRestriction $id
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = ServiceLocationRestriction::findOrFail($id);

            $cities = City::where('status',City::STATUS_ACTIVE)
            ->get();
            return view('dashboard.service_location_restriction.edit',compact('cities','data'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error finding the service restriction in edit dashboard : ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in edit page dashboard : ');
            return back(); 
        }
    }

    public function store(Request $request)
    {
        /**
        * Store
        * 
        * Doc: create service restrictions in the database
        *
        * @param Illuminate\Http\Request $request
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
        {
            ServiceLocationRestriction::create($request->all());
            return redirect()->route('dashboard.serviceLocationRestrictions.index');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in storing service location restriction : ');
            return back(); 
        }
    }

    public function update(Request $request,$id)
    {
         /**
        * Update
        * 
        * Doc: update the selected service restriction
        *
        * @param Illuminate\Http\Request $request
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
        {
            ServiceLocationRestriction::findOrFail($id)->update($request->all());
            return redirect()->route('dashboard.serviceLocationRestrictions.index');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error in finding service location restriction : ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error updating the service restriction : ');
            return back(); 
        }
    }

    public function delete($id)
    {
         /**
        * Delete 
        * 
        * Doc: delete service restriction
        *
        * @param Models\ServiceLocationRestriction $id
        *
        * @return Json
        */

        try
        {
            ServiceLocationRestriction::findOrFail($id)->delete();

              #return to the front end with updated status if there is an links with the same info return false
              $responseObj = [
                'msg'=>'Item was deleted',
                'status'=>config('status_codes.success.deleted')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.deleted'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorJson($e,'Error finding the service restriction in delete dashboard : ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,'Error in delete the service restriction : ');
            return back();
        }
    }

    public function check_if_place_available($lat,$long,$radius,$id = null)
    {
         /**
        * Check if place available 
        * 
        * Doc: check if the place was selected is overlapping with another service restriction throw the radius
        *
        * @param Integer $lat
        * @param Integer $long
        * @param Integer $radius
        * @param Integer $id
        *
        * @return Json
        */

        try
        {
            
            $data = ServiceLocationRestriction::get();
            $available = (object)[];
            $item_in_service_location_restriction = null;

            #check if it's overlapping with any of the service we have in the database
            foreach($data as $item)
            {
                $check = $this->areRadiusOverlapping($item->latitude,$item->longitude,$item->radius,$lat,$long,$radius);
                if($check && $id != $item->id)
                {
                    $available = false;
                    $item_in_service_location_restriction = $item;
                    break;
                }
            };

             #return to the front end with updated status if there is an links with the same info return false
             $responseObj = [
                'msg'=>'data returned',
                'data'=>[
                    'is_available'=>$available,
                    'item'=>[
                        'name'=>$item_in_service_location_restriction?$item_in_service_location_restriction->address:null
                    ]
                ],
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
            
        }
        catch(Exception $e)
        {
           return $this->logErrorJson($e,'error in check place available.');
        }
    }

}
