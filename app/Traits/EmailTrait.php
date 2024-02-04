<?php

namespace App\Traits;

use App\Mail\AffiliateApplication;
use App\Mail\ChauffeurApplication;
use App\Mail\ContactMail;
use App\Mail\MailSettings;
use App\Mail\SendMail;
use App\Mail\SendPasswordMail;
use App\Mail\FailedReservationEmail;
use App\Models\ForgetPassword;
use Exception;
use App\Models\NewBill;
use App\Models\Reservation;
use App\Services\TwilioService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;
use App\Traits\LogErrorAndRedirectTrait;

trait EmailTrait {

    use UtilitiesTrait,LogErrorAndRedirectTrait;
    
    /**
     * Email Trait 
     * 
     * Responsible  for all the email types in the website
     *  
     */


    public function verifyEmail($user)
    {
        /**
         * Doc: Verify Email Send to user in registration.
         * 
         * @param user will have the user information
         * 
         */

        try{
             #the linked will be sent to the user to verify he's email
             $verify_link = route('frontEnd.email-verification-front-end', $user->id);
    
             #user information
             $to_name = $user->first_name;
             $to_email = $user->email;
     
             #email content
             $data = [
                 'subject' => 'Email verification - ' . config('mail.from.name'),
                 'title' => 'Welcome ' . $to_name,
                 'message' => 'Thank you for signing up with Lavish Ride!
                 In order to get started, you need to first verify your email through the button below.',
                 'url' => $verify_link,
                 'url_msg' => 'Verify'
             ];
             
             #send the email
            Mail::to($to_email, $to_name)->send(new SendMail ($data));

         }catch(Exception $e)
         {
            return response()->json([
                'err'=>'error verify email',
                'status'=>config('status_codes.server_error.internal_error')
            ],config('status_codes.server_error.internal_error'));
         }

    }

    public function forgetPasswordEmail($user)
    {
         /**
         * Doc: send forget password email to the user.
         * 
         * @param user will have the user information
         * 
         */

        try{
            $code = random_int(10000, 99999);

            $forgetPassword = ForgetPassword::Where("user_id", $user->id)->first();
            if (!$forgetPassword) {
                    $forgetPassword = new ForgetPassword;
                    $forgetPassword->user_id = $user->id;
            }
            $forgetPassword->code = $code;
            $forgetPassword->save();
    
            $user_email = $user->email;
            $user_name = $user->first_name ;
    
             //Notification
             $notification_data = [
                "subject" => "Lavish Ride",
                "title" => "Thank you for choosing Lavish Ride, Your verification code is " . $code,
                "message" => "If you have any questions, don't hesitate to get in touch with us at ".config('general.support_email')." or call us at +1-888-816-1015.",
                "url" => "",
                "url_msg" => "",
            ];
    
            $sendMail = new SendMail($notification_data);
            Mail::to($user_email, $user_name)->send($sendMail);

            return true;

        }catch(Exception $e)
        {
            \Log::error('Error sending forget password email: ' . $e->getMessage());

            return response()->json([
                'err'=>'Error sending forget password email',
                'status'=>config('status_codes.server_error.internal_error')
            ],config('status_codes.server_error.internal_error'));
        }
    }

    public function sentGeneratedPasswordEmail($email,$password = null)
    {
        /**
         * Sent Generated Password Email
         * 
         * Doc: To send the user the generated password to the selected email address.
         * 
         * @param email selected email
         * @param password if the password was provided the function wil not generate password else it will
         * 
         */

        try{
            #if the password was provided send it to the selected email else generate one
            $generatePassword = $password ? $password : $this->userGeneratePassword(5);

            #add the generated password to the email
            $resetPasswordEmail = new SendPasswordMail($generatePassword);
            #send the email
            Mail::to($email)->send($resetPasswordEmail);

        }
        catch(Exception $e)
        {
            \Log::error('Error sending generate password email: ' . $e->getMessage());
            return response()->json([
                'err'=>'Error sending generate password email',
                'status'=>config('status_codes.server_error.internal_error')
            ],config('status_codes.server_error.internal_error'));
        }
    }

    public function sendEmailChauffeurApplication($chauffeur)
    {
         /**
         * Send Email Chauffeur Application
         * 
         * Doc: to send the email to the new register chauffeur application
         * 
         * @param App\Models\ChauffeurApplication $chauffeur 
         * @param password if the password was provided the function wil not generate password else it will
         * 
         */

        try
        {
            $sendMail_admin = new ChauffeurApplication($chauffeur);
            Mail::to(config("general.sales_email"), config("general.support_email"))->send($sendMail_admin);    
        }
        catch(Exception $e)
        {

            return $this->logErrorJson($e,'Error sending chauffeur application: ');
        }
    }

    public function sendContactEmail($request)
    {
        /**
         * Send Contact Email
         * 
         * Doc: to send contact email
         * 
         * @param Illuminate\Http\Request $request 
         * 
         * 
         * @return
         */ 

         try
         {
            $sendMail_admin = new ContactMail($request);
            Mail::to(config("general.sales_email"), config("general.support_email"))->send($sendMail_admin);

         }
         catch(Exception $e)
         {

            
            \Log::error('Error sending contact email: ' . $e->getMessage());

            return response()->json([
                'err'=>'Error sending contact email',
                'status'=>config('status_codes.server_error.internal_error')
            ],config('status_codes.server_error.internal_error'));
         }
    }

    public function sendAffiliateApplicationEmail($affiliate)
    {
         /**
         * Send Affiliate Application
         * 
         * Doc: to send contact email
         * 
         * @param App\Models\AffiliateApplication $affiliate
         * @param Illuminate\Http\Request $request 
         * 
         * 
         * @return
         */ 

        try
        {
            $sendMail_admin = new AffiliateApplication($affiliate);
            Mail::to($this->sales_mail, $this->admin_name)
            ->send($sendMail_admin);
        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,'Log error in sending the affiliate application: ');
        }

    }

    public function sendOtpEmail($code,$email,$name)
    {
        /**
         * Send Otp Email
         * 
         * Doc: send otp email to the user
         * 
         * @param String $code 
         * @param String $email 
         * @param String $name 
         * 
         * 
         * @return
         */ 

        $notification_data = [
            "subject" => "Lavish Ride",
            "title" => "Thank you for choosing Lavish Ride, Your verification code is " . $code,
            "message" => "If you have any questions, don't hesitate to get in touch with us at ".config('general.support_email')." or call us at +1-888-816-1015.",
            "url" => "",
            "url_msg" => "",
        ];

        $sendMail = new SendMail($notification_data);
        Mail::to($email, $name)->send($sendMail);

    }

    public function sendReportReservationEmail($trip_id,$user,$message)
    {
         /**
         * Send report reservation email
         * 
         * Doc: Send report reservation email to the user
         * 
         * @param Integer $trip_id 
         * @param User $user App\Models\NewBill
         * @param String $message 
         * 
         * 
         * @return Illuminate\Support\Facades\Mail
         */ 

        $headerData = [
            'view' => 'mail.ReportMail',
            'subject' => "Report Trip : ". $trip_id,
        ];

        $successResponse = [
            'fname' => $user->first_name,
            'lname' => $user->last_name,
            'trips_id' => $trip_id,
            'message' => $message,
        ];

        $sendMail_admin = new MailSettings($successResponse, $headerData);

        Mail::to(config("general.support_email"), config("general.admin_name"))->send($sendMail_admin);

    }

    public function sendUpdateReservationEmail($trip_id, $status)
    {

         /**
         * Send update reservation email
         * 
         * Doc: Send report reservation email to the user
         * 
         * @param Integer $trip_id 
         * @param User $user App\Models\NewBill
         * @param String $message 
         * 
         * 
         * @return Illuminate\Support\Facades\Mail
         */ 

        $twilioService = new TwilioService();
        $trip = Reservation::where("id", $trip_id)->first();
        $bill = NewBill::where('reservation_id', $trip->id)->first();

        $customer = $trip->users;
        $driver = $trip->drivers;

        $vehicle = $trip->fleets;
        $driver_name = $driver->fullname ?? '';
        $driver_phone = $driver->phone_primary ?? '';
        $customerName = $customer->fullname ?? '';
        $customerEmail = $customer->email ?? '';
        $customer_phone = $customer->phone_primary ?? '';

        switch ($status) 
        {
            case "new reservation":

                #get the pdf view
                $pdf = PDF::loadView('emails.invoice_pdf_new', [
                    'customer' => $customer,
                    'bill' => $bill,
                    'trip' => $trip,
                    'vehicle' => $vehicle,
                    'driver' => $driver,
                ])
                ->setPaper('A4');

                #get the logo
                $logo = asset("images/logo-white.svg");

                $customer_data = 
                [
                    "subject" => "LR Trip #" . $trip->id. " - Create",
                    "title" => 'Thank you for choosing LR!',
                    "message" => '
                         Your trip #' . $trip->id . ' has been created successfully and will be reviewed and accepted shortly. For inquiries, do not hesitate to contact us at  '.config('general.support_email').' or call us at 
                        '.config('general.support_phone').'',
                    'cc' => [config('general.support_email'), "lavishride"],
                    "url" => "",
                    "url_msg" => "",
                ];


                $customerSendMail = new SendMail($customer_data);
                $customerSendMail->attachData($pdf->output(), "standard-confirmation.pdf");

                Mail::to($customerEmail)->cc(config('general.support_email'))->send($customerSendMail);

                break;



            case "update":

                $pdf = PDF::loadView('emails.invoice_pdf_new', [
                    'customer' => $customer,
                    'bill' => $bill,
                    'trip' => $trip,
                    'vehicle' => $vehicle,
                    'driver' => $driver,
                ])->setPaper('A4');
                $signPDF = PDF::loadView('emails.airport_sign', [
                    'customer' => $customer,

                ])->setPaper('0,0,567.00,283.80', 'landscape');
                //Customer
                $customer_data = [
                    "subject" => "LR Trip #". $trip->id." - Update",
                    "title" => 'Dear ' . $customer->first_name. ",",
                    "message" => "Your trip #". $trip->id." has been updated successfully. To view the updates, please check the confirmation attached. For inquiries, do not hesitate to contact us at  ".config('general.support_email')." or call us at 
                    ".config("general.support_phone").".",
                    "url" => "",
                    "url_msg" => "",
                ];
                $customerSendMail = new SendMail($customer_data);
                $customerSendMail->attachData($pdf->output(), "standard-confirmation.pdf");
                if ((isset($driver))) {
                    $customerSendMail->attachData($signPDF->output(), "LR-Pickup-Sign.pdf");
                    $dirverEmail = $driver->email;
                    Mail::to($customerEmail)->cc($dirverEmail)->send($customerSendMail);
                }else{
                    Mail::to($customerEmail)->send($customerSendMail);
                }


                break;


        }

        return true;
    }

    public function sendFailedReservation()
    {
        $mailData = [
            'title'=>'Payment Alert!',
            'message'=>"A potential customer's payment is failing to go through your system. You can check it out by clicking on the button below",
            'subject'=>'Failed Payment Attempt',
        ];

        $mailData = new FailedReservationEmail($mailData);

        Mail::to(config('general.support_email'))->send($mailData);
    }

}