<?php

namespace App\Services;

use App\Models\OtpDevice;
use App\Models\OtpPhone;
use App\Models\UserOtp;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use DateTime;
use Exception;
use App\Traits\LogErrorAndRedirectTrait;
use Twilio\Rest\Client;

class TwilioService
{
    use LogErrorAndRedirectTrait;
        
    public function generateOtp($user)
    {

        $otp_code = rand(100000, 999999);
        $user_otp = $user->otpNew;
        $current_date = new DateTime();

        if ($user_otp) {
            $last_attempts = new DateTime($user_otp->last_attempts);
            $interval = $current_date->diff($last_attempts);

            // check if the user is sent more than 5 before send
            if ($user_otp->number_of_attempts < 5) {

                #send OTP message
                $send_otp = $this->sendOtp($otp_code, $user->phone);

                if ($send_otp) {
                    $otp = UserOtp::find($user_otp->id);
                    $otp->last_attempts = date('Y-m-d H:i:s');
                    $otp->otp = $otp_code;
                    $otp->number_of_attempts = $otp->number_of_attempts + 1;
                    $otp->save();

                    return [
                        'status' => 'success',
                        'message' => 'The message is sent'
                    ];
                }

                // check if the user is sent more than 5 but before 24 hours
            } elseif ($user_otp->number_of_attempts >= 5 && $interval->h > 24) {

                #send OTP message
                $send_otp = $this->sendOtp($otp_code, $user->phone);

                if ($send_otp) {
                    $otp = UserOtp::find($user_otp->id);
                    $otp->last_attempts = date('Y-m-d H:i:s');
                    $otp->otp = $otp_code;
                    $otp->number_of_attempts = 1;
                    $otp->save();

                    return [
                        'status' => 'success',
                        'message' => 'The message is sent'
                    ];
                }
            } else {
                return [
                    'status' => 'error',
                    'message' => 'You have exceeded attempts limit, you can try again after 24 hours'
                ];
            }
        }

        # check if the user first verify, create new record in the database
        if (!$user_otp) {
            #send OTP message
            $send_otp = $this->sendOtp($otp_code, $user->phone);

            if ($send_otp) {
                $user_otp = new UserOtp([
                    'user_id' => $user->id,
                    'otp' => $otp_code,
                    'number_of_attempts' => 1,
                    'last_attempts' => date('Y-m-d H:i:s')
                ]);
                $user_otp->save();

                return [
                    'status' => 'success',
                    'message' => 'The message is sent'
                ];
            }
        }

    }

    public function generate_otp($device_id, $phone)
    {
        /**
         * Generate otp
         * 
         * doc: generate the otp code
         */

        $check_phone = $this->checkValidPhone($phone);
        
        if($check_phone !== true){
            return [
                'errors' => $check_phone
            ];
        }

        $otp_code = rand(100000, 999999);
        $current_date = new DateTime();
        $otp_check = false;

        // check device if it added
        $device = OtpDevice::where(['device' => $device_id])->first();

        if (!$device) 
        {
            $device = new OtpDevice();
            $device->device = $device_id;
            $device->save();

        } 
        elseif ($device) 
        {
            // check device if allowed to send otp or he exceeded attempts limit
            if ($device->number_of_attempts >= 6 || $device->blocked_at !== null) 
            {
                $device->blocked_at = date('Y-m-d H:i:s');
                $device->save();
                return [
                    'errors' => 'You have exceeded attempts limit for this device'
                ];

            }
        }

        // get number of attempts for all records for the phone number
        $check_phone_attempts = OtpPhone::selectRaw('sum(number_of_attempts) as number_of_attempts , max(last_attempts) as last_attempts')
            ->where('phone', $phone)
            ->groupBy('phone')
            ->first();

        $number_of_attempts = $check_phone_attempts->number_of_attempts ?? 0;
        
        if($number_of_attempts > 0 ){
            $last_attempts = new DateTime($check_phone_attempts->last_attempts);
            $interval = $current_date->diff($last_attempts);
        }

        if ($number_of_attempts < 4) {
            $otp_check = true;

        } elseif ($number_of_attempts >= 4 && $interval->d > 0) {
            $clear_counter_phone_otp = OtpPhone::where(['phone' => $phone])->update(['number_of_attempts' => 0]);
            $otp_check = true;
        } else {
            return [
                'errors' => 'You have exceeded attempts limit for this phone number, you can try again after 24 hours'
            ];
        }

        
        $send_otp = $this->sendOtp($otp_code, $phone);

        
        if ($otp_check && $send_otp) {
            $phone_otp = OtpPhone::where(['phone' => $phone, 'device_id' => $device->id])->first();

            if (!$phone_otp) {
                $phone_otp = new OtpPhone();
                $phone_otp->device_id = $device->id;
                $phone_otp->phone = $phone;
                $phone_otp->status = 1;
                $phone_otp->otp_code = $otp_code;
                $phone_otp->end_time = date('Y-m-d H:i:s', strtotime('+2 minute', time()));
                $phone_otp->last_attempts = date('Y-m-d H:i:s');
                $phone_otp->number_of_attempts = 1;
                $phone_otp->save();

                $device->number_of_attempts = ++$device->number_of_attempts;
                $device->save();
            } else {
                $phone_otp->number_of_attempts = ++ $phone_otp->number_of_attempts ;
                $phone_otp->status = 1;
                $phone_otp->otp_code = $otp_code;
                $phone_otp->end_time = date('Y-m-d H:i:s', strtotime('+2 minute', time()));
                $phone_otp->last_attempts = date('Y-m-d H:i:s');
                $phone_otp->save();

                $device->number_of_attempts = ++$device->number_of_attempts;
                $device->save();
            }

            return [
                'success' => 'The message has been sent successfully',
                'otp_code' => $phone_otp->otp_code
            ];
        }

    }

    public function sendMessage($message, $recipients)
    {
        /**
         * Send Message
         * 
         * doc: send the message throw TWILIO
         * 
         * @param $message
         * @param $recipients
         * @param $message
         */

        try 
        {
            $account_sid = env('TWILIO_SID');
            $auth_token = env('TWILIO_AUTH_TOKEN');
            $twilio_number = env('TWILIO_NUMBER');

            $content = $message;


            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                $recipients,
                array(
                    'from' => "Lavish Ride",
                    'body' => $content
                ));
                
        } 
        catch (Exception $e) 
        {
            return $this->logErrorAndRedirect($e,'Fix in sending message');
        }
    }

    private function sendOtp($otp_code, $phone)
    {
            $twilioService = new TwilioService();
            $twilioService->sendMessage(
                "Dear Customer, your OTP is " . $otp_code,
                $phone,
                "LavishRide"
            );

            return true;
    }

    private function checkValidPhone($phoneNumber)
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        
        try {
            $parsedNumber = $phoneNumberUtil->parse($phoneNumber, null);
            // dd($phoneNumber);

            if ($phoneNumberUtil->isValidNumber($parsedNumber)) {
                // The phone number is valid
                return true;
            } else {
                // The phone number is invalid
                return "Invalid phone number.";
            }
        } catch (NumberParseException $e) {
            // Failed to parse the phone number
            return "Failed to parse phone number.";
        }
    }
}

