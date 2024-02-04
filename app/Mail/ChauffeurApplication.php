<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChauffeurApplication extends Mailable
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
        $mimeType = \GuzzleHttp\Psr7\MimeType::fromFilename($this->mailData->resume);
        return $this->view("mail.chauffeur_application")
                    ->cc(config('general.support_email'), "lavishride")
                    ->replyTo(config('general.support_email'), "lavishride")
                    ->subject("Chauffeur Application")
                    ->attach($this->mailData->avatar, ["as" => $this->mailData->name . "'s Resume", "mime" => $mimeType]);

    }
}
