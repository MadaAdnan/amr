<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSettings extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData, $headerData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData, $headerData)
    {
        $this->mailData = $mailData;
        $this->mailData["logo"] = env("APP_PREFIX")."/images/lavishLogo.png";

        if(!empty($headerData)){
            $this->headerData= $headerData;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $headerData = $this->headerData;
        return $this->view($headerData['view'])
                    ->subject($headerData['subject']);
                    // ->replyTo($headerData['replyTo'][0], $headerData['replyTo'][1]);

    }
}
