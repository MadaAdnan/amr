<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ChildSeat\StoreChildeSeatFormRequest;
use App\Http\Requests\Dashboard\ChildSeat\UpdateChildeSeatFormRequest;
use App\Models\ChildSeat;
use Illuminate\Support\Facades\Log;
use Exception;
use Alert;
use App\Traits\LogErrorAndRedirectTrait;

class ChildSeatController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Child Seat Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all child seats action in the dashboard
    |
    */

    
    use LogErrorAndRedirectTrait;

    public function index()
    {
        /**
        * Index
        * 
        * Doc: send the user to the child seats page
        * 
        * @return \Illuminate\View\View
        */

        try 
        {
            $seats = ChildSeat::paginate(config('general.dashboard_pagination_number'));
            return view('dashboard.child_seats.table', compact('seats'));

        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error viewing child seats page: ');
            return back();
        }

    }

    public function create()
    {
        /**
        * Create
        * 
        * Doc: send the create page for the child seats
        * 
        * @return \Illuminate\View\View
        */

        try 
        {
            return view('dashboard.child_seats.create');
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error viewing create page: ');
            return back();
        }

    }

    public function store(StoreChildeSeatFormRequest $request)
    {
        /**
        * Store
        * 
        * Doc: store the child seat in the database
        * 
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {

            #save child seats and redirect to the child seats index within alerts
            ChildSeat::create($request->all());

            Alert::toast('The Seat has been added successfully', 'success');
            return redirect()->route('dashboard.childSeats.index');
            
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error storing the child seats: ');
        }

    }
    
    public function edit($id)
    {
        /**
         * Edit
         * 
         * Doc: Send the user to edit child seats page
         * 
         * @param App\Models\ChildSeat
         * 
         * @return \Illuminate\View\View
         */
        
        try {

            #child seats
            $seat = ChildSeat::findOrFail($id);
            return view('dashboard.child_seats.edit',compact('seat'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Child seat id was not found dashboard: ');
           return back();
        }
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Child seat id was not found dashboard: ');
            return back();
        }

    }

    public function update(UpdateChildeSeatFormRequest $request, $id)
    {
        /**
        * Update
        * 
        * Doc: Update the child seats information 
        * 
        * @param \Illuminate\Http\Request $request will have the category information
        * @param App\Models\Category $id category
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        * 
        */

        try 
        {
            ChildSeat::findOrFail($id)->update($request->all());
            Alert::toast('The Seat has been updated successfully', 'success');
            return redirect()->route('dashboard.childSeats.index');   
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Child seat id was not found dashboard: ');
           return back();
        }
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Child seat: ');
            return back();

        }

    }

    public function activeInactiveSingle($id)
    {
         /**
        * Active Inactive Single
        * 
        * Doc: Toggle between the active and inactive status of child seats. 
        * 
        * @param App\Models\Category $id category
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        * 
        */

        try 
        {
            #find the child seats
            $data = ChildSeat::findOrFail($id);

            #toggle between the status
            $newStatus = $data->status == ChildSeat::STATUS_PUBLISHED ? ChildSeat::STATUS_DISABLED : ChildSeat::STATUS_PUBLISHED;

            #update child seats
            $data->update([
                'status'=> $newStatus
            ]);

            Alert::toast('The status has been changed successfully', 'success');
            return back();
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Child seat id was not found dashboard: ');
           return back();
        }
         catch (Exception $e) {

            Log::error($e->getMessage()); // Log the error for debugging purposes
            Alert::toast('Server Error', 'error');
            return back();
        }
    }

    
}