<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AdRequest;
use App\Http\Requests\Dashboard\AdRequestUpdate;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Ad;
use Exception;

class AdsController extends Controller
{
      /*
    |--------------------------------------------------------------------------
    | Ads Controller
    |--------------------------------------------------------------------------
    |
    | This handles the CRUD action's of the dashboard Ads Section 
    |
    */

    public function index()
    {
        /**
         * Index
         * 
         * Doc: Send the user to ads page in the dashboard
         * 
         * @return \Illuminate\View\View
         */

         try
         {
            #get ads information and return to the view page
            $data = Ad::orderBy('created_at','desc')->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.ads.index',compact('data'));
         }
         catch(Exception $e)
         {
             #Log the exception
             \Log::error('Error in Ads Dashboard Index: ' . $e->getMessage());
        
             #Redirect to an error view with a specific message
             Alert::toast('Something went wrong sending the verify the reset password email, please contact us at '.config('general.support_email'));
             return back();
         }
    }

    public function create()
    {
         /**
         * Create
         * 
         * Doc: Send the user to create ads page in the dashboard
         * 
         * @return \Illuminate\View\View
         */
        try
        {
            return view('dashboard.ads.create');
        } 
        catch(Exception $e)
        {
            #Log the exception
            \Log::error('Error in create ads: ' . $e->getMessage());
       
            #Redirect to an error view with a specific message
            Alert::toast('Something went wrong, please contact us at '.config('general.support_email'));
            return back();
        }
    }

    public function store(AdRequest $request)
    {
        /**
         * Store
         * 
         * Doc: Save the Ad data in the data base
         * 
         * @param request have the ad data
         * 
         * 
         * 
         * 
         * @return \Illuminate\Http\RedirectResponse
         */

        try
        {
            #save the data
            $data = Ad::create($request->all());

            #adding the image
            $data
            ->clearMediaCollection('images')
            ->addMedia($request->file('image'))
            ->toMediaCollection('images');

            #send the user to the ads page with success message 
            Alert::toast('Ad was updated','success');
            return redirect()->route('dashboard.ads.index');
        }
        catch(Exception $e)
        {
            #Log the exception
            \Log::error('Error in create ads: ' . $e->getMessage());
       
            #Redirect to an error view with a specific message
            Alert::toast('Something went wrong, please contact us at '.config('general.support_email'));
            return back();
        }
    }

    public function edit($id)
    {
         /**
         * Edit
         * 
         * Doc: Send the user to edit ads page in the dashboard
         * 
         * @param id The ad id
         * @return \Illuminate\View\View
         */

        try
        {
            #find the ad and sent the user to the edit page
            $data = Ad::findOrFail($id);
            return view('dashboard.ads.edit',compact('data'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
             #log the Exception
             \Log::error('Ad id was not found in the edit Ad dashboard: ' . $e->getMessage());

             #back with an error message
             Alert::toast('Please check the Ad id was sent', 'error');
             return back();

        } 
        catch(Exception $e)
        {
            #Log the exception
            \Log::error('Error in edit ads: ' . $e->getMessage());
       
            #Redirect to an error view with a specific message
            Alert::toast('Something went wrong, please contact us at '.config('general.support_email'));

            return back();
        }
    }

    public function update(AdRequestUpdate $request,$id)
    {
        /**
         * Update
         * 
         * Doc: Save the user data in the database 
         * 
         * @param request will have the ad information
         * @param id the ad id
         * @return \Illuminate\Http\RedirectResponse
         */

        try
        {
            $data = Ad::find($id);
            if($request->hasFile('image'))
            {
                $data
                    ->clearMediaCollection('images')
                    ->addMedia($request->file('image'))
                    ->toMediaCollection('images');
            }
            $data->update($request->all());
            Alert::toast('Ad was updated','success');
            return redirect()->route('dashboard.ads.index');
        }
        catch(Exception $e)
        {
            Alert::toast('Server Error','error');
            back();
            return;
        }
    }
    
}
