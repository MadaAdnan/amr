<?php

namespace App\Http\Controllers\Dashboard;

use Alert;
use App\Http\Requests\Dashboard\Company\StoreCompanyFormRequest;
use App\Http\Requests\Dashboard\Company\UpdateCompanyFormRequest;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\State;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;

class CompanyController extends Controller
{

      /*
    |--------------------------------------------------------------------------
    | Companies Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all companies action in the dashboard
    |
    */

    use LogErrorAndRedirectTrait,JsonResponseTrait;

    public function index()
    {
         /**
        * Index
        * 
        * Doc: send the user to the companies page 
        *
        *
        * @return \Illuminate\View\View
        */

        try 
        {
            #To check if the user need to be sent to the trash page or index page
            $pageType = 'index';
            $companies  = Company::paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.companies.index', compact('companies','pageType'));

        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error viewing companies index: ');
            return back();
        }
    }

    public function create()
    {
        /**
        * Create
        * 
        * Doc: send the user to the create companies page 
        *
        *
        * @return \Illuminate\View\View
        */

        try 
        {
            #get countries so the user can select the company is related to any country
            $countries = Country::get();

            return view('dashboard.companies.create', compact('countries'));

        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error viewing companies index: ');
            return back();
        }
    }

    public function store(StoreCompanyFormRequest $request)
    {
         /**
        * Store
        * 
        * Doc: store the company data
        * 
        * @param Illuminate\Http\Request $request to get company information
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            #get the data and save the company information
            $data = $request->all();
            Company::create($data);
            Alert::toast('The Company Added Successfully', 'success');

            return redirect()->route('dashboard.companies.index');
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error storing the companies: ');
            return back();
        }

    }

    public function edit($id)
    {
        /**
        * Edit
        * 
        * Doc: send the user to the company edit page
        * 
        * @param Integer $id App\Models\Company
        *
        *
        * @return \Illuminate\View\View
        */

        try 
        {
            #get the company data
            $company = Company::findOrFail($id);
            #get the countries and return to the front end
            $countries = Country::get();

            return view('dashboard.companies.edit', compact('company', 'countries'));
        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'error in finding the companies: ');
           return back();
        }
        catch (Exception $e) 
        {
           $this->logErrorAndRedirect($e,'error getting the user info in the view page: ');
           return back();
        }
    }

    public function update(UpdateCompanyFormRequest $request, $id)
    {
        /**
        * Update
        * 
        * Doc: send the user to the company edit page
        * 
        * @param Illuminate\Http\Request $request to get company information
        * @param Integer $id App\Models\Company
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            #update the company information and redirect the user to the index page 
            $data = array_merge($request->all());
            Company::findOrFail($id)
            ->update($data);

            Alert::toast('The Company Updated Successfully', 'success');
            return redirect()->route('dashboard.companies.index');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'error in finding company update: ');
           return back();
        }
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'error in updating companies: ');
            return back();

        }
    }

    public function softDelete($id)
    {
         /**
        * Soft Delete
        * 
        * Doc: delete the company and send it to the trash
        * 
        * @param Integer $id App\Models\Company
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            #delete the company and return it to the index page
            Company::findOrFail($id)->delete();
            Alert::toast('Deleted Successfully', 'success');
            return back();
        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'error in finding company soft delete: ');
           return back();
        }
        catch (Exception $e) 
        {
           $this->logErrorAndRedirect($e,'error in company soft delete: ');
           return back();
        }

    }

    public function showSoftDelete()
    {
        /**
        * Show soft delete 
        * 
        * Doc: send the user to all the trashed companies
        * 
        *
        * @return \Illuminate\View\View
        */

        try 
        {
            #To separate this page from the index one 
            $pageType = 'trashed';
            $companies  = Company::onlyTrashed()
            ->orderBy('created_at', 'asc')
            ->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.companies.trashed',compact('pageType','companies'));
        }
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'error in show soft delete: ');
            return back();

        }
    }

    public function softDeleteRestore($id)
    {
        /**
        * Soft delete restore 
        * 
        * Doc: restore the soft deleted companies
        * 
        * @param Integer $id App\Models\Company
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try 
        {
            #search for the company in the trash than restore it
            Company::onlyTrashed()->findOrFail($id)->restore();
            Alert::toast('Company Restored Successfully', 'success');

            return back();
        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'error in finding company soft delete: ');
           return back();
        }
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e,'error in restoring the trashed items: ');
           return back();
        }
    }

    public function getStates(Request $request)
    {
         /**
        * Get States 
        * 
        * Doc: Get states according to the country
        * 
        * @param Illuminate\Http\Request $request to get country id
        *
        *
        * @return Json
        */

        try
        {
            $data = State::where('country_id', $request->input('country_id'))->get();

            return $this->successResponse($data,config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
           return $this->logErrorJson($e,'in get states: ');
        }
        

    }

    public function getCities(Request $request)
    {

          /**
        * Get Cities 
        * 
        * Doc: Get cities according to the state
        * 
        * @param Illuminate\Http\Request $request to get state id
        *
        *
        * @return Json
        */
        
        try
        {
            $data = City::where('state_id', $request->input('state_id'))->get();

            return $this->successResponse($data,config('status_codes.success.ok'));    
        }
        catch(Exception $e)
        {
           return $this->logErrorJson($e,'in get cities: ');
        }
    }



}