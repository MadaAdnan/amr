<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreBrokenRequest;
use App\Models\RedirectMapping;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;
use Illuminate\Http\Request;

class RedirectMappingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Redirect Mapping Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the redirection in the dashboard
    |
    */

    use LogErrorAndRedirectTrait,JsonResponseTrait;

    public function index(Request $request)
    {
        /**
        * Index
        * 
        * Doc: get all the links the  RedirectMapping links
        *
        * @param Illuminate\Http\Request $request filtering
        *
        * @return \Illuminate\View\View
        */
        
        try
        {

            $data = RedirectMapping::orderBy('created_at','desc');

            #filtering with pagination
            $searchQuery = $request->query('query');
            if($searchQuery)
            {
                $data = $data->where('old_url','like','%'.$searchQuery.'%')
                ->OrWhere('new_url','like','%'.$searchQuery.'%');
            }
            $data = $data->paginate(config('general.dashboard_pagination_number'));
            $data->appends(request()->query());

            return view('dashboard.broken_url.index',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting pages in index: ');
            return back();
        }
    }

    public function store(StoreBrokenRequest $request)
    {
        /**
        * Store
        * 
        * Doc: save redirect mapping
        *
        * @param Illuminate\Http\Request $request filtering
        *
        * @return \Illuminate\View\View
        */

        try
        {
            RedirectMapping::create($request->all());

            #return to the front end with updated status
            $responseObj = [
                'msg'=>'Data was created',
                'status'=>config('status_code.success.created')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.created'));    
        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,'Error Creating Broken Requests: ');
            return back();
        }

    }

    public function update(StoreBrokenRequest $request,$id)
    {
        /**
        * Update
        * 
        * Doc: get all the links the  RedirectMapping links
        *
        * @param Illuminate\Http\Request $request filtering
        * @param Integer App\Models\RedirectMapping $id
        *
        * @return \Illuminate\View\View
        */

        try
        {
            RedirectMapping::findOrFail($id)->update($request->all());

              #return to the front end with updated status
              $responseObj = [
                'msg'=>'Data was updated',
                'status'=>config('status_code.success.updated')
              ];

            return $this->successResponse($responseObj,config('status_codes.success.updated'));    
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           return $this->logErrorJson($e,'Error Finding the redirectMapping dashboard : ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error updating Broken Requests : ');
        }
    }

    public function delete($id)
    {
        /**
        * Delete
        * 
        * Doc: delete redirect the link
        *
        * @param Integer App\Models\RedirectMapping $id
        *
        * @return Json
        */

        try
        {
            RedirectMapping::findOrFail($id)->delete();

             #return to the front end with updated status
             $responseObj = [
                'msg'=>'data was deleted',
                'status'=>config('status_code.success.deleted')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.deleted'));  

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           return $this->logErrorJson($e,'Error finding redirect mapping : ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Delete the redirect mapping link : ');
        }
    }

    public function checkUrl(Request $request)
    {      
        /**
        * Check Url
        * 
        * Doc: check url if it dose exist or not so the user can add it
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            #initial query
            $data = RedirectMapping::where('old_url',$request->url);

            #if the query exist  exclude it from the query
            $idQuery = $request->query('id');
            if($idQuery != null)
            {
                $data = $data->where('id','!=',$idQuery);
            }
            
            $data = $data->first();

             #return to the front end with updated status if there is an links with the same info return false
             $responseObj = [
                'data'=>[
                    'is_available'=> $data ? false : true
                ],
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Delete the redirect mapping link : ');
        }

    }
}
