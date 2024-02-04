<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreStateRequest;
use App\Models\Country;
use App\Models\State;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | State Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the state controller in the dashboard
    |
    */

    use LogErrorAndRedirectTrait,JsonResponseTrait;

    public function index(Request $request)
    {
        /**
        * Index
        * 
        * Doc: go to index 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #get state where status unassigned
            $data = State::where('status','!=',State::STATUS_UNASSIGNED)
            ->orderBy('created_at','desc');

            #get filter query
            $query = $request->get('query');
            $countries = Country::where('status',Country::STATUS_ACTIVE)->get();
            $status = $request->get('status');
            $getAllStates = State::pluck('name')->toArray();
            
            if($query)
            {
                $data = $data->where('name','like','%'.$query.'%')
                ->orWhereHas('countries',function($q) use($query){
                    $q->where('countries.name','like','%'.$query.'%');
                });
            }
            if($status)
            {
                $data = $data->where('status',$status);
            }
            $data = $data->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.states.index',compact('data','countries','getAllStates'));

        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'index state controller');
            return back();
        }
    }

    public function store(StoreStateRequest $request)
    {
        /**
        * Store
        * 
        * Doc: search for the state and update it to active
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try{
            /** Change the value of the state from unassign to active */
            State::where('name',$request->name)->update([
                'status'=>State::STATUS_ACTIVE
            ]);

             #return to the front end with updated status if there is an links with the same info return false
             $responseObj = [
                'msg'=>'data was created',
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error storing state: ');
        }
    }

    public function update(Request $request,$id)
    {
         /**
        * Update
        * 
        * Doc: search for the state and update it to active
        *
        * @param Integer $id App\Models\State
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
             State::findOrFail($id)->update($request->all());

            #response json
            $responseObj = [
                'msg'=>'data was updated',
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorAndRedirect($e,'Error in finding service: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'update state error');
        }
    }
        
    public function getStateAccordingToCountry($name)
    {
         /**
        * Get State According To Country
        * 
        * Doc: search for the state and update it to active
        *
        * @param String $name
        *
        *
        * @return Json
        */

        try
        {
            #get states
            $countries = Country::where('name',$name)
            ->first()
            ->states()
            ->where('status',State::STATUS_UNASSIGNED)
            ->pluck('name')
            ->toArray();
     
             #response json
             $responseObj = [
                 'msg'=>'The states was found',
                 'data'=>$countries
                ];
     
             return $this->successResponse($responseObj,config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'error get state according to country');
        }
     
    }


}
