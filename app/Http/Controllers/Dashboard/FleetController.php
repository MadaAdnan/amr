<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FleetRequest;
use App\Models\Fleet;
use App\Models\FleetCategory;
use Exception;
use Auth;
use App\Traits\LogErrorAndRedirectTrait;

class FleetController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Fleet Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all fleet actions in the dashboard
    |
    */

    use LogErrorAndRedirectTrait;

    public function edit($id)
    {
        /**
        * Edit
        * 
        * Doc: send the user to the edit page 
        *
        * @param Integer App\Models\Fleet $id
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = Fleet::findOrFail($id);

            #get the fleet categories so the user can attach the fleet to it
            $categories = FleetCategory::orderBy('created_at','desc')->get();

            return view('dashboard.fleet.edit',compact('data','categories'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the fleet edit dashboard: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting fleet page edit: ');
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
            $categories = FleetCategory::orderBy('created_at','desc')->get();
            
            return view('dashboard.fleet.create',compact('categories'));    
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in create page fleet: ');
            return back(); 
        }
    }

    public function store(FleetRequest $request)
    {
         /**
        * Store
        * 
        * Doc: save the fleet data 
        *
        * @param Illuminate\Http\Request $request will have the fleet category information
        *
        * @return \Illuminate\Http\RedirectResponse
        */
        
        try
        {
            #add the data to the request
            $request_data = array_merge($request->all(),[
                'seats'=>0,
                'luggage'=>0,
                'passengers'=>0,
                'user_id'=>Auth::user()->id,
                'category_id'=>$request->categories
            ]);
            
            
            $data = Fleet::create($request_data);

            if($request->hasFile('image'))
            {
                $data
                ->addMedia($request->file('image'))
                ->toMediaCollection('images'); 
            };

            return redirect()->route('dashboard.pages.index','Fleet');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in updating storing the fleet category: ');
            return back(); 
        }

    }

    public function update(FleetRequest $request,$id)
    {
         /**
        * Update
        * 
        * Doc: update the fleet information 
        *
        * @param App\Models\Fleet $id
        * @param Illuminate\Http\Request $request will have the fleet category information
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
        {
            #save the updated data
            $request_data = array_merge($request->all(),[
                'seats'=>0,
                'luggage'=>0,
                'passengers'=>0,
                'user_id'=>Auth::user()->id,
                'category_id'=>$request->categories
            ]);

            $data = Fleet::findOrFail($id);
            $data->update($request_data);

            if($request->hasFile('image'))
            {
                $data
                ->clearMediaCollection('images')
                ->addMedia($request->file('image'))
                ->toMediaCollection('images'); 
            };

            return redirect()->route('dashboard.pages.index','Fleet');

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the fleet category update dashboard: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in updating fleet category: ');
            return back(); 
        }
    }

    public function delete($id)
    {
        /**
        * Delete
        * 
        * Doc: delete fleet 
        *
        * @param App\Models\Fleet $id
        *
        * @return Json
        */

        try
        {
            #find and delete the fleet
            Fleet::findOrFail($id)->delete();

             #return to the front end with updated status
             $responseObj = [
                'msg'=>'fleet was deleted',
                'status'=>config('status_codes.success.deleted')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.deleted'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorJson($e,'Error Finding the fleet category delete dashboard: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error Finding the fleet category delete dashboard: ');
        }

       
    }
}
