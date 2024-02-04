<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Requests\Dashboard\Coupon\StoreCouponFormRequest;
use App\Http\Requests\Dashboard\Coupon\UpdateCouponFormRequest;
use App\Http\Controllers\Controller;
use Exception;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Alert;
use App\Traits\LogErrorAndRedirectTrait;

class CouponController extends Controller
{

    use LogErrorAndRedirectTrait;
    
      /*
    |--------------------------------------------------------------------------
    | Coupon Controllers
    |--------------------------------------------------------------------------
    |
    | Responsible for all countries action in the dashboard
    |
    */

    public function index(Request $request)
    {
        /**
        * Index
        * 
        * Doc: send the user to the companies page 
        *
        * @param Illuminate\Http\Request $request will have the filtering information
        *
        * @return \Illuminate\View\View
        */

        try 
        {
            #page type for the table to decide the if the table for trash or normal coupons
            $pageType = 'index';

            #initiate the coupon eloquent  query 
            $coupons = Coupon::orderBy('created_at', 'desc');

            #filtering the data according to the search query
            $searchQuery = $request->query('query');
            if ($searchQuery) {
                $coupons->where(function ($q) use ($searchQuery) {
                    $q->where('id', 'like', '%' . $searchQuery . '%')
                                ->orWhere('coupon_name', 'like', '%' . $searchQuery . '%')
                                ->orWhere('coupon_code', 'like', '%' . $searchQuery . '%')
                            ;
                       
                });

            }


            #paginate the data
            $coupons = $coupons->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.coupons.index', compact('coupons', 'pageType'));

        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error getting coupons: ');
            return back();
        }
    }

    public function create()
    {
         /**
        * Create
        * 
        * Doc: send the user to create page
        *
        *
        * @return \Illuminate\View\View
        */

        try 
        {
            return view('dashboard.coupons.create');
        } 
        catch (Exception $e) 
        {

            $this->logErrorAndRedirect($e,'Error getting coupons create: ');
            return back();
        }
    }

    public function store(StoreCouponFormRequest $request)
    {
         /**
        * Store
        * 
        * Doc: creating coupons in the database
        *
        * @param Illuminate\Http\Request $request
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
         {

            #creating coupons than redirect to the index page 
            $data = array_merge([
                'percentage_discount'=>$request->percentage??$request->price
            ],$request->all());
            
            Coupon::create($data);

            Alert::toast('Coupon added successfully','success');

            return redirect()->route('dashboard.coupons.index');

        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error getting storing the coupon: ');
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
        * @param Illuminate\Http\Request $request to get data 
        * @param App\Models\Coupon $id
        *
        *
        * @return \Illuminate\View\View
        */
        try 
        {
            $coupon = Coupon::findOrFail($id);
            return view('dashboard.coupons.edit',compact('coupon'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorAndRedirect($e,'error finding coupon in the edit page: ');
            return back(); 
        }
        catch (Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting storing: ');
            return back();

        }
    }

    public function update(UpdateCouponFormRequest $request, $id)
    {
        /**
        * Update
        * 
        * Doc: update the coupon data
        * 
        * @param Illuminate\Http\Request $request to get data 
        * @param App\Models\Coupon $id
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            #updating the coupons
            $update_request = array_merge([
                'percentage_discount'=>$request->percentage??$request->price
            ],$request->all());

            Coupon::findOrFail($id)->update($update_request);

            #redirect to the index coupons page 
            Alert::toast('Coupon updated successfully','success');
            return redirect()->route('dashboard.coupons.index');

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorAndRedirect($e,'error finding the coupon in updating coupons: ');
            return back();
        }
        catch (Exception $e)
        {
            $this->logErrorAndRedirect($e,'error updating the coupons: ');
            return back();
        }
    }

    public function softDelete($id)
    {
         /**
        * Soft Delete
        * 
        * Doc: soft delete the coupon 
        *
        * @param App\Models\Coupon $id
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            Coupon::findOrFail($id)->delete();
            return redirect()->route('dashboard.coupons.index')->with('success', 'The Deletion process has done successful');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorAndRedirect($e,'error finding the soft deleting the coupon: ');
            return back();
        }
        catch (Exception $e)
        {
            $this->logErrorAndRedirect($e,'error in soft deleting the data: ');
            return back();
        }
    }

    public function showSoftDelete(Request $request)
    {
         /**
        * Show Soft Delete
        * 
        * Doc: show the soft deleted data 
        *
        * @param App\Models\Coupon $id
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            #show only the deleted items
            $coupons = Coupon::onlyTrashed()->orderBy('created_at', 'asc');

            #to decides what actions to show in the table 
            $pageType = 'trashed';
            
            #filtering the items
            $searchQuery = $request->query('query');
            $statusQuery = $request->query('status');
            $startDateQuery = $request->query('startDate');
            $endDateQuery = $request->query('endDate');

            if ($searchQuery) 
            {
                $coupons->where(function ($q) use ($searchQuery) {
                    $q->where('id', 'like', '%' . $searchQuery . '%')
                        ->orWhereHas('users', function ($user_q) use ($searchQuery) {
                            $user_q->where('first_name', 'like', '%' . $searchQuery . '%')
                                ->orWhere('last_name', 'like', '%' . $searchQuery . '%')
                                ->orWhere('pick_up_location', 'like', '%' . $searchQuery . '%')
                                ->orWhere('drop_off_location', 'like', '%' . $searchQuery . '%')
                            ;
                        });
                });

            }

            #the date filter
            if ($startDateQuery && $endDateQuery) 
            {
                $coupons->whereDate('pick_up_date', $startDateQuery)
                    ->whereDate('return_date', $endDateQuery);
            }

            #status filter
            if ($statusQuery) 
            {
                $coupons->where('status', $statusQuery);
            }

            $coupons = $coupons->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.coupons.trashed', compact('coupons', 'pageType'));

        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error getting data in show soft delete: ');
            return back();
        }
    }

    public function softDeleteRestore($id)
    {
        /**
        * Soft Delete Restore
        * 
        * Doc: to restore the deleted coupons
        *
        * @param App\Models\Coupon $id
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            Coupon::onlyTrashed()->findOrFail($id)->restore();
            return redirect()->route('dashboard.coupons.showSoftDelete')->with('success', 'Restore Completed Successfully');
        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorAndRedirect($e,'error finding the coupon in restoring deleted coupon: ');
            return back();
        }
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'error restoring the deleted coupons: ');
            return back();
        }
    }

}