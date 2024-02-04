<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\ChauffeurStoreRequest;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\EmailTrait;
use Illuminate\Support\Facades\File;
use App\Models\ChauffeurApplication;
use Carbon\Carbon;

use Exception;

class ChauffeurController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Chauffeur Controller
    |--------------------------------------------------------------------------
    |
    | doc: to handel all the frontEnd actions for the chauffeur
    |
    */

    use LogErrorAndRedirectTrait,EmailTrait;


    public function index()
    {
        /**
         * Index
         * 
         * 
         * doc: going to the blog main page
         * 
         * @return View
         */

         try
         {
             return view('frontEnd.chauffeur.index');
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
         * doc: going to the create page for the chauffeur application
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

             return view('frontEnd.chauffeur.create',compact('states'));
         }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'error entering the about us page');
             return back();
         }


    }

    public function store(ChauffeurStoreRequest $request)
    {
        /**
         * Store
         * 
         * 
         * doc: store the chauffeur application
         * 
         * @param App\Http\Requests\FrontEnd\ChauffeurStoreRequest $request
         * @return View
         */

         try
         {
            #get the date
            $formData = array_merge($request->all(),[
                'date_of_birth'=>Carbon::parse($request->date_of_birth)
            ]);

            #store the data in the database
            $chauffeur = ChauffeurApplication::create($formData);

            #attach the file
            if($request->hasFile('resume'))
            {
                $chauffeur->addMedia($request->file('resume'))->toMediaCollection('files');
            }

           #send email
           //$this->sendEmailChauffeurApplication($chauffeur); 

            #set the expiration time for the session variable
            $request->session()->put('form_submitted', now()->addMinutes(5));


            return redirect()->route('frontEnd.chauffeur.thank_you');
            
         }
         catch(Exception $e)
         {
            throw $e;
             $this->logErrorAndRedirect($e,'error entering the about us page');
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
            return view('frontEnd.chauffeur.thank_you');
         }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'error in chauffeur thank you form page: ');
             return back();
         }
    }


}
