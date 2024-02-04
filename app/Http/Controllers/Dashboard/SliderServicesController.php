<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SliderRequest;
use App\Models\SliderServices;
use Exception;
use App\Traits\LogErrorAndRedirectTrait;
use Illuminate\Http\Request;

class SliderServicesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Services Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the slider services in the dashboard
    |
    */

    use LogErrorAndRedirectTrait;

    public function create()
    {
        /**
        * Create
        * 
        * Doc: go to create page
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            return view('dashboard.slider_services.create');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Slider services create : ');
            return back(); 
        }

    }
    
    public function store(SliderRequest $request)
    {
         /**
        * Store
        * 
        * Doc: create a slider
        *
        * @param Illuminate\Http\Request
        *
        * @return \Illuminate\View\View
        */
        
        try
        {
            #save data
            $data = SliderServices::create($request->all());
            if($request->hasFile('image'))
            {
                $data->clearMediaCollection('images')
                ->addMedia($request->file('image'))
                ->toMediaCollection('images');
            }
            return redirect()->route('dashboard.pages.index','Services Type');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Slider services store : ');
            return back();
        }
    }

    public function edit(Request $request, $id)
    {
         /**
        * Edit
        * 
        * Doc: edit slider
        *
        * @param Illuminate\Http\Request
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = SliderServices::find($id);
            if($data)
            {
                return view('dashboard.slider_services.edit',compact('data'));
            }
            else
            {
                Alert::toast('The slider not found','error');
                return back();
            }
        }
        catch(Exception $e)
        {
            Alert::toast('Error edit the slider','error');
            return back();
        }
    }

    public function update(SliderRequest $request, $id)
    {
         /**
        * Update
        * 
        * Doc: update slider modal
        *
        * @param Illuminate\Http\Request
        *
        * @return Redirect
        */

        try 
        {
            $slider = SliderServices::findOrFail($id);
            $slider->update($request->all());
    
            if ($request->hasFile('image')) {
                // Clear existing media and add the new one
                $slider->clearMediaCollection('images')
                    ->addMedia($request->file('image'))
                    ->toMediaCollection('images');
            }
    
            return redirect()->route('dashboard.pages.index', 'Services Type');
        } 
        catch (Exception $e) 
        {
            Alert::toast('Error updating the slider', 'error');
            return back();
        }
    }
    
    public function delete($id)
    {
          /**
        * Delete
        * 
        * Doc: delete a slider
        *
        * @param Integer App\Models\SliderServices $id
        *
        *
        * @return Json
        */

        try
        {
            $data = SliderServices::findOrFail($id);
            $data->delete();

             #return to the front end with updated status if there is an links with the same info return false
             $responseObj = [
                'msg'=>'slider was deleted',
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error deleting the slider');
        }
    }
}
