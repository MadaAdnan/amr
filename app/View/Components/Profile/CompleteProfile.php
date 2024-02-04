<?php

namespace App\View\Components\Profile;

use Illuminate\View\Component;
use Auth;

class CompleteProfile extends Component
{
    /**
     * Complete Profile
     * 
     * doc: complete profile for the user profile
     *
     * @return void
     */

     public $isPhoneNumberVerified; //check if the phone number is verified
     public $isEmailVerified; //check if the phone number is verified
     public $percentage; //the percentage of the data the user completed

    public function __construct()
    {
        $this->isPhoneNumberVerified = Auth::user()->phone_verified_at ? true : false;
        $this->isEmailVerified = Auth::user()->email_verified_at ? true : false;
        
        //get the percentage 
        if ($this->isPhoneNumberVerified == true && $this->isEmailVerified == true) {
            $this->percentage = '100%';
        } 
        else if ($this->isPhoneNumberVerified == false && $this->isEmailVerified == false) 
        {
            $this->percentage = '0%';
        } 
        else 
        {
            $this->percentage = '50%';
        }

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.profile.complete-profile');
    }
}
