<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Traits\LogErrorAndRedirectTrait;
use App\Models\Faq;

class FaqController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Faq Controller
    |--------------------------------------------------------------------------
    |
    | This handles the showing of the faq's in the frontEnd 
    |
    */

    use LogErrorAndRedirectTrait;

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
            #get all faq's type's 
            $general = Faq::where('type',Faq::GENERAL_TYPE)
            ->orderBy('sort', 'asc')
            ->get();

            $professionalChauffeur = Faq::where('type',Faq::PROFESSIONAL_CHAUFFEUR_TYPE)
            ->orderBy('sort', 'asc')
            ->get();

            $cancellationAndRefundType = Faq::where('type',Faq::CANCELLATION_AND_REFUND_TYPE)
            ->orderBy('sort', 'asc')
            ->get();

            return view('frontEnd.faq.index',compact('general','professionalChauffeur','cancellationAndRefundType'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error entering the faq page');
            return back();
        }
    }

    


}
