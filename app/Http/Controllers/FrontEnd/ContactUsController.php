<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\ContactUsStoreRequest;
use Illuminate\Http\Request;
use App\Models\ContactForm;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;
use App\Traits\EmailTrait;

class ContactUsController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Contact Us Controller
    |--------------------------------------------------------------------------
    |
    | doc: to handel all the frontEnd actions for the contact Us
    |
    */

    use LogErrorAndRedirectTrait,EmailTrait;

    public function index()
    {
        /**
         * Index
         * 
         * 
         * doc: going to the faq page
         * 
         * @return View
         */

        try
        {
            return view('frontEnd.contactUs.index');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error entering the faq page');
            return back();
        }
    }

    public function store(ContactUsStoreRequest $request)
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
            $formData = $request->all();

            #store in the database
            ContactForm::create($formData);

            #send email
          // $this->sendContactEmail($request); 

            #set the expiration time for the session variable
            $request->session()->put('form_submitted', now()->addMinutes(5));
            
            return redirect()->route('frontEnd.contactUs.thank_you');
         }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'error in storing the contact form: ');
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
            return view('frontEnd.contactUs.thank_you');
         }
         catch(Exception $e)
         {
             $this->logErrorAndRedirect($e,'error in contactUs thank you form page: ');
             return back();
         }

    }



}
