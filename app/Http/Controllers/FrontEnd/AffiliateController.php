<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\AffiliateStoreRequest;
use Illuminate\Support\Facades\File;
use App\Traits\LogErrorAndRedirectTrait;
use App\Models\AffiliateApplication;
use App\Traits\EmailTrait;
use Exception;

class AffiliateController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Affiliate Controller
    |--------------------------------------------------------------------------
    |
    | This is handel the form for the affiliate in the front end
    |
    */

    use LogErrorAndRedirectTrait,EmailTrait;

    public function index()
    {
        /**
         * Index
         * 
         * 
         * doc: going to the affiliate page
         * 
         * @return View
         */

        try
        {
            return view('frontEnd.affiliate.index');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error entering the about us page');
            return back();
        }
    }

    public function create()
    {
         /**
         * Create
         * 
         * 
         * doc: going to create form for the affiliate
         * 
         * @return View
         */

         try
         {
            #get file 
             $statesFile = public_path('general/states-object.json');

             #get file content
             $jsonContent = File::get($statesFile);

             #convert it to array
             $states = json_decode($jsonContent, true);

             return view('frontEnd.affiliate.create' , compact('states'));
         }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'error in affiliate form page: ');
             return back();
         }

    }

    public function store(AffiliateStoreRequest $request)
    {
         /**
         * Store
         * 
         * 
         * doc: saving the affiliate data and redirect to thank you page
         * 
         * @param App\Http\Requests\FrontEnd\AffiliateStoreRequest $request
         * 
         * @return Redirect
         */

         try
         {
            #change the array to string to save it
            $form_data = array_merge($request->all(),[
                'area_of_service'=>implode(', ', $request->area_of_service)
            ]);

           #store in the database
           $data = AffiliateApplication::create($form_data);

           #send email
           $this->sendAffiliateApplicationEmail($data);

           #set the expiration time for the session variable
           $request->session()->put('form_submitted', now()->addMinutes(5));


           return redirect()->route('frontEnd.affiliate.thank_you');
         }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'error in storing the affiliate form: ');
             return back();
         }

    }

    public function thank_you()
    {
         /**
         * Thank You
         * 
         * 
         * doc: send the user to the thank you page
         * 
         * @return View
         */

         try
         {
            return view('frontEnd.affiliate.thank_you');
         }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'error in affiliate thank you form page: ');
             return back();
         }

    }
    
}
