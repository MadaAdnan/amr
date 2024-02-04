<?php

namespace App\Services;

use App\Models\NewBill;
use App\Models\Reservation;
use App\Mail\SendMail;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;


class ReservationStatusService
{

    private $admin_mail;
    private $admin_phone;
    private $sub_admin_phone;
    private $admin_name;

    public function __construct()
    {
        $this->admin_mail = config("global.ADMIN_MAIL");
        $this->admin_phone = config("global.ADMIN_PHONE");
        $this->sub_admin_phone = config("global.SUB_ADMIN_PHONE");
        $this->admin_name = config("global.ADMIN_NAME");
    }

    public function sendNotifications($trip_id, $status, $send_email)
    {

        $trip = Reservation::where("id", $trip_id)->first();
        $bill = NewBill::where('reservation_id', $trip->id)->first();

        $customer = $trip->users;
        $driver = $trip->drivers;

        $vehicle = $trip->fleets;

        switch ($status) 
        {
            case "new reservation":
                $pdf = PDF::loadView('emails.invoice_pdf_new', [
                    'customer' => $customer,
                    'bill' => $bill,
                    'trip' => $trip,
                    'vehicle' => $vehicle,
                    'driver' => $driver,
                ])->setPaper('A4');
                
                //Customer
                $logo = asset("images/logo-white.svg");

                $customer_data = [
                    "subject" => "LR Trip #" . $trip->id. " - Create",
                    "title" => 'Thank you for choosing LR!',
                    "message" => '
                         Your trip #' . $trip->id . ' has been created successfully and will be reviewed and accepted shortly. For inquiries, do not hesitate to contact us at  info@lavishride.com or call us at 
                        '.config('general.support_phone').'',
                    'cc' => ["info@lavishride.com", "lavishride"],

                    "url" => "",
                    "url_msg" => "",
                ];


                $customerSendMail = new SendMail($customer_data);
                $customerSendMail->attachData($pdf->output(), "standard-confirmation.pdf");

                Mail::to($send_email)->cc('reservation@lavishride.com')->send($customerSendMail);

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
                    'trip' => $trip,

                ])->setPaper('0,0,567.00,283.80', 'landscape');
                //Customer
                $customer_data = [
                    "subject" => "LR Trip #". $trip->id." - Update",
                    "title" => 'Dear ' . $customer->first_name. ",",
                    "message" => "Your trip #". $trip->id." has been updated successfully. To view the updates, please check the confirmation attached. For inquiries, do not hesitate to contact us at  info@lavishride.com or call us at 
                    ".config('general.support_phone')."",
                    "url" => "",
                    "url_msg" => "",
                ];
                $customerSendMail = new SendMail($customer_data);
                $customerSendMail->attachData($pdf->output(), "standard-confirmation.pdf");

                #mail
                $mail = Mail::to($send_email);

                if ((isset($driver))) 
                {
                    $customerSendMail->attachData($signPDF->output(), "LR-Pickup-Sign.pdf");
                    $driverEmail = $driver->email;
                    $mail->cc($driverEmail);
                }
               
                $mail->send($customerSendMail);
                


                break;


        }

        return true;
    }
}