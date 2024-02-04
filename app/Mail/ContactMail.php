<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
        $this->mailData["logo"] = env("APP_PREFIX")."/images/lavishLogo.png";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view("mail.ContactMail")
                    ->cc("info@lavishride.com", "lavishride")
                    ->replyTo("info@lavishride.com", "lavishride")
                    ->subject("Contact Us Mail");
    }
}
