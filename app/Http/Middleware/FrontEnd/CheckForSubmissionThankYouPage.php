<?php

namespace App\Http\Middleware\FrontEnd;

use Closure;
use Illuminate\Http\Request;

class CheckForSubmissionThankYouPage
{
    /**
     * Check For Submission Thank You Page
     * 
     * doc: check if the user already submitted the form or not throw the session
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        #Check if the form has been submitted and the session has not expired
        if (!$request->session()->has('form_submitted') || now()->gt($request->session()->get('form_submitted'))) {
            #Redirect to a different page or show an error message
           return redirect()->route('frontEnd.index');
        }

        return $next($request);

    }
}
