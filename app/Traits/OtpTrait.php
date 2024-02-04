<?php

namespace App\Traits;

use App\Services\TwilioService;
use App\Models\OtpPass;
use App\Models\OtpDevice;
use App\Models\OtpEmails;
use Carbon\Carbon;
use Exception;
use App\Traits\LogErrorAndRedirectTrait;

trait OtpTrait 
{
    use LogErrorAndRedirectTrait;
    /**
     * Otp Trait 
     * 
     * Responsible for all otp action
     *  
     */


    public function sendOtp($user)
    {
        /**
         * Send Otp
         * 
         * doc: send otp code to the selected phone number
         * 
         * @param App\Models\User $user
         * 
         * @return boolean
         */
        
        try
        {
            #generate code
            $otpCode = rand(100000, 999999);

            #get all old attempts
            $userOtp = $user->otp;
            
            $currentDate = Carbon::now();

            #check otp emails attempts
            $emailOtp = OtpEmails::firstOrCreate(
                ['email'=>$user->email],
                [
                    'email'=>$user->email,
                    'otp_code'=>$otpCode,
                    'last_attempts'=>Carbon::now()
                ]
            );

            #get the diff in hours 
            $diffInHoursFromLastAttempts = $currentDate->diffInHours($emailOtp->last_attempts);

            #get number of attempts for email
            $emailNumberOfAttempts = $emailOtp->number_of_attempts;

            #check if the email was above the agreed attempts block him from sending attempts
            if($emailNumberOfAttempts > 6 && $diffInHoursFromLastAttempts < 24)
            {
                return false;
            }
            else if($emailNumberOfAttempts <= 6)
            {
                $emailNumberOfAttempts = $emailNumberOfAttempts + 1;
            }
            else if($emailNumberOfAttempts > 6 && $diffInHoursFromLastAttempts > 24)
            {
                $emailNumberOfAttempts = 0;
            }
            
         
            $emailOtp->update([
                'last_attempts'=>Carbon::now(),
                'otp_code'=>$otpCode,
                'number_of_attempts'=>$emailNumberOfAttempts
            ]);
            

            #create or get the otp_device
            $getUserIpAddress = getenv("REMOTE_ADDR");

            $device = OtpDevice::firstOrCreate(
                ['device' => $getUserIpAddress],
                ['device'=>$getUserIpAddress]
            );

            #check the number of attempts for the device.
            if($device->number_of_attempts > 3)
            {
                return false;
            }
            else
            {
                $device->update([
                    'number_of_attempts'=> $device->number_of_attempts + 1,
                    'otp_code'=>$otpCode
                ]);
            }
            
            #create and otp pass for the user
            $data = [
                'phone'=>$user->phone,
                'otp_code'=>$otpCode,
                'user_id'=>$user->id
            ];

            #create an otp pass
            OtpPass::create($data);

            $twilioService = new TwilioService();
            $twilioService->sendMessage(
                "Dear ".$user->fullName.", your OTP is " . $otpCode,
                "+".$user->country_code.$user->phone,
                "LavishRide"
            );

            return true;
        }
        catch (Exception $e) 
        {
            throw $e;
            return $this->logErrorJson($e,'Error getting otp data');
        }
    }
}