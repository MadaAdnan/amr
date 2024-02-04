<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\FleetCategory;
use App\Models\PriceRule;
use App\Models\ReservingTime;
use App\Models\State;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\PricingRulesTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class CitiesController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Cities Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all cities action in the dashboard
    |
    */
    
    use PricingRulesTrait , JsonResponseTrait , LogErrorAndRedirectTrait;

    public function index()
    {
     /**
        * Index
        * 
        * Doc: send the user to the cities page in the dashboard with cities have pricing rules
        * 
        * @return \Illuminate\View\View
        */


        try
        {
            $data = City::where('status','!=',City::STATUS_UNASSIGNED)
            ->whereHas('pricesRules')
            ->orderBy('created_at','desc')
            ->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.cities.index',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error viewing cities index: ');
            return back();
        }
       
    }
    
    public function create()
    {
        /**
        * Create
        * 
        * Doc: send the user the create page
        * 
        * @return \Illuminate\View\View
        */

        try
        {
            #Show the fleet categories in the create page so the user can add pricing to the fleet inside the selected city
            $fleetCategory = FleetCategory::where('status',FleetCategory::STATUS_ACTIVE)
            ->get()
            ->map(function($item){            
                #format and get the pricing for the fleet category
                return $this->fleetResponse($item);
            });

            #Get the names of the states
            $states = State::where('status',State::STATUS_ACTIVE)->pluck('name')->toArray();

            #Get cities only for the active states
            $cities = City::whereHas('states',function($q){
                $q->where('status',State::STATUS_ACTIVE);
            })
            ->where('status',City::STATUS_ACTIVE)
            ->get();
                   
            return view('dashboard.cities.create',compact('fleetCategory','states','cities'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error viewing cities index page: ');
            return back();
        }
    }

    public function edit($id)
    {
         /**
        * Edit
        * 
        * Doc: send the user the create page
        * 
        * @param App\Models\City $id
        * 
        * 
        * @return \Illuminate\View\View
        */
        
        try
        {
            $data = City::findOrFail($id);

            #Show the fleet categories in the edit page so the user can add pricing to the fleet category inside the selected city
            $fleetCategory = FleetCategory::where('status',FleetCategory::STATUS_ACTIVE)
            ->get()
            ->map(function($item) use($data){
                #format and get the pricing for the fleet category
                return $this->fleetResponse($item,$data->id);
            });

            #Get reserving time data from 6 to 24 hours
            $periodTwentyFour = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.twentyFourHour'))->first();
            $periodNineteen = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.nineTeenHour'))->first();
            $periodTwelve = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.nineTwelveHour'))->first();
            $periodSix = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.sixHour'))->first();

            #get the city events
            $events = $data->events()->get();

            #get cities to the user so can that can copy data from
            $cities = City::where(function ($query) use ($id) {
                $query->where('city_id', '!=', $id)
                      ->orWhereNull('city_id');
            })
            ->where('id', '!=', $id)
            ->where('status', 'Active')
            ->whereHas('states', function($q) {
                $q->where('status', 'Active');
            })
            ->get();

            #get city selected fleets
            $selected_fleets = $data->fleets_category()->pluck('fleet_categories.id')->toArray();

            #get the default pricing if there is no pricing for the fleet category
            $defaultPrice = config('general.default_pricing');

            #get the states
            $states = State::get();

            #send the user to edit cities with the data
            return view('dashboard.cities.edit',
            compact('fleetCategory',
                'defaultPrice',
                'states','data',
                'selected_fleets',
                'periodTwentyFour',
                'periodNineteen',
                'periodTwelve',
                'periodSix',
                'events',
                'cities'
            ));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Cities id was not found dashboard: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error viewing cities index page: ');
            return back();
        }

    }

    public function store(Request $request)
    {
        /**
        * Store
        * 
        * Doc: update the city data and make it visible in the dashboard. 
        * 
        * @param \Illuminate\Http\Request $request wil have the city information
        * 
        * @return Json
        */

        try
        {
            #get the selected city
            $data = City::where('title',$request->title)->firstOrFail();

            #the update status for the city
            $status = 'Active';

            #update the selected city from unassigned to active
            $this->updateCityInfo($data,$request,$status);

            #return to the front end with updated status
            $responseObj  = [
                'data'=> [
                    'url'=>route('dashboard.countries.cities_index',$data->states->id),
                    'status'=>config('status_codes.success.updated')
                ]
             ];

            return $this->successResponse($responseObj,config('status_codes.success.updated'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorJson($e,'Error finding city name in the store request dashboard: ');
        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,'Error storing city data dashboard: ');
        }

    }

    public function update(Request $request,$id)
    {
         /**
        * Update
        * 
        * Doc: update the city information. 
        * 
        * @param \Illuminate\Http\Request $request wil have the city information
        * @param \App\Models\City $id selected city need to be updated
        * 
        * @return Json
        */

        try
        {
             #get the city information
             $data = City::findOrFail($id);

             #update the city parent
             $this->updateCityInfo($data,$request,$request->status);

             /** UPDATE LINKED CITIES */
             foreach ($data->parent_city()->get() as $value) 
             {
                #find child than update it's data
                $city_child = City::find($value->id);
                $this->updateCityInfo($city_child,$request,$request->status,$data->id);
             }

            #return to the front end with updated status
            $responseObj  = [
                'data'=> [
                    'url'=>route('dashboard.countries.cities_index',$data->states->id),
                    'status'=>config('status_codes.success.updated')
                ]
             ];

            return $this->successResponse($responseObj,config('status_codes.success.updated'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
          return $this->logErrorJson($e,'city was not found in update city dashboard: ');
        }
        catch(Exception $e)
        {
            throw $e;
           return $this->logErrorJson($e,'Error updating the city data: ');
        }
    }

    public function getCityAccordingToState($state_name)
    {
         /**
        * Get City According To State
        * 
        * Doc: get the cities according to states in the dashboard create state
        * 
        * @param String $state_name
        * 
        * @return Json
        */

        try
        {
            #get the states
            $state = State::where('name',$state_name)->firstOrFail();

            #get unassigned cities
            $getUnassigned = $state
            ->cities()
            ->where('status',State::STATUS_UNASSIGNED)
            ->pluck('title')
            ->toArray();

             #return to the front end with updated status
             $responseObj  = [
                'data'=>$getUnassigned,
                'status'=>config('status_codes.success.ok')
               ];
            
            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
          return $this->logErrorJson($e,'state was not found: ');
        }
        catch(Exception $e)
        {
           return $this->logErrorJson($e,'problem with getting the state dashboard: ');
        }
    }

    public function getCityInfo($city_id = null)
    {
        /**
        * Get City According To State
        * 
        * Doc:  
        * 
        * @param \App\Models\City $city_id
        * 
        * @return Json
        */

        try
        {
            $data = City::find($city_id);
            
            #if the city was found get it's information else get the fleet default information 
            if($data)
            {
                #get the fleets attached to the city
                $fleetCategory = $data
                ->fleets_category()
                ->select('fleet_categories.id','fleet_categories.title','fleet_categories.pricing_rules')
                ->get();

                #get the reserving time information for the city
                $TwentyFour = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.twentyFourHour'))->first();
                $Nineteen = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.nineTeenHour'))->first();
                $Twelve = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.nineTwelveHour'))->first();
                $Six = $data->reservingTimes()->where('to_hour',config('general.reserving_time_ranges.sixHour'))->first();
            }
            else
            {
                $fleetCategory = FleetCategory::select('fleet_categories.id','fleet_categories.title','fleet_categories.pricing_rules')
                ->get();
            }

            #get the pricing rules
            $pricingRules = [];

            #push the pricing rules to the fleet
            foreach($fleetCategory as $item)
            {   $rules = $this->fleetResponse($item,$city_id);
                $rules->category_id = $item->id;        
                array_push($pricingRules,$rules);
            }

            #return to the front end with updated status
            $responseObj  =[
                'data'=>[
                    'fleet_category'=>$fleetCategory,
                    'selected_fleets_ids'=>$data?$data->fleets_category()->pluck('fleet_categories.id')->toArray():[],
                    'pricing_rules'=>$pricingRules,
                    'daily_time'=>[
                        'from'=>$data?$data->daily_from:null,
                        'to'=>$data?$data->daily_to:null,
                        'price'=>$data?$data->daily_price:null
                    ],
                    'reserving_time'=>[
                        'Twentyfour'=> isset($TwentyFour) ? $TwentyFour : null ,
                        'Nineteen'=> isset($Nineteen) ? $Nineteen : null,
                        'Twelve'=> isset($Twelve) ? $Twelve : null,
                        'Six'=> isset($Six) ? $Six : null,
                    ]
                ],
                'msg'=>'City info'
            ];
            
            return $this->successResponse($responseObj,config('status_codes.success.ok'));


        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'get the city information: ');
        }

    }

    private function updateCityInfo($city,$request,$status = City::STATUS_ACTIVE , $parentId = null)
    {
        /**
        * Update City Information
        * 
        * Doc: send the user to the cities page in the dashboard with cities have pricing rules
        * 
        * @param App\Models\City $city need to be update
        * @param \Illuminate\Http\Request $request The request containing the city data.
        * @param String $status selected status the fro the city
        * @param Number $parent the selected city id for the child (This will change the selected city from parent to child)
        *
        *
        * @return void
        */

         #update city basic information
         $city_data = 
         [
            'status'=>$status,
            'daily_from'=>$request->daily_from,
            'daily_to'=>$request->daily_to,
            'daily_price'=>$request->daily_price,
            'updated_at'=>Carbon::now(),
            'city_id'=> $request->link_city == 1 ? $request->city_id : null
        ];

        #make it child
        if($parentId)
        {
            $city_data['city_id'] = $parentId;
        }
        #delete the old pricing rules and add the newly added one
        $city->pricesRules()->delete();
        
        #hourly pricing to the fleet category
        foreach ($request->hourly as $key => $value) 
        {
            $value = (object) $value;

            $save_data =[

                #update the hourly data
                'minimum_hour'=>$value->minimum_hour ?? 0,
                'minimum_mile_hour'=>$value->minimum_mile_hour ?? 0,
                'price_per_hour'=>$value->price_per_hour ?? 0,
                'extra_price_per_mile_hourly'=>$value->extra_price_per_mile_hourly ?? 0,
                'city_id'=>$city->id,
                'service_id'=>2,
                'category_id'=>$value->category_id,

                #below update for the point to point data
                'initial_fee'=>$value->initial_fee ?? 0,
                'minimum_price'=>$value->minimum_price ?? 0,
                'price_per_mile_hour'=>$value->price_per_mile_hour ?? 0,
                'extra_price_per_hour'=>$value->extra_price_per_hour ?? 0
            ];
            
            PriceRule::create($save_data);
        }

        #point to point  pricing to the fleet category
        foreach ($request->pointToPoint as $key => $value) 
        {
            $value = (object)$value;
            $save_data = [

                #update for the point to pint data
                'initial_fee'=>$value->initial_fee ?? 0,
                'minimum_price'=>$value->initial_fee ?? 0,
                'minimum_mile'=>$value->minimum_mile ?? 0,
                'extra_price_per_mile'=>$value->point_to_point_extra_price_per_mile ?? 0,

                /** Below this it's not necessary for the point to point data */
                'minimum_mile_hour'=>$value->minimum_mile_hour ?? 0,
                'price_per_mile_hour'=>$value->price_per_mile_hour ?? 0,
                'extra_price_per_hour'=>$value->extra_price_per_hour ?? 0,
                'price_per_hour'=>$value->price_per_hour ?? 0,
                'minimum_hour'=>$value->minimum_hour ?? 0,
                'extra_price_per_mile_hourly'=>$value->extra_price_per_mile_hourly ?? 0,
                'city_id'=>$city->id,
                'service_id'=>1,
                'category_id'=>$value->category_id
            ];

            PriceRule::create($save_data);
        
        }


        #remove all reserving time data and added new one
        $city->reservingTimes()->delete();

        #create a reserving time for every selected period
        ReservingTime::create([
            'period'=>$request->periodTwentyfour,
            'charge'=>$request->chargeTwentyfour,
            'city_id'=>$city->id,
            'from_hour'=>20,
            'to_hour'=>24
        ]);

        ReservingTime::create([
            'period'=>$request->periodNineteen,
            'charge'=>$request->chargeNineteen,
            'city_id'=>$city->id,
            'from_hour'=>13,
            'to_hour'=>19
        ]);

        ReservingTime::create([
            'period'=>$request->periodTwelve,
            'charge'=>$request->chargeTwelve,
            'city_id'=>$city->id,
            'to_hour'=>12,
            'from_hour'=>7
        ]);

        ReservingTime::create([
            'period'=>$request->periodSix,
            'charge'=>$request->chargeSix,
            'city_id'=>$city->id,
            'to_hour'=>6,
            'from_hour'=>1
        ]);

        #add the new fleets to the city
        $city->fleets_category()->sync($request->fleet_category);

        $city->update($city_data);
        
        return;
    }
    
}
