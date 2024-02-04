<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreCountryRequest;
use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Company;
use App\Models\State;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;

class CountriesController extends Controller
{
      /*
    |--------------------------------------------------------------------------
    | Countries Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all countries action in the dashboard
    |
    */

    use LogErrorAndRedirectTrait,JsonResponseTrait;

    public function index(Request $request)
    {
          /**
        * Index
        * 
        * Doc: send the user to the countries page 
        *
        * @param Illuminate\Http\Request $request will have the filtering information
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #get the countries that status is not unassigned (unassigned mean the admin did't added yet to the dashboard)
            $data = Country::where('status','!=',Country::STATUS_UNASSIGNED)
            ->orderBy('created_at','desc');

            #get unassigned countries 
            $getAllCountries = Country::where('status',Country::STATUS_UNASSIGNED)
            ->pluck('name')
            ->toArray();

            #filtering the data
            $query = $request->get('query');
            $status = $request->get('status');

            if($query)
            {
                $data = $data->where('name','like','%'.$query.'%');
            }
            if($status)
            {
                $data = $data->where('status',$status);
            }

            #create pagination 
            $data = $data->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.countries.index',compact('data','getAllCountries'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting countries: ');
            return back();
        }

    }

    public function store(StoreCountryRequest $request)
    {
        /**
        * Store
        * 
        * Doc: change the country from unassigned to active
        * 
        * @param Illuminate\Http\Request $request to get country name 
        *
        *
        * @return Json
        */

        try
        {
            #get the active countries
            Country::where('name',$request->name)->update([
                'status'=>Country::STATUS_ACTIVE
            ]);

             #return to the front end with updated status
             $responseObj = [
                'msg'=>'data was created',
                'status'=>config('status_coeds.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
           return $this->logErrorJson($e,'Error creating the country: ');
        }
    }

    public function update(Request $request,$id)
    {
         /**
        * Update
        * 
        * Doc: update the country data
        * 
        * @param Illuminate\Http\Request $request to get data 
        * @param App\Models\Country $id
        *
        *
        * @return Json
        */

        try
        {
            #update the user info
            Country::findOrFail($id)->update($request->all());

            #return to the front end with updated status
            $responseObj = [
                'msg'=>'data was updated',
                'status'=>config('status_codes.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorJson($e,'Error finding the country in the update countries: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error updating the country: ');
        }
    }

    public function states(Request $request,$id)
    {
         /**
        * States
        * 
        * Doc: get the states according to the country
        * 
        * @param Illuminate\Http\Request $request to get data 
        * @param App\Models\Country $id
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #get the state according to the country
            $data = State::where('country_id',$id);

            #filter the data 
            $query = $request->get('query');
            $status = $request->get('status');
    
            if($query)
            {
                $data = $data->where('name','like','%'.$query.'%');
            }
            if($status)
            {
                $data = $data->where('status',$status);
            }

            #paginate the data
            $data = $data->paginate(config('general.dashboard_pagination_number'));
    
            return view('dashboard.countries.states',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error getting the states data: ');
            return back(); 
        }
    }

    public function states_index($country_id)
    {
         /**
        * States Index
        * 
        * Doc: get the states according to the country
        * 
        * @param App\Models\Country $id
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #get the country
            $country = Country::findOrFail($country_id);

            #get the states according the countries
            $data = $country->states()
            ->where('status','!=',Country::STATUS_UNASSIGNED)
            ->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.countries.states',compact('data','country'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting countries: ');
            return back(); 
        }
        
    }

    public function cities_index($state_id)
    {
        /**
        * Cities Index
        * 
        * Doc: get the cities according to the states
        * 
        * @param App\Models\Country $id
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $state = State::findOrFail($state_id);
            $data = $state
            ->cities()
            ->where('status','!=',City::STATUS_UNASSIGNED)
            ->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.countries.cities',compact('data','state'));    
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting countries: ');
            return back(); 
        }
    }

    public function companies_index($city_id)
    {
         /**
        * Companies index
        * 
        * Doc: get the companies according to the city
        * 
        * @param App\Models\Country $id
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $city = City::findOrFail($city_id);
            $data = Company::where('city_id',$city_id)->paginate(config('general.dashboard_pagination_number'));
            return view('dashboard.countries.companies',compact('data','city'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting countries: ');
            return back(); 
        }
    }


}
