<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FaqRequest;
use Exception;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;

class FaqController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Faq Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all faq actions in the dashboard
    |
    */

    use LogErrorAndRedirectTrait , JsonResponseTrait;

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
            return view('dashboard.faq.create');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting create page: ');
            return back();
        }
    }


    public function store(FaqRequest $request)
    {
         /**
        * Store
        * 
        * Doc: store the faq information 
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #store the faq information and redirect to the faq setting page
            Faq::create($request->all());

            return redirect()->route('dashboard.pages.index','Faq');
        }
        catch(Exception $e)
        {

            $this->logErrorAndRedirect($e,'Error Creating Faq: ');
            return back();
        }
    }

    public function edit($id)
    {
        /**
        * Edit 
        * 
        * Doc: go the edit page 
        *
        * @param App\Models\Faq $id 
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = Faq::findOrFail($id);
            return view('dashboard.faq.edit',compact('data'));    
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the faq dashboard: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in storing the faq: ');
            return back(); 
        }
    }

    public function update(FaqRequest $request , $id)
    {
         /**
        * Update 
        * 
        * Doc: update the faq information 
        *
        * @param App\Models\Faq $id 
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #update faq information
            Faq::findOrFail($id)->update($request->all());
            return redirect()->route('dashboard.pages.index','Faq');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the faq dashboard update request: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error updating faq: ');
            return back();
         }
    }

    public function delete($id)
    {
         /**
        * Delete 
        * 
        * Doc: delete the faq information 
        *
        * @param Integer $id App\Models\Faq
        *
        * @return \Illuminate\View\View
        */

        try
        {
            Faq::findOrFail($id)->delete();
            
            return back();
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the faq dashboard delete request: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error Finding the faq dashboard delete request: ');
            return back(); 
        }
    }

    public function sort(Request $request)
    {
         /**
        * Sort 
        * 
        * Doc: sort faq information 
        *
        * @param  Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            foreach ($request->items as $value) 
            {
               Faq::findOrFail($value['id'])->update(['sort'=>$value['sort']]);
            }
            
             #return to the front end with updated status
             $responseObj = [
                'msg'=>'Faqs was sorted',
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
            
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           return $this->logErrorJson($e,'Error Finding the faq dashboard delete request: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error Finding the faq dashboard sort request: ');
        }
    }

}
