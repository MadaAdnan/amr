<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\LogErrorAndRedirectTrait;

class CustomerController extends Controller
{

    use LogErrorAndRedirectTrait;

    /*
    |--------------------------------------------------------------------------
    | Customer Controllers
    |--------------------------------------------------------------------------
    |
    | Responsible for all customer actions in the website
    |
    */

    public function index()
    {
        /**
        * Index
        * 
        * Doc: send the user to the customers page 
        *
        *
        * @return \Illuminate\View\View
        */

        try 
        {
            #get all the user with the role customer
            $customers = User::orderBy('created_at', 'desc')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Customer');
            })
            ->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.customers.index', compact('customers'));
        } 
        catch (\Exception $e) 
        {
            $this->logErrorAndRedirect($e,'Error getting customers: ');
            return back();
        }
    }

}
