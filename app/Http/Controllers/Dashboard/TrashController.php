<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\JsonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use App\Traits\LogErrorAndRedirectTrait;

class TrashController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Trash Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the trash actions in the dashboard
    |
    */

    use LogErrorAndRedirectTrait , JsonResponseTrait;

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
            $data = User::whereHas('roles')->onlyTrashed();
            $query = $request->get('query');

            if($query)
            {
                $data = $data->where('name','like','%'.$query.'%');
            }

            $data = $data->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.trash.index',compact('data'));
        }
        catch(Exception $e)
        {
            return $this->logErrorAndRedirect($e,'Error in getting trash users');
        }

    }

    public function return_user($id)
    {
         /**
        * Return user
        * 
        * Doc: return user
        *
        * @param App\Models\User $id
        *
        * @return Json
        */

        try
        {
            User::withTrashed()
            ->findOrFail($id)
            ->restore();

              #return to the front end with updated status if there is an links with the same info return false
              $responseObj = [
                'msg'=>'User was returned',
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'returning the user: ');
        }
    }
}
