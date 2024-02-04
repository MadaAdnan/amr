<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Role;
use Exception;
use Alert;
use App\Http\Requests\Dashboard\UpdateDriverRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\LogErrorAndRedirectTrait;

class DriverController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Driver Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all customer actions in the website
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
            #show the user the actions in the table for the index or the trash page
            $pageType = 'index';
            $query = $request->get('query');

            $drivers = User::whereHas('roles', function ($q) {
                $q->where('name', 'Driver');
            });

            #if the query exist filter the data according to it
            if ($query) 
            {
                $drivers = $drivers->where('first_name', 'like', '%' . $query . '%')
                        ->orWhere('last_name', 'like', '%' . $query . '%')            
                    ->orWhere('id',$query);
            }

            $drivers = $drivers->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.drivers.index', compact('drivers','pageType'));
            
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error getting customers: ');
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
            return view('dashboard.drivers.create');
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error getting the driver information: ');
            return back();
        }

    }

    public function edit($id)
    {
        /**
        * edit
        * 
        * Doc: send the user to the edit page view
        *
        * @param App\Models\User
        *
        *
        * @return \Illuminate\View\View
        */

        try 
        {   
            #get all the drivers users  
            $driver = User::whereHas('roles', function ($query) {
                $query->where('name', 'Driver');
            })->findOrFail($id);
            
            #get roles the user can assign to 
            $roles= Role::whereIn('name',config('general.roles_admin_can_assign'))->get();

            return view('dashboard.drivers.edit', compact('driver','roles'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Driver was not found: ');
           return back();
        }
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'error getting the driver in the edit page: ');
            return back();
        }
    }

    public function update(UpdateDriverRequest $request, $id)
    {
        /**
        * Update
        * 
        * Doc: update the driver information
        *
        * @param Illuminate\Http\Request $request will have the user information
        * @param Integer $id for the App\Models\User
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        */

       try
       {
            #find the driver
            $driver = User::findOrFail($id);

            #update the driver role
            if($request->role_id)
            {
                $driver->syncRoles([$request->role_id]);
            }

            #update driver info
            $driver->update($request->all());

            #redirect and send the user to the drivers route
            Alert::toast('Data was updated','success');
            return redirect()->route('dashboard.drivers.index');
           
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Driver was not found: ');
           return back();
        }
        catch (Exception $e) 
        {
           $this->logErrorAndRedirect($e,'Error updating the driver: ');
           return back();
        }

    }

    public function softDelete($id)
    {
         /**
        * Soft Delete
        * 
        * Doc: soft delete the user
        *
        * @param Integer $id for the App\Models\User
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            #find the user and delete it
            User::findOrFail($id)->delete();
            Alert::toast('Data was deleted successfully','success');

            return redirect()->route('dashboard.drivers.index');
        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Driver was not found in soft delete: ');
           return back();
        }
        catch (Exception $e) 
        {
           $this->logErrorAndRedirect($e,'Error deleting the user: ');
           return back();
        }

    }

    public function showSoftDelete(Request $request)
    {
        /**
        * Show Soft Delete
        * 
        * Doc: soft delete the user
        *
        * @param Integer $id for the App\Models\User
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            #to show the appropriate buttons
            $pageType = 'drivers';
            
            #get the trashed users
            $data = User::onlyTrashed()
            ->orderBy('created_at', 'asc')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Driver');
            })
            ->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.trash.index', compact('data', 'pageType'));

        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error in showing soft delete: ');
            return back();
        }

    }

   

}