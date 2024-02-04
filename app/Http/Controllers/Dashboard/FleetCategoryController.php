<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FleetCategory;
use App\Models\Fleet;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\FleetCategoryRequest;
use App\Models\City;
use App\Models\ReservingTime;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;

class FleetCategoryController extends Controller
{

      /*
    |--------------------------------------------------------------------------
    | Fleet Category Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all fleet categories actions in the dashboard
    |
    */


    use LogErrorAndRedirectTrait , JsonResponseTrait;

    public function index()
    {
         /**
        * Create
        * 
        * Doc: send the user to the index page 
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = FleetCategory::orderBy('created_at','desc')->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.fleet_category.index',compact('data'));    
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting fleet category page index: ');
            return back();
        }
    }

    public function create()
    {
        /**
        * Create
        * 
        * Doc: send the user to the create page 
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $cities = City::get();
            #get the default pricing so to select from in create fleet category
            $defaultPrice = config('general.default_pricing');
            return view('dashboard.fleet_category.create',compact('cities','defaultPrice'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting fleet category page index: ');
            return back(); 
        }
    }

    public function edit($id)
    {
         /**
        * Edit
        * 
        * Doc: send the user to the edit page 
        *
        * @param Integer App\Models\City $id
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = FleetCategory::findOrFail($id);

            #get the ranges for the reserving time for the fleet category
            $periodTwentyfour = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.twentyFourHour'))->first();
            $periodNineteen = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.nineTeenHour'))->first();
            $periodTwelve = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.nineTwelveHour'))->first();
            $periodSix = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.sixHour'))->first();  
            
            return view('dashboard.fleet_category.edit',compact(
                'data',
                'periodTwentyfour',
                'periodNineteen',
                'periodTwelve',
                'periodSix'
            ));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the faq dashboard: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in getting the fleet category: ');
            return back();
        }

    }

    public function store(FleetCategoryRequest $request)
    {
        /**
        * Store
        * 
        * Doc: store the fleet information 
        *
        * @param Illuminate\Http\Request $request will have the fleet category information
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
        {
            #save the create new fleet category
            $this->save_data($request);

            #redirect to the index page
            return redirect()->route('dashboard.fleetCategories.index');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting storing the fleet information: ');
            return back();
        }
    }

    public function update(FleetCategoryRequest $request,$id)
    {
         /**
        * Update
        * 
        * Doc: update the fleet category information 
        *
        * @param Integer App\Models\FleetCategory
        * @param Illuminate\Http\Request $request will have the fleet category information
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
        {

            #update the selected fleet category
            $this->save_data($request,$id);

            #This may effect the seo dashboard
            return redirect()->route('dashboard.fleetCategories.index');

        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error creating fleet category: ');
            return back();
        }

    }

    public function check_slug($slug)
    {
        /**
        * Check Slug
        * 
        * Doc: to check if the slug is available for the fleet category or not 
        *
        * @param String $slug the selected slug
        *
        * @return Json
        */

        try
        {
            #check if the slug is available or not if its dose exist will be not available for use 
            $checkSlug = FleetCategory::where('slug',$slug)->first();

            #return to the front end with updated status
             $responseObj = [
                'msg'=>'Slug status',
                'data'=>[
                    'is_available'=>$checkSlug ? false : true
                ]
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error creating the fleet category: ');
        }

    }

    public function delete(Request $request, $id)
    {
          /**
        * Delete
        * 
        * Doc: delete the fleet category 
        *
        * @param Integer $id App\Models\FleetCategory
        * @param Illuminate\Http\Request $request will have the fleet category information
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try{

           $data = FleetCategory::findOrFail($id);

           #to transfer the fleets from the delete one to the selected fleet category before deleting it
           $chosen_category = Fleet::findOrFail($request->fleet_category);

           foreach ($data->fleets as $key => $value) 
           {
             $chosen_category->fleets()->save($value);
           }

           #delete the fleet
           $data->delete();

            #return to the front end with updated status
            $responseObj = [
                'msg'=>'fleet category was deleted',
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'error deleting the fleet category: ');
        }
    }

    private function save_data($request,$id = null)
    {
          /**
        * Save data
        * 
        * Doc: to save the data for the fleet category 
        *
        * @param Integer App\Models\FleetCategory
        * @param Illuminate\Http\Request $request will have the fleet category information
        *
        * @return Void
        */

        #get the fleet category data with the pricing info
        $request_data = array_merge($request->all(),[
            'pricing_rules'=> json_encode([
                'minimum_hour' => $request->minimum_hour, /** Minium Amount of hours the customer can book */
                'minimum_mile_hour' => $request->mile_per_hour, /** How Many Miles Should The Hours Have and if it's exceeded */
                'price_per_hour' => $request->price_per_hour, /** Price per hour */
                'extra_price_per_mile_hourly'=>$request->extra_price_per_mile_hourly, /** The Extra price for mile hourly */
                'initial_fee' => $request->initial_fee, /** The initial fee the customer should pay when he book a point to point trip */
                'minimum_mile' => $request->minimum_mile, /** The minium mile the user should it pe charged for */
                'extra_price_per_mile' => $request->point_to_point_extra_price_per_mile /** Extra price per mile for the point to point service */
            ]),
            'flight_tracking' => $request->flight_tracking == 'on'?1:0,
            'split_hour_mechanism' => 0, /** This is taken from the pricing rule table */
            'split_hour_mechanism_price' => 0  /** This is taken from the pricing rule table */,
        ]);
        
        #if the id was sent update the selected one else create
        if($id)
        {
            $data = FleetCategory::findOrFail($id);
            $data->update($request_data);
        }
        else
        {
            $data = FleetCategory::create($request_data);
        }

        if($request->hasFile('image'))
        {
            $data
            ->clearMediaCollection('images')
            ->addMedia($request->file('image'))
            ->toMediaCollection('images'); 
        };

        #delete all the old reserving time data
        $data->reservingTimes()->delete();

        #create new reserving data
        ReservingTime::create([
            'period'=>$request->periodTwentyfour,
            'charge'=>$request->chargeTwentyfour,
            'fleet_category_id'=>$data->id,
            'from_hour'=>20,
            'to_hour'=>24
        ]);
        ReservingTime::create([
            'period'=>$request->periodNineteen,
            'charge'=>$request->chargeNineteen,
            'fleet_category_id'=>$data->id,
            'from_hour'=>13,
            'to_hour'=>19
        ]);
        ReservingTime::create([
            'period'=>$request->periodTwelve,
            'charge'=>$request->chargeTwelve,
            'fleet_category_id'=>$data->id,
            'to_hour'=>12,
            'from_hour'=>7
        ]);
        ReservingTime::create([
            'period'=>$request->periodSix,
            'charge'=>$request->chargeSix,
            'fleet_category_id'=>$data->id,
            'to_hour'=>6,
            'from_hour'=>1
        ]);

        return;

    }
    
}
