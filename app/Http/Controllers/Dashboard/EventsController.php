<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreEventsRequest;
use App\Models\City;
use App\Models\Event;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Traits\LogErrorAndRedirectTrait;

class EventsController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Events Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all event actions in the dashboard
    |
    */

    use LogErrorAndRedirectTrait;


    public function index(Request $request)
    {
         /**
        * Index
        * 
        * Doc: send the user to the driver page 
        *
        * @param Illuminate\Http\Request $request to get the filter information
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #initiate the eloquent query 
            $data = Event::whereHas('cities')->orderBy('created_at','desc');

            #filter queries
            $searchQuery = $request->query('query');
            $statusQuery = $request->query('status');
            $startDateQuery = $request->query('startDate');
            $endDateQuery = $request->query('endDate');
            
            if($searchQuery)
            {
                $data->where(function($q) use($searchQuery){
                    $q->where('address','like','%'.$searchQuery.'%')
                    ->orWhereHas('cities',function($city_q)use($searchQuery){
                         $city_q->where('title','like','%'.$searchQuery.'%');
                    });
                });
                
            }

            #filter according to start and end date
            if($startDateQuery&&$endDateQuery)
            {
                $data->whereDate('start_date',$startDateQuery)
                ->whereDate('end_date',$endDateQuery);
            }
    
            #filter according to status query
            if($statusQuery)
            {
                $data->where('status',$statusQuery);
            }
            
            #get the pagination
            $data = $data->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.events.index',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting events: ');
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
            $cities = City::where('status',City::STATUS_ACTIVE)
            ->select('id','title')
            ->get();

            return view('dashboard.events.create',compact('cities'));    
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting create page: ');
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
        * @param Integer $id for App\Models\Event
        *
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = Event::findOrFail($id);
            $cities = City::where('status',City::STATUS_ACTIVE)
            ->select('id','title')
            ->get();
            return view('dashboard.events.edit',compact('cities','data'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the event: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting create page: ');
            return back();
        }
    }

    public function update(StoreEventsRequest $request,$id)
    {
         /**
        * Update
        * 
        * Doc: update the event info
        *
        * @param Illuminate\Http\Request $request will have the updated info for the event
        * @param Integer $id for App\Models\Event
        *
        *
        *
        * @return Json
        */

        try
        {
            #save the user data with it's radius info
            $data = array_merge($request->all(),[
                'radius_area'=>json_encode($request->radius_area),
                'end_date'=>$request->end_date ?? Carbon::create($request->start_date)->addYears(10)
            ]);
            Event::findOrFail($id)->update($data);

            #return to the front end with updated status
            $responseObj = [
                'msg'=>'Event was updated',
                'status'=>config('status_codes.success.created')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.created'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the event update: ');
           return back();
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error creating event');
        }
    }

    public function store(StoreEventsRequest $request)
    {
        /**
        * Store
        * 
        * Doc: store the event data 
        *
        * @param Illuminate\Http\Request $request will have the updated info for the event
        *
        *
        * @return Json
        */

        try
        {
            #save the user data with it's radius info
            $data = array_merge($request->all(),[
                'radius_area'=>json_encode($request->radius_area),
                'end_date'=>$request->end_date ?? Carbon::create($request->start_date)->addYears(10)
            ]);
            
            Event::create($data);

             #return to the front end with updated status
             $responseObj = [
                'msg'=>'Event was created',
                'status'=>config('status_codes.success.created')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.created'));

        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error creating event');
        }
    }

    public function delete($id)
    {
          /**
        * Delete
        * 
        * Doc: delete the event 
        *
        * @param Integer $id for App\Models\Event
        *
        *
        *
        * @return Json
        */

        try
        {
            #find and delete the item
            Event::findOrFail($id)->delete();

             #return to the front end with updated status
             $responseObj = [
                'msg'=>'Event was deleted',
                'status'=>config('status_codes.success.deleted')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.created'));

        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error deleting the event');
        }
    }
}
